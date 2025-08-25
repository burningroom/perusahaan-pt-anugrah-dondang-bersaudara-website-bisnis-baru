<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Models\Bill;
use App\Models\Configuration;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceStatus;
use App\Models\SpkPandu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BillResource extends Resource
{
    protected static ?string $model = SpkPandu::class;
    protected static ?string $navigationLabel = 'Data Nota Pandu';
    protected static ?string $modelLabel = 'Data Nota Pandu';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'invoice/bill';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                //
                Select::make('user_id')
                    ->label('Pandu')
                    ->relationship('pandu','name')
                    ->columnSpan(12),
                Select::make('agent_id')
                    ->label('Agent')
                    ->relationship('agent','name')
                    ->columnSpan(12),
                TextInput::make('nomor_pkk')
                    ->label('Nomor PKK')
                    ->columnSpan(12),
                TextInput::make('nomor_nota')
                    ->label('Nomor Nota')
                    ->columnSpan(12),
                TextInput::make('keperluan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn ($record) => Str::title($record->keperluan))
                    ->columnSpan(12),
                DatePicker::make('waktu_gerak')
                    ->label('Tanggal')
                    ->columnSpan(12)
                // ->formatStateUsing(fn ($record) => date('Y-m-d',strtotime($record->keperluan)))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                //
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex()
                    ->width('10px')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('agent.name')
                    ->searchable(),
                TextColumn::make('nomor_nota')
                    ->label('Nomor Nota')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_pkk')
                    ->label('No. PKK')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('no_spk_pandu')
                    ->label('No. SPK Pandu')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('pkk.requestable.vesselRequestTB.name')
                    ->label('Nama Kapal')
                    ->formatStateUsing(fn ($state) => Str::title($state))
                    ->searchable(),
                TextColumn::make('keperluan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($state) => Str::title($state))
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Masuk')
                    ->formatStateUsing(function (SpkPandu $order) {
                        return $order->keperluan == 'masuk'
                            ? date('d/m/Y', strtotime($order->waktu_gerak))
                            : '-';
                    })
                    ->searchable(),

                TextColumn::make('pilotage_finished')
                    ->label('Keluar')
                    ->formatStateUsing(function (SpkPandu $order) {
                        return $order->keperluan == 'keluar'
                            ? date('d/m/Y', strtotime($order->waktu_gerak))
                            : '-';
                    })
                    ->searchable(),

                TextColumn::make('signature')
                    ->label('Pindah')
                    ->formatStateUsing(function (SpkPandu $order) {
                        return $order->keperluan == 'pindah'
                            ? date('d/m/Y', strtotime($order->waktu_gerak))
                            : '-';
                    })
                    ->searchable(),
                // TextColumn::make('waktu_gerak')
                // ->label('Tanggal')
                // ->formatStateUsing(fn ($state) => Date('d/m/Y',strtotime($state))),
            ])
            ->filters([
                //
                SelectFilter::make('agent_id')
                    ->label('Pilih Agent')
                    ->searchable()
                    ->options(fn () => User::whereHas('roles', fn ($query) => $query->whereIn('name', ['pandu']))->pluck('name', 'id')->toArray()),
                DateRangeFilter::make('created_at')
                    ->label('Range Nota'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('generateinvoice')
                    ->label('Proses Invoice')
                    ->color('primary')
                    ->hidden(fn (Tables\Contracts\HasTable $livewire): bool => !($livewire->getTableFilterState('agent_id')['value'] && $livewire->getTableFilterState('created_at')['created_at']))
                    // ->form([
                    //     DatePicker::make('payment_date')
                    //     ->label('Tanggal Jatuh Tempo')
                    //     ->required()
                    // ])
                    ->icon('fas-file-invoice')
                    ->requiresConfirmation()
                    ->action(function (Collection $records, Tables\Contracts\HasTable $livewire,array $data) {
                        $collection = $records->each->collection->pluck('id');

                        try {
                            DB::beginTransaction();
                            $agent = $livewire->getTableFilterState('agent_id')['value'];

                            $rangeNota = $livewire->getTableFilterState('created_at')['created_at'];

                            // Pisahkan string berdasarkan tanda '-'
                            $dates = explode(' - ', $rangeNota);

                            // Mengubah string tanggal menjadi objek Carbon
                            $startDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay(); // Tanggal mulai
                            $endDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay(); // Tanggal akhir

                            // Hitung jumlah SpkPandu berdasarkan rentang tanggal
                            $spk_pandus = SpkPandu::whereIn('id', $collection)->where('agent_id', $agent)->whereIn('keperluan',['masuk','keluar'])->whereIn('status',['setuju','selesai'])->whereNotNull('nomor_nota')->where('is_process_by_finance',0)->whereBetween('created_at', [$startDate, $endDate])->get();

                            if ($spk_pandus->isEmpty()) {
                                return Notification::make()
                                    ->title('Gagal!')
                                    ->body('Belum Ada Nota!')
                                    ->danger()
                                    ->send();
                            }

                            $invoice = Invoice::create([
                                'user_id'=>$agent,
                                // 'payment_date'=>$data['payment_date'],
                                // 'number_invoice'=>self::generateInvoiceNumber(),
                            ]);

                            $invoice_status = InvoiceStatus::create([
                                'invoice_id'=>$invoice->id,
                                'name'=>'Draft',
                                'status'=>'draft',
                            ]);

                            $configuration = Configuration::first();

                            foreach($spk_pandus as $spk_pandu) {
                                $waktu_gerak = Carbon::parse($spk_pandu->waktu_gerak);

                                InvoiceItem::create([
                                    'invoice_id'=>$invoice->id,
                                    'invoice_status_id'=>$invoice_status->id,
                                    'spk_pandu_id'=>$spk_pandu->id,
                                    'vessel_master_id'=>$spk_pandu->pkk->requestable->vesselRequestTB->id,
                                    'nomor_nota'=>$spk_pandu->nomor_nota,
                                    'in'=>$spk_pandu->keperluan == 'masuk' ? $waktu_gerak->toDateString() : null,
                                    'out'=>$spk_pandu->keperluan == 'keluar'? $waktu_gerak->toDateString() : null,
                                    'move'=>$spk_pandu->keperluan == 'pindah'? $waktu_gerak->toDateString() : null,
                                    'scouting'=>$configuration->pemanduan,
                                    'procrastination'=>$spk_pandu->keperluan == 'pindah' ? 0 : $configuration->penundaan,
                                ]);

                                $spk_pandu->is_process_by_finance = 1;
                                $spk_pandu->save();
                            }

                            $invoice = Invoice::find($invoice->id);

                            $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->where('invoice_status_id', $invoice_status->id)->get();

                            $scouting = $invoiceItem->sum('scouting');
                            $procrastination = $invoiceItem->sum('procrastination');

                            $invoice->scouting = $scouting;
                            $invoice->procrastination = $procrastination;
                            $sub_total_price = $scouting + $procrastination;

                            // Set the sub_total_price on the invoice status
                            $invoice->sub_total_price = $sub_total_price;

                            // Define the tax rate (replace with actual value if needed)
                            $invoice->tax = $configuration->tax;
                            $invoice->check_tax_price = true;
                            $invoice->check_dpp_ppn = true;
                            $invoice->dpp_ppn = 11 / 12 * $sub_total_price;
                            $tax_rate = $configuration->tax / 100;

                            // Calculate the tax price
                            $tax_price = $invoice->dpp_ppn * $tax_rate;

                            // Calculate the grand total price
                            $grand_total_price = $sub_total_price + $tax_price;

                            // Set the calculated tax_price and grand_total_price
                            $invoice->tax_price = $tax_price;
                            $invoice->grand_total_price = $grand_total_price;
                            $invoice->save();
                            DB::commit();
                        } catch (\Exception | \Throwable $th) {
                            DB::rollBack();
                            Log::error($th->getMessage());
                            Log::error('Ada kesalahan saat menambah data Invoice');
                            return Notification::make()
                                ->title('Gagal')
                                ->body('Ada kesalahan saat menambah data Invoice')
                                ->danger()
                                ->send();
                        };

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data Invoice berhasil di simpan!')
                            ->success()
                            ->send();
                        redirect('/invoice/invoice/'.$invoice->id.'/edit');
                    }),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('keperluan',['masuk','keluar'])->whereIn('status',['setuju','selesai'])->whereNotNull('nomor_nota')->where('is_process_by_finance',0));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}

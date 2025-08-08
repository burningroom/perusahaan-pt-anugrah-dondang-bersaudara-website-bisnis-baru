<?php

namespace App\Livewire\Receipt;

use App\Models\Receipt;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Detail extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $invoice;

    public function mount($invoice) {
        $this->invoice = $invoice;
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Receipt::query()->where('invoice_id', $this->invoice))
            ->columns([
                //
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('No.')
                    ->alignCenter()
                    ->width('10px'),
                TextColumn::make('number_receipt')
                    ->label('Nomor Kwitansi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grand_total_price')
                    ->label('Grand Total')
                    ->formatStateUsing(fn ($state) => 'Rp. '.number_format($state,0,',','.'))
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                //
                Action::make('print')
                    ->icon('fas-print')
                    ->label('Cetak Kwitansi')
                    ->url(function (Receipt $record) {
                        $url = '/cetak/kwitansi/'.$record->id;
                        return $url;
                    })
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.receipt.detail');
    }
}

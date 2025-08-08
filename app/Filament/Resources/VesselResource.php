<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VesselResource\Pages;
use App\Models\VesselMaster;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VesselResource extends Resource
{
    protected static ?string $model = VesselMaster::class;

    protected static ?string $navigationLabel = 'Data Kapal';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon = 'icon-boat';


    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Section::make('')
                    ->columns(12)
                    ->schema([
                        Select::make('user_id')
                            ->label('Agent')
                            ->preload()
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->searchable()
                            ->live()
                            ->columnSpan(12),

                        TextInput::make('registration_sign')
                            ->label('Tanda Pendaftaran Kapal')
                            ->required()
                            ->placeholder('Masukkan tanda pendaftaran kapal')
                            ->columnSpan(4),

                        TextInput::make('name')
                            ->label('Nama Kapal')
                            ->required()
                            ->placeholder('Masukkan nama kapal')
                            ->columnSpan(4),

                        TextInput::make('code')
                            ->label('Kode Kapal')
                            ->required()
                            ->placeholder('Masukkan kode kapal')
                            ->columnSpan(4),

                        TextInput::make('type')
                            ->label('Tipe Kapal')
                            ->required()
                            ->placeholder('Masukkan tipe kapal (contoh: TB/BG)')
                            ->columnSpan(2),

                        TextInput::make('drt')
                            ->label('DWT')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan DRT kapal')
                            ->columnSpan(2),

                        TextInput::make('grt')
                            ->label('GRT')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan GRT kapal')
                            ->columnSpan(2),

                        TextInput::make('loa')
                            ->label('LOA')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan panjang LOA kapal (meter)')
                            ->columnSpan(2),

                        TextInput::make('kind')
                            ->label('Jenis Kapal')
                            ->required()
                            ->placeholder('Masukkan jenis kapal')
                            ->columnSpan(2),

                        TextInput::make('width')
                            ->label('Lebar Kapal')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan lebar kapal (meter)')
                            ->columnSpan(2),

                        TextInput::make('max_draft')
                            ->label('Draft Maksimum')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan draft maksimum kapal (meter)')
                            ->columnSpan(3),

                        TextInput::make('front_draft')
                            ->label('Draft Depan')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan draft depan kapal (meter)')
                            ->columnSpan(3),

                        TextInput::make('back_draft')
                            ->label('Draft Belakang')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan draft belakang kapal (meter)')
                            ->columnSpan(3),

                        TextInput::make('central_draft')
                            ->label('Draft Tengah')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan draft tengah kapal (meter)')
                            ->columnSpan(3),

                        TextInput::make('route_type')
                            ->label('Jenis Trayek')
                            ->required()
                            ->placeholder('Masukkan jenis trayek kapal')
                            ->columnSpan(3),

                        TextInput::make('flag')
                            ->label('Bendera')
                            ->required()
                            ->placeholder('Masukkan bendera kapal')
                            ->columnSpan(3),

                        TextInput::make('call_sign')
                            ->label('Call Sign')
                            ->required()
                            ->placeholder('Masukkan call sign kapal')
                            ->columnSpan(3),

                        TextInput::make('imo_number')
                            ->label('IMO Number')
                            ->required()
                            ->placeholder('Masukkan nomor IMO kapal')
                            ->columnSpan(3),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                //
                TextColumn::make('user.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('registration_sign')
                    ->label('Tanda Pendaftaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Kapal')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => Str::title($state)),

                TextColumn::make('code')
                    ->label('Kode Kapal')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                 Tables\Actions\EditAction::make(),
                ViewAction::make()
                    ->modalWidth(12),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListVessels::route('/'),
            'create' => Pages\CreateVessel::route('/create'),
            'edit' => Pages\EditVessel::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Data User';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('EMAIL')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make("username")
                            ->label('USERNAME')
                            ->string()
                            ->nullable()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('NO TELEPON')
                            ->string()
                            ->nullable()
                            ->prefix('+62')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable(),
                        Select::make('role')
                            ->label('HAK AKSES')
                            ->required()
                            ->live()
                            ->placeholder('Pilih Hak Akses..')
                            ->relationship('roles')
                            ->getOptionLabelFromRecordUsing(fn($record) => Str::title(Str::replace('_', ' ', $record?->name)))
                            ->native(false)
                            ->searchable()
                            ->preload()
                    ]),
                Section::make('Detail Perusahaan')
                    ->columns(12)
                    ->schema([
                        TextInput::make('company.name')
                            ->label('Nama Perusahaan')
                            ->required(fn(Get $get) => $get('role') != null && Role::find($get('role'))[0]?->name == 'agent')
                            ->columnSpan(4),
                        TextInput::make('company.npwp')
                            ->label('NPWP')
                            ->required(fn(Get $get) => $get('role') != null && Role::find($get('role'))[0]?->name == 'agent')
                            ->columnSpan(4),
                        TextInput::make('company.sktd')
                            ->label('No. SKTD')
                            ->required(fn(Get $get) => $get('role_id') != null && Role::find($get('role_id'))[0]?->name == 'agent')
                            ->columnSpan(4),
                        TextInput::make('company.city')
                            ->label('Kota')
                            ->columnSpan(6),
                        TextInput::make('company.address')
                            ->label('Alamat')
                            ->columnSpan(6),
                        TextInput::make('company.phone')
                            ->label('No. Telepon Perusahaan')
                            ->prefix('+62')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(4),
                        TextInput::make('company.email')
                            ->label('Email')
                            ->email()
                            ->columnSpan(4),
                        TextInput::make('company.website')
                            ->label('Alamat Website')
                            ->columnSpan(4),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Hak Akses')
                    ->formatStateUsing(fn($state) => Str::title(Str::replace('_', ' ', $state)))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

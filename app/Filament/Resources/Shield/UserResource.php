<?php

namespace App\Filament\Resources\Shield;

use App\Filament\Clusters\HalamanPengaturan;
use App\Filament\Resources\Shield\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Forms\Components\{DateTimePicker, Grid, TextInput, Select};
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $navigationIcon = 'fas-user-gear';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $navigationGroup = 'Pengaturan Hak Akses';

    protected static ?string $slug = 'pengguna';

    protected static ?string $breadcrumb = 'Pengguna';

    protected static ?string $cluster = HalamanPengaturan::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'download_backup',
            'delete_backup',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Pengguna')
                            ->placeholder('Masukkan nama pengguna')
                            ->maxLength(255),
                        TextInput::make('username')
                            ->hint('Username tidak dapat diubah.')
                            ->placeholder('Masukkan username pengguna. Digunakan untuk login.')
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Masukkan email pengguna')
                            ->email()
                            ->maxLength(255),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Terverifikasi Pada'),
                        TextInput::make('password')
                            ->placeholder('Masukkan kata sandi pengguna')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create'),
                        Select::make('roles')
                            ->label('Peran Pengguna')
                            ->relationship('roles', 'name')->preload()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('email')
                    ->placeholder('Belum ada email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->colors(['primary', 'secondary', 'success', 'danger', 'warning', 'info'])
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->hasRole('Admin'))->button()->iconSize(IconSize::Small),
                Tables\Actions\DeleteAction::make()->hidden(fn($record) => $record->hasRole('Admin'))->button()->iconSize(IconSize::Small),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record->name;
    }
}

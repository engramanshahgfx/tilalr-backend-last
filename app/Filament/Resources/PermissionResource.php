<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    use Concerns\HasResourcePermissions;

    protected static ?string $model = Permission::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.administration');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.permission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.permissions');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.permissions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Permission Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Permission Key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Format: resource.action (e.g., users.create)')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('display_name')
                            ->label('Display Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('group')
                            ->label('Group')
                            ->helperText('Used for organizing permissions (e.g., Users, Trips)')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->maxLength(500),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(fn () => __('admin.form.permission_key'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->formatStateUsing(fn (string $state) => __("permissions.{$state}", ['default' => $state])),

                Tables\Columns\TextColumn::make('display_name')
                    ->label(fn () => __('admin.form.display_name'))
                    ->getStateUsing(function ($record) {
                        // Use the permission name (key) to look up the translation
                        $key = $record->name;
                        return __("permissions.{$key}_desc", ['default' => $record->display_name]);
                    })
                    ->searchable('display_name')
                    ->sortable('display_name'),

                Tables\Columns\TextColumn::make('group')
                    ->label(fn () => __('admin.form.group'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles_count')
                    ->label(fn () => __('admin.resources.roles'))
                    ->counts('roles')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => Permission::whereNotNull('group')->distinct()->pluck('group', 'group')->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group')
            ->defaultGroup('group');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canViewAny(): bool
    {
        return static::isSuperAdmin(auth()->user());
    }

    public static function canCreate(): bool
    {
        return static::isSuperAdmin(auth()->user());
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return static::isSuperAdmin(auth()->user());
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return static::isSuperAdmin(auth()->user());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}

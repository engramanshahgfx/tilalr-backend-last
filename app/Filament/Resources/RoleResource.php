<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    use Concerns\HasResourcePermissions;

    protected static ?string $model = Role::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.administration');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.roles');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Role Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Role Key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique identifier (e.g., content_manager)')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('display_name')
                            ->label('Display Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->maxLength(500),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Permissions Assignment')
                    ->description('Select permissions for this role grouped by navigation section. Super Admin automatically has full access.')
                    ->schema([
                        Forms\Components\Tabs::make('PermissionsCategories')
                            ->tabs(
                                array_map(function ($key, $config) {
                                    return Forms\Components\Tabs\Tab::make($config['tab_name'])
                                        ->icon($config['icon'])
                                        ->schema([
                                            Forms\Components\CheckboxList::make("perm_{$key}")
                                                ->label($config['tab_name'] . ' Permissions')
                                                ->options(
                                                    Permission::whereIn('group', $config['groups'])
                                                        ->pluck('display_name', 'id')
                                                        ->toArray()
                                                )
                                                ->columns(3)
                                                ->bulkToggleable()
                                                ->dehydrated(false),
                                        ]);
                                }, array_keys(\App\Providers\Filament\AdminPanelProvider::getNavigationMap()), \App\Providers\Filament\AdminPanelProvider::getNavigationMap())
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(fn () => __('admin.form.role_key'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'executive_manager' => 'warning',
                        'consultant' => 'success',
                        'administration' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('display_name')
                    ->label(fn () => __('admin.form.display_name'))
                    ->getStateUsing(function ($record) {
                        if (!empty($record->display_name) && !str_starts_with($record->display_name, 'roles.')) {
                            return $record->display_name;
                        }
                        $key = $record->name;
                        if (\Illuminate\Support\Facades\Lang::has("roles.{$key}")) {
                            return __("roles.{$key}");
                        }
                        return ucwords(str_replace(['_', '-'], ' ', $key));
                    })
                    ->searchable('display_name')
                    ->sortable('display_name'),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label(fn () => __('admin.resources.permissions'))
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label(fn () => __('admin.resources.users'))
                    ->counts('users')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(fn () => __('admin.form.is_active') ?? 'Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (Role $record) => $record->name === 'super_admin'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (Role $record) => $record->name === 'super_admin'),
            ])
            ->bulkActions([
                // Disabled to prevent checkbox display issues
            ])
            ->defaultSort('sort_order');
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
        if ($record->name === 'super_admin') {
            return false;
        }
        return static::isSuperAdmin(auth()->user());
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        if ($record->name === 'super_admin') {
            return false;
        }
        return static::isSuperAdmin(auth()->user());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use UnitEnum;
use BackedEnum;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use Concerns\HasResourcePermissions;

    protected static ?string $model = User::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.users');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.users');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.users');
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.form.customer_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin.form.full_name')),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label(__('admin.form.email_address')),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->label(__('admin.form.phone_number')),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => !empty($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->label(__('admin.form.password'))
                            ->helperText(fn (string $context): string => $context === 'edit' ? __('admin.form.leave_blank_password') : ''),

                        Forms\Components\Toggle::make('is_admin')
                            ->label(__('admin.form.admin_access'))
                            ->helperText(__('admin.form.admin_access_helper'))
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('admin.form.role_assignment'))
                    ->description(__('admin.form.role_assignment_desc'))
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship('roles', 'display_name')
                            ->columns(3)
                            ->searchable()
                            ->bulkToggleable()
                            ->descriptions(
                                Role::pluck('description', 'id')->filter()->toArray()
                            )
                            ->helperText(__('admin.form.select_roles')),
                    ]),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.table.name')),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.table.email')),

                Tables\Columns\TextColumn::make('roles.display_name')
                    ->label(__('admin.table.roles'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Super Admin' => 'danger',
                        'Executive Manager' => 'warning',
                        'Consultant' => 'success',
                        'Content Manager' => 'info',
                        default => 'gray',
                    })
                    ->separator(', '),

                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean()
                    ->label(__('admin.form.admin_access'))
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('admin.table.created_at'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('admin.table.updated_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_admin')
                    ->label(__('admin.form.type'))
                    ->options([
                        '0' => __('admin.resources.users'),
                        '1' => __('admin.nav.administration'),
                    ])
                    ->default('0')
                    ->placeholder(__('admin.misc.all')),

                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'display_name')
                    ->label(__('admin.resources.role'))
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('admin.actions.edit'))
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->label(__('admin.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('admin.actions.bulk_delete'))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_admin', false)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}

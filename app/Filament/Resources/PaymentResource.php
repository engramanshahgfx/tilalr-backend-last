<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Filament\Resources\Concerns\HasResourcePermissions;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = Payment::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.payments');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.payment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.payments');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.payments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_id')
                    ->label(__('admin.form.booking_id'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('method')
                    ->label(__('admin.form.method'))
                    ->required()
                    ->maxLength(255)
                    ->default('dummy'),
                Forms\Components\TextInput::make('status')
                    ->label(__('admin.form.status'))
                    ->required(),
                Forms\Components\TextInput::make('transaction_id')
                    ->label(__('admin.form.transaction_id'))
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('meta')
                    ->label(__('admin.form.meta'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_id')
                    ->label(__('admin.form.booking_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->label(__('admin.table.method'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('admin.table.status')),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label(__('admin.table.transaction_id'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('admin.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(__('admin.actions.edit')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label(__('admin.actions.bulk_delete')),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

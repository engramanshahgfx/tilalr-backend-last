<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomPaymentOfferResource\Pages;
use App\Models\CustomPaymentOffer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class CustomPaymentOfferResource extends Resource
{
    use Concerns\HasResourcePermissions;
    use Concerns\HasTranslations;

    protected static ?string $model = CustomPaymentOffer::class;
    protected static ?string $permissionKey = 'custom_payment_offers';
<<<<<<< HEAD
=======

    protected static bool $shouldRegisterNavigation = false;
>>>>>>> 54ebecf6b7242dc5515172bc87b4895b853545c3

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.payments');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.custom_payment_offer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.custom_payment_offers');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.custom_payment_offers');
    }

    /**
     * Access control based on permissions
     * Super Admin bypasses all checks, others need specific permissions
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // Super Admin has full access
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        // Check if user has view permission for custom payment offers
        return $user->hasPermission('custom_payment_offers.view');
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return $user->hasPermission('custom_payment_offers.view');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return $user->hasPermission('custom_payment_offers.create');
    }

    public static function canEdit($record): bool
    {
        return false; // Offers should not be edited after creation
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // Super Admin can delete pending offers
        if ($user->hasRole('super_admin')) {
            return $record->payment_status === 'pending';
        }
        
        // Others need delete permission and offer must be pending
        return $user->hasPermission('custom_payment_offers.delete') && $record->payment_status === 'pending';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->description('Enter the customer details for this payment offer')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->placeholder('Enter customer full name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email Address')
                            ->placeholder('customer@example.com')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Phone Number')
                            ->placeholder('+966 5XX XXX XXXX')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Payment Details')
                    ->description('Specify the payment amount and description')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount (SAR)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->step(0.01)
                            ->prefix('﷼')
                            ->placeholder('0.00')
                            ->helperText('Enter the amount in Saudi Riyals'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description / Notes')
                            ->placeholder('Describe what this payment is for...')
                            ->required()
                            ->maxLength(1000)
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('This will be visible to the customer on the payment page'),
                    ]),

                Forms\Components\Section::make('Payment Status')
                    ->description('Current payment status and transaction details')
                    ->icon('heroicon-o-document-check')
                    ->schema([
                        Forms\Components\Select::make('payment_status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('moyasar_transaction_id')
                            ->label('Moyasar Transaction ID')
                            ->disabled()
                            ->placeholder('Will be set after payment')
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->visible(fn ($livewire) => $livewire instanceof Pages\ViewCustomPaymentOffer),

                Forms\Components\Section::make('Payment Link')
                    ->description('Share this link with the customer')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Placeholder::make('payment_link_display')
                            ->label('Payment Link')
                            ->content(function ($record) {
                                if (!$record) return 'Will be generated after creation';
                                $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
                                return $frontendUrl . '/en/pay-custom-offer/' . $record->unique_link;
                            }),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\ViewCustomPaymentOffer),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->sortable()
                    ->money('SAR')
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'paid', 'completed' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                        'cancelled' => 'heroicon-o-minus-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                Tables\Columns\TextColumn::make('moyasar_transaction_id')
                    ->label('Transaction ID')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('copyLink')
                    ->label(__('admin.actions.copy_link'))
                    ->icon('heroicon-o-clipboard-document')
                    ->color('info')
                    ->requiresConfirmation(false)
                    ->action(function (CustomPaymentOffer $record) {
                        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
                        $paymentLink = $frontendUrl . '/en/pay-custom-offer/' . $record->unique_link;

                        Notification::make()
                            ->title(__('admin.notifications.payment_link_copied'))
                            ->body('Payment Link:' . PHP_EOL . $paymentLink)
                            ->success()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('copy')
                                    ->label('Copy URL')
                                    ->button()
                                    ->close()
                                    ->extraAttributes([
                                        'onclick' => "navigator.clipboard.writeText(" . json_encode($paymentLink) . "); this.parentElement.parentElement.remove();",
                                    ]),
                            ])
                            ->send();
                    })
                    ->visible(fn(CustomPaymentOffer $record) => $record->payment_status === 'pending' && (auth()->user()?->hasRole('super_admin') || auth()->user()?->hasPermission('custom_payment_offers.view_payment_link'))),
                Tables\Actions\ViewAction::make()
                    ->visible(fn() => auth()->user()?->hasRole('super_admin') || auth()->user()?->hasPermission('custom_payment_offers.manage_payments')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(CustomPaymentOffer $record) => $record->payment_status === 'pending' && (auth()->user()?->hasRole('super_admin') || auth()->user()?->hasPermission('custom_payment_offers.delete'))),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()?->hasRole('super_admin') || auth()->user()?->hasPermission('custom_payment_offers.delete')),
                ]),
            ])
            ->emptyStateHeading('No Custom Payment Offers')
            ->emptyStateDescription('Create a custom payment offer to send to your customers.')
            ->emptyStateIcon('heroicon-o-currency-dollar');
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
            'index' => Pages\ListCustomPaymentOffers::route('/'),
            'create' => Pages\CreateCustomPaymentOffer::route('/create'),
            'view' => Pages\ViewCustomPaymentOffer::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('payment_status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}

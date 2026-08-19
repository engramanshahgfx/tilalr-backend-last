<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use App\Models\TourismDestination;
use App\Models\TourismOffer;
use App\Models\JamoulaOffer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;

class CouponResource extends Resource
{
    use Concerns\HasResourcePermissions;

    protected static ?string $model = Coupon::class;
    protected static ?string $permissionKey = 'coupons';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Tourism';
    protected static ?int $navigationSort = 10;
    protected static ?string $label = 'Discount Coupon';
    protected static ?string $pluralLabel = 'Discount Coupons';
    protected static bool $shouldRegisterNavigation = true;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // CARD 1: Campaign Identity & Automation Strategy
                Section::make('CARD 1: Campaign Identity & Automation Strategy')
                    ->collapsible()
                    ->schema([
                        TextInput::make('code')
                            ->label('Promotion Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('e.g. SUMMER5, WELCOME10')
                            ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state))),

                        TextInput::make('name')
                            ->label('Campaign Name / Internal Tag')
                            ->placeholder('e.g. Q3 Meta Ads, Summer Flash Sale')
                            ->maxLength(255),

                        Select::make('distribution_scope')
                            ->label('Distribution Scope')
                            ->options([
                                'public' => '🔓 Public (Default)',
                                'private' => '🔐 Private / Direct Sale',
                                'user_restricted' => '👑 User Restricted',
                            ])
                            ->default('public')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'archived' => 'Archived',
                            ])
                            ->default('active')
                            ->required(),

                        Toggle::make('auto_apply')
                            ->label('Auto-Apply at Checkout')
                            ->default(false)
                            ->helperText('If ON, automatically applies at checkout when customer qualifies.'),
                    ])->columns(2),

                // CARD 2: Value Engine & Mathematical Safeguards
                Section::make('CARD 2: Value Engine & Mathematical Safeguards')
                    ->collapsible()
                    ->schema([
                        Select::make('type')
                            ->label('Calculation Method')
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed Deduction (SAR)',
                            ])
                            ->default('percentage')
                            ->required(),

                        TextInput::make('value')
                            ->label('Base Discount Value')
                            ->numeric()
                            ->required()
                            ->placeholder('e.g. 5 for 5% or 50.00 for SAR 50'),

                        TextInput::make('min_booking_amount')
                            ->label('Cart Floor (Min Booking Subtotal)')
                            ->numeric()
                            ->nullable()
                            ->placeholder('e.g. 500 (Blank = No Minimum)'),

                        TextInput::make('max_discount_amount')
                            ->label('Smart Ceiling (Max Discount Cap)')
                            ->numeric()
                            ->nullable()
                            ->placeholder('e.g. 200 (Blank = No Cap)'),

                        Toggle::make('is_stackable')
                            ->label('Stackable with Other Offers')
                            ->default(false),
                    ])->columns(2),

                // CARD 3: Dynamic Category, Package Scope & Targeting
                Section::make('CARD 3: Dynamic Category, Package Scope & Targeting')
                    ->collapsible()
                    ->schema([
                        Select::make('category_type')
                            ->label('Offer Category Selection')
                            ->options([
                                'all' => '🌍 All Offers (Default)',
                                'international' => '✈️ International Destinations',
                                'tourism_offer' => '🏜️ Tourism Offers',
                                'jamoula_offer' => '🌴 Jamoula Offers',
                            ])
                            ->default('all')
                            ->reactive()
                            ->required(),

                        Select::make('applicable_package_ids')
                            ->label('Specific Packages Only (Optional)')
                            ->multiple()
                            ->options(function (callable $get) {
                                $category = $get('category_type');
                                $options = [];

                                if (in_array($category, ['all', 'international'])) {
                                    $destinations = TourismDestination::pluck('title_en', 'id')->toArray();
                                    foreach ($destinations as $id => $title) {
                                        $options["dest_{$id}"] = "✈️ International: {$title}";
                                    }
                                }
                                if (in_array($category, ['all', 'tourism_offer'])) {
                                    $offers = TourismOffer::pluck('title_en', 'id')->toArray();
                                    foreach ($offers as $id => $title) {
                                        $options["offer_{$id}"] = "🏜️ Offer: {$title}";
                                    }
                                }
                                if (in_array($category, ['all', 'jamoula_offer'])) {
                                    $jOffers = JamoulaOffer::pluck('title_en', 'id')->toArray();
                                    foreach ($jOffers as $id => $title) {
                                        $options["jamoula_{$id}"] = "🌴 Jamoula: {$title}";
                                    }
                                }
                                return $options;
                            })
                            ->searchable()
                            ->nullable()
                            ->helperText('Leave empty to apply to all packages in selected category.'),

                        Select::make('target_cohort')
                            ->label('Customer Cohort Targeting')
                            ->options([
                                'everyone' => '● Everyone (Default)',
                                'first_time' => '○ First-Time Buyers Only',
                                'specified_users' => '○ Specified Emails Only',
                            ])
                            ->default('everyone')
                            ->reactive(),

                        TagsInput::make('target_emails')
                            ->label('Allowed Email Addresses')
                            ->placeholder('Enter email and press Enter')
                            ->visible(fn (callable $get) => $get('target_cohort') === 'specified_users')
                            ->nullable(),
                    ])->columns(2),

                // CARD 4: Velocity Controls, Anti-Fraud & Schedule Window
                Section::make('CARD 4: Velocity Controls, Anti-Fraud & Schedule Window')
                    ->collapsible()
                    ->schema([
                        TextInput::make('max_uses')
                            ->label('Total Global Redemption Cap')
                            ->numeric()
                            ->nullable()
                            ->placeholder('e.g. 100 uses total (Blank = Unlimited)'),

                        TextInput::make('max_uses_per_user')
                            ->label('Customer Redemption Limit (Per User)')
                            ->numeric()
                            ->nullable()
                            ->default(1)
                            ->placeholder('e.g. 1 (Blank = Unlimited)'),

                        DateTimePicker::make('starts_at')
                            ->label('Starts At (Asia/Riyadh)')
                            ->nullable(),

                        DateTimePicker::make('expires_at')
                            ->label('Expires At (Asia/Riyadh)')
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Discount')
                    ->formatStateUsing(fn ($record) => $record->type === 'percentage' ? "{$record->value}%" : "SAR {$record->value}")
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'archived',
                        'warning' => 'inactive',
                    ]),

                TextColumn::make('uses_count')
                    ->label('Redemptions')
                    ->formatStateUsing(fn ($record) => $record->max_uses ? "{$record->uses_count} / {$record->max_uses}" : "{$record->uses_count} / ∞")
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'archived' => 'Archived',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}

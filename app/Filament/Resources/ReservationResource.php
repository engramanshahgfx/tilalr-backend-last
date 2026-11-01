<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\Concerns\HasResourcePermissions;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    use HasResourcePermissions;
    use Concerns\HasTranslations;

    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static ?string $navigationGroup = 'Bookings';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.bookings');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.reservation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.reservations');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.reservations');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.form.customer_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('admin.form.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('admin.form.email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Trip Information')
                    ->schema([
                        Forms\Components\Select::make('trip_type')
                            ->options([
                                'activity' => 'Activity',
                                'hotel' => 'Hotel',
                                'flight' => 'Flight',
                                'package' => 'Package',
                                'school' => 'School Trip',
                                'corporate' => 'Corporate Trip',
                                'family' => 'Family Trip',
                                'private' => 'Private Group Trip',
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('trip_title')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('trip_slug')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('preferred_date')
                            ->label('Preferred Date'),
                        Forms\Components\TextInput::make('guests')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Hotel Booking Details')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('details.roomCount')
                            ->label('Number of Rooms')
                            ->numeric(),
                        Forms\Components\Toggle::make('details.roomsNearEachOther')
                            ->label('Rooms Near Each Other')
                            ->helperText('Guest requested adjacent/nearby rooms'),
                        Forms\Components\TextInput::make('details.roomsNearEachOtherCount')
                            ->label('Adjacent Rooms Count')
                            ->numeric()
                            ->helperText('How many rooms should be adjacent'),
                        Forms\Components\TextInput::make('details.roomType')
                            ->label('Room Type')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('details.checkInDate')
                            ->label('Check-in Date')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('details.checkOutDate')
                            ->label('Check-out Date')
                            ->maxLength(100),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Customer Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('details')
                            ->label('All Additional Details (JSON)')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status & Management')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'contacted' => 'Contacted',
                                'confirmed' => 'Confirmed',
                                'converted' => 'Converted to Booking',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false)
                            ->default('pending'),
                        Forms\Components\Toggle::make('admin_contacted')
                            ->label('Customer Contacted')
                            ->helperText('Check if you have contacted the customer'),
                        Forms\Components\DateTimePicker::make('contacted_at')
                            ->label('Contacted At')
                            ->disabled(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->helperText('Internal notes (not visible to customer)')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('trip_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'activity' => 'Activity',
                        'hotel' => 'Hotel',
                        'flight' => 'Flight',
                        'package' => 'Package',
                        'school' => 'School',
                        'corporate' => 'Corporate',
                        'family' => 'Family',
                        'private' => 'Private',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match($state) {
                        'activity' => 'success',
                        'hotel' => 'info',
                        'flight' => 'warning',
                        'package' => 'primary',
                        'school' => 'info',
                        'corporate' => 'warning',
                        'family' => 'success',
                        'private' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('trip_title')
                    ->limit(30)
                    ->toggleable()
                    ->label('Destination'),
                Tables\Columns\TextColumn::make('details.country')
                    ->label('Country')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A'),
                Tables\Columns\TextColumn::make('details.city')
                    ->label('City')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A'),
                Tables\Columns\TextColumn::make('details.numberOfGuests')
                    ->label('Booking Guests')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A'),
                Tables\Columns\TextColumn::make('details.roomCount')
                    ->label('Rooms')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ? $state . (intval($state) > 1 ? ' rooms' : ' room') : 'N/A'),
                Tables\Columns\TextColumn::make('details.roomsNearEachOther')
                    ->label('Near Each Other')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state === true ? '✓ Yes' : ($state === false ? 'No' : 'N/A'))
                    ->color(fn ($state) => $state === true ? 'success' : ($state === false ? 'gray' : 'gray')),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guests')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'contacted' => 'info',
                        'confirmed' => 'success',
                        'converted' => 'primary',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('admin_contacted')
                    ->label('Contacted')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Submitted'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'contacted' => 'Contacted',
                        'confirmed' => 'Confirmed',
                        'converted' => 'Converted',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('trip_type')
                    ->options([
                        'activity' => 'Activity',
                        'hotel' => 'Hotel',
                        'flight' => 'Flight',
                        'package' => 'Package',
                        'school' => 'School Trip',
                        'corporate' => 'Corporate Trip',
                        'family' => 'Family Trip',
                        'private' => 'Private Group',
                    ]),
                Tables\Filters\TernaryFilter::make('admin_contacted')
                    ->label('Contacted Status')
                    ->placeholder('All')
                    ->trueLabel('Contacted')
                    ->falseLabel('Not Contacted'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('markContacted')
                    ->label('Mark Contacted')
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Reservation $record) {
                        $record->update([
                            'admin_contacted' => true,
                            'contacted_at' => now(),
                            'status' => $record->status === 'pending' ? 'contacted' : $record->status,
                        ]);
                        Notification::make()
                            ->title('Reservation marked as contacted')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Reservation $record) => !$record->admin_contacted),
                Tables\Actions\Action::make('convertToBooking')
                    ->label('Convert to Booking')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Convert Reservation to Booking')
                    ->modalDescription('This will create a new booking from this reservation. The customer will need to complete payment.')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Booking Amount (SAR)')
                            ->numeric()
                            ->required()
                            ->prefix('SAR'),
                    ])
                    ->action(function (Reservation $record, array $data) {
<<<<<<< HEAD
=======
<<<<<<< Updated upstream
>>>>>>> 54ebecf6b7242dc5515172bc87b4895b853545c3
                         $booking = \App\Models\Booking::create([
                             'booking_number' => \App\Models\Booking::generateBookingNumber(),
                             'user_id' => null,
                             'first_name' => $record->name,
                             'last_name' => '',
                             'email' => $record->email,
                             'mobile' => $record->phone,
                             'date' => $record->preferred_date,
                             'guests' => $record->guests,
                             'details' => array_merge($record->details ?? [], [
                                 'trip_slug' => $record->trip_slug,
                                 'trip_title' => $record->trip_title,
                                 'name' => $record->name,
                                 'email' => $record->email,
                                 'phone' => $record->phone,
                                 'amount' => $data['amount'],
                                 'converted_from_reservation' => $record->id,
                             ]),
                             'status' => 'pending',
                             'payment_status' => 'pending',
                         ]);
<<<<<<< HEAD
=======
=======
                        $nameParts = explode(' ', trim($record->name ?? 'Customer'), 2);
                        $firstName = $record->details['first_name'] 
                            ?? $record->details['company_name'] 
                            ?? ($nameParts[0] ?? 'Customer');

                        $lastName = $record->details['last_name'] 
                            ?? ($nameParts[1] ?? 'Reservation');

                        $packageId = $record->trip_slug ?? 'custom-reservation';
                        $packageCode = $record->details['trip_code'] ?? ('RES-' . $record->id);
                        $packageTitle = $record->trip_title ?? $record->details['package_name'] ?? 'Package Reservation';

                        $booking = \App\Models\Booking::create([
                            'booking_number' => \App\Models\Booking::generateBookingNumber(),
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $record->email ?: 'customer@example.com',
                            'mobile' => $record->phone ?: 'N/A',
                            'user_id' => $record->user_id,
                            'travel_date' => $record->preferred_date,
                            'package_id' => $packageId,
                            'package_code' => $packageCode,
                            'package_title' => $packageTitle,
                            'guests' => $record->guests,
                            'total_amount' => $data['amount'],
                            'price' => $data['amount'],
                            'status' => 'pending',
                            'payment_status' => 'pending',
                            'notes' => $record->notes,
                        ]);
>>>>>>> Stashed changes
>>>>>>> 54ebecf6b7242dc5515172bc87b4895b853545c3
                        
                        $record->update([
                            'status' => 'converted',
                            'converted_booking_id' => $booking->id,
                        ]);
                        
                        Notification::make()
                            ->title('Reservation converted to booking #' . $booking->booking_number)
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Reservation $record) => !$record->isConverted()),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAllContacted')
                        ->label('Mark as Contacted')
                        ->icon('heroicon-o-phone')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'admin_contacted' => true,
                                    'contacted_at' => now(),
                                    'status' => $record->status === 'pending' ? 'contacted' : $record->status,
                                ]);
                            });
                            Notification::make()
                                ->title('Selected reservations marked as contacted')
                                ->success()
                                ->send();
                        }),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ReservationResource\Widgets\ReservationStatsOverview::class,
        ];
    }
}

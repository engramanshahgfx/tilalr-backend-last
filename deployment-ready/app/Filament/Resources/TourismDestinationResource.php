<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourismDestinationResource\Pages;
use App\Models\TourismDestination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TourismDestinationResource extends Resource
{
    protected static ?string $model = TourismDestination::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $navigationGroup = 'Tourism';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = true;

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getModelLabel(): string
    {
        return 'International Destination';
    }

    public static function getPluralModelLabel(): string
    {
        return 'International Destinations';
    }

    public static function getNavigationLabel(): string
    {
        return 'International Destinations';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('International Destination Details')
                    ->tabs([
                        // ============ BASIC INFO TAB ============
                        Forms\Components\Tabs\Tab::make('Basic Info')
                            ->schema([
                                Forms\Components\Section::make('Basic Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('region')
                                            ->options([
                                                'europe' => 'Europe',
                                                'asia' => 'Asia',
                                                'africa' => 'Africa',
                                                'australia' => 'Australia & New Zealand',
                                                'america' => 'America',
                                            ])
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('trip_code')
                                            ->label('Trip Code')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\DatePicker::make('available_to')
                                            ->label('Available To')
                                            ->columnSpan(1),
                                    ])->columns(2),
                            ]),

                        // ============ ENGLISH CONTENT TAB ============
                        Forms\Components\Tabs\Tab::make('English Content')
                            ->schema([
                                Forms\Components\Section::make('English Specifications')
                                    ->schema([
                                        Forms\Components\TextInput::make('title_en')
                                            ->required()
                                            ->label('Title (English)')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('description_en')
                                            ->label('Short Description (English)')
                                            ->rows(3),
                                        Forms\Components\RichEditor::make('long_description_en')
                                            ->label('Long Description (English)')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline', 'strike',
                                                'blockquote', 'bulletList', 'orderedList',
                                                'link', 'image', 'undo', 'redo',
                                            ]),
                                        Forms\Components\TextInput::make('location_en')
                                            ->label('Location (English)')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('duration_en')
                                            ->label('Duration (English)')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('features_en')
                                            ->label('Package Includes (English)')
                                            ->placeholder('Enter one feature per line')
                                            ->rows(4)
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : (is_string($state) ? implode("\n", json_decode($state, true) ?? [$state]) : ''))
                                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? array_values(array_filter(array_map('trim', explode("\n", $state)))) : $state),

                                   
                                        Forms\Components\Textarea::make('not_includes_en')
                                            ->label('Package Not Includes (English)')
                                            ->placeholder('Enter one item per line')
                                            ->rows(4)
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : (is_string($state) ? implode("\n", json_decode($state, true) ?? [$state]) : ''))
                                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? array_values(array_filter(array_map('trim', explode("\n", $state)))) : $state),

                                        Forms\Components\Textarea::make('itinerary_en')
                                            ->label('Itinerary (English)')
                                            ->placeholder('Format: Day|Title|Description (one day per line)')
                                            ->rows(6)
                                            ->formatStateUsing(function ($state) {
                                                $items = is_array($state) ? $state : (is_string($state) ? json_decode($state, true) ?? [] : []);
                                                $lines = [];
                                                foreach ($items as $item) {
                                                    $lines[] = ($item['day'] ?? '') . '|' . ($item['title'] ?? '') . '|' . ($item['description'] ?? '');
                                                }
                                                return implode("\n", $lines);
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    $result = [];
                                                    foreach ($lines as $line) {
                                                        $parts = explode('|', $line);
                                                        $result[] = [
                                                            'day' => trim($parts[0] ?? ''),
                                                            'title' => trim($parts[1] ?? ''),
                                                            'description' => trim($parts[2] ?? ''),
                                                        ];
                                                    }
                                                    return array_values($result);
                                                }
                                                return $state;
                                            }),
                                    ]),
                            ]),

                        // ============ ARABIC CONTENT TAB ============
                        Forms\Components\Tabs\Tab::make('Arabic Content')
                            ->schema([
                                Forms\Components\Section::make('Arabic Specifications')
                                    ->schema([
                                        Forms\Components\TextInput::make('title_ar')
                                            ->required()
                                            ->label('Title (Arabic)')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('description_ar')
                                            ->label('Short Description (Arabic)')
                                            ->rows(3),
                                        Forms\Components\RichEditor::make('long_description_ar')
                                            ->label('Long Description (Arabic)')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline', 'strike',
                                                'blockquote', 'bulletList', 'orderedList',
                                                'link', 'image', 'undo', 'redo',
                                            ]),
                                        Forms\Components\TextInput::make('location_ar')
                                            ->label('Location (Arabic)')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('duration_ar')
                                            ->label('Duration (Arabic)')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('features_ar')
                                            ->label('Package Includes (Arabic)')
                                            ->placeholder('أدخل كل ميزة في سطر منفصل')
                                            ->rows(4)
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : (is_string($state) ? implode("\n", json_decode($state, true) ?? [$state]) : ''))
                                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? array_values(array_filter(array_map('trim', explode("\n", $state)))) : $state),

                                        Forms\Components\Textarea::make('includes_ar')
                                            ->label('Package Includes (Arabic)')
                                            ->placeholder('أدخل كل عنصر في سطر منفصل')
                                            ->rows(4)
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : (is_string($state) ? implode("\n", json_decode($state, true) ?? [$state]) : ''))
                                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? array_values(array_filter(array_map('trim', explode("\n", $state)))) : $state),

                                        Forms\Components\Textarea::make('not_includes_ar')
                                            ->label('Package Not Includes (Arabic)')
                                            ->placeholder('أدخل كل عنصر في سطر منفصل')
                                            ->rows(4)
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : (is_string($state) ? implode("\n", json_decode($state, true) ?? [$state]) : ''))
                                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? array_values(array_filter(array_map('trim', explode("\n", $state)))) : $state),

                                        Forms\Components\Textarea::make('itinerary_ar')
                                            ->label('Itinerary (Arabic)')
                                            ->placeholder('التنسيق: Day|Title|Description (يوم في كل سطر)')
                                            ->rows(6)
                                            ->formatStateUsing(function ($state) {
                                                $items = is_array($state) ? $state : (is_string($state) ? json_decode($state, true) ?? [] : []);
                                                $lines = [];
                                                foreach ($items as $item) {
                                                    $lines[] = ($item['day'] ?? '') . '|' . ($item['title'] ?? '') . '|' . ($item['description'] ?? '');
                                                }
                                                return implode("\n", $lines);
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    $result = [];
                                                    foreach ($lines as $line) {
                                                        $parts = explode('|', $line);
                                                        $result[] = [
                                                            'day' => trim($parts[0] ?? ''),
                                                            'title' => trim($parts[1] ?? ''),
                                                            'description' => trim($parts[2] ?? ''),
                                                        ];
                                                    }
                                                    return array_values($result);
                                                }
                                                return $state;
                                            }),
                                    ]),
                            ]),

                        // ============ IMAGES TAB ============
                        Forms\Components\Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\Section::make('Media Assets')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('16:9')
                                            ->imageResizeTargetWidth('1200')
                                            ->imageResizeTargetHeight('675')
                                            ->maxSize(5120)
                                            ->directory('tourism')
                                            ->visibility('public')
                                            ->preserveFilenames()
                                            ->helperText('Upload a destination image. Recommended size: 1200x675px (16:9)')
                                            ->loadingIndicatorPosition('left')
                                            ->panelLayout('grid')
                                            ->uploadingMessage('Uploading image...'),

                                        Forms\Components\FileUpload::make('images')
                                            ->label('Gallery Images')
                                            ->multiple()
                                            ->directory('tourism-gallery')
                                            ->visibility('public')
                                            ->preserveFilenames()
                                            ->helperText('Upload multiple gallery images.')
                                            ->panelLayout('grid'),
                                    ]),
                            ]),

                        // ============ PRICING & RATING TAB ============
                        Forms\Components\Tabs\Tab::make('Pricing & Rating')
                            ->schema([
                                Forms\Components\Section::make('Pricing Configurations')
                                    ->schema([
                                        Forms\Components\Repeater::make('person_prices')
                                            ->label('Person Offers & Pricing (Dynamic)')
                                            ->schema([
                                                Forms\Components\TextInput::make('persons')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(1)
                                                    ->label('Number of Persons (e.g., 1, 2, 3, 4...)')
                                                    ->placeholder('1'),
                                                Forms\Components\TextInput::make('price')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('SAR')
                                                    ->label('Price for this Offer (SAR)')
                                                    ->placeholder('2500.00'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(1)
                                            ->createItemButtonLabel('Add New Person Offer')
                                            ->columnSpanFull()
                                            ->helperText('Add custom price tiers based on the number of persons (e.g. 1 Person = 2500 SAR, 2 Persons = 4000 SAR, etc.)'),
                                        Forms\Components\TextInput::make('price')
                                            ->nullable()
                                            ->numeric()
                                            ->label('Base Starting Price (SAR)')
                                            ->prefix('SAR')
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('rating')
                                            ->required()
                                            ->default(4.5)
                                            ->numeric()
                                            ->step(0.1)
                                            ->minValue(0)
                                            ->maxValue(5)
                                            ->label('Rating (0-5)')
                                            ->columnSpan(1),
                                    ])->columns(2),
                            ]),

                        // ============ STATUS TAB ============
                        Forms\Components\Tabs\Tab::make('Status')
                            ->schema([
                                Forms\Components\Section::make('Status Controls')
                                    ->schema([
                                        Forms\Components\Toggle::make('active')
                                            ->label('Active')
                                            ->default(true)
                                            ->columnSpan(1),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->square()
                    ->size(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title_en')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('title_ar')
                    ->label('Title (Arabic)')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('region')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'europe' => 'info',
                        'asia' => 'warning',
                        'africa' => 'success',
                        'australia' => 'primary',
                        'america' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'europe' => 'Europe',
                        'asia' => 'Asia',
                        'africa' => 'Africa',
                        'australia' => 'Australia & NZ',
                        'america' => 'America',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('location_en')
                    ->label('Location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('double_room_price')
                    ->label('Double Room')
                    ->money('SAR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('single_room_price')
                    ->label('Single Room')
                    ->money('SAR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => '' . $state),
                Tables\Columns\TextColumn::make('duration_en')
                    ->label('Duration')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region')
                    ->options([
                        'europe' => 'Europe',
                        'asia' => 'Asia',
                        'africa' => 'Africa',
                        'australia' => 'Australia & New Zealand',
                        'america' => 'America',
                    ])
                    ->label('Region'),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('lg'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTourismDestinations::route('/'),
            'create' => Pages\CreateTourismDestination::route('/create'),
            'edit' => Pages\EditTourismDestination::route('/{record}/edit'),
        ];
    }
}

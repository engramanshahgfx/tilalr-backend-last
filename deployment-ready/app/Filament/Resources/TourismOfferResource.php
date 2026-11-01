<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourismOfferResource\Pages;
use App\Models\TourismOffer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class TourismOfferResource extends Resource
{
    protected static ?string $model = TourismOffer::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Tourism';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Tourism Offer';
    protected static ?string $pluralLabel = 'Saudi Offers';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Saudi Offer Details')
                    ->tabs([
                        // ============ BASIC INFO TAB ============
                        Tabs\Tab::make('Basic Info')
                            ->schema([
                                Section::make('Basic Information')
                                    ->schema([
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('Unique URL identifier (e.g., maldives-paradise)'),

                                        TextInput::make('type')
                                            ->required()
                                            ->default('international')
                                            ->maxLength(50)
                                            ->helperText('e.g., international, local, vip'),

                                        Select::make('region')
                                            ->options([
                                                'asia' => 'Asia',
                                                'europe' => 'Europe',
                                                'africa' => 'Africa',
                                                'americas' => 'Americas',
                                                'oceania' => 'Oceania',
                                                'middle_east' => 'Middle East',
                                            ]),

                                        TextInput::make('country')->maxLength(100),
                                        TextInput::make('city')->maxLength(100),
                                    ])->columns(2),
                            ]),

                        // ============ ENGLISH CONTENT TAB ============
                        Tabs\Tab::make('English Content')
                            ->schema([
                                Section::make('English Content')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Title (English)')
                                            ->required()
                                            ->maxLength(255),

                                        Textarea::make('description_en')
                                            ->label('Short Description (English)')
                                            ->rows(3)
                                            ->maxLength(500),

                                        RichEditor::make('long_description_en')
                                            ->label('Long Description (English)')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline', 'strike',
                                                'blockquote', 'bulletList', 'orderedList',
                                                'link', 'image', 'undo', 'redo',
                                            ]),

                                        TextInput::make('duration_en')
                                            ->label('Duration (English)')
                                            ->placeholder('e.g., 7 Days'),

                                        TextInput::make('location_en')
                                            ->label('Location (English)')
                                            ->placeholder('e.g., Maldives'),

                                        TextInput::make('group_size_en')
                                            ->label('Group Size (English)')
                                            ->placeholder('e.g., 2-4 People'),

                                        Textarea::make('features_en')
                                            ->label('Package Includes (English)')
                                            ->placeholder('Enter each feature on a new line')
                                            ->rows(4)
                                            ->helperText('Enter one feature per line')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        return implode("\n", $decoded);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    return implode("\n", $state);
                                                }
                                                return '';
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    return json_encode(array_values($lines));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),

                                        Textarea::make('not_includes_en')
                                            ->label('Package Not Includes (English)')
                                            ->placeholder('Enter each item on a new line')
                                            ->rows(4)
                                            ->helperText('Enter one item per line')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        return implode("\n", $decoded);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    return implode("\n", $state);
                                                }
                                                return '';
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    return json_encode(array_values($lines));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),

                                        Textarea::make('itinerary_en')
                                            ->label('Itinerary (English)')
                                            ->placeholder('Enter each day in format: Day|Title|Description')
                                            ->rows(6)
                                            ->helperText('Format: Day|Title|Description (one day per line)')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        $lines = [];
                                                        foreach ($decoded as $item) {
                                                            $lines[] = ($item['day'] ?? '') . '|' . ($item['title'] ?? '') . '|' . ($item['description'] ?? '');
                                                        }
                                                        return implode("\n", $lines);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    $lines = [];
                                                    foreach ($state as $item) {
                                                        $lines[] = ($item['day'] ?? '') . '|' . ($item['title'] ?? '') . '|' . ($item['description'] ?? '');
                                                    }
                                                    return implode("\n", $lines);
                                                }
                                                return '';
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
                                                    return json_encode(array_values($result));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),
                                    ])->columns(2),
                            ]),

                        // ============ ARABIC CONTENT TAB ============
                        Tabs\Tab::make('Arabic Content')
                            ->schema([
                                Section::make('Arabic Content')
                                    ->schema([
                                        TextInput::make('title_ar')
                                            ->label('Title (Arabic)')
                                            ->required()
                                            ->maxLength(255),

                                        Textarea::make('description_ar')
                                            ->label('Short Description (Arabic)')
                                            ->rows(3)
                                            ->maxLength(500),

                                        RichEditor::make('long_description_ar')
                                            ->label('Long Description (Arabic)')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline', 'strike',
                                                'blockquote', 'bulletList', 'orderedList',
                                                'link', 'image', 'undo', 'redo',
                                            ]),

                                        TextInput::make('duration_ar')
                                            ->label('Duration (Arabic)')
                                            ->placeholder('e.g., ٧ أيام'),

                                        TextInput::make('location_ar')
                                            ->label('Location (Arabic)')
                                            ->placeholder('e.g., المالديف'),

                                        TextInput::make('group_size_ar')
                                            ->label('Group Size (Arabic)')
                                            ->placeholder('e.g., ٢-٤ أشخاص'),

                                        Textarea::make('features_ar')
                                            ->label('Package Includes (Arabic)')
                                            ->placeholder('Enter each feature on a new line')
                                            ->rows(4)
                                            ->helperText('Enter one feature per line')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        return implode("\n", $decoded);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    return implode("\n", $state);
                                                }
                                                return '';
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    return json_encode(array_values($lines));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),

                                        Textarea::make('includes_ar')
                                            ->label('Package Includes (Arabic)')
                                            ->placeholder('Enter each include on a new line')
                                            ->rows(4)
                                            ->helperText('Enter one item per line')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        return implode("\n", $decoded);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    return implode("\n", $state);
                                                }
                                                return '';
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    return json_encode(array_values($lines));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),

                                        Textarea::make('not_includes_ar')
                                            ->label('Package Not Includes (Arabic)')
                                            ->placeholder('Enter each item on a new line')
                                            ->rows(4)
                                            ->helperText('Enter one item per line')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        return implode("\n", $decoded);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    return implode("\n", $state);
                                                }
                                                return '';
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $lines = array_filter(array_map('trim', explode("\n", $state)));
                                                    return json_encode(array_values($lines));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),

                                        Textarea::make('itinerary_ar')
                                            ->label('Itinerary (Arabic)')
                                            ->placeholder('Enter each day in format: Day|Title|Description')
                                            ->rows(6)
                                            ->helperText('Format: Day|Title|Description (one day per line)')
                                            ->formatStateUsing(function ($state) {
                                                if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (is_array($decoded)) {
                                                        $lines = [];
                                                        foreach ($decoded as $item) {
                                                            $lines[] = ($item['day'] ?? '') . '|' . ($item['title'] ?? '') . '|' . ($item['description'] ?? '');
                                                        }
                                                        return implode("\n", $lines);
                                                    }
                                                    return $state;
                                                }
                                                if (is_array($state)) {
                                                    $lines = [];
                                                    foreach ($state as $item) {
                                                        $lines[] = ($item['day'] ?? '') . '|' . ($item['title'] ?? '') . '|' . ($item['description'] ?? '');
                                                    }
                                                    return implode("\n", $lines);
                                                }
                                                return '';
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
                                                    return json_encode(array_values($result));
                                                }
                                                if (is_array($state)) {
                                                    return json_encode(array_values($state));
                                                }
                                                return json_encode([]);
                                            }),
                                    ])->columns(2),
                            ]),

                        // ============ IMAGES TAB ============
                        Tabs\Tab::make('Images')
                            ->schema([
                                Section::make('Images')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Main Image')
                                            ->image()
                                            ->directory('offers/main')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(5120)
                                            ->helperText('Max file size: 5MB. Recommended: 1920x1080'),

                                        FileUpload::make('gallery')
                                            ->label('Gallery Images')
                                            ->image()
                                            ->directory('offers/gallery')
                                            ->visibility('public')
                                            ->multiple()
                                            ->imageEditor()
                                            ->maxSize(5120)
                                            ->helperText('Upload multiple images. Max file size: 5MB each.'),
                                    ]),
                            ]),

                        // ============ PRICING TAB ============
                        Tabs\Tab::make('Pricing & Stats')
                            ->schema([
                                Section::make('Pricing')
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
                                            ->helperText('Add custom price tiers based on number of persons (e.g. 1 Person = 2500 SAR, 2 Persons = 4000 SAR, etc.)'),

                                        TextInput::make('price')
                                            ->label('Base Starting Price (SAR)')
                                            ->nullable()
                                            ->numeric()
                                            ->prefix('SAR')
                                            ->default(0)
                                            ->step(0.01),

                                        TextInput::make('original_price')
                                            ->label('Original Price (SAR)')
                                            ->numeric()
                                            ->prefix('SAR')
                                            ->step(0.01)
                                            ->helperText('If set, shows a strike-through price'),

                                        TextInput::make('discount')
                                            ->label('Discount (%)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%')
                                            ->helperText('e.g., 20 for 20% off'),

                                        TextInput::make('rating')
                                            ->label('Rating')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(5)
                                            ->step(0.1)
                                            ->default(0)
                                            ->helperText('e.g., 4.8'),
                                    ])->columns(2),
                            ]),

                        // ============ STATUS TAB ============
                        Tabs\Tab::make('Status')
                            ->schema([
                                Section::make('Status & Visibility')
                                    ->schema([
                                        Toggle::make('active')
                                            ->label('Active')
                                            ->default(true)
                                            ->helperText('Show this offer on the website'),

                                        Toggle::make('popular')
                                            ->label('Popular')
                                            ->default(false)
                                            ->helperText('Mark as popular offer'),

                                        Toggle::make('limited')
                                            ->label('Limited Offer')
                                            ->default(false)
                                            ->helperText('Show limited time offer badge'),
                                    ])->columns(1),
                            ]),

                        // ============ META TAGS TAB ============
                        Tabs\Tab::make('Meta Tags')
                            ->schema([
                                Section::make('Meta Tags (SEO)')
                                    ->schema([
                                        TextInput::make('meta_title_en')
                                            ->label('Meta Title (English)')
                                            ->maxLength(255)
                                            ->helperText('SEO title for English version'),

                                        TextInput::make('meta_title_ar')
                                            ->label('Meta Title (Arabic)')
                                            ->maxLength(255)
                                            ->helperText('SEO title for Arabic version'),

                                        Textarea::make('meta_description_en')
                                            ->label('Meta Description (English)')
                                            ->rows(2)
                                            ->maxLength(500)
                                            ->helperText('SEO description for English version'),

                                        Textarea::make('meta_description_ar')
                                            ->label('Meta Description (Arabic)')
                                            ->rows(2)
                                            ->maxLength(500)
                                            ->helperText('SEO description for Arabic version'),

                                        Textarea::make('meta_keywords_en')
                                            ->label('Meta Keywords (English)')
                                            ->rows(2)
                                            ->helperText('Comma separated keywords for English version'),

                                        Textarea::make('meta_keywords_ar')
                                            ->label('Meta Keywords (Arabic)')
                                            ->rows(2)
                                            ->helperText('Comma separated keywords for Arabic version'),
                                    ])->columns(1),
                            ]),

                     ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/placeholder.png'),

                TextColumn::make('title_en')
                    ->label('Title (EN)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title_ar')
                    ->label('Title (AR)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'international' => 'success',
                        'local' => 'info',
                        'vip' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('SAR')
                    ->sortable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? '⭐ ' . $state : '-'),

                ToggleColumn::make('active')
                    ->label('Active')
                    ->sortable(),

                ToggleColumn::make('popular')
                    ->label('Popular')
                    ->sortable(),

                ToggleColumn::make('limited')
                    ->label('Limited')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'international' => 'International',
                        'local' => 'Local',
                        'vip' => 'VIP',
                    ]),

                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),

                Tables\Filters\TernaryFilter::make('popular')
                    ->label('Popular')
                    ->placeholder('All')
                    ->trueLabel('Popular Only')
                    ->falseLabel('Not Popular'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Toggle Active Status')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['active' => !$record->active]);
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTourismOffers::route('/'),
            'create' => Pages\CreateTourismOffer::route('/create'),
            'edit' => Pages\EditTourismOffer::route('/{record}/edit'),
            'view' => Pages\ViewTourismOffer::route('/{record}'),
        ];
    }
}

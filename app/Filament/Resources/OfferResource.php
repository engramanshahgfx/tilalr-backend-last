<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Filament\Resources\Concerns\HasResourcePermissions;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Offer as OfferModel;

class OfferResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = Offer::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.offer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.offers');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.offers');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('admin.form.language_tools'))
                ->schema([
                    Forms\Components\Toggle::make('copy_en_to_ar')
                        ->label(__('admin.form.copy_en_to_ar'))
                        ->helperText(__('admin.form.copy_helper'))
                        ->dehydrated(false)
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set, $get, $context) {
                            if ($state && ($context === 'create' || $context === 'edit')) {
                                $set('title_ar', $get('title_en'));
                                $set('description_ar', $get('description_en'));
                                $set('location_ar', $get('location_en'));
                                $set('duration_ar', $get('duration_en') ?? $get('duration'));
                                $set('group_size_ar', $get('group_size_en') ?? $get('group_size'));
                                $set('badge_ar', $get('badge_en') ?? $get('badge'));
                                $set('features_ar', $get('features_en') ?? $get('features') ?? []);
                                $set('highlights_ar', $get('highlights_en') ?? $get('highlights') ?? []);
                                // reset toggle so it can be used again
                                $set('copy_en_to_ar', false);
                            }
                        }),
                ])->columns(1),

            Forms\Components\Tabs::make('Content')
                ->tabs([
                    Forms\Components\Tabs\Tab::make(__('admin.form.english_tab'))
                        ->schema([
                            // Basic fields
                            Forms\Components\TextInput::make('title_en')->required()->label(__('admin.form.title_en')),
                            Forms\Components\Textarea::make('description_en')->label(__('admin.form.description_en')),
                            Forms\Components\TextInput::make('location_en')->label(__('admin.form.location_en')),

                            // Details
                            Forms\Components\TextInput::make('duration_en')->label(__('admin.form.duration') . ' (EN)'),
                            Forms\Components\TextInput::make('group_size_en')->label(__('admin.form.group_size_en')),
                            Forms\Components\TextInput::make('badge_en')->label(__('admin.form.badge_en')),
                            Forms\Components\TagsInput::make('features_en')->label(__('admin.form.features_en'))->placeholder(__('admin.placeholders.add_feature')),
                            Forms\Components\TagsInput::make('highlights_en')->label(__('admin.form.highlights_en'))->placeholder(__('admin.placeholders.add_highlight')),
                        ])->columnSpanFull(),

                    Forms\Components\Tabs\Tab::make(__('admin.form.arabic_tab'))
                        ->schema([
                            // Basic fields (Arabic)
                            Forms\Components\TextInput::make('title_ar')->required()->label(__('admin.form.title_ar'))->extraAttributes(['dir'=>'rtl']),
                            Forms\Components\Textarea::make('description_ar')->label(__('admin.form.description_ar'))->extraAttributes(['dir'=>'rtl']),
                            Forms\Components\TextInput::make('location_ar')->label(__('admin.form.location_ar'))->extraAttributes(['dir'=>'rtl']),

                            // Details (Arabic)
                            Forms\Components\TextInput::make('duration_ar')->label(__('admin.form.duration') . ' (AR)')->extraAttributes(['dir' => 'rtl']),
                            Forms\Components\TextInput::make('group_size_ar')->label(__('admin.form.group_size_ar'))->extraAttributes(['dir' => 'rtl']),
                            Forms\Components\TextInput::make('badge_ar')->label(__('admin.form.badge_ar'))->extraAttributes(['dir' => 'rtl']),
                            Forms\Components\TagsInput::make('features_ar')->label(__('admin.form.features_ar'))->placeholder(__('admin.placeholders.add_feature'))->extraAttributes(['dir' => 'rtl']),
                            Forms\Components\TagsInput::make('highlights_ar')->label(__('admin.form.highlights_ar'))->placeholder(__('admin.placeholders.add_highlight'))->extraAttributes(['dir' => 'rtl']),
                        ])->columnSpanFull(),

                    Forms\Components\Tabs\Tab::make('Chinese')
                        ->schema([
                            // Basic fields (Chinese)
                            Forms\Components\TextInput::make('title_zh')->label('Title (Chinese)'),
                            Forms\Components\Textarea::make('description_zh')->label('Description (Chinese)'),
                            Forms\Components\TextInput::make('location_zh')->label('Location (Chinese)'),

                            // Details (Chinese)
                            Forms\Components\TextInput::make('duration_zh')->label('Duration (Chinese)'),
                            Forms\Components\TextInput::make('group_size_zh')->label('Group Size (Chinese)'),
                            Forms\Components\TextInput::make('badge_zh')->label('Badge (Chinese)'),
                            Forms\Components\TagsInput::make('features_zh')->label('Features (Chinese)')->placeholder('Enter features'),
                            Forms\Components\TagsInput::make('highlights_zh')->label('Highlights (Chinese)')->placeholder('Enter highlights'),
                        ])->columnSpanFull(),
                ])->columnSpanFull(),

            // Keep discount as a single (non-localized) field for now
            Forms\Components\Section::make(__('admin.form.pricing'))
                ->schema([
                    Forms\Components\TextInput::make('discount')->label(__('admin.form.discount')),
                ])->columns(1),

            Forms\Components\Section::make(__('admin.form.media_status'))
                ->schema([
                    Forms\Components\FileUpload::make('image')->image()->directory('offers')->label(__('admin.form.image')),
                    Forms\Components\Toggle::make('is_active')->label(__('admin.form.is_active'))->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {        if (app()->environment('local')) {
            try {
                Log::debug('OfferResource::table called — Offer count: ' . OfferModel::count());
            } catch (\Throwable $e) {
                Log::debug('OfferResource::table debug failed: ' . $e->getMessage());
            }
        }
        return $table->columns([
            Tables\Columns\TextColumn::make('title_en')->label(__('admin.form.title_en'))->searchable()->sortable(),
            Tables\Columns\TextColumn::make('duration')->label(__('admin.form.duration')),
            Tables\Columns\TextColumn::make('discount')->label(__('admin.form.discount')),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label(__('admin.table.active')),
        ])->actions([
            Tables\Actions\EditAction::make()->label(__('admin.actions.edit')),
            Tables\Actions\DeleteAction::make()->label(__('admin.actions.delete')),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()->label(__('admin.actions.bulk_delete')),
            ]),
        ]);
    }


    public static function getEloquentQuery(): Builder
    {
        // Use a direct unscoped query to avoid any global scopes or accidental filters
        return OfferModel::query();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}

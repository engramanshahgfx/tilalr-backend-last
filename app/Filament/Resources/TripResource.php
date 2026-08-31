<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TripResource\Pages;
use App\Filament\Resources\Concerns\HasResourcePermissions;
use App\Models\Trip;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class TripResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = Trip::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.trip');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.trips');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.trips');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make(__('admin.resources.trip'))
                ->schema([
                    TextInput::make('title')->hidden(),
                    TextInput::make('title_trans.en')->label(__('admin.form.title_en'))->required()->maxLength(255),
                    TextInput::make('title_trans.ar')->label(__('admin.form.title_ar'))->extraAttributes(['dir' => 'rtl']),
                    TextInput::make('title_trans.zh')->label('Title (ZH)')->maxLength(255),
                    TextInput::make('slug')->label(__('admin.form.slug'))->unique(ignoreRecord: true)->maxLength(255),
                    Select::make('lang')->label(__('admin.form.language'))->options(['en' => 'EN', 'ar' => 'AR', 'zh' => 'ZH'])->default('en')->required(),
                    Select::make('city_id')->label(__('admin.form.destination_choose'))->options(City::all()->pluck('name', 'id'))->searchable()->placeholder(__('admin.placeholders.select_city')),
                    TextInput::make('city_name')->label(__('admin.form.destination_custom'))->helperText(__('admin.form.destination_custom_helper')),
                    TextInput::make('price')->label(__('admin.form.price'))->numeric()->prefix(__('admin.currency.sar')),
                    TextInput::make('duration')->label(__('admin.form.duration'))->numeric()->suffix(__('admin.time.days')),
                    TextInput::make('type')->label(__('admin.form.type'))->maxLength(100),
                    DatePicker::make('start_date')->label(__('admin.form.start_date')),
                    DatePicker::make('end_date')->label(__('admin.form.end_date')),
                    FileUpload::make('video')->label(__('admin.form.video'))->disk('public')->directory('videos'),
                    Textarea::make('description')->label(__('admin.form.description'))->rows(3)->helperText('Legacy single-language field'),
                    Textarea::make('description_trans.en')->label(__('admin.form.description_en'))->rows(3),
                    Textarea::make('description_trans.ar')->label(__('admin.form.description_ar'))->rows(3)->extraAttributes(['dir' => 'rtl']),
                    Textarea::make('description_trans.zh')->label('Description (ZH)')->rows(3),
                    Forms\Components\TagsInput::make('highlights')->label(__('admin.form.highlights'))->placeholder(__('admin.placeholders.add_highlight')),
                    TextInput::make('group_size')->label(__('admin.form.group_size')),
                    FileUpload::make('image')
                        ->label(__('admin.form.image'))
                        ->disk('public')
                        ->directory('trips')
                        ->image(),
                    Toggle::make('is_active')->label(__('admin.form.is_active'))->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label(__('admin.table.title'))->searchable()->sortable(),
                TextColumn::make('city.name')->label(__('admin.table.destination'))->sortable(),
                TextColumn::make('price')->label(__('admin.table.price'))->money('SAR'),
                IconColumn::make('is_active')->label(__('admin.table.active'))->boolean(),
                TextColumn::make('created_at')->label(__('admin.table.created_at'))->dateTime('M d, Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(__('admin.actions.edit')),
                Tables\Actions\DeleteAction::make()->label(__('admin.actions.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label(__('admin.actions.bulk_delete')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}

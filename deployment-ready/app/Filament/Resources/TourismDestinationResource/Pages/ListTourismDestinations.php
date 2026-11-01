<?php

namespace App\Filament\Resources\TourismDestinationResource\Pages;

use App\Filament\Resources\TourismDestinationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourismDestinations extends ListRecords
{
    protected static string $resource = TourismDestinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth('lg'),
        ];
    }
}

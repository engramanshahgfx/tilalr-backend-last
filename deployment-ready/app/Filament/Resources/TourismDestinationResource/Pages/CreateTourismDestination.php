<?php

namespace App\Filament\Resources\TourismDestinationResource\Pages;

use App\Filament\Resources\TourismDestinationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTourismDestination extends CreateRecord
{
    protected static string $resource = TourismDestinationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure image is properly set
        if (isset($data['image']) && is_array($data['image'])) {
            $data['image'] = $data['image'][0] ?? null;
        }
        return $data;
    }
}

<?php

namespace App\Filament\Resources\TourismDestinationResource\Pages;

use App\Filament\Resources\TourismDestinationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourismDestination extends EditRecord
{
    protected static string $resource = TourismDestinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure image is properly set
        if (isset($data['image']) && is_array($data['image'])) {
            $data['image'] = $data['image'][0] ?? null;
        }
        return $data;
    }
}

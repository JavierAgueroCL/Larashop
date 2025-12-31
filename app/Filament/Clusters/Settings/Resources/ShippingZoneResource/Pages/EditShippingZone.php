<?php

namespace App\Filament\Clusters\Settings\Resources\ShippingZoneResource\Pages;

use App\Filament\Clusters\Settings\Resources\ShippingZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingZone extends EditRecord
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

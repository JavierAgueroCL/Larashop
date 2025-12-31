<?php

namespace App\Filament\Clusters\Sales\Resources\ShipmentResource\Pages;

use App\Filament\Clusters\Sales\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShipments extends ListRecords
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Clusters\Sales\Resources\OrderResource\Pages;

use App\Filament\Clusters\Sales\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(), // Usually orders come from frontend
        ];
    }
}

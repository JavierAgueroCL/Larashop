<?php

namespace App\Filament\Clusters\Customers\Resources\CustomerGroupResource\Pages;

use App\Filament\Clusters\Customers\Resources\CustomerGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerGroups extends ListRecords
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

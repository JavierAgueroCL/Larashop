<?php

namespace App\Filament\Clusters\Customers\Resources\CustomerGroupResource\Pages;

use App\Filament\Clusters\Customers\Resources\CustomerGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerGroup extends CreateRecord
{
    protected static string $resource = CustomerGroupResource::class;
}

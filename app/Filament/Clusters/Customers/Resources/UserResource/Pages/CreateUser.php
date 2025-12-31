<?php

namespace App\Filament\Clusters\Customers\Resources\UserResource\Pages;

use App\Filament\Clusters\Customers\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

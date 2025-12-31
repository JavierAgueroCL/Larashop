<?php

namespace App\Filament\Clusters\Customers\Resources\UserResource\Pages;

use App\Filament\Clusters\Customers\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

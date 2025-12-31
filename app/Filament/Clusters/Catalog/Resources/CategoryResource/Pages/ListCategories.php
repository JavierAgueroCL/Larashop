<?php

namespace App\Filament\Clusters\Catalog\Resources\CategoryResource\Pages;

use App\Filament\Clusters\Catalog\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

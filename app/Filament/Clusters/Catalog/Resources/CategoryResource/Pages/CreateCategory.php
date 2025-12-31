<?php

namespace App\Filament\Clusters\Catalog\Resources\CategoryResource\Pages;

use App\Filament\Clusters\Catalog\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}

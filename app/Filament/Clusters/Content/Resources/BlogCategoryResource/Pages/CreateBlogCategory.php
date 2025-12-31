<?php

namespace App\Filament\Clusters\Content\Resources\BlogCategoryResource\Pages;

use App\Filament\Clusters\Content\Resources\BlogCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogCategory extends CreateRecord
{
    protected static string $resource = BlogCategoryResource::class;
}

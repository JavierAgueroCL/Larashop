<?php

namespace App\Filament\Clusters\Content\Resources\BlogPostResource\Pages;

use App\Filament\Clusters\Content\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;
}

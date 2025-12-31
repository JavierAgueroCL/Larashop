<?php

namespace App\Filament\Clusters\Catalog\Resources\AttributeValueResource\Pages;

use App\Filament\Clusters\Catalog\Resources\AttributeValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttributeValue extends EditRecord
{
    protected static string $resource = AttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

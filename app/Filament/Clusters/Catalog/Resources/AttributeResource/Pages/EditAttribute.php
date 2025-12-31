<?php

namespace App\Filament\Clusters\Catalog\Resources\AttributeResource\Pages;

use App\Filament\Clusters\Catalog\Resources\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Clusters\Marketing\Resources\PriceRuleResource\Pages;

use App\Filament\Clusters\Marketing\Resources\PriceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceRule extends EditRecord
{
    protected static string $resource = PriceRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

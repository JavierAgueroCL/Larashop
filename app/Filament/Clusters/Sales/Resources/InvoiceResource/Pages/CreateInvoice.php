<?php

namespace App\Filament\Clusters\Sales\Resources\InvoiceResource\Pages;

use App\Filament\Clusters\Sales\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}

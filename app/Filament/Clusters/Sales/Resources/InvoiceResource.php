<?php

namespace App\Filament\Clusters\Sales\Resources;

use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Sales\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Sales\SalesCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('order_id')->relationship('order', 'name')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('invoice_number')->maxLength(255),
                Forms\Components\DateTimePicker::make('invoice_date'),
                Forms\Components\TextInput::make('subtotal')->numeric()->prefix('$'),
                Forms\Components\TextInput::make('tax_total')->numeric()->prefix('$'),
                Forms\Components\TextInput::make('grand_total')->numeric()->prefix('$'),
                Forms\Components\FileUpload::make('pdf_path')->image()->directory('pdf_paths')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('order.name')->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('invoice_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('subtotal')->money('clp')->sortable(),
                Tables\Columns\TextColumn::make('tax_total')->money('clp')->sortable(),
                Tables\Columns\TextColumn::make('grand_total')->money('clp')->sortable(),
                Tables\Columns\ImageColumn::make('pdf_path')
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}

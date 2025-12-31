<?php

namespace App\Filament\Clusters\Sales\Resources;

use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Sales\Resources\ShipmentResource\Pages;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Sales\SalesCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('order_id')->relationship('order', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('carrier_id')->relationship('carrier', 'name')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('tracking_number')->maxLength(255),
                Forms\Components\DateTimePicker::make('shipped_at'),
                Forms\Components\DateTimePicker::make('delivered_at')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('order.name')->sortable(),
                Tables\Columns\TextColumn::make('carrier.name')->sortable(),
                Tables\Columns\TextColumn::make('tracking_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('shipped_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')->dateTime()->sortable()
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
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}

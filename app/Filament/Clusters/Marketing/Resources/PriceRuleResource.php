<?php

namespace App\Filament\Clusters\Marketing\Resources;

use App\Filament\Clusters\Marketing;
use App\Filament\Clusters\Marketing\Resources\PriceRuleResource\Pages;
use App\Models\PriceRule;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceRuleResource extends Resource
{
    protected static ?string $model = PriceRule::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Marketing\MarketingCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('rule_type')->maxLength(255),
                Forms\Components\Select::make('customer_group_id')->relationship('customer_group', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('product_id')->relationship('product', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('category_id')->relationship('category', 'name')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('min_quantity')->maxLength(255),
                Forms\Components\TextInput::make('discount_type')->maxLength(255),
                Forms\Components\TextInput::make('discount_value')->maxLength(255),
                Forms\Components\DateTimePicker::make('start_date'),
                Forms\Components\DateTimePicker::make('end_date'),
                Forms\Components\TextInput::make('priority')->maxLength(255),
                Forms\Components\Toggle::make('is_active')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('rule_type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_group.name')->sortable(),
                Tables\Columns\TextColumn::make('product.name')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('min_quantity')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('discount_type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('discount_value')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('start_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('priority')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()
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
            'index' => Pages\ListPriceRules::route('/'),
            'create' => Pages\CreatePriceRule::route('/create'),
            'edit' => Pages\EditPriceRule::route('/{record}/edit'),
        ];
    }
}

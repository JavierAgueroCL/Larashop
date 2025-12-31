<?php

namespace App\Filament\Clusters\Marketing\Resources;

use App\Filament\Clusters\Marketing;
use App\Filament\Clusters\Marketing\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Marketing\MarketingCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('code')->maxLength(255),
                Forms\Components\RichEditor::make('description')->columnSpanFull(),
                Forms\Components\TextInput::make('discount_type')->maxLength(255),
                Forms\Components\TextInput::make('discount_value')->maxLength(255),
                Forms\Components\TextInput::make('min_purchase_amount')->maxLength(255),
                Forms\Components\TextInput::make('max_uses')->maxLength(255),
                Forms\Components\TextInput::make('uses_count')->maxLength(255),
                Forms\Components\TextInput::make('max_uses_per_user')->maxLength(255),
                Forms\Components\DateTimePicker::make('start_date'),
                Forms\Components\DateTimePicker::make('end_date'),
                Forms\Components\Toggle::make('is_active')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('discount_type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('discount_value')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('min_purchase_amount')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('max_uses')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('uses_count')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('max_uses_per_user')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('start_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->dateTime()->sortable(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}

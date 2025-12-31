<?php

namespace App\Filament\Clusters\Marketing\Resources;

use App\Filament\Clusters\Marketing;
use App\Filament\Clusters\Marketing\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Marketing\MarketingCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')->maxLength(255),
                Forms\Components\TextInput::make('subtitle')->maxLength(255),
                Forms\Components\TextInput::make('button_text')->maxLength(255),
                Forms\Components\TextInput::make('button_url')->maxLength(255),
                Forms\Components\FileUpload::make('image_url')->image()->directory('image_urls'),
                Forms\Components\TextInput::make('order')->maxLength(255),
                Forms\Components\TextInput::make('position')->numeric(),
                Forms\Components\Toggle::make('is_active')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subtitle')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('button_text')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('button_url')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('image_url'),
                Tables\Columns\TextColumn::make('order')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position')->searchable()->sortable(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}

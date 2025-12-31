<?php

namespace App\Filament\Clusters\Catalog\Resources;

use App\Filament\Clusters\Catalog;
use App\Filament\Clusters\Catalog\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Filament\Clusters\Catalog\Resources\ProductResource\RelationManagers;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $cluster = Catalog\CatalogCluster::class;

    protected static ?int $navigationSort = 0;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Tabs::make('Product Details')
                    ->tabs([
                        // Tab 1: General
                        Schemas\Components\Tabs\Tab::make('General')
                            ->schema([
                                Schemas\Components\Group::make([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                    Forms\Components\TextInput::make('slug')
                                        ->disabled()
                                        ->dehydrated()
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(Product::class, 'slug', ignoreRecord: true),
                                    Forms\Components\TextInput::make('sku')
                                        ->label('SKU')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(Product::class, 'sku', ignoreRecord: true),
                                    Forms\Components\Select::make('brand_id')
                                        ->relationship('brand', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),
                                            Forms\Components\TextInput::make('slug')
                                                ->disabled()
                                                ->dehydrated()
                                                ->required(),
                                        ]),
                                ])->columns(2),
                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('short_description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        // Tab 2: Pricing
                        Schemas\Components\Tabs\Tab::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('base_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                Forms\Components\TextInput::make('cost_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->helperText('Customers will not see this price.'),
                                Forms\Components\TextInput::make('discount_price')
                                    ->numeric()
                                    ->prefix('$'),
                                Forms\Components\Select::make('tax_id')
                                    ->relationship('tax', 'name')
                                    ->required(),
                            ])->columns(2),

                        // Tab 3: Inventory
                        Schemas\Components\Tabs\Tab::make('Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('stock_quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(0),
                                Forms\Components\TextInput::make('low_stock_threshold')
                                    ->numeric()
                                    ->default(5),
                                Schemas\Components\Section::make('Shipping')
                                    ->schema([
                                        Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
                                        Forms\Components\TextInput::make('width')->numeric()->suffix('cm'),
                                        Forms\Components\TextInput::make('height')->numeric()->suffix('cm'),
                                        Forms\Components\TextInput::make('depth')->numeric()->suffix('cm'),
                                    ])->columns(4),
                                Forms\Components\Toggle::make('has_combinations')
                                    ->label('Has Combinations (Variants)')
                                    ->helperText('Enable if this product has options like size or color.'),
                            ]),

                        // Tab 4: Media
                        Schemas\Components\Tabs\Tab::make('Media')
                            ->schema([
                                Forms\Components\Repeater::make('images')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\FileUpload::make('image_path')
                                            ->image()
                                            ->directory('products')
                                            ->required(),
                                        Forms\Components\TextInput::make('alt_text')
                                            ->label('Alt Text'),
                                        Forms\Components\Toggle::make('is_primary')
                                            ->label('Primary Image')
                                            ->default(false),
                                        Forms\Components\TextInput::make('position')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(2)
                                    ->orderColumn('position'),
                            ]),

                        // Tab 5: SEO
                        Schemas\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('meta_description')
                                    ->maxLength(255),
                            ]),
                    ])->columnSpanFull(),
                    
                Schemas\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('Image')
                    ->limit(1),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('brand.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->money('clp')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->sortable()
                    ->label('Stock'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
            RelationManagers\CategoriesRelationManager::class,
            RelationManagers\CombinationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

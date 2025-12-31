<?php

namespace App\Filament\Clusters\Sales\Resources;

use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Sales\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Filament\Clusters\Sales\Resources\OrderResource\RelationManagers;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $cluster = Sales\SalesCluster::class;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make('Order Information')
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->disabled(),
                                Forms\Components\TextInput::make('customer_email')
                                    ->disabled()
                                    ->label('Email'),
                                Forms\Components\TextInput::make('customer_first_name')
                                    ->disabled()
                                    ->label('First Name'),
                                Forms\Components\TextInput::make('customer_last_name')
                                    ->disabled()
                                    ->label('Last Name'),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->disabled(),
                            ])->columns(2),
                        
                        Schemas\Components\Section::make('Payment & Shipping')
                            ->schema([
                                Forms\Components\TextInput::make('payment_method')
                                    ->disabled(),
                                Forms\Components\TextInput::make('payment_status')
                                    ->disabled(),
                                Forms\Components\TextInput::make('shipping_method')
                                    ->disabled(),
                                Forms\Components\TextInput::make('grand_total')
                                    ->prefix('$')
                                    ->disabled(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make('Management')
                            ->schema([
                                Forms\Components\Select::make('current_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        'refunded' => 'Refunded',
                                    ])
                                    ->required(),
                                Forms\Components\Textarea::make('notes')
                                    ->rows(4),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn (Order $record) => $record->customer_first_name . ' ' . $record->customer_last_name)
                    ->searchable(['customer_first_name', 'customer_last_name', 'customer_email']),
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('clp')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('current_status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
            RelationManagers\StatusHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Clusters\Customers\Resources;

use App\Filament\Clusters\Customers;
use App\Filament\Clusters\Customers\Resources\UserResource\Pages;
use App\Models\User;
use App\Filament\Clusters\Customers\Resources\UserResource\RelationManagers;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Customers\CustomersCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('first_name')->maxLength(255),
                Forms\Components\TextInput::make('last_name')->maxLength(255),
                Forms\Components\TextInput::make('email')->maxLength(255),
                Forms\Components\TextInput::make('password')->password()->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create'),
                Forms\Components\TextInput::make('phone')->maxLength(255),
                Forms\Components\Select::make('customer_group_id')->relationship('customer_group', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('google_id')->relationship('google', 'name')->searchable()->preload()->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('password')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_group.name')->sortable(),
                Tables\Columns\TextColumn::make('google.name')->sortable()
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
            RelationManagers\AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

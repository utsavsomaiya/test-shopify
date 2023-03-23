<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages\ListProducts;
use App\Filament\Resources\ProductsResource\Pages\CreateProducts;
use App\Filament\Resources\ProductsResource\Pages\EditProducts;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required(),
                TextInput::make('description')
                    ->label('Product Description')
                    ->required(),
                TextInput::make('owner_name')
                    ->required(),
                TextInput::make('type')
                    ->label('Product Type')
                    ->required(),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'archived' => 'Archived',
                        'draft' => 'Draft',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                     ->description(fn (Product $record): string => $record->description),
                TextColumn::make('description')->limit(50),
                TextColumn::make('owner_name'),
                TextColumn::make('type'),
                TextColumn::make('status')->enum([
                    'active' => 'Active',
                    'archived' => 'Archived',
                    'draft' => 'Draft',
                ])
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProducts::route('/create'),
            'edit' => EditProducts::route('/{record}/edit'),
        ];
    }
}

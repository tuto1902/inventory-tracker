<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('description'),
                    ])
                    ->columnSpan(1)->columnStart(1),
                Section::make('Product Stock')
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required(),
                        TextInput::make('barcode'),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('stock_quantity')
                            ->required()
                            ->numeric(),
                    ])
                    ->columnSpan(1)->columnStart(1),
            ]);
    }
}

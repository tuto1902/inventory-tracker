<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Resources\Products\Pages\EditProduct;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('barcode')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('out_of_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', '<=', 0)),
                SelectFilter::make('suppliers')
                    ->relationship('suppliers', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                TrashedFilter::make()
            ])
            ->recordUrl(fn (Product $record): string => EditProduct::getUrl([$record->getKey()]))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('addStock')
                    ->label('Add Stock')
                    ->icon(Heroicon::SquaresPlus)
                    ->color('success')
                    ->schema([
                        TextInput::make('stock_quantity')
                            ->label('Add Stock Quantity')
                            ->numeric()
                            ->required()
                    ])
                    ->action(function (array $data, Product $record) {
                        $record->stock_quantity += $data['stock_quantity'];
                        $record->save();
                    })
                    ->modalWidth(Width::Medium)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

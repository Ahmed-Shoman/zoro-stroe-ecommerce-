<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Store Management';
    protected static ?string $navigationLabel = 'Products';

    /* =========================
        FORM
    ========================= */

    public static function form(Form $form): Form
    {
        return $form->schema([

            /* ---------- Basic Info ---------- */
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn ($state, callable $set) =>
                                    $set('slug', str($state)->slug())
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),

                    Forms\Components\Textarea::make('description')
                        ->rows(4),
                ]),

            /* ---------- Relations ---------- */
            Forms\Components\Section::make('Relations')
                ->schema([
                    Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\MultiSelect::make('categories')
                        ->relationship('categories', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2),

            /* ---------- Pricing ---------- */
            Forms\Components\Section::make('Pricing')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('sale_price')
                        ->numeric()
                        ->nullable(),
                ])
                ->columns(2),

            /* ---------- Inventory ---------- */
            Forms\Components\Section::make('Inventory')
                ->schema([
                    Forms\Components\TextInput::make('sku')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('quantity')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('track_quantity')
                        ->default(true),

                    Forms\Components\Toggle::make('allow_backorder')
                        ->default(false),
                ])
                ->columns(4),

            /* ---------- Images (JSON) ---------- */
            Forms\Components\Section::make('Images')
                ->schema([
                    Forms\Components\FileUpload::make('images')
                        ->multiple()
                        ->image()
                        ->reorderable()
                        ->directory('products/images')
                        ->disk('public')
                        ->maxFiles(6),
                ]),

            /* ---------- Visibility ---------- */
            Forms\Components\Section::make('Visibility')
                ->schema([
                    Forms\Components\Toggle::make('is_new'),
                    Forms\Components\Toggle::make('is_bestseller'),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])
                ->columns(3),
        ]);
    }

    /* =========================
        TABLE
    ========================= */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand'),

                Tables\Columns\TextColumn::make('price')
                    ->money('EGP'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name'),

                Tables\Filters\SelectFilter::make('categories')
                    ->relationship('categories', 'name'),

                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /* =========================
        PAGES
    ========================= */

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function form(Form $form): Form
{
    return $form->schema([

        Forms\Components\Section::make('Basic Information')
            ->schema([
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

                Forms\Components\Textarea::make('description')
                    ->rows(4),
            ]),

        Forms\Components\Section::make('Relations')
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ])
            ->columns(2),

        Forms\Components\Section::make('Pricing & Stock')
            ->schema([
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('sale_price')
                    ->numeric()
                    ->nullable(),

                Forms\Components\TextInput::make('stock')
                    ->numeric()
                    ->default(0),
            ])
            ->columns(3),

        Forms\Components\Section::make('Flags')
            ->schema([
                Forms\Components\Toggle::make('is_new'),
                Forms\Components\Toggle::make('is_bestseller'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])
            ->columns(3),
    ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Category'),

            Tables\Columns\TextColumn::make('brand.name')
                ->label('Brand'),

            Tables\Columns\TextColumn::make('price')
                ->money('EGP'),

            Tables\Columns\TextColumn::make('sale_price')
                ->money('EGP')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('stock')
                ->sortable(),

            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('is_active'),
            Tables\Filters\TernaryFilter::make('is_new'),
            Tables\Filters\TernaryFilter::make('is_bestseller'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
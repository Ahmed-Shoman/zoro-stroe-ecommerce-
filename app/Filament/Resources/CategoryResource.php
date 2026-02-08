<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Store Management';
    protected static ?string $navigationLabel = 'Categories';

    public static function form(Form $form): Form
{
    return $form->schema([

        Forms\Components\Section::make('Category Information')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->placeholder('e.g. Laptops')
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn ($state, callable $set) =>
                                    $set('slug', str($state)->slug())
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->helperText('Auto generated from name')
                            ->unique(ignoreRecord: true),
                    ]),

                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Main category (optional)')
                    ->nullable(),
            ]),

        Forms\Components\Section::make('Appearance')
            ->schema([
                Forms\Components\FileUpload::make('icon')
                    ->label('Category Icon')
                    ->image()
                    ->directory('categories/icons')
                    ->disk('public')
                    ->imagePreviewHeight('80')
                    ->maxSize(1024)
                    ->helperText('SVG / PNG recommended'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]),
    ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('icon')
                ->disk('public')
                ->circular()
                ->label('Icon'),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            Tables\Columns\BadgeColumn::make('parent.name')
                ->label('Parent')
                ->color('gray')
                ->default('Main'),

            Tables\Columns\IconColumn::make('is_active')
                ->boolean()
                ->label('Active'),

            Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('is_active'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
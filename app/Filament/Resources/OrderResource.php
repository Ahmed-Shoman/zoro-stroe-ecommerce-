<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Store Management';
    protected static ?string $navigationIcon  = 'heroicon-o-receipt-percent';
    protected static ?string $navigationLabel = 'Orders';

    /* =========================
     |  FORM (Create Order)
     ========================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
    ->label('Customer')
    ->options(
        \App\Models\User::pluck('email', 'id')
    )
    ->searchable()
    ->required(),


                Forms\Components\Repeater::make('items')
            ->label('Order Items')
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(
                        \App\Models\Product::where('is_active', true)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ])
            ->columns(2)
            ->required(),

        ]);
    }

    /* =========================
     |  TABLE
     ========================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('EGP')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'primary' => 'shipped',
                        'gray'    => 'completed',
                        'danger'  => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                // 👁 View Order
                Tables\Actions\ViewAction::make(),

                // 🔄 Change Status
                Tables\Actions\Action::make('change_status')
                    ->label('Change Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Order $record) =>
                        ! in_array($record->status, ['completed', 'cancelled'])
                    )
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Order Status')
                            ->options([
                                'pending'   => 'Pending',
                                'paid'      => 'Paid',
                                'shipped'   => 'Shipped',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data, Order $record) {

                        if (! $record->canTransitionTo($data['status'])) {
                            throw new \Exception('Invalid order status transition');
                        }

                        $record->update([
                            'status' => $data['status'],
                        ]);

                        $record->notifyStatusChanged();
                    }),
            ]);
    }

    /* =========================
     |  RELATIONS
     ========================= */
    public static function getRelations(): array
    {
        return [
            // OrderItemsRelationManager::class
        ];
    }

    /* =========================
     |  PAGES
     ========================= */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view'   => Pages\ViewOrder::route('/{record}'),
        ];
    }
}

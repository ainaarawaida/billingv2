<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TeamSetting;
use App\Models\PaymentMethod;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PaymentResource\Pages;
use App\Filament\App\Resources\PaymentResource\RelationManagers;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationGroup = 'Billing';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('invoice_id')
                            ->prefix('#I')
                            ->relationship('invoice', 'numbering', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant(), 'teams'))
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('payment_method_id')
                            ->label("Payment Method")
                            ->options(function (Get $get, string $operation){
                                $payment_method = PaymentMethod::where('team_id', Filament::getTenant()->id)
                                ->where('status', 1)->get()->pluck('name', 'id');
                                return $payment_method ;
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('payment_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->prefix('RM')
                            ->regex('/^[0-9]*(?:\.[0-9]*)?(?:,[0-9]*(?:\.[0-9]*)?)*$/')
                            ->formatStateUsing(fn (string $state): string => number_format($state, 2))
                            ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                            ->default(0.00),
                        Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'pending_payment' => 'Pending payment',
                                    'on_hold' => 'On hold',
                                    'processing ' => 'Processing ',
                                    'completed' => 'Completed',
                                    'failed' => 'Failed',
                                    'canceled' => 'Canceled',
                                    'refunded' => 'Refunded',
                                ])
                                ->default('draft')
                                ->searchable()
                                ->preload()
                                ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $prefix = TeamSetting::where('team_id', Filament::getTenant()->id )->first()->invoice_prefix_code ?? '#I' ;
       
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice.numbering')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function(string $state, $record): string {
                            return "{$state}";
                        } 
                    )
                    ->color('primary')
                    ->prefix($prefix)
                    ->url(fn($record) => InvoiceResource::getUrl('edit', ['record' => $record->invoice_id])),
                Tables\Columns\TextColumn::make('payment_method.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date('j F, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->prefix('RM ')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => __(ucwords($state))),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

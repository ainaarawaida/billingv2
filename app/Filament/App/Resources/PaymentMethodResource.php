<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserSetting;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PaymentMethodResource\Pages;
use App\Filament\App\Resources\PaymentMethodResource\RelationManagers;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationGroup = 'Setting';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('type')
                        ->live(onBlur: true)
                        ->options([
                            'manual' => 'Manual',
                            'payment_gateway' => 'Payment Gateway',
                        ])
                        ->default('manual')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                            if($state == 'payment_gateway'){
                                $set('name', 'Payment Gateway');
                            }else{
                                $set('name', '');
                                $set('payment_gateway_id', '');
                            }
                        }),
                    Forms\Components\Select::make('payment_gateway_id')
                        ->live()
                        ->visible(fn (Get $get) => $get('type') == 'payment_gateway')
                        ->label('Payment Gateway')
                        ->options(function (Get $get, string $operation){
                                $user_setting = UserSetting::where('user_id', auth()->user()->id)->first()->payment_gateway;
                             
                                $temp = collect($user_setting)->where('status', true)->pluck('name', 'id');

                                return $temp ;
                            })
                        ->required()
                        ->preload()
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                            $user_setting = UserSetting::where('user_id', auth()->user()->id)->first()->payment_gateway;
                            $temp = collect($user_setting)->where('id', $state)->first();
                            if($temp && isset($temp['name'])){
                                $set('name', 'Payment Gateway:'.$temp['name']);

                            }else{
                                $set('name', '');
                            }
                        }),
                    Forms\Components\TextInput::make('name')
                        ->readonly(fn (Get $get) => $get('type') == 'payment_gateway')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('bank_account')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\Toggle::make('status')
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->onColor('success')
                        ->offColor('danger'),


                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_account')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_gateway_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'view' => Pages\ViewPaymentMethod::route('/{record}'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}

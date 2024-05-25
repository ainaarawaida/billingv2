<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\CustomerResource\Pages;
use App\Filament\App\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Resources';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info')
                ->schema([
                    Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                    ])
                    ->columns(1),
                    Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                       
                        
                    ])
                    ->columns(2),
             
                    
                    Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('company')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('ssm')
                                ->label('SSM No.')
                                ->maxLength(255),
                            
                        ])
                        ->columns(3),
                ]),
                Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('poscode')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\Select::make('state')
                                    ->options([
                                        'JHR' => 'Johor',
                                        'KDH' => 'Kedah',
                                        'KTN' => 'Kelantan',
                                        'MLK' => 'Melaka',
                                        'NSN' => 'Negeri Sembilan',
                                        'PHG' => 'Pahang',
                                        'PRK' => 'Perak',
                                        'PLS' => 'Perlis',
                                        'PNG' => 'Pulau Pinang',
                                        'SBH' => 'Sabah',
                                        'SWK' => 'Sarawak',
                                        'SGR' => 'Selangor',
                                        'TRG' => 'Terengganu',
                                        'KUL' => 'W.P. Kuala Lumpur',
                                        'LBN' => 'W.P. Labuan',
                                        'PJY' => 'W.P. Putrajaya'
                                    ])
                                    ->searchable()
                                    ->preload()

                            
                ])
                ->columns(3)
               
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ssm')
                    ->label("SSM")
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('poscode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('state')
                ->disabled(true)
                ->label(new HtmlString('<span style="">State</span>'))
                ->extraHeaderAttributes([
                    'style' => 'padding-right:150px'
                ])
                    ->options([
                        'JHR' => 'Johor',
                        'KDH' => 'Kedah',
                        'KTN' => 'Kelantan',
                        'MLK' => 'Melaka',
                        'NSN' => 'Negeri Sembilan',
                        'PHG' => 'Pahang',
                        'PRK' => 'Perak',
                        'PLS' => 'Perlis',
                        'PNG' => 'Pulau Pinang',
                        'SBH' => 'Sabah',
                        'SWK' => 'Sarawak',
                        'SGR' => 'Selangor',
                        'TRG' => 'Terengganu',
                        'KUL' => 'W.P. Kuala Lumpur',
                        'LBN' => 'W.P. Labuan',
                        'PJY' => 'W.P. Putrajaya'
                    ])
                    ->searchable(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
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

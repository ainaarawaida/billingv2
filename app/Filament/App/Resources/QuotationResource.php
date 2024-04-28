<?php

namespace App\Filament\App\Resources;

use stdClass;
use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Livewire\Livewire;
use App\Models\Invoice;
use App\Models\Product;
use Livewire\Component;
use App\Models\Customer;
use Filament\Forms\Form;
use App\Models\Quotation;
use Filament\Tables\Table;
use App\Mail\QuotationEmail;
use Filament\Facades\Filament;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\App\Resources\InvoiceResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\QuotationResource\Pages;
use App\Filament\App\Resources\QuotationResource\RelationManagers;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Billing';
    protected static ?int $navigationSort = 4;

    // protected static ?string $tenantOwnershipRelationshipName = 'teams';


    // public static function form(Form $form): Form
    // {
    //     return $form
    //             ->schema([
    //                 Forms\Components\Group::make()
    //                     ->schema([
    //                         Forms\Components\Section::make()
    //                             ->schema([

    //                                 Forms\Components\Select::make('customer_id')
    //                                     ->relationship('customer', 'name')
    //                                     ->searchable()
    //                                     ->required()
    //                                     ->preload()
    //                                     ->live(onBlur: true)
                                    
    //                                     ->createOptionForm([
    //                                         Forms\Components\TextInput::make('name')
    //                                             ->required()
    //                                             ->maxLength(255),
                    
    //                                         Forms\Components\TextInput::make('email')
    //                                             ->label('Email address')
    //                                             ->required()
    //                                             ->email()
    //                                             ->maxLength(255)
    //                                             ->unique(),
                    
    //                                         Forms\Components\TextInput::make('phone')
    //                                             ->maxLength(255),
                    
    //                                         Forms\Components\Select::make('gender')
    //                                             ->placeholder('Select gender')
    //                                             ->options([
    //                                                 'male' => 'Male',
    //                                                 'female' => 'Female',
    //                                             ])
    //                                             ->required()
    //                                             ->native(false),
    //                                     ])
    //                                     ->createOptionAction(function (Action $action) {
    //                                         return $action
    //                                             ->modalHeading('Create customer')
    //                                             ->modalSubmitActionLabel('Create customer')
    //                                             ->modalWidth('lg');
    //                                     }),

    //                                 Forms\Components\ViewField::make('detail_customer')
    //                                     ->view('filament.detail_customer'),
                                
    //                             ])

                            
    //                     ]),
    //                 Forms\Components\Group::make()
    //                     ->schema([
    //                         Forms\Components\Section::make()
    //                         ->schema([
    //                             Forms\Components\DatePicker::make('quotation_date')
    //                                 // ->format('d/m/Y')
    //                                 ->native(false)
    //                                 ->displayFormat('d/m/Y')
    //                                 ->default(now())
    //                                 ->required(),
    //                             Forms\Components\TextInput::make('valid_days')
    //                                 ->numeric()
    //                                 ->default(1)
    //                                 ->minValue(0)
    //                                 ->required(),

    //                             Forms\Components\Select::make('quote_status')
    //                                 ->options([
    //                                     'draft' => 'Draft',
    //                                     'new' => 'New',
    //                                     'process' => 'Process',
    //                                     'done' => 'Done',
    //                                     'expired' => 'Expired',
    //                                     'cancelled' => 'Cancelled',

    //                                 ])
    //                                 ->default('draft')
    //                                 ->searchable()
    //                                 ->preload()
    //                                 ->required()
    //                                 ->columnSpan(2),


    //                         ])->columns(2)
                            
    //                     ]),
    //                     Forms\Components\Section::make()
    //                         ->schema([
    //                             Forms\Components\TextInput::make('title')
    //                             ->afterStateHydrated(function ($component, string $state) {
    //                                 $component->state(ucwords($state));
    //                             })
    //                                 ->required()
    //                                 ->maxLength(255),

    //                         ]),
    //                     Forms\Components\Section::make()
    //                         ->schema([
    //                             Forms\Components\Repeater::make('items')
    //                                 ->live(onBlur: true)
    //                                 ->minItems(1)
    //                                 ->collapsible()
    //                                 ->relationship('items')
    //                                 ->schema([
    //                                     Forms\Components\Textarea::make('title')
    //                                         ->required()
    //                                         ->columnSpan(2),
    //                                         Forms\Components\Select::make('product_id')
    //                                         ->relationship('product','title')
    //                                         ->searchable()
    //                                         ->preload()
    //                                         ->distinct()
    //                                         ->disableOptionsWhenSelectedInSiblingRepeaterItems()
    //                                         // ->live(onBlur: true)
    //                                         ->columnSpan(3),
    //                                     Forms\Components\TextInput::make('price')
    //                                         ->required()
    //                                         ->prefix('RM')
    //                                         ->formatStateUsing(fn (string $state): string => number_format($state, 2))

    //                                         // ->live(onBlur: true)
    //                                         ->afterStateUpdated(function ($state, $set, $get ){
    //                                             $set('total', number_format($state*$get('quantity'), 2)  );
    //                                             // $total = 0 ; 
    //                                             // if(!$repeaters = $get('../../items')){
    //                                             //     return $total ;
    //                                             // }
    //                                             // foreach($repeaters AS $key => $val){
    //                                             //     $total += (float)$get("../../items.{$key}.total");
    //                                             // }
    //                                             // $set('../../sub_total', number_format($total, 2) );
    //                                             // $set('../../final_amount', number_format($total, 2));
    //                                         })
    //                                         ->default(0.00),
    //                                     Forms\Components\Checkbox::make('tax')
    //                                     // ->live(onBlur: true)
    //                                     ->inline(false),
    //                                     Forms\Components\TextInput::make('quantity')
    //                                         ->required()
    //                                         ->numeric()
    //                                         // ->live(onBlur: true)
    //                                         ->afterStateUpdated(function ($state, $set, $get ){
    //                                             $set('total', number_format($state*$get('price'), 2)  );
    //                                         })
    //                                         ->default(1),
    //                                     Forms\Components\Select::make('unit')
    //                                         ->options([
    //                                             'Unit' => 'Unit',
    //                                             'Kg' => 'Kg',
    //                                             'Gram' => 'Gram',
    //                                             'Box' => 'Box',
    //                                             'Pack' => 'Pack',
    //                                             'Day' => 'Day',
    //                                             'Month' => 'Month',
    //                                             'Year' => 'Year',
    //                                             'People' => 'People',

    //                                         ])
    //                                         ->default('Unit')
    //                                         ->searchable()
    //                                         ->preload()
    //                                         ->required(),
    //                                     Forms\Components\TextInput::make('total')
    //                                         ->prefix('RM')
    //                                         ->readonly()
    //                                         ->formatStateUsing(fn (string $state): string => number_format($state, 2))
    //                                         ->default(0.00),
    //                                 ])->columns(5),

    //                         ]),
    //                     Forms\Components\Section::make()
    //                         ->schema([
    //                             Forms\Components\Textarea::make('notes'),
    //                             Forms\Components\Group::make()
    //                             ->schema([
    //                                 Forms\Components\TextInput::make('sub_total')
    //                                     ->formatStateUsing(fn ( $state)  => number_format($state, 2))
    //                                     ->prefix('RM')
    //                                     ->readonly()
    //                                     ->default(0),
    //                                 Forms\Components\TextInput::make('taxes')
    //                                     ->prefix('RM')
    //                                     ->readonly()
    //                                     ->default(0),
    //                                 Forms\Components\TextInput::make('percentage_tax')
    //                                     ->prefix('%')
    //                                     ->live(onBlur: true)
    //                                     ->integer()
    //                                     ->default(0),
    //                                 Forms\Components\TextInput::make('delivery')
    //                                     ->prefix('RM')
    //                                     ->live(onBlur: true)
    //                                     ->numeric()
    //                                     ->default(0.00),
    //                                 Forms\Components\TextInput::make('final_amount')
    //                                     ->prefix('RM')
    //                                     ->readonly()
    //                                     ->live(onBlur: true)
    //                                     ->default(0.00),
                                        

    //                             ])->inlineLabel(),

    //                             Forms\Components\Placeholder::make('calculation')
    //                                 ->hiddenLabel()
    //                                 ->content(function ($get, $set){
    //                                     $sub_total = 0 ; 
    //                                     $taxes = 0 ;
                                      
    //                                     if(!$repeaters = $get('items')){
    //                                         return $sub_total ;
    //                                     }
    //                                     foreach($repeaters AS $key => $val){
    //                                         $sub_total += (float)$get("items.{$key}.total");
                                          
    //                                         if($get("items.{$key}.tax") == true){
    //                                             $taxes = $taxes + ((int)$get('percentage_tax') / 100 * (float)$get("items.{$key}.total")) ;
    //                                         }else{

    //                                         }
                                           
    //                                     }

    //                                     $set('sub_total', number_format($sub_total, 2));
    //                                     $set('taxes', number_format($taxes, 2));
    //                                     $set('final_amount', number_format($sub_total + (float)$get("taxes") + (float)$get("delivery"), 2));

    //                                     return "";
    //                                     // return $sub_total." ".(float)$get("taxes"). " ". (float)$get("delivery")." ".$sub_total + (float)$get("taxes") + (float)$get("delivery")  ;
    //                                 }),

    //                         ])->columns(2),
                           

                        
    //             ]);
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([

                                Forms\Components\Select::make('customer_id')
                                ->relationship('customer', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant(), 'teams'))
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->live(onBlur: true)
                                
                                    ->createOptionForm([
                                        self::customerForm(),
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        $action->mutateFormDataUsing(function ($data) {
                                            $data['team_id'] = Filament::getTenant()->id;
                                    
                                            return $data;
                                        });

                                        return $action
                                            ->modalHeading('Create customer')
                                            ->modalSubmitActionLabel('Create customer')
                                            ->modalWidth('7xl');
                                    })
                                    ->native(false),

                                Forms\Components\ViewField::make('detail_customer')
                                    ->dehydrated(false)
                                    ->view('filament.detail_customer'),
                                // Forms\Components\Placeholder::make('detail_customer2')
                                // ->content(fn ($record) => new HtmlString('<b>asma</b>')),
                            
                            ])

                        
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\DatePicker::make('quotation_date')
                                // ->format('d/m/Y')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->default(now())
                                ->required(),
                            Forms\Components\TextInput::make('valid_days')
                                ->numeric()
                                ->default(1)
                                ->minValue(0)
                                ->required(),

                            Forms\Components\Select::make('quote_status')
                                ->options([
                                    'draft' => 'Draft',
                                    'new' => 'New',
                                    'process' => 'Process',
                                    'done' => 'Done',
                                    'expired' => 'Expired',
                                    'cancelled' => 'Cancelled',

                                ])
                                ->default('draft')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('numbering')
                            ->hiddenLabel()
                            ->readOnly()
                            ->dehydrated(false)
                            ->prefix('#Q')
                            // ->visible(fn (string $operation): bool => $operation === 'edit')
                            ->formatStateUsing(function(?string $state, $operation, $record): ?string {
                                if($operation === 'create'){
                                    $tenant_id = Filament::getTenant()->id ;
                                    $lastid = Quotation::where('team_id', $tenant_id)->count('id') + 1 ;
                                    return str_pad($lastid, 6, "0", STR_PAD_LEFT) ;

                                }else{
                                    return $record->numbering ;
                                }
                            })
                            ->columnSpan(2),


                        ])->columns(2)
                        
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                        ->afterStateHydrated(function (?TextInput $component, ?string $state) {
                            $component->state(ucwords($state));
                        })
                            ->required()
                            ->maxLength(255),

                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->live(onBlur: true)
                            ->minItems(1)
                            ->collapsible()
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Textarea::make('title')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'title', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant(), 'teams'))
                                    ->searchable()
                                    ->preload()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->createOptionForm([
                                        Forms\Components\Textarea::make('title')
                                            ->maxLength(65535)
                                            ->columnSpanFull(),
                                        Forms\Components\Checkbox::make('tax')
                                            // ->live(onBlur: true)
                                            ->inline(false),

                                        Forms\Components\TextInput::make('quantity')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                        Forms\Components\TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('RM')
                                            ->formatStateUsing(fn (?string $state): ?string => number_format($state, 2))
                                            ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        $action->mutateFormDataUsing(function ($data) {
                                            $data['team_id'] = Filament::getTenant()->id;
                                    
                                            return $data;
                                        });
                                        
                                        return $action
                                            // ->modalHeading('Create customer')
                                            // ->modalSubmitActionLabel('Create customer')
                                            ->modalWidth('Screen');
                                    })
                                    ->afterStateUpdated(function ($state, $set, $get ){
                                        
                                        $product = Product::find($state);
                                        $set('price', number_format((float)$product?->price, 2));
                                        $set('tax', (bool)$product?->tax);
                                        $set('quantity', (int)$product?->quantity);

                                        // dd((float)$product?->price,number_format((float)str_replace(",", "", $product?->price), 2), $product?->quantity, $get('price'), (float)$get('price'));
                                        $set('total', number_format((int)$product?->quantity*(float)str_replace(",", "", $get('price')), 2)  );
                                       
                                    })
                                    // ->live(onBlur: true)
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->prefix('RM')
                                    ->formatStateUsing(fn (string $state): string => number_format($state, 2))
                                    ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))

                                    // ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get ){
                                        $set('total', number_format((float)str_replace(",", "", $state)*(int)$get('quantity'), 2)  );
                                        // $total = 0 ; 
                                        // if(!$repeaters = $get('../../items')){
                                        //     return $total ;
                                        // }
                                        // foreach($repeaters AS $key => $val){
                                        //     $total += (float)$get("../../items.{$key}.total");
                                        // }
                                        // $set('../../sub_total', number_format($total, 2) );
                                        // $set('../../final_amount', number_format($total, 2));
                                    })
                                    ->default(0.00),
                                Forms\Components\Checkbox::make('tax')
                                    // ->live(onBlur: true)
                                    ->inline(false),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    // ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get ){
                                        $set('total', number_format($state*(float)str_replace(",", "", $get('price')), 2)  );
                                    })
                                    ->default(1),
                                Forms\Components\Select::make('unit')
                                    ->options([
                                        'Unit' => 'Unit',
                                        'Kg' => 'Kg',
                                        'Gram' => 'Gram',
                                        'Box' => 'Box',
                                        'Pack' => 'Pack',
                                        'Day' => 'Day',
                                        'Month' => 'Month',
                                        'Year' => 'Year',
                                        'People' => 'People',

                                    ])
                                    ->default('Unit')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\TextInput::make('total')
                                    ->prefix('RM')
                                    ->readonly()
                                    ->formatStateUsing(fn (string $state): string => number_format($state, 2))
                                    ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                                    ->default(0.00),
                            ])
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                               return $data;
                            })->columns(5),

                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Textarea::make('notes'),
                        Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('sub_total')
                                ->formatStateUsing(fn ( $state)  => number_format($state, 2))
                                ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                                ->prefix('RM')
                                ->readonly()
                                ->default(0),
                            Forms\Components\TextInput::make('taxes')
                                ->formatStateUsing(fn ( $state)  => number_format($state, 2))
                                ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                                ->prefix('RM')
                                ->readonly()
                                ->default(0),
                            Forms\Components\TextInput::make('percentage_tax')
                                ->prefix('%')
                                ->live(onBlur: true)
                                ->formatStateUsing(fn ( $state)  => (int)$state)
                                ->integer()
                                ->default(0),
                            Forms\Components\TextInput::make('delivery')
                                ->formatStateUsing(fn ( $state)  => number_format($state, 2))
                                ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                                ->prefix('RM')
                                ->live(onBlur: true)
                                ->numeric()
                                ->default(0.00),
                            Forms\Components\TextInput::make('final_amount')
                                ->formatStateUsing(fn ( $state)  => number_format($state, 2))
                                ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                                ->prefix('RM')
                                ->readonly()
                                ->live(onBlur: true)
                                ->default(0.00),
                                

                        ])->inlineLabel(),

                        Forms\Components\Placeholder::make('calculation')
                            ->hiddenLabel()
                            ->content(function ($get, $set){
                                $sub_total = 0 ; 
                                $taxes = 0 ;
                                
                                if(!$repeaters = $get('items')){
                                    return $sub_total ;
                                }
                                foreach($repeaters AS $key => $val){
                                    $sub_total += (float)str_replace(",", "", $get("items.{$key}.total"));
                                    
                                    if($get("items.{$key}.tax") == true){
                                        $taxes = $taxes + ((int)$get('percentage_tax') / 100 * (float)str_replace(",", "", $get("items.{$key}.total"))) ;
                                    }else{

                                    }
                                    
                                }

                                $set('sub_total', number_format($sub_total, 2));
                                $set('taxes', number_format($taxes, 2));
                                $set('final_amount', number_format($sub_total + (float)str_replace(",", "", $get("taxes")) + (float)str_replace(",", "", $get("delivery")), 2));

                                return ;
                                // return $sub_total." ".(float)$get("taxes"). " ". (float)$get("delivery")." ".$sub_total + (float)$get("taxes") + (float)$get("delivery")  ;
                            }),

                    ])->columns(2),
                         
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->state(
                        static function (HasTable $livewire, stdClass $rowLoop): string {
                            return (string) (
                                $rowLoop->iteration +
                                ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                            );
                        }
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('numbering')
                    ->label('No.')
                    ->formatStateUsing(function(string $state, $record): string {
                            $newDate = date("d M, Y", strtotime($record->quotation_date));
                            return __("<b class=''>#Q{$state}</b><br>{$newDate}");

                        } 
                    )
                    ->html()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('valid_days')
                    ->label(__('Valid Days'))
                    ->wrapHeader()
                    ->width('1%')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function(string $state, $record): string {
                            // dd($record->items()->count());
                            return __("{$state}<br><i>({$record->items()->count()} items)</i>");

                        } 
                    )
                    ->html(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->formatStateUsing(fn (string $state): string => __("<b>{$state}</b>"))
                    ->html()
                    ->searchable()
                    ->url(function ($record) {
                        return $record->customer
                            ? CustomerResource::getUrl('edit', ['record' => $record->customer_id])
                            : null;
                    }),
                Tables\Columns\TextColumn::make('quotation_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
          

                Tables\Columns\SelectColumn::make('quote_status')
                    ->label('Status')
                    ->extraHeaderAttributes([
                        'style' => 'padding-right:100px'
                    ])
                    ->options([
                        'draft' => 'Draft',
                        'new' => 'New',
                        'process' => 'Process',
                        'done' => 'Done',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',

                    ])
                    ->selectablePlaceholder(false)
                    ->searchable(),
              
                Tables\Columns\TextColumn::make('sub_total')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('taxes')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('percentage_tax')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('delivery')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_amount')
                    ->label(__("Amount"))
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('quote_status')
                    ->label('Status')
                    ->multiple()
                    ->options([
                        'draft' => 'Draft',
                        'new' => 'New',
                        'process' => 'Process',
                        'done' => 'Done',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->indicator('Status'),
                Filter::make('numbering_f')
                    ->form([
                        TextInput::make('numbering')
                        ->label('No' )
                        ->prefix('#Q'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['numbering'],
                                fn (Builder $query, $data): Builder => $query->where('numbering', 'LIKE', '%' . $data . '%'),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['numbering']) {
                            return null;
                        }
                 
                        return 'No %'.$data['numbering'] . '%';
                    }),
                Filter::make('customer_name_f')
                    ->form([
                        TextInput::make('customer_name')
                        ->label('Customer Name' ),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['customer_name'],
                                fn (Builder $query, $data): Builder =>  $query->whereHas('customer', function (Builder $query) use ($data) {
                                    $query->where('customers.name', 'LIKE', '%' . $data . '%');
                                }),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['customer_name']) {
                            return null;
                        }
                 
                        return 'Customer Name %'.$data['customer_name'] . '%';
                    }),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('replicate')
                        ->label(__('Replicate'))
                        ->icon('heroicon-m-square-2-stack')
                        ->color('info')
                        ->action(function (Model $record, Component $livewire) {
                            $lastid = Quotation::where('team_id', $record->team_id)->count('id') + 1 ;
                            $quotation =  Quotation::create([
                                'customer_id' => $record->customer_id ,
                                'team_id' => $record->team_id ,
                                'numbering' => str_pad($lastid, 6, "0", STR_PAD_LEFT),
                                'quotation_date' => $record->quotation_date,
                                'valid_days' => $record->valid_days, // Valid days between 7 and 30
                                'quote_status' => $record->quote_status,
                                'title' => $record->title,
                                'notes' => $record->notes,
                                'sub_total' => $record->sub_total, // Subtotal between 1000 and 10000
                                'taxes' => $record->taxes, // Can be calculated based on percentage_tax and sub_total later
                                'percentage_tax' => $record->percentage_tax, // Tax percentage between 0 and 20
                                'delivery' => $record->delivery, // Delivery cost between 0 and 100
                                'final_amount' => $record->final_amount, //
                            ]);
                            $item = Item::where('quotation_id', $record->id)->get();
                            foreach ($item as $key => $value) {
                                Item::create([
                                    'quotation_id' => $quotation->id,
                                    'product_id' => $value->product_id,
                                    'title' => $value->title,
                                    'price' => $value->price,
                                    'tax' => $value->tax,
                                    'quantity' => $value->quantity,
                                    'unit' => $value->unit,
                                    'total' => $value->total,
                                ]);
                            };

                            Notification::make()
                            ->title('Replicate Quotation successfully')
                            ->success()
                            ->send();

                            $livewire->redirect(QuotationResource::getUrl('edit', ['record' => $quotation->id]), navigate:true);
                        }),
                    
                    
                    Tables\Actions\Action::make('gen_invoice')
                        ->label(__('Gen Invoice'))
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('info')
                        ->action(function (Model $record, Component $livewire) {
                            $lastid = Invoice::where('team_id', $record->team_id)->count('id') + 1 ;
                            $invoice =  Invoice::create([
                                'customer_id' => $record->customer_id ,
                                'team_id' => $record->team_id ,
                                'numbering' => str_pad($lastid, 6, "0", STR_PAD_LEFT),
                                'invoice_date' => now()->format('Y-m-d'),
                                'pay_before' => now()->format('Y-m-d'), // Valid days between 7 and 30
                                'invoice_status' => 'draft',
                                'title' => $record->title,
                                'notes' => $record->notes,
                                'sub_total' => $record->sub_total, // Subtotal between 1000 and 10000
                                'taxes' => $record->taxes, // Can be calculated based on percentage_tax and sub_total later
                                'percentage_tax' => $record->percentage_tax, // Tax percentage between 0 and 20
                                'delivery' => $record->delivery, // Delivery cost between 0 and 100
                                'final_amount' => $record->final_amount, //
                            ]);
                            $item = Item::where('quotation_id', $record->id)->get();
                            foreach ($item as $key => $value) {
                                Item::create([
                                    'invoice_id' => $invoice->id,
                                    'product_id' => $value->product_id,
                                    'title' => $value->title,
                                    'price' => $value->price,
                                    'tax' => $value->tax,
                                    'quantity' => $value->quantity,
                                    'unit' => $value->unit,
                                    'total' => $value->total,
                                ]);
                            };

                            Notification::make()
                            ->title('Generate Invoice successfully')
                            ->success()
                            ->send();

                            $livewire->redirect(InvoiceResource::getUrl('edit', ['record' => $invoice->id]), navigate:true);
                        }),
                    Tables\Actions\Action::make('pdf') 
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn ($record): ?string => url('quotationpdf')."/".base64_encode("luqmanahmadnordin".$record->id))
                        ->openUrlInNewTab(),
                        // ->action(function (Model $record) {
                        //     return response()->streamDownload(function () use ($record) {
                        //         echo Pdf::loadHtml(
                        //             Blade::render('pdf', ['record' => $record])
                        //         )
                        //         ->setBasePath(public_path())
                        //         ->stream();
                        //     }, str_pad($record->id, 6, "0", STR_PAD_LEFT)  . '.pdf');
                        // }), 
                    Tables\Actions\Action::make('sendEmail')
                        ->label('Send Email')
                        ->color('warning')
                        ->icon('heroicon-o-envelope')
                        // ->form([
                        //     TextInput::make('subject')->required(),
                        //     RichEditor::make('body')->required(),
                        // ])
                        ->action(function (Model $record) {
                            $customer = Customer::where('id', $record->customer_id)->first();
                            Mail::to($customer->email)
                            ->send(new QuotationEmail($record, $customer));


                            Notification::make()
                              ->title('Email Send successfully')
                              ->success()
                              ->send()
                              ->sendToDatabase(auth()->user());
                        }),
                       
                       
                        
                       
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('numbering', 'desc');
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'test' => Pages\Tests::route('/test'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereBelongsTo(Filament::getTenant(), 'teams')->count();
        
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function customerForm(){
        return  Forms\Components\Group::make()
        ->schema([
            Forms\Components\Section::make('Info')
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    
                ])
                ->columns(1),
                Forms\Components\Group::make()
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
         
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('company')
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('ssm')
                            ->label('SSM No.')
                            ->maxLength(255),
                        
                    ])
                    ->columns(3),
            ]),
            Forms\Components\Section::make('Address')
                ->schema([
                    Forms\Components\TextInput::make('address')
                        ->maxLength(255)
                        ->columnSpan(3),
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
}
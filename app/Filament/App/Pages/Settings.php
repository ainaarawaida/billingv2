<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\UserSetting;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Document Settings';
    protected static ?string $navigationGroup = 'Setting';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.app.pages.settings';
    protected ?string $heading = '';

    public function mount(): void
    {
        $userSetting = UserSetting::where('user_id', auth()->user()->id )->first()?->toArray();

        $this->form->fill([
            $userSetting['quotation_prefix_code'] ?? 'quotation_prefix_code' => 'Q',
            $userSetting['quotation_current_no'] ??'quotation_current_no' => 1,
            $userSetting['quotation_template'] ?? 'quotation_template' => 1
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Quotation')
                            ->schema([
                                // ...
                                TextInput::make('quotation_prefix_code')
                                    ->prefixIcon('heroicon-o-clipboard-document-check')
                                    ->required(),
                                TextInput::make('quotation_current_no')
                                    ->prefixIcon('heroicon-o-clipboard-document-check')
                                    ->numeric()
                                    ->required(),
                                Select::make('quotation_template')
                                    ->prefixIcon('heroicon-o-clipboard-document-check')
                                    ->required()
                                    ->options([
                                        '1' => 'Template 01',
                                        '2' => 'Template 02',
                                        '3' => 'Template 03',
                                    ]),
                            ]),
                        Tabs\Tab::make('Invoice')
                            ->schema([
                                // ...
                                 TextInput::make('invoice_prefix_code')
                                    ->prefixIcon('heroicon-o-clipboard-document-check')
                                    ->required(),
                                TextInput::make('invoice_current_no')
                                    ->prefixIcon('heroicon-o-clipboard-document-check')
                                    ->numeric()
                                    ->required(),
                                Select::make('invoice_template')
                                    ->prefixIcon('heroicon-o-clipboard-document-check')
                                    ->required()
                                    ->options([
                                        '1' => 'Template 01',
                                        '2' => 'Template 02',
                                        '3' => 'Template 03',
                                    ]),
                            ]),
                        Tabs\Tab::make('Payment')
                            ->schema([
                                // ...
                            ]),
                    ])

            ])
            ->columns(1)
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {

        $data = $this->form->getState();
        try {
            $userSetting = UserSetting::updateOrCreate(
                ['user_id' => auth()->user()->id], // Search by email
                $data
            );
        } catch (Halt $exception) {
            return;
        }

        Notification::make() 
        ->success()
        ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
        ->send(); 
        
    }


}

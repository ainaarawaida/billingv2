<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Attributes\On;
use Filament\Facades\Filament;
use Filament\Widgets\AccountWidget;
use App\Filament\App\Pages\Dashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = '2';
    protected static bool $isLazy = false;
    public ?string $filter = null;
    
    public function mount(){
        $this->filter = date('Y');
    }

    #[On('updateWidgetFilter')] 
    public function updateWidgetFilter($data){
        $this->filter = $data;
    }
    
    protected function getColumns(): int
    {
        return 2;
    }
    
    protected function getStats(): array
    {
        $received =Payment::where('team_id', Filament::getTenant()->id)
        ->where('status', 'completed')->whereYear('payment_date', $this->filter)->sum('total') ;
        $waiting = Payment::where('team_id', Filament::getTenant()->id)
        ->whereIn('status', ['pending_payment','on_hold','processing'])->whereYear('payment_date', $this->filter)->sum('total');
        return [
            Stat::make(__('Payment Completed (RM)'), number_format($received, 2) )
            ->description($this->filter)
            ->color('success'),
            Stat::make(__('Payment Pending/On Hold/Processing (RM)'), number_format($waiting, 2))
            ->description($this->filter)
            ->color('success'),
           
        ];
    }


}

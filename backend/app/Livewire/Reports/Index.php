<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Category;
use App\Models\MilitaryUser;

class Index extends Component
{
    public $assetFilters = [
        'category_id' => '',
        'status' => ''
    ];

    public $maintenanceFilters = [
        'type' => '',
        'start_date' => '',
        'end_date' => ''
    ];

    public $termFilters = [
        'user_id' => ''
    ];

    public function render()
    {
        return view('livewire.reports.index', [
            'categories' => Category::all(),
            'users' => MilitaryUser::orderBy('name')->get()
        ]);
    }
}

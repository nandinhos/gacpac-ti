<?php

namespace App\Livewire\Reports;

use App\Models\Category;
use App\Models\Sector;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $reportType = 'assets_by_sector';

    public $format = 'pdf'; // pdf, xlsx, csv

    // Filtros
    public $sector_id;

    public $category_id;

    public $status;

    public $start_date;

    public $end_date;

    public $user_id;

    public function render()
    {
        return view('livewire.reports.index', [
            'sectors' => Sector::orderBy('code')->get(),
            'categories' => Category::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }
}

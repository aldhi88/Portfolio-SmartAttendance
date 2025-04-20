<?php

namespace App\Livewire\Jabatan;

use App\Models\MasterOrganization;
use Livewire\Component;
use DataTables;

class JabatanData extends Component
{

    public function render()
    {
        return view('jabatan.jabatan_data');
    }
}

<?php

namespace App\Livewire\Organization;

use App\Models\MasterOrganization;
use Livewire\Component;
use DataTables;

class OrganizationDT extends Component
{
    public function __invoke()
    {
        $data = MasterOrganization::query();

        return DataTables::of($data)
            ->addColumn('action', function($data){
                $return = '
                <div class="btn-group">
                    <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu" style="">
                ';

                $return .= '
                    <a class="dropdown-item" href="#"><i class="fas fa-unlock-alt fa-fw"></i> Reset Password</a>
                ';
                return $return;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}

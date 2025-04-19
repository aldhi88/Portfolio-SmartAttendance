<?php

namespace App\Repositories;

use App\Models\MasterMinor;
use App\Repositories\Interfaces\MasterMinorFace;

class MasterMinorRepo implements MasterMinorFace
{
    public function getAll()
    {
        return MasterMinor::all();
    }
}

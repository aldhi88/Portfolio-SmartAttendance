<?php

namespace App\Repositories;

use App\Models\MasterLocation;
use App\Repositories\Interfaces\MasterLocationFace;

class MasterLocationRepo implements MasterLocationFace
{
    public function getAll()
    {
        return MasterLocation::all();
    }
}

<?php

namespace App\Repositories;

use App\Models\RdpMasterAset;

class RdpMasterAsetRepo
{
    public static function getDT($data)
    {
        return RdpMasterAset::query();
    }
}

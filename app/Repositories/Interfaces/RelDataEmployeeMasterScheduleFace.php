<?php

namespace App\Repositories\Interfaces;

interface RelDataEmployeeMasterScheduleFace
{
    public function isExistByCol($col, $val);
    public function getMultiByCol($col, $val);
}

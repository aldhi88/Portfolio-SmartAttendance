<?php

namespace App\Repositories\Interfaces;

interface RelDataEmployeeMasterScheduleFace
{
    public function insert($data);
    public function update($employeeId, $data);
    public function isExistByCol($col, $val);
    public function getMultiByCol($col, $val);
    public function delByCol($col, $val);
}

<?php

namespace App\Repositories\Interfaces;

interface DataLemburFace
{
    public function update($data);
    public function create($data);
    public function getDataDT($data);
    public function getDataByPengawas($data);
    public function process($id, $data);
    public function delete($id);
    public function deleteMultiple($ids);
    public function getByCol($col, $val);
}

<?php

namespace App\Repositories\Interfaces;

interface DataLiburIzinFace
{
    public function create($data);
    public function getDataIzinDT($data);
    public function process($id, $data);
    public function delete($id);
    public function deleteMultiple($ids);
    public function getByCol($col, $val);
}

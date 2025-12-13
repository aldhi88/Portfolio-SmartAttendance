<?php

namespace App\Repositories\Interfaces;

interface DataVendorFace
{
    public function getDT($data);
    public function getAll();
    public function getByKey($id);
    public function create($data);
    public function delete($id);
    public function deleteMultiple($id);
    public function update($id, $data);
}

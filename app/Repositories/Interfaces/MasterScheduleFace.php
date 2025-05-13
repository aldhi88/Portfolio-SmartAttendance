<?php

namespace App\Repositories\Interfaces;

interface MasterScheduleFace
{
    public function getDT($data);
    public function getAll();
    public function create($data);
    public function delete($id);
    public function deleteMultiple($id);
    public function getByKey($id);
    public function update($id, $data);
}

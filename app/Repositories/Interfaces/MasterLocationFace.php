<?php

namespace App\Repositories\Interfaces;

interface MasterLocationFace
{
    public function getDT($data);
    public function getByKey($id);
    public function getAll();
    public function create($data);
    public function delete($id);
    public function deleteMultiple($id);
    public function update($id, $data);
}

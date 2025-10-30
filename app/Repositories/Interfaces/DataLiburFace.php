<?php

namespace App\Repositories\Interfaces;

interface DataLiburFace
{
    public function getByDate($month, $year);
    public function isDateLibur($date);
    public function insert($data);
    public function remove($tgl);
}

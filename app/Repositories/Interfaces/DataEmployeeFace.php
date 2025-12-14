<?php

namespace App\Repositories\Interfaces;

interface DataEmployeeFace
{
    public function delMember($data);
    public function addMember($data);
    public function getPengawas();
    public function getNonMember($data);
    public function getMember($data);
    public function setStatusMultiple($data, $status);
    public function createForm($data);
    public function update($id, $data);
    public function getByKey($id);
    public function delete($id);
    public function deleteMulti($id);
    public function insertAPI($data);
    public function getDT($data);
    public function getReportDT($data);
    public function getColValByCol($col, $val, $get);
    public function getColValByColMulti($col, $val, $get);
    public function isExistByCol($col, $val);
    public function getMultiByCol($col, $val);
    public function searchByName($name);
    public function apiGetById($request);
    public function getPengawasLembur();
    public function getSecurityLembur();
}

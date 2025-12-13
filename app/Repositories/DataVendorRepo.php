<?php

namespace App\Repositories;

use App\Models\DataVendor;
use App\Models\MasterOrganization;
use App\Models\UserLogin;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataVendorFace;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DataVendorRepo implements DataVendorFace
{
    protected $dataEmployee;
    public const DEFAULT_ROLE = 600;
    public const DEFAULT_STATUS = 'Aktif';

    public function __construct(DataEmployeeFace $dataEmployee)
    {
        $this->dataEmployee = $dataEmployee;
    }

    public function getByKey($id)
    {
        return DataVendor::where('id', '=', $id)
            ->with([
                'user_logins',
            ])
            ->get()
            ->first()
        ;
    }

    public function create($data)
    {
        try {
            $userLogin['user_role_id'] = self::DEFAULT_ROLE;
            $userLogin['nickname'] = $data['name'];
            $userLogin['username'] = $data['username'];
            $userLogin['password'] = Hash::make($data['password']);
            $q = UserLogin::create($userLogin);

            $dataVendor['user_login_id'] = $q->id;
            $dataVendor['master_organization_id'] = $data['master_organization_id'];
            $dataVendor['name'] = $data['name'];
            $dataVendor['status'] = self::DEFAULT_STATUS;
            DataVendor::create($dataVendor);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert data_vendor failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAll()
    {
        return MasterOrganization::all();
    }

    public function getDT($data)
    {
        return DataVendor::query()
            ->with([
                'user_logins',
                'master_organizations'
            ])
        ;
    }

    public function delete($id)
    {
        try {
            $userLoginId = DataVendor::select('user_login_id')->where('id', $id)->first()->getAttribute('user_login_id');
            DataVendor::find($id)->forceDelete();
            UserLogin::find($userLoginId)->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }

        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            $userLoginIds = DataVendor::whereIn('id', $ids)
                ->pluck('user_login_id')
                ->toArray();

            DataVendor::whereIn('id', $ids)->forceDelete();
            UserLogin::whereIn('id', $userLoginIds)->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple data_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $userLogin['username'] = $data['username'];
            $userLogin['password'] = Hash::make($data['password']);
            UserLogin::where('id', $data['user_login_id'])
                ->update($userLogin);

            $dataVendor['master_organization_id'] = $data['master_organization_id'];
            $dataVendor['name'] = $data['name'];
            DataVendor::where('id', $id)->update($dataVendor);
            return true;
        } catch (\Exception $e) {
            Log::error("Update data_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}

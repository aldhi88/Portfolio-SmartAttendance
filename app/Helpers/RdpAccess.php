<?php

namespace App\Helpers;

use App\Models\UserLogin;

class RdpAccess
{
    public static function isAdmin(?UserLogin $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) ($user?->is_superuser || $user?->is_pengawas_rdp);
    }

    public static function isEmployee(?UserLogin $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) ($user?->is_karyawan || $user?->is_pengawas);
    }

    public static function isRdpEligibleEmployee(?UserLogin $user = null): bool
    {
        $user ??= auth()->user();

        if (!$user?->data_employees) {
            return false;
        }

        $user->data_employees->loadMissing('master_organizations:id,is_rdp_eligible');

        return (bool) $user->data_employees->master_organizations?->is_rdp_eligible;
    }

    public static function isPimpinan(?UserLogin $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) $user?->is_manajer;
    }

    public static function isVendor(?UserLogin $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) $user?->is_vendor_rdp;
    }

    public static function employeeId(?UserLogin $user = null): ?int
    {
        $user ??= auth()->user();
        $employeeId = $user?->data_employees?->id;

        return $employeeId ? (int) $employeeId : null;
    }

    public static function vendorId(?UserLogin $user = null): ?int
    {
        $user ??= auth()->user();
        $vendorId = $user?->rdp_master_vendors?->id;

        return $vendorId ? (int) $vendorId : null;
    }

    public static function matchesAnyRole(array $roles, ?UserLogin $user = null): bool
    {
        $user ??= auth()->user();

        foreach ($roles as $role) {
            if (match ($role) {
                'admin' => self::isAdmin($user),
                'employee' => self::isEmployee($user) && self::isRdpEligibleEmployee($user),
                'pimpinan' => self::isPimpinan($user),
                'vendor' => self::isVendor($user),
                default => false,
            }) {
                return true;
            }
        }

        return false;
    }
}

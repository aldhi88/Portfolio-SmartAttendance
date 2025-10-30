<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserLoginResource;
use App\Models\LaravelPersonalAccessToken;
use App\Repositories\Interfaces\UserLoginInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $userLoginRepository;

    public function __construct(UserLoginInterface $userLoginRepository)
    {
        $this->userLoginRepository = $userLoginRepository;
    }

    public function login(Request $request)
    {

        $data = $request->validate([
            'username'     => 'required|string',
            'password'     => 'required|string',
            'client'       => 'nullable|string',           // 'web'|'webview'|'android'|'ios'
            'device_id'    => 'nullable|string|max:191',   // unik per device
            'expires_days' => 'nullable|integer|min:1|max:365', // opsional
        ]);

        $user = $this->userLoginRepository->getByUsername($data['username']);

        if ($user) {
            if ($user->data_employees->status != 'Aktif') {
                return response()->json(['message' => 'User Tidak Aktif'], 401);
            }

            if (!Hash::check($data['password'], $user->password)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Identitas device untuk menamai token
        $client    = $data['client']    ?? 'web';
        $deviceId  = $data['device_id'] ?? Str::uuid()->toString();

        $tokenName = "{$client}:{$deviceId}";
        $user->tokens()->whereIn('name', [$tokenName, "{$tokenName}:rt"])->delete();

        $access = $user->createToken($tokenName, abilities: ['*'], expiresAt: now()->addDays(15));
        // $access = $user->createToken($tokenName, abilities: ['*'], expiresAt: now()->addMinutes(15));
        $refresh = $user->createToken("{$tokenName}:rt", abilities: ['refresh'], expiresAt: now()->addDays(30));

        return response()->json([
            'user' => $user,
            'access_token' => $access->plainTextToken,
            'refresh_token' => $refresh->plainTextToken,
            'expires_at' => optional($access->accessToken->expires_at)?->toISOString(),
            'refresh_expires_at' => optional($refresh->accessToken->expires_at)?->toISOString(),
        ], 200);
    }

    public function refresh(Request $r)
    {
        $refresh = $r->bearerToken(); // kirim refresh token di Authorization
        $pat = LaravelPersonalAccessToken::findToken($refresh);
        if (!$pat || !$pat->can('refresh') || ($pat->expires_at && $pat->expires_at->isPast())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = $pat->tokenable;

        // Rotasi access token baru (15 menit)
        $name = str_replace(':rt', '', $pat->name);
        $user->tokens()->where('name', $name)->delete();
        $newAccess = $user->createToken($name, ['*'], now()->addMinutes(15));

        // ⬇️ Tambahan minimal: rotasi refresh token + kirim balik
        $pat->delete(); // hapus refresh token lama
        $newRefresh = $user->createToken($name . ':rt', ['refresh'], now()->addDays(30)); // atur TTL sesuai kebutuhan

        return response()->json([
            'access_token'  => $newAccess->plainTextToken,
            'expires_at'    => optional($newAccess->accessToken->expires_at)?->toISOString(),
            'refresh_token' => $newRefresh->plainTextToken,
            'refresh_expires_at' => optional($newRefresh->accessToken->expires_at)?->toISOString(),
        ]);
    }


    public function logout(Request $r)
    {
        if ($bearer = $r->bearerToken()) {

            $plain = Str::after($bearer, '|');
            $hash = hash('sha256', $plain);

            $pat = LaravelPersonalAccessToken::where('token', $hash)->first();

            \Log::info("Logout token:", [
                'bearer' => $bearer,
                'plain'  => $plain,
                'found_name' => $pat->name ?? null,
                'userId' => $pat->tokenable_id ?? null
            ]);

            if ($pat) {
                $userId = $pat->tokenable_id;
                $base   = str_replace(':rt', '', $pat->name);

                LaravelPersonalAccessToken::where('tokenable_id', $userId)
                    ->whereIn('name', [$base, "$base:rt"])
                    ->delete();
            }
        }

        return response()->noContent();
    }
}

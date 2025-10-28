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

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Identitas device untuk menamai token
        $client    = $data['client']    ?? 'web';
        $deviceId  = $data['device_id'] ?? Str::uuid()->toString();

        $tokenName = "{$client}:{$deviceId}";
        $user->tokens()->whereIn('name', [$tokenName, "{$tokenName}:rt"])->delete();

        $access = $user->createToken($tokenName, abilities: ['*'], expiresAt: now()->addMinutes(60));
        $refresh = $user->createToken("{$tokenName}:rt", abilities: ['refresh'], expiresAt: now()->addDays(30));

        return response()->json([
            'user' => new UserLoginResource($user),
            'access_token' => $access->plainTextToken,
            'refresh_token' => $refresh->plainTextToken,
            'expires_at' => optional($access->accessToken->expires_at)?->toISOString(),
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

        // Rotasi access token baru
        $name = str_replace(':rt', '', $pat->name);
        $user->tokens()->where('name', $name)->delete();
        $newAccess = $user->createToken($name, ['*'], now()->addMinutes(60));

        return response()->json([
            'access_token' => $newAccess->plainTextToken,
            'expires_at'   => optional($newAccess->accessToken->expires_at)?->toISOString(),
        ]);
    }

    public function logout(Request $r)
    {
        if ($bearer = $r->bearerToken()) {
            if ($pat = LaravelPersonalAccessToken::findToken($bearer)) {
                $base = $pat->name;            // bisa access atau refresh
                $base = str_replace(':rt', '', $base);
                $r->user()?->tokens()->whereIn('name', [$base, "$base:rt"])->delete();
            }
        }
        return response()->noContent();
    }
}

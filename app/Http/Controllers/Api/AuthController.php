<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserLoginResource;
use App\Models\LaravelPersonalAccessToken;
use App\Repositories\Interfaces\UserLoginInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

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

        // Hapus token lama HANYA untuk device ini (bukan semua)
        $user->tokens()->where('name', $tokenName)->delete();

        // Opsional expiry
        $expiresAt = isset($data['expires_days']) ? now()->addDays((int)$data['expires_days']) : null;

        $tokenResult = $user->createToken(
            name: $tokenName,
            abilities: ['*'],          // batasi sesuai kebutuhan
            expiresAt: $expiresAt      // null = tidak auto-expired
        );

        return response()->json([
            'user'       => new UserLoginResource($user),
            'token'      => $tokenResult->plainTextToken,
            'device'     => ['name' => $tokenName, 'client' => $client, 'device_id' => $deviceId],
            'expires_at' => optional($tokenResult->accessToken->expires_at)?->toISOString(),
        ], 200);
    }

    public function logout(Request $request)
    {
        // Idempotent: hapus token jika ada; jika token ngasal, tetap 204
        if ($bearer = $request->bearerToken()) {
            if ($pat = LaravelPersonalAccessToken::findToken($bearer)) {
                $pat->delete();
            }
        }
        return response()->noContent(); // 204
    }
}

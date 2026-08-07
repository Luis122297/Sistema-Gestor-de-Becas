<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{

    public function generateSecret(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();
        $user->two_factor_secret = encrypt($secret);
        $user->save();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrCodeUrl);

        return response()->json([
            'message'     => 'Escanea este QR en tu aplicación de autenticación',
            'qr_code_svg' => $svg,
            'secret'      => $secret, 
        ]);
    }


    public function verifyChallenge(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        /** @var User $user */
        $user = $request->user();

        if (! $user->currentAccessToken()->can('2fa-verify')) {
            return response()->json(['message' => 'Token inválido para esta operación'], 403);
        }

        if (! $user->two_factor_secret) {
            return response()->json(['message' => '2FA no está configurado'], 400);
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(decrypt($user->two_factor_secret), $request->code);

        if ($valid) {
            $user->currentAccessToken()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message'      => 'Autenticación exitosa',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'role'         => $user->role,
            ]);
        }

        return response()->json(['message' => 'El código proporcionado es incorrecto'], 401);
    }
}
<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActionVerificationCode;
use Carbon\Carbon;

class VerificationCodeService
{
    public function sendCode(User $user, string $action, ?array $data = null): void
    {
        // Invalidate previous codes for this action
        VerificationCode::where('user_id', $user->id)
            ->where('action', $action)
            ->delete();

        $code = (string) random_int(100000, 999999);

        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'action' => $action,
            'data' => $data,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($user)->send(new ActionVerificationCode($code, $action));
    }

    public function verifyCode(User $user, string $action, string $code): ?VerificationCode
    {
        $verification = VerificationCode::where('user_id', $user->id)
            ->where('action', $action)
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $verification;
    }

    public function clearCode(VerificationCode $verificationCode): void
    {
        $verificationCode->delete();
    }
}

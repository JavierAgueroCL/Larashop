<?php

namespace App\Http\Controllers;

use App\Services\VerificationCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class ProfileSecurityController extends Controller
{
    public function __construct(
        protected VerificationCodeService $verificationCodeService
    ) {}

    public function sendCode(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:update_password,delete_account,update_email',
        ]);

        $user = $request->user();
        $this->verificationCodeService->sendCode($user, $request->action);

        return response()->json(['message' => 'Código enviado correctamente a su correo.']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $verification = $this->verificationCodeService->verifyCode($user, 'update_password', $request->verification_code);

        if (! $verification) {
            return back()->withErrors(['verification_code' => 'El código de verificación es inválido o ha expirado.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $this->verificationCodeService->clearCode($verification);

        return back()->with('status', 'password-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $user = $request->user();
        $verification = $this->verificationCodeService->verifyCode($user, 'delete_account', $request->verification_code);

        if (! $verification) {
            return back()->withErrors(['userDeletion' => ['verification_code' => 'El código de verificación es inválido o ha expirado.']]);
        }

        $this->verificationCodeService->clearCode($verification);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request->user()->id,
            'name' => 'nullable|string|max:255',
        ]);
        
        $user = $request->user();
        $verification = $this->verificationCodeService->verifyCode($user, 'update_email', $request->verification_code);

         if (! $verification) {
            return back()->withErrors(['verification_code' => 'El código de verificación es inválido o ha expirado.']);
        }
        
        // Proceed with update
        $user->email = $request->email;
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        
        $this->verificationCodeService->clearCode($verification);
        
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectUser($request->user());
    }

    protected function redirectUser($user): RedirectResponse
    {
        switch ($user->type) {
            case 'target':
                return redirect('webmaster');
            case 'store':
                return redirect('store');
            case 'manager':
                return redirect('manager');
            case 'msadmin':
                return redirect('landing');
            case 'storekeeper':
                return redirect('storekeeper');
            case 'superadmin':
                return redirect('superadmin');
            default:
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }
}

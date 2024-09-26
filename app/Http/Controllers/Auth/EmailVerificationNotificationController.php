<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectUser($request->user());
//            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
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

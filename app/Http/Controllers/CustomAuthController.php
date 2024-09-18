<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->input('email');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Merge the determined field type with login input into the request for authentication
        $request->merge([$fieldType => $login]);

        // Prepare credentials dynamically, assuming password is common for both
        $credentials = [
            $fieldType => $login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Authentication passed...
            $user = Auth::user();

            // Redirect based on user type or other identifiers
            switch ($user->type) {
                case 'target':
                    return redirect()->route('filament.pages.admin.dashboard');
                case 'store':
                    return redirect()->route('filament.pages.store.dashboard');
                // Add more cases as needed
                default:
                    return redirect()->route('filament.dashboard'); // Default redirect
            }
        }
        return back()->withErrors([
            $fieldType => __("Taqdim etilgan hisob ma'lumotlari bizning hisobotlarimizga mos kelmaydi."),
        ]);
    }

}

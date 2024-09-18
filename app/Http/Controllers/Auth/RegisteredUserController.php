<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $source;
    protected $store;
    protected $manager;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'userType' => ['required', 'in:target,store,msadmin,manager,storekeeper,superadmin'],
            'type_name' => ['string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->userType === 'target') {
            $this->source = $request->type_name;
        }
        elseif ($request->userType === 'store') {
            $this->store = $request->type_name;
        }
        elseif ($request->userType === 'manager') {
            $this->manager = $request->type_name;
        }


        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'type' => $request->userType,
            'source' => $this->source,
            'store' => $this->store,
            'manager' => $this->manager,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

}

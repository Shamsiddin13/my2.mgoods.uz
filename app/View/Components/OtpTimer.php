<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OtpTimer extends Component
{
    public $remainingTime;
    /**
     * Create a new component instance.
     */

    public function __construct()
    {
        $expirationTime = session('otp_expiration_time');
        $this->remainingTime = $expirationTime ? max(0, $expirationTime->diffInSeconds(now())) : 0;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.otp-timer');
    }
}

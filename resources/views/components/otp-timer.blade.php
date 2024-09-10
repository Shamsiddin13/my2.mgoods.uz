@if ($remainingTime > 0)
    <span class="mr-2">Time left: {{ $remainingTime }} seconds</span>
@else
    <span class="mr-2">OTP expired</span>
@endif

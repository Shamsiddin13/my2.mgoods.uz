<form action="{{ route('verify-otp') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">
    <label for="otp">Enter OTP:</label>
    <input type="text" name="otp" maxlength="6" required>

    <button type="submit">Verify</button>
</form>

<div id="countdown">Time left: 60s</div>
<button id="resend" style="display:none;" onclick="resendOTP()">Resend OTP</button>

<script>
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdown');
    const resendBtn = document.getElementById('resend');

    const timer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownEl.style.display = 'none';
            resendBtn.style.display = 'block';
        } else {
            countdownEl.innerHTML = `Time left: ${timeLeft}s`;
        }
        timeLeft -= 1;
    }, 1000);

    function resendOTP() {
        // Send AJAX request to resend OTP
        fetch('{{ route('resend-otp', ['email' => $email]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: '{{ $email }}' })
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    timeLeft = 60;
                    countdownEl.style.display = 'block';
                    resendBtn.style.display = 'none';
                }
            });
    }
</script>

<?php

namespace App\Filament\Pages;

use App\Http\EmailVerifyService;
use App\Mail\OTPMail;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Filament\Forms\Form;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;

class Registration extends Register
{
    public $is_verified_otp = false;
    protected ?string $maxWidth = '2xl';

    public function form(Form $form): Form
    {
        $expirationTime = session('otp_expiration_time');

        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Contact')
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getUserNameFormComponent(),
                            $this->getEmailFormComponent(),
                        ]),
                    Wizard\Step::make('Email Verification')
                        ->schema([
                            TextInput::make('otp')
                                ->label('OTP Kod')
                                ->placeholder("Bir martalik OTP kodingizni kiriting ..")
                                ->minLength(6)
                                ->maxLength(6)
                                ->required()
                                ->readOnly(fn (callable $get) => $this->is_verified_otp === true)
                                ->live(true)
                                ->prefix($this->formatTimer($expirationTime))
                                ->afterStateUpdated(function ($state, $get, $set) {
                                    if ($state) {
                                        $is_verified = $this->verifyOtp($get('otp')); // Проверяем введённый код OTP
                                        if ($is_verified) {
                                            $this->is_verified_otp = true;
                                            session(['email_verified_at' => now()->addHours(5)]);
                                            session()->flash('message', 'ваш электронная почта был успешно проверял!');
                                        }
                                        else{
                                            session()->flash('message', 'error!');

                                        }

                                    }
                                }),
                            Checkbox::make("is_send")
                                ->label("OTP kod jo'natilsinmi ?")
                                ->visible(fn (callable $get) => $this->is_verified_otp === false)
                                ->reactive() // Позволяет динамически реагировать на изменения состояния
                                ->live(true)
                                ->afterStateUpdated(function ($state, $get, $set) {
                                    if ($state) { // Если флажок установлен
                                        $email = $get('email'); // Получаем email пользователя
                                        $result = $this->sendOtp($email); // Отправляем OTP код на email
                                        if ($result){
                                            $expirationTime = now()->addHours(5)->addSeconds(90);
                                            session(['otp_expiration_time' => $expirationTime]); // Update last activity time
                                            $set('is_send', false);
                                            Notification::make()
                                                ->title('Muvaffaqiyatli yuborildi!')
                                                ->success()
                                                ->body('Ushbu elektron pochta manziliga OTP kodi yuborildi.' . ' ' . $email)
                                                ->send();
                                        }
                                        else{
                                            $set('is_send', false);
                                            Notification::make()
                                                ->title('Yuborilmadi!')
                                                ->danger()
                                                ->body("Ushbu kiritilgan email mavjud emas." . '      ' . ' Iltimos tekshirib qaytadan kiriting! ' . '  '. $email)
                                                ->send();
                                        }
                                    }
                                }),
                    ]),
                    Wizard\Step::make('Specific Information')
                        ->schema([
                            Select::make('type')
                                ->label('Select User Type')
                                ->options(User::getAvailableTypes())
                                ->reactive()
                                ->required(),

                            // Conditional fields based on selected type
                            TextInput::make('source')
                                ->label('Source Name')
                                ->required()
                                ->visible(fn (callable $get) => $get('type') === 'target'),

                            TextInput::make('store')
                                ->label('Store Name')
                                ->required()
                                ->visible(fn (callable $get) => $get('type') === 'store'),

                            TextInput::make('manager')
                                ->label('Manager Name')
                                ->required()
                                ->visible(fn (callable $get) => $get('type') === 'manager'),
                        ]),
                    // Password step, which will trigger OTP generation
                    Wizard\Step::make('Password')
                        ->schema([
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:submit="register"
                    >
                        Register
                    </x-filament::button>
                    BLADE))),
            ]);
    }
    private function formatTimer($expirationTime)
    {
        // If expiration time is not set or has passed, return '00:00'
        if (!$expirationTime || now()->addHours(5)->greaterThan($expirationTime)) {
            return '00:00';
        }

        // Calculate the remaining time in seconds
        $remainingTime = $expirationTime->timestamp - now()->addHours(5)->timestamp;
        return $this->formatTime($remainingTime);
    }

    private function formatTime($seconds)
    {
        $minutes = intdiv($seconds, 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    private function isOtpExpired($expirationTime)
    {
        return !$expirationTime || now()->addHours(5)->greaterThan($expirationTime);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getGithubFormComponent(): Component
    {
        return TextInput::make('github')
            ->prefix('https://github.com/')
            ->label(__('GitHub'))
            ->maxLength(255);
    }

    protected function getTwitterFormComponent(): Component
    {
        return TextInput::make('twitter')
            ->prefix('https://x.com/')
            ->label(__('Twitter (X)'))
            ->maxLength(255);
    }

    protected function  getUserNameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->placeholder('Majburiy emas')
            ->maxLength(30)
            ->minLength(3)
            ->unique($this->getUserModel());
    }

    public function verifyOtp($inputOtp) : bool
    {
        $sessionOtp = session('otp_code');
        $expirationTime = session('otp_expiration_time');

        // Check if OTP is correct
        if ($inputOtp != $sessionOtp) {
            $this->addError('otp', 'Неверный код OTP.');
            Notification::make()
                ->title('OTP Tasdiqlanmadi!')
                ->danger()
                ->body("Ushbu kiritilgan OTP kodi noto'g'ri!")
                ->send();
            return false;
        }

        // Check if the OTP is still valid (within 90 seconds of expirationTime)
        $currentTime = now()->addHours(5)->timestamp; // Get current timestamp
        $expirationTimestamp = $expirationTime->timestamp; // Get expiration timestamp

        // Calculate time difference in seconds
        $timeDifference = $currentTime - $expirationTimestamp;

        // Check if the OTP is still valid (within 1 minute of last activity)
        if ($timeDifference > 90) {
            $this->addError('otp', 'Срок действия OTP истек.');
            session()->forget('otp_code');
//            session()->forget('last_activity');
            session()->forget('otp_expiration_time');
            Notification::make()
                ->title("Muddati o'tgan OTP!")
                ->danger()
                ->body("Ushbu kiritilgan OTP kod muddati o'tib ketgan ! " . '  ' . 'Qaytadan yuboring' . '  ' . 'Bir martalik OTP kod muddati 90 soniya')
                ->send();
            return false;
        }

        // Successful verification, remove OTP from session and update last activity time
        session()->forget('otp_code');
        session(['last_activity' => time()]);
        Notification::make()
            ->title('Muvaffaqiyatli tasdiqlandi')
            ->success()
            ->body('Sizning elektron pochtangiz muvaffaqiyatli tasdiqlandi.')
            ->send();
        return true;
    }

    public function sendOtp($email)
    {
        $otp = random_int(100000, 999999); // Generate OTP code
        session(['otp_code' => $otp]); // Save OTP in session

        $registrationService = new EmailVerifyService();
        $result = $registrationService->verifyEmail($email);

        if ($result['status'] === 'valid' && $result['result'] === 'deliverable' && $result['score'] > 0) {
            // Email is valid
            Mail::to($email)->send(new OtpMail($otp)); // Create Mail class for sending OTP

            return true; // Return true since sending the OTP is always successful
        } else {
            // Handle invalid or undeliverable email
            return false;
        }

    }

}

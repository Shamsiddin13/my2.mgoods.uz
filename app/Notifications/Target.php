<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class Target extends Notification implements ShouldQueue
{
    use Queueable;

    public $requestFin;

    public $requestStatus;

    public $title;
    public $icon;
    public $color;
    public $status;

    public function __construct($requestFin, $requestStatus)
    {
        $this->requestFin = $requestFin;
        $this->requestStatus = $requestStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database']; // Could also include 'mail' if you want email notifications
    }
    /**
     * Get the mail representation of the notification.
     */

    public function toDatabase($notifiable)
    {
        if ($this->requestStatus === 'approved') {
            $this->title = "Sizning so'rovingiz muvaffaqiyatli qabul qilindi !";
            $this->icon = "heroicon-o-check-circle";
            $this->color = "success";
            $this->status = "Tasdiqlandi";
        }
        else if($this->requestStatus === 'cancel'){
            $this->title = "Sizning so'rovingiz bekor qilindi !";
            $this->icon = "heroicon-o-x-circle";
            $this->color = "danger";
            $this->status = "Bekor qilindi";
        }
        else if($this->requestStatus === 'new'){
            $this->title = "<p><strong>Review Needed:</strong> New Request for Withdrawal</p>";
            $this->icon = "heroicon-o-currency-dollar";
            $this->color = "warning";
            $this->status = "New";
        }

        if ($this->requestStatus !== 'new') {
            return [
                'title' => $this->title,
                'body' => "<p><strong>Holati:</strong> $this->status<br><strong>Karta raqam:</strong> {$this->formatAccountNumber($this->requestFin->account)}<br><strong>Summa:</strong> {$this->formatAmount($this->requestFin->amount)} UZS<br><strong>Sana-Vaqti: </strong>{$this->formatDate($this->requestFin->updated_at)}</p>",
                'color' => $this->color,
                'duration' => 'persistent',
                'icon' => $this->icon,
                'iconColor' => $this->color,
                'status' => $this->color,
                'view' => 'filament-notifications::notification',
                'viewData' => [],
                'format' => 'filament'
            ];
        }
        else{
            return [
                'title' => $this->title,
                'body' => "<p><strong>Request ID:</strong> {$this->requestFin->id}<br><strong>Username:</strong> {$this->requestFin->user->name}<br><strong>UserType:</strong> {$this->requestFin->user_type}<br><strong>Status:</strong> $this->status<br><strong>Account:</strong> {$this->formatAccountNumber($this->requestFin->account)}<br><strong>Amount:</strong> {$this->formatAmount($this->requestFin->amount)} UZS<br><strong>Datetime: </strong>{$this->formatDate($this->requestFin->created_at)}</p>",
                'color' => $this->color,
                'duration' => 'persistent',
                'icon' => $this->icon,
                'iconColor' => $this->color,
                'status' => $this->color,
                'view' => 'filament-notifications::notification',
                'viewData' => [],
                'format' => 'filament'
            ];
        }


    }

    public function toMail(object $notifiable): MailMessage
    {
        $notifications = $this->user->notifications()->latest()->take(5)->get();

        // Return the email with the notifications passed to the view
        return (new MailMessage)
            ->subject('Your Recent Notifications')
            ->markdown('target', [
                'user' => $this->user,
                'notifications' => $notifications
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }

    public function formatAccountNumber($accountNumber) {
        return implode(' ', str_split((string)$accountNumber, 4));
    }

    public function formatAmount($amount) {
        return number_format($amount, 0, '.', ' ');
    }

    public function formatDate($datetime) {
        return Carbon::parse($datetime)->format('d-m-Y H:i:s');
    }
}

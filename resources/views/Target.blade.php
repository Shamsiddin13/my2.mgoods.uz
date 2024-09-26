<x-mail::message>
# Introduction

The body of your message.
@foreach ($notifications as $notification)
    - **Title**: {{ $notification->data['title'] ?? 'No Title' }}
    - **Message**: {{ $notification->data['message'] ?? 'No Message' }}
    - **Received at**: {{ $notification->created_at->format('Y-m-d H:i:s') }}

@endforeach

<x-mail::button :url="route('user.notifications')">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

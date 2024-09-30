<div>
    <h3 class="text-lg font-bold mb-4">Oqimlar ro'yhati</h3>
    <table class="min-w-full bg-white">
        <thead>
        <tr>
            <th class="py-2">Stream Name</th>
            <th class="py-2">Full Link</th>
        </tr>
        </thead>
        <tbody>
        @foreach($streams as $stream)
            <tr>
                <td class="py-2">{{ $stream->stream_name }}</td>
                <td class="py-2">
                    <a href="{{ $stream->full_link }}" target="_blank" class="text-blue-500">
                        {{ $stream->full_link }}
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

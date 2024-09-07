<x-filament::table>
    @php
        $columns = [
            'Sana',
            'Lead',
            'Otkaz',
            'Qabul',
            'Yolda',
            'Yetkazildi',
            'Sotildi'
        ];
    @endphp

    <x-slot name="columns">
        @foreach ($columns as $column)
            <x-filament::table.column :label="$column" />
        @endforeach
    </x-slot>

    <x-slot name="rows">
        @foreach ($rows as $row)
            <tr>
                @foreach ($columns as $column)
                    <td>{{ $row[$column] }}</td>
                @endforeach
            </tr>
        @endforeach
    </x-slot>
</x-filament::table>

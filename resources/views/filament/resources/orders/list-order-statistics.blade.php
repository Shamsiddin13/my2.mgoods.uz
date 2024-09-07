<x-app-layout>

<table>
    <thead>
    <tr>
        <th>Article</th>
        <th>Lead</th>
        <th>Qabul</th>
        <th>Otkaz</th>
        <th>Yolda</th>
        <th>Yetkazildi</th>
        <th>Sotildi</th>
        <th>Qaytib Keldi</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($statistics as $stat)
        <tr>
            <td>{{ $stat->article }}</td>
            <td>{{ $stat->lead }}</td>
            <td>{{ $stat->qabul }}</td>
            <td>{{ $stat->otkaz }}</td>
            <td>{{ $stat->yolda }}</td>
            <td>{{ $stat->yetkazildi }}</td>
            <td>{{ $stat->sotildi }}</td>
            <td>{{ $stat->qaytib_keldi }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</x-app-layout>

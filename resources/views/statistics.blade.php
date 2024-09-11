<!-- resources/views/statistics/index.blade.php -->

@extends('layouts.app')

@section('title', 'Statistics')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Statistics</h1>

    <table class="w-1.5 bg-white border-collapse border border-gray-300 shadow-md rounded-lg">
        <thead>
        <tr class="bg-gray-100">
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Article</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Lead</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Qabul</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Otkaz</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Yolda</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Yetkazildi</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Sotildi</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-gray-600">Qaytib Keldi</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $item)
            <tr class="hover:bg-gray-50">
                <td class="border border-gray-300 px-4 py-2">{{ $item->Article }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->Lead }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->Qabul }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->Otkaz }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->Yolda }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->Yetkazildi }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->Sotildi }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item->QaytibKeldi }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

<!-- resources/views/filament/admin/resources/profit-loss-statistics-resource/pages/update-target.blade.php -->
@extends('filament::layouts.app')

@section('content')
    <x-modal :title="'Update Target for Article: ' . $article">
        <form wire:submit.prevent="updateTarget">
            <div class="space-y-4">
                {{ $this->form }}
            </div>
            <x-filament::button type="submit">
                Update Target
            </x-filament::button>
        </form>
    </x-modal>
@endsection

@php
    $props = [
        "polls" => $polls,
        "mode" => $mode,
        "pollToEdit" => $pollToEdit,
        "loginUrl" => route("login"),
        "username" => "test name",
    ];
@endphp

<x-vue-app-layout>
    <x-slot:scripts>
        @vite(['resources/js/poll-dashboard.js'])
    </x-slot>

    <x-slot:title>
        Sondages
    </x-slot>

    <div
        id="app"
        data-props='@json($props)'
    ></div>
</x-vue-app-layout>
<x-main>
    <x-slot name="title"><?= $title ?></x-slot>
    <div id="app" class="w-100"></div>
    <x-slot name="scripts">
        @vite([$appJs])
    </x-slot>
</x-main>
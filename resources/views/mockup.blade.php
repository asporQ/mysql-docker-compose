<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Conflicting Emotions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white px-6 py-4 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-xl p-4 text-gray-900 dark:text-gray-100">
                    {{ __("Summary") }}
                </div>
                <table class="mx-auto text-gray-900 dark:text-gray-100 table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-2">ID</th>
                            <th class="px-6 py-2">Date</th>
                            <th class="px-6 py-2">Content</th>
                            <th class="px-6 py-2">Emotion</th>
                            <th class="px-6 py-2">Intensity</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-200 dark:bg-gray-700 border border-gray-500">
                        @foreach($results as $entry)
                        <tr>
                            <td class="px-6 py-2 text-center border border-gray-500">{{ $entry->id }}</td>
                            <td class="px-6 py-2 text-center border border-gray-500">{{ $entry->date}}</td>
                            <td class="px-6 py-2 text-center border border-gray-500">{{ $entry->content, 50 }}</td>
                            <td class="px-6 py-2 text-center border border-gray-500">{{ $entry->name }}</td>
                            <td class="px-6 py-2 text-center border border-gray-500">{{ $entry->intensity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

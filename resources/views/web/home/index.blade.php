<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="homeNotes">
            @forelse($notes as $note)
                <a href="{{ route('web.notes.show', [ 'id' => $note->id ]) }}">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-5 mb-5">
                        <h5 class="text-2xl">
                            {{ $note->title }}
                        </h5>
                        <pre class="text-gray-500">{{ Str::limit($note->content()->content, 120) }}</pre>
                    </div>
                </a>
            @empty
                <div class="overflow-hidden sm:rounded-lg p-5 mb-5">
                    {{ __('No notes published yet.') }}
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

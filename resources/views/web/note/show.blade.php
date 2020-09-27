<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $note->title }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h6 class="text-gray-500 font-extrabold text-right mb-2 mr-2">
                {{ __(ucwords($note->type)) }}
                &bull;
                @if ($note->user_id != Auth::id())
                    <a href="#">{{ Auth::user()->visibleName() }}</a>
                @elseif (Auth::user()->type == \App\Enums\UserType::Administrator || $note->user_id == Auth::id())
                    <a href="{{ route('web.notes.edit', [ 'id' => $note->id ]) }}">{{ __('Edit') }}</a>
                @endif
            </h6>
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-3 px-4 mb-5 font-mono">
                <pre>{{ $note->content()->content }}</pre>
            </div>
            @foreach($comments as $comment)
                {{ $comment }}
            @endforeach
            @if ($note->comments)
                <div class="mb-5">
                    <x-jet-validation-errors class="mb-4" />
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="">
                        @csrf

                        <div>
                            <x-jet-label value="{{ __('Write Comment') }}" />
                            <x-jet-input class="block mt-1 w-full" type="comment" name="comment" :value="old('comment')" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4">
                                {{ __('Send Comment') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Delete Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-5 mb-5">
                <x-jet-validation-errors class="mb-4" />
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                <div>
                    @csrf

                    <div class="text-center text-2xl mt-4">
                        {{ __('Are you sure to delete this note?') }}
                    </div>
                    <div class="text-center text-xl text-gray-700">
                        {{ $note->title }}
                    </div>
                    <div class="mt-6 text-center text-gray-400">
                        {{ __('You cannot be undo this.') }}
                    </div>

                    <div class="text-center mt-12 mb-4">
                        <a href="{{ route('web.notes.show', [ 'id' => $note->id ]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-jet-button onclick="execute()" class="inline-flex items-center px-4 py-2 bg-red-300 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-red-400 active:bg-red-500 focus:outline-none focus:border-red-900 focus:shadow-outline-red disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                            {{ __('Delete') }}
                        </x-jet-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript" defer>
        function execute() {
            let config = {
                headers: {
                    Authorization: 'Bearer ' + window.Application.api_token,
                    Accept: 'application/json'
                }
            };

            let _token = '{{ Auth::user()->tokens()->where('name', 'private-token')->first()->token }}';

            let url = window.Application.api_url + '/note/destroy/{{ $note->id }}?token=' + _token;

            axios.delete(url, config).then((response) => {
                if (response.status === 200) {
                    window.location.href = "{{ route('web.home.index') }}";
                }
            }).catch((error) => {

            });
        }
    </script>
</x-app-layout>

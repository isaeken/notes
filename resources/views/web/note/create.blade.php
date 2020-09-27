<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Note') }}
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
                    <div>
                        <x-jet-label value="{{ __('Title') }}" />
                        <x-jet-input class="block mt-1 w-full" id="title" type="title" name="title" :value="old('title')" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-jet-label value="{{ __('Type') }}" />
                        <select id="type" name="type" class="form-input rounded-md shadow-sm block mt-1 w-full" required>
                            @foreach(\App\Enums\NoteType::getValues() as $type)
                                <option value="{{ $type }}" {{ $loop->last ? 'selected' : '' }}>{{ ucwords($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-jet-label value="{{ __('Content') }}" />
                        <textarea id="content" name="content" required class="form-input rounded-md shadow-sm block mt-1 w-full" cols="30" rows="10">{{ old('content') }}</textarea>
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <input id="comments" type="checkbox" class="form-checkbox" name="comments">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Enable Comments') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-jet-button class="ml-4" onclick="execute()">
                            {{ __('Create Note') }}
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

            let url = window.Application.api_url + '/note/store';

            let data = {
                token: _token,
                title: document.getElementById('title').value,
                type: document.getElementById('type').value,
                content: document.getElementById('content').value,
                comments: document.getElementById('comments').checked,
            };

            axios.post(url, data, config).then((response) => {
                if (response.status === 200) {
                    window.location.href = response.data.response.url;
                }
            }).catch((error) => {

            });
        }
    </script>
</x-app-layout>

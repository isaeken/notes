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
            <div id="comments"></div>
            @if ($note->comments)
                <div class="mb-5">
                    <x-jet-validation-errors class="mb-4" />
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        <div>
                            <x-jet-label value="{{ __('Write Comment') }}" />
                            <x-jet-input class="block mt-1 w-full" type="comment" id="comment" name="comment" :value="old('comment')" required />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4" onclick="sendComment()">
                                {{ __('Send Comment') }}
                            </x-jet-button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script type="application/javascript" defer>
        let config = {
            headers: {
                Authorization: 'Bearer ' + window.Application.api_token,
                Accept: 'application/json'
            }
        };

        /**
         * Add comment element to comments
         * @param id
         */
        function addComment(id) {
            const $comments = $('#comments');
            let url = window.Application.api_url + '/comment/read/' + id;
            axios.get(url, config).then((commentResponse) => {
                const comment = commentResponse.data.response;
                axios.get(window.Application.api_url + '/user/read/' + comment.user_id, config).then((userResponse) => {
                    const user = userResponse.data.response;

                    const $container = $('<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-2 px-2 mb-5"></div>');
                    const $photo = $('<img />');
                    const $name = $('<div></div>');
                    const $comment = $('<div></div>');
                    const $left = $('<div></div>');
                    const $right = $('<div></div>');

                    $photo.attr('src', user.profile_photo_url).attr('alt', $name.text()).addClass('inline-block rounded-full w-100');
                    $name.text((user.first_name + ' ' + user.last_name).toLowerCase()).addClass('font-bold capitalize align-top ml-3 inline-block');
                    $comment.text(comment.content).addClass('ml-3');

                    $left.addClass('float-left w-auto inline-block');
                    $right.addClass('float-left w-auto inline-block');
                    $left.append($photo);
                    $right.append($name);
                    $right.append($comment);

                    $container.append($left);
                    $container.append($right);
                    $comments.append($container);
                });
            });
        }

        /**
         * Get and add comments
         */
        function getComments() {
            const $comments = $('#comments');
            $comments.html('');
            let url = window.Application.api_url + '/comment/';
            axios.get(url, config).then((commentResponse) => {
                $.each(commentResponse.data.response, function (index, comment) {
                    addComment(comment.id);
                });
            });
        }

        /**
         * Send comment
         */
        function sendComment() {
            let data = {
                note_id: {{ $note->id }},
                content: document.getElementById('comment').value,
            };

            axios.post(window.Application.api_url + '/comment/store', data, config).then((response) => {
                if (response.status === 200) {
                    addComment(response.data.response.id);
                }
            }).catch((error) => {

            });
        }

        $(document).ready(function () {
            getComments();
        });
    </script>
</x-app-layout>

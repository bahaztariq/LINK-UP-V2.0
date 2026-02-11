<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Conversation with {{ $conversation->users->where('id', '!=', auth()->id())->first()->name }}</div>

                    <div class="card-body">
                        <div id="messages" style="height: 300px; overflow-y: scroll;">
                            @foreach($conversation->messages as $message)
                                <div class="{{ $message->user_id == auth()->id() ? 'text-end' : 'text-start' }}">
                                    <strong>{{ $message->user->name }}:</strong> {{ $message->content }}
                                </div>
                            @endforeach
                        </div>

                        <form id="message-form" action="{{ route('messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                            <div class="input-group mt-3">
                                <input type="text" name="content" class="form-control" placeholder="Type your message...">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        Echo.private('chat-message.{{ $conversation->id }}')
            .listen('.message.sent', (e) => {
                const messagesDiv = document.getElementById('messages');
                const messageElement = document.createElement('div');
                messageElement.classList.add(e.message.user_id === {{ auth()->id() }} ? 'text-end' : 'text-start');
                messageElement.innerHTML = `<strong>${e.message.user.name}:</strong> ${e.message.content}`;
                messagesDiv.appendChild(messageElement);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
    </script>
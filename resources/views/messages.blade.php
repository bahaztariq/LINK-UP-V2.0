<x-app-layout>
    <div class="flex-1 h-[calc(100vh-1rem)] overflow-hidden flex flex-col">
        <div class="max-w-7xl mx-auto h-full w-full">
            <div class="bg-white overflow-hidden shadow-xl  flex h-full border border-slate-200">
                
                <!-- Sidebar: Conversation List -->
                <div class="w-1/3 border-r border-slate-100 flex flex-col bg-white">
                    <div class="p-4 border-b border-slate-100">
                        <h2 class="text-xl font-bold text-slate-800">Messages</h2>
                    </div>
                    <div class="overflow-y-auto flex-1 no-scrollbar">
                        @forelse($conversations as $convo)
                            @php
                                $otherUser = $convo->users->where('id', '!=', auth()->id())->first();
                            @endphp
                            <a href="{{ route('conversations.show', $convo->id) }}" class="block p-4 hover:bg-slate-50 transition-colors {{ isset($conversation) && $conversation->id === $convo->id ? 'bg-slate-50 border-r-4 border-slate-900' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold overflow-hidden shrink-0">
                                        @if($otherUser->profile_photo_url)
                                            <img src="{{ $otherUser->profile_photo_url }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($otherUser->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $otherUser->name }}</h3>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ $convo->messages->last() ? $convo->messages->last()->content : 'Start a conversation' }}
                                        </p>
                                    </div>
                                    @if(isset($conversation) && $conversation->id === $convo->id)
                                        <span class="w-2 h-2 rounded-full bg-slate-900"></span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center text-slate-500">
                                <p>No conversations yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Main Chat Area -->
                <div class="flex-1 flex flex-col bg-slate-50">
                    @if(isset($conversation))
                        <!-- Header -->
                        <div class="p-4 bg-white border-b border-slate-100 flex items-center gap-3 shadow-sm z-10">
                            <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold overflow-hidden">
                                @if($conversation->users->where('id', '!=', auth()->id())->first()->profile_photo_url)
                                    <img src="{{ $conversation->users->where('id', '!=', auth()->id())->first()->profile_photo_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($conversation->users->where('id', '!=', auth()->id())->first()->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 leading-tight">
                                    {{ $conversation->users->where('id', '!=', auth()->id())->first()->name }}
                                </h2>
                                <span class="text-xs text-green-500 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Online
                                </span>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div id="messages" class="flex-1 overflow-y-auto min-h-0 no-scrollbar flex flex-col">
                            <div class="mt-auto p-6 space-y-6">
                            @foreach($conversation->messages as $message)
                                <div class="flex w-full {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="flex max-w-[75%] {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : 'flex-row' }} gap-2">
                                        <!-- Avatar -->
                                        <div class="shrink-0 h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold overflow-hidden text-slate-500">
                                             @if($message->user->profile_photo_url)
                                                <img src="{{ $message->user->profile_photo_url }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($message->user->name, 0, 1) }}
                                            @endif
                                        </div>

                                        <!-- Bubble -->
                                        <div>
                                            <div class="{{ $message->sender_id === auth()->id() ? 'bg-slate-900 text-white rounded-tr-none' : 'bg-white text-slate-900 border border-slate-200 rounded-tl-none' }} rounded-2xl px-4 py-3 shadow-sm">
                                                <p class="text-sm">{{ $message->content }}</p>
                                            </div>
                                            <span class="text-[10px] text-slate-400 mt-1 block {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                                {{ $message->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>

                        <!-- Input Area -->
                        <div class="p-4 bg-white border-t border-slate-100">
                            <form id="message-form" class="flex items-center gap-2">
                                <button type="button" class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <span class="material-symbols-outlined">add_circle</span>
                                </button>
                                <button type="button" class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <span class="material-symbols-outlined">image</span>
                                </button>
                                
                                <div class="flex-1 relative">
                                    <input type="text" id="message-input" name="content" 
                                        class="w-full pl-4 pr-12 py-3 bg-slate-100 border-transparent focus:border-slate-300 focus:bg-white focus:ring-0 rounded-full text-sm transition-all" 
                                        placeholder="Type your message..." required autocomplete="off">
                                    <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600">
                                        <span class="material-symbols-outlined text-[20px]">sentiment_satisfied</span>
                                    </button>
                                </div>

                                <button type="submit" class="p-3 bg-slate-900 hover:bg-slate-800 text-white rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center group">
                                    <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform">send</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                            <div class="h-24 w-24 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-4xl">chat_bubble_outline</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-600">Your Messages</h3>
                            <p class="text-sm">Select a conversation to start chatting</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($conversation))
    <script type="module">
        const messagesDiv = document.getElementById('messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        
        // Auto-scroll to bottom on load
        function scrollToBottom() {
            if(messagesDiv) messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
        scrollToBottom();

        // Listen for new messages
        Echo.private('chat-message.{{ $conversation->id }}')
            .listen('.message.sent', (e) => {
                const isMyMessage = e.message.sender_id === {{ auth()->id() }};
                
                const wrapperDiv = document.createElement('div');
                wrapperDiv.className = `flex w-full ${isMyMessage ? 'justify-end' : 'justify-start'}`;
                
                const innerDiv = document.createElement('div');
                innerDiv.className = `flex max-w-[75%] ${isMyMessage ? 'flex-row-reverse' : 'flex-row'} gap-2`;

                // Avatar
                const avatarDiv = document.createElement('div');
                avatarDiv.className = 'shrink-0 h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold overflow-hidden text-slate-500';
                
                if (e.message.user.profile_photo_url) {
                    const img = document.createElement('img');
                    img.src = e.message.user.profile_photo_url;
                    img.className = 'w-full h-full object-cover';
                    avatarDiv.appendChild(img);
                } else {
                    avatarDiv.innerText = e.message.user.name.charAt(0);
                }

                // Bubble
                const bubbleWrapper = document.createElement('div');
                
                const bubble = document.createElement('div');
                bubble.className = `${isMyMessage ? 'bg-slate-900 text-white rounded-tr-none' : 'bg-white text-slate-900 border border-slate-200 rounded-tl-none'} rounded-2xl px-4 py-3 shadow-sm`;
                
                const messageP = document.createElement('p');
                messageP.className = 'text-sm';
                messageP.innerText = e.message.content;
                
                bubble.appendChild(messageP);

                const timeSpan = document.createElement('span');
                timeSpan.className = `text-[10px] text-slate-400 mt-1 block ${isMyMessage ? 'text-right' : 'text-left'}`;
                const now = new Date();
                timeSpan.innerText = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

                bubbleWrapper.appendChild(bubble);
                bubbleWrapper.appendChild(timeSpan);

                innerDiv.appendChild(avatarDiv);
                innerDiv.appendChild(bubbleWrapper);
                wrapperDiv.appendChild(innerDiv);
                
                // Find the content container
                const contentDiv = messagesDiv.querySelector('.mt-auto');
                contentDiv.appendChild(wrapperDiv);
                
                scrollToBottom();
            });

        // AJAX Form Submission
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const content = messageInput.value;
            if (!content.trim()) return;

            messageInput.value = '';

            try {
                const response = await fetch('{{ route('messages.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        conversation_id: {{ $conversation->id }},
                        receiver_id: {{ $conversation->users->where('id', '!=', auth()->id())->first()->id }},
                        content: content
                    })
                });

                if (!response.ok) {
                    console.error('Failed to send message');
                    alert('Failed to send message.');
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });
    </script>
    @endif
</x-app-layout>
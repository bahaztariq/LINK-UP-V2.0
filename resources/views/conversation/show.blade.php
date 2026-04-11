@php
    $otherUser = $conversation->users->where('id', '!=', auth()->id())->first();
@endphp

<x-app-layout>
    <div class="container py-8">
        <div class="row justify-content-center">
            <div class="col-md-8 mx-auto">
                <div class="card bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="card-header p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                        <div class="flex items-center gap-3">
                            <div class="font-bold text-gray-900 dark:text-white">Conversation with {{ $otherUser->name }}</div>
                        </div>
                        <div class="flex gap-2">
                            <button id="start-video-call" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-600 transition-colors">
                                <span class="material-symbols-outlined">videocam</span>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Video Call Area (Hidden by default) -->
                        <div id="video-call-container" class="hidden relative bg-black aspect-video w-full overflow-hidden">
                            <video id="remote-video" class="w-full h-full object-cover" autoplay playsinline></video>
                            
                            <!-- Calling Overlay for Initiator -->
                            <div id="calling-overlay" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-900/80 z-10">
                                <div class="size-24 rounded-full bg-blue-500/20 flex items-center justify-center mb-4 animate-pulse">
                                    <span class="material-symbols-outlined text-5xl text-blue-400">call</span>
                                </div>
                                <h4 class="text-white text-xl font-bold mb-1">Calling {{ $otherUser->name }}...</h4>
                                <p class="text-gray-400 text-sm">Waiting for answer</p>
                            </div>

                            <video id="local-video" class="absolute bottom-4 right-4 w-32 aspect-video object-cover rounded-lg border-2 border-white shadow-lg bg-gray-800 z-20" autoplay muted playsinline></video>
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-4 z-30">
                                <button id="end-call" class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-full shadow-lg transition-transform hover:scale-110">
                                    <span class="material-symbols-outlined">call_end</span>
                                </button>
                            </div>
                        </div>

                        <div id="messages" class="p-4 space-y-4" style="height: 450px; overflow-y: scroll; scroll-behavior: smooth;">
                            @foreach($conversation->messages as $message)
                                <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[70%] {{ $message->user_id == auth()->id() ? 'bg-blue-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-r-2xl rounded-tl-2xl' }} p-3 shadow-sm">
                                        <div class="text-xs opacity-75 mb-1 font-bold">{{ $message->user->name }}</div>
                                        <div class="text-sm">{{ $message->content }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <form id="message-form" action="{{ route('messages.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                                <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                                <div class="flex gap-2">
                                    <input type="text" name="content" class="flex-1 rounded-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Type your message..." required>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-bold transition-colors">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // --- Echo Messaging ---
            Echo.private('chat-message.{{ $conversation->id }}')
                .listen('.message.sent', (e) => {
                    const messageElement = document.createElement('div');
                    messageElement.className = `flex ${e.message.user_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'}`;
                    messageElement.innerHTML = `
                        <div class="max-w-[70%] ${e.message.user_id === {{ auth()->id() }} ? 'bg-blue-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-r-2xl rounded-tl-2xl'} p-3 shadow-sm">
                            <div class="text-xs opacity-75 mb-1 font-bold">${e.message.user.name}</div>
                            <div class="text-sm">${e.message.content}</div>
                        </div>
                    `;
                    messagesDiv.appendChild(messageElement);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                })
                .listen('.message.updated', (e) => {
                    // Logic to update message if we implemented editing UI
                    console.log('Message updated:', e);
                });

            // --- PeerJS Calling ---
            let localStream;
            let currentCall;
            const otherUserId = {{ $otherUser->id }};
            const videoContainer = document.getElementById('video-call-container');
            const callingOverlay = document.getElementById('calling-overlay');
            const localVideo = document.getElementById('local-video');
            const remoteVideo = document.getElementById('remote-video');

            async function startCallFlow(isInitiator = true, remotePeerId = null) {
                try {
                    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                    localVideo.srcObject = localStream;
                    videoContainer.classList.remove('hidden');

                    if (isInitiator) {
                        callingOverlay.classList.remove('hidden');
                        
                        // Wait for peer to be ready
                        if (!window.peer.id) {
                            await new Promise(resolve => window.peer.on('open', resolve));
                        }
                        
                        // Send signal to other user
                        axios.post('/call/signal', {
                            receiver_id: otherUserId,
                            peer_id: window.peer.id,
                            type: 'incoming-call'
                        });

                        console.log('Call signaling sent, waiting for answer...');
                    } else if (remotePeerId) {
                        const call = window.peer.call(remotePeerId, localStream);
                        handleCall(call);
                    }
                } catch (err) {
                    console.error('Failed to get local stream', err);
                    alert('Could not access camera/microphone');
                }
            }

            function handleCall(call) {
                currentCall = call;
                call.on('stream', (remoteStream) => {
                    callingOverlay.classList.add('hidden');
                    remoteVideo.srcObject = remoteStream;
                });
                call.on('close', endCall);
                call.on('error', endCall);
            }

            function endCall() {
                if (currentCall) currentCall.close();
                if (localStream) {
                    localStream.getTracks().forEach(track => track.stop());
                }
                videoContainer.classList.add('hidden');
                callingOverlay.classList.add('hidden');
                remoteVideo.srcObject = null;
                localVideo.srcObject = null;
            }

            document.getElementById('start-video-call').addEventListener('click', () => startCallFlow(true));
            document.getElementById('end-call').addEventListener('click', () => {
                axios.post('/call/signal', {
                    receiver_id: otherUserId,
                    peer_id: window.peer.id,
                    type: 'cancel-call'
                });
                endCall();
            });

            // Handle incoming calls while on this page
            window.peer.on('call', (call) => {
                // If we're already in transition (handled by app.js redirect)
                // or if we want to handle it automatically here
                navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then((stream) => {
                    localStream = stream;
                    localVideo.srcObject = localStream;
                    videoContainer.classList.remove('hidden');
                    call.answer(localStream);
                    handleCall(call);
                });
            });

            // Handle signal parameters from redirect
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('startCall') === 'true' && urlParams.get('remotePeerId')) {
                startCallFlow(false, urlParams.get('remotePeerId'));
            }
        });
    </script>
</x-app-layout>
window.Echo.private(`New-notification.${userId}`).listen(
  ".new.notification",
  (e) => {
    console.log('Notification received:', e);

    if (e.type === 'incoming-call') {
      handleIncomingCall(e);
    } else {
      alertMessage(e);
      updateNotificationDots(e);
    }
  },
);

function updateNotificationDots(data) {
  const NotificationMssg = document.getElementById('msg-dot');
  const NotificationAll = document.getElementById('nt-dot');

  if (data.type === 'mssg') {
    if (NotificationMssg) NotificationMssg.classList.remove('hidden');
  } else {
    if (NotificationAll) NotificationAll.classList.remove('hidden');
  }
}

function alertMessage(data) {
  // Remove existing toast if any
  const existingToast = document.getElementById("toast-container");
  if (existingToast) existingToast.remove();

  const icons = {
    'invitation': "📫",
    'like': "❤️",
    'comment': "🧾",
    'post': '🚩',
    'accept': '✔️',
    'mssg': '💬'
  };

  const icon = icons[data.type] || '🔔';
  const link = data.type === "mssg" ? `/conversations/${data.room}` : '/notifications';

  const toastHTML = `
        <a href='${link}' id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3">
            <div id="chat-toast" class="transform transition-all duration-500 ease-out translate-x-[120%] opacity-0">
                <div class="w-72 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] p-3.5 flex items-center gap-3 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-400 flex items-center justify-center text-white shadow-sm">
                        ${icon}
                    </div>
                    <div class="flex-1 min-w-0 pr-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-900 dark:text-white truncate">${data.sender_name}</span>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Just now</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">${data.message}</p>
                    </div>
                    <button onclick="event.preventDefault(); document.getElementById('toast-container').remove();" class="text-gray-300 hover:text-gray-500 dark:hover:text-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </a>
    `;

  document.body.insertAdjacentHTML('beforeend', toastHTML);

  const toast = document.getElementById("chat-toast");
  setTimeout(() => {
    toast.classList.remove("translate-x-[120%]", "opacity-0");
    toast.classList.add("translate-x-0", "opacity-100");
  }, 100);

  // Auto-hide after 5s
  setTimeout(() => {
    if (toast) {
      toast.classList.replace("translate-x-0", "translate-x-[120%]");
      toast.classList.replace("opacity-100", "opacity-0");
      setTimeout(() => {
        const container = document.getElementById("toast-container");
        if (container) container.remove();
      }, 500);
    }
  }, 5000);
}

function handleIncomingCall(data) {
  const callModalHTML = `
        <div id="incoming-call-modal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-2xl max-w-sm w-full text-center">
                <div class="size-20 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <span class="material-symbols-outlined text-4xl text-blue-600">call</span>
                </div>
                <h3 class="text-xl font-bold mb-2">${data.sender_name}</h3>
                <p class="text-gray-500 mb-6">Incoming video call...</p>
                <div class="flex justify-center gap-4">
                    <button onclick="window.answerCall('${data.peer_id}', '${data.sender_id}')" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full font-bold transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">call</span> Answer
                    </button>
                    <button onclick="window.declineCall('${data.sender_id}')" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-full font-bold transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">call_end</span> Decline
                    </button>
                </div>
            </div>
        </div>
    `;
  document.body.insertAdjacentHTML('beforeend', callModalHTML);
}

window.answerCall = (peerId, senderId) => {
  // Logic to redirect or open call UI
  document.getElementById('incoming-call-modal').remove();
  // Usually redirect to the conversation page if not already there
  window.location.href = `/conversations/${senderId}?startCall=true&remotePeerId=${peerId}`;
};

window.declineCall = (senderId) => {
  document.getElementById('incoming-call-modal').remove();
  axios.post('/call/signal', {
    receiver_id: senderId,
    peer_id: window.peer.id,
    type: 'decline-call'
  });
};

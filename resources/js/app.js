import "./bootstrap";

import Echo from "laravel-echo";

// window.Echo.private('chat-message.'+ window.Laravel.userId)
//     .listen('.message.sent', (e) => {
//         console.log(e.message);
//         const chatMessages = document.getElementById('chat-messages');
//         const messageElement = document.createElement('div');
//         messageElement.textContent = e.message;
//         chatMessages.appendChild(messageElement);
//     });


const NotificationMssg = document.getElementById('msg-dot');
const NotificationAll = document.getElementById('nt-dot');

console.log(NotificationMssg);


window.Echo.private(`New-notification.${userId}`).listen(
    ".new.notification",
    (e) => {

      

      console.log('is done');
      

        alertMessage(e);
     

        const toast = document.getElementById("chat-toast");

        // Slide In
        setTimeout(() => {
            toast.classList.remove("translate-x-[120%]", "opacity-0");
            toast.classList.add("translate-x-0", "opacity-100");
        }, 100);

        // Close function
        const closeToast = () => {
            toast.classList.replace("translate-x-0", "translate-x-[120%]");
            toast.classList.replace("opacity-100", "opacity-0");
            setTimeout(() => toast.remove(), 500);
        };

        // 4s Auto-hide
        setTimeout(closeToast, 4000);
    },
);

function alertMessage(data) {
    console.log(data);
    if(data.type ==  'mssg'){
      NotificationMssg.classList.remove('hidden');
    }else{
      NotificationAll.classList.remove('hidden');
      return 'none';
    }
    document.querySelector("body").innerHTML += `
        // alert notifications ==================================================================================================
<a href='${data.type === "mssg" ? `/conversations/${data.room}`  : 'notifications' }' id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3">
  
  <div id="chat-toast" class="transform transition-all duration-500 ease-out translate-x-[120%] opacity-0">
    <div class="w-72 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] p-3.5 flex items-center gap-3 relative overflow-hidden group">
      
      <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>

      <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-400 flex items-center justify-center text-white shadow-sm">
     ${data.type === "invitation"
    ? "📫"
    : data.type === "like"
        ? "❤️"
        : data.type === "comment" ? "🧾" : data.type === "post" ? '🚩' : data.type === "accept" ? '✔️' : '💬' } 

      </div>

      <div class="flex-1 min-w-0 pr-4">
        <div class="flex justify-between items-center">
          <span class="text-sm font-bold text-gray-900 dark:text-white truncate">${data.sender_name}</span>
          <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Just now</span>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">${data.message}</p>
      </div>

      <button onclick="closeToast()" class="text-gray-300 hover:text-gray-500 dark:hover:text-gray-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

    </div>
  </div>
</a>
`;

      }
      
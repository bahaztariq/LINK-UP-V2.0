<x-app-layout>

<!--  -->

<!--  -->
    <div class="flex min-h-screen">
        <!-- Middle Column: Notifications -->
        <div class="flex-1 w-full border-r border-gray-100">
             <!-- Header -->
            <div class="sticky top-0 bg-white/80 backdrop-blur-md z-30 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-bold text-xl">Notifications</h2>
                <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <span class="material-symbols-outlined text-[20px]">settings</span>
                </button>
            </div>

            <!-- Filters -->
            <div class="px-4 py-2 border-b border-gray-100 flex gap-3 overflow-x-auto no-scrollbar">
                <button class="whitespace-nowrap px-4 py-1.5 bg-black text-white rounded-full font-bold text-[15px]">All</button>
                <button class="whitespace-nowrap px-4 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-full font-bold text-[15px] transition-colors">Verified</button>
                <button class="whitespace-nowrap px-4 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-full font-bold text-[15px] transition-colors">Mentions</button>
            </div>

            <!-- Notifications List -->
    <!-- // template of part of all notifications -->
<div class="w-full h-[84vh] dark:bg-gray-900   border-gray-100 dark:border-gray-800 overflow-hidden">
  
  <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Messages</h3>
    <button onclick="markAsRead()" class="text-blue-500  text-sm font-semibold hover:text-blue-600">Mark all as read</button>
  </div>
  <div class="max-h-[400px] overflow-y-auto">
@foreach(auth()->user()->notifications as $notification)

@php
$type = $notification->data['type'] ?? 'message';
@endphp

<div class="flex items-center px-4 py-3 cursor-pointer transition-colors duration-200 
    hover:bg-gray-50 dark:hover:bg-gray-800 relative group">

    <div class="relative flex-shrink-0">
        
    
        @if($type === 'Posts')
            <div class="h-11 w-11 flex items-center justify-center rounded-full bg-purple-100 text-purple-600">
                📢
            </div>

        @elseif($type === 'invitation')
            <div class="h-11 w-11 flex items-center justify-center rounded-full bg-green-100 text-green-600">
                🤝
            </div>

        @else
            <div class="h-11 w-11 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                💬
            </div>
        @endif

    </div>

    <div class="ml-3 flex-1 overflow-hidden">
        <div class="flex justify-between items-center">
            <span class="font-bold text-sm text-gray-900 dark:text-white truncate">
                {{ $notification->data['sender'] }}
            </span>

            <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap ml-2">
                {{ $notification->data['time'] }}
            </span>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 pr-4">
            {{ $notification->data['message'] }}
        </p>
    </div>

    @if(is_null($notification->read_at))
        <div class="absolute right-4 top-1/2 -translate-y-1/2">
            <div class="h-2.5 w-2.5 bg-blue-500 rounded-full"></div>
        </div>
    @endif

</div>

@endforeach


  </div>
<!-- 
  <a href="#" class="block py-3  text-center text-sm font-medium text-blue-500 hover:bg-gray-50 dark:hover:bg-gray-800 border-t border-gray-100 dark:border-gray-800">
    View all in Messenger
  </a> -->
</div>
        </div>
    </div>
<script>
    // async function markAsRead(){

    // try{

        
    //     const response = fetch('{{ route('mark_as_read') }}' , {
    //         method : 'POST',
    //         headers : {
                
    //             'X-CSRF-TOKEN' : document.querySelector('meta[name = "csrf-token"]').content,
    //             'Content-Type' : 'application/json'
    //         }
    //     });
        
    //     const data =  await response.json();
    //     console.log(data);
    //     //     document.querySelectorAll('.blue-dot').forEach(dot => {
    //     //     dot.remove();
    //     // });
        
    // }catch(error){
    //     console.error("Error" , error);
    // }
        
    // }

    async function markAsRead() {

    try {

        const response = await fetch("{{ route('mark_as_read') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        console.log(data);

    } catch (error) {
        console.error(error);
    }
}

</script>
</x-app-layout>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        return view('notifications');
        }

        
        
        public function markAsRead(){
            auth()->user()->unreadNotifications()->update(['read_at' => now()]);
            return response()->json(['success' => true]);
    }
}

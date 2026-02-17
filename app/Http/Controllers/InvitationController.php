<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class InvitationController extends Controller
{
    public function generate()
    {
        // Try to find an existing valid token (unused and not expired)
        $invitation = Invitation::where('user_id', Auth::id())
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$invitation) {
            $invitation = Invitation::create([
                'user_id' => Auth::id(),
                'token' => Str::random(32),
                'expires_at' => now()->addHour(),
            ]);
        }

        return response()->json([
            'token' => $invitation->token,
            'url' => route('invitations.accept', $invitation->token)
        ]);
    }

    public function showQR($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        
        if ($invitation->isExpired()) {
            abort(404, 'Invitation expired');
        }

        $url = route('invitations.accept', $token);
        
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($url);

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('dashboard')->with('error', 'This invitation has expired.');
        }

        if ($invitation->isUsed()) {
            return redirect()->route('dashboard')->with('error', 'This invitation has already been used.');
        }

        if ($invitation->user_id === Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'You cannot accept your own invitation.');
        }

        // Check if already friends
        $existing = Friendship::where(function($q) use ($invitation) {
            $q->where('requester_id', Auth::id())->where('addressee_id', $invitation->user_id);
        })->orWhere(function($q) use ($invitation) {
            $q->where('requester_id', $invitation->user_id)->where('addressee_id', Auth::id());
        })->first();

        if (!$existing) {
            Friendship::create([
                'requester_id' => $invitation->user_id,
                'addressee_id' => Auth::id(),
                'status' => 'accepted'
            ]);
        } else {
            $existing->update(['status' => 'accepted']);
        }

        $invitation->markAsUsed();

        return redirect()->route('user.show', $invitation->user_id)->with('success', 'Friendship accepted!');
    }
}

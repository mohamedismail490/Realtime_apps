<?php

namespace App\Http\Controllers;

use App\Events\GreetingSent;
use App\Events\MessageSent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChat() {
        return view('chat.show');
    }

    public function messageReceived(Request $request) {
        $request->validate([
            'message' => 'required'
        ]);
        $now = Carbon::now()->timestamp;
        broadcast(new MessageSent($request->user(), $request->message, $now));

        return response()->json('Message Sent');
    }

    public function greetReceived(Request $request, User $user) {
        $now = Carbon::now()->timestamp;
        broadcast(new GreetingSent($user, "{$request->user()->name} Greeted You", $now));
        broadcast(new GreetingSent($request->user(), "You Greeted {$user->name}", $now));
        return response()->json("Greeting Sent!");
    }
}

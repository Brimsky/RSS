<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Models\User;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chat');
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $message = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $request->input('message')
            ]);

            broadcast(new MessageSent($message))->toOthers();
            
            return response()->json($message, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to send message'], 500);
        }
    }


    public function getAllUsers() {
        $users = User::where('id', '!=', auth()->id())->get(['id', 'name']);
        return response()->json($users);
    }
    
    public function getMessages() {
        try {
            $messages = ChatMessage::with('user')->get();
            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not retrieve messages'], 500);
        }
    }    

    public function getMessagesForUser($id) {
        $messages = ChatMessage::where('user_id', $id)->get();
        return response()->json($messages);
    }    
}
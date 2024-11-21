<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chat');
    }

    public function store(Request $request)
    {
        Log::info('Attempting to store message', [
            'user' => auth()->user(),
            'request' => $request->all()
        ]);

        if (!auth()->check()) {
            Log::error('Unauthorized message attempt');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $validated = $request->validate([
                'message' => 'required|string',
                'recipient_id' => 'required|exists:users,id'
            ]);

            $message = ChatMessage::create([
                'user_id' => auth()->id(),
                'recipient_id' => $validated['recipient_id'],
                'message' => $validated['message']
            ]);

            Log::info('Message created', ['message' => $message]);

            $message->load(['sender', 'recipient']);

            $message->isCurrentUser = true;
            
            broadcast(new MessageSent($message))->toOthers();
            
            Log::info('Message broadcast successful');
            
            return response()->json($message, 201);

        } catch (\Exception $e) {
            Log::error('Chat message error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Unable to send message',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function getAllUsers()
    {
        try {
            Log::info('Starting getAllUsers method');
            
            if (!Auth::check()) {
                Log::warning('User not authenticated');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $currentUser = Auth::user();
            $isAdmin = $currentUser->role === 'admin';
            
            // Базовый запрос
            $query = User::where('id', '!=', $currentUser->id)
                ->select([
                    'id',
                    'name',
                    'email',
                    'role',
                    'avatar'
                ]);

            if (!$isAdmin) {
                $users = $query->get()->map(function ($user) use ($currentUser) {
                    $lastMessage = ChatMessage::where(function($query) use ($user, $currentUser) {
                        $query->where(function($q) use ($user, $currentUser) {
                            $q->where('user_id', $currentUser->id)
                            ->where('recipient_id', $user->id);
                        })->orWhere(function($q) use ($user, $currentUser) {
                            $q->where('user_id', $user->id)
                            ->where('recipient_id', $currentUser->id);
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar_url' => $user->getAvatarUrlAttribute(),
                        'last_message' => $lastMessage ? [
                            'message' => $lastMessage->message,
                            'created_at' => $lastMessage->created_at,
                            'is_own' => $lastMessage->user_id === $currentUser->id
                        ] : null
                    ];
                });
            } else {
                $users = $query->orderBy('name')->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'avatar_url' => $user->getAvatarUrlAttribute()
                    ];
                });
            }

            Log::info('Users retrieved successfully', [
                'count' => $users->count(),
                'is_admin' => $isAdmin
            ]);

            return response()->json($users);

        } catch (\Exception $e) {
            Log::error('Error in getAllUsers', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'error' => 'Error fetching users',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getMessages() {
        try {
            $messages = ChatMessage::with('user')->get();
            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not retrieve messages'], 500);
        }
    }    

    public function getMessagesForUser($id)
    {
        Log::info('Fetching messages for user', ['user_id' => $id]);
        
        try {
            $currentUserId = auth()->id();
            
            $messages = ChatMessage::where(function($query) use ($currentUserId, $id) {
                $query->where(function($q) use ($currentUserId, $id) {
                    $q->where('user_id', $currentUserId)
                    ->where('recipient_id', $id);
                })->orWhere(function($q) use ($currentUserId, $id) {
                    $q->where('user_id', $id)
                    ->where('recipient_id', $currentUserId);
                });
            })
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

            $messages->each(function ($message) use ($currentUserId) {
                $message->isCurrentUser = $message->user_id === $currentUserId;
            });

            Log::info('Messages fetched successfully', [
                'count' => $messages->count(),
                'messages' => $messages->toArray()
            ]);

            return response()->json($messages);
        } catch (\Exception $e) {
            Log::error('Error fetching messages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Unable to fetch messages',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
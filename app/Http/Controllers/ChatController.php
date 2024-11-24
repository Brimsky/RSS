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
    public function show($recipientId)
    {
        $recipient = User::findOrFail($recipientId);
        
        $messages = ChatMessage::where(function($query) use ($recipientId) {
            $query->where(function($q) use ($recipientId) {
                $q->where('user_id', auth()->id())
                  ->where('recipient_id', $recipientId);
            })->orWhere(function($q) use ($recipientId) {
                $q->where('user_id', $recipientId)
                  ->where('recipient_id', auth()->id());
            });
        })
        ->with(['sender', 'recipient'])
        ->orderBy('created_at', 'asc')
        ->get();

        return Inertia::render('Chat', [
            'recipient' => [
                'id' => $recipient->id,
                'name' => $recipient->name,
                'avatar' => $recipient->avatar
            ],
            'initialMessages' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                    'isCurrentUser' => $message->user_id === auth()->id()
                ];
            })
        ]);
    }

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

        $validated = $request->validate([
            'message' => 'required|string',
            'recipient_id' => 'required|exists:users,id',
            'product_data' => 'nullable|array'
        ]);

        try {
            $message = ChatMessage::create([
                'user_id' => auth()->id(),
                'recipient_id' => $validated['recipient_id'],
                'message' => $validated['message'],
                'product_data' => $request->product_data ? json_encode($request->product_data) : null,
                'is_first_message' => true 
            ]);

            Log::info('Message created', ['message' => $message]);

            $message->load(['sender', 'recipient']);

            $message->isCurrentUser = true;
            
            broadcast(new MessageSent($message))->toOthers();
            
            Log::info('Message broadcast successful');
            
            return response()->json($message, 201);

        } catch (\Exception $e) {
            \Log::error('Chat message error: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to send message'], 500);
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
            
            if ($isAdmin) {
                $users = User::where('id', '!=', $currentUser->id)
                    ->select(['id', 'name', 'email', 'role', 'avatar'])
                    ->orderBy('name')
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role,
                            'avatar_url' => $user->getAvatarUrlAttribute()
                        ];
                    });
            } else {
                $userIds = ChatMessage::where('user_id', $currentUser->id)
                    ->orWhere('recipient_id', $currentUser->id)
                    ->select('user_id', 'recipient_id')
                    ->get()
                    ->flatMap(function ($message) use ($currentUser) {
                        return [
                            $message->user_id,
                            $message->recipient_id
                        ];
                    })
                    ->unique()
                    ->reject(function ($id) use ($currentUser) {
                        return $id === $currentUser->id;
                    });

                $users = User::whereIn('id', $userIds)
                    ->select(['id', 'name', 'avatar'])
                    ->get()
                    ->map(function ($user) use ($currentUser) {
                        $lastMessage = ChatMessage::where(function($query) use ($user, $currentUser) {
                            $query->where(function($q) use ($user, $currentUser) {
                                $q->where('user_id', $currentUser->id)
                                  ->where('recipient_id', $user->id);
                            })->orWhere(function($q) use ($user, $currentUser) {
                                $q->where('user_id', $user->id)
                                  ->where('recipient_id', $currentUser->id);
                            });
                        })
                        ->latest()
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
            }

            Log::info('Users retrieved successfully', [
                'count' => $users->count(),
                'is_admin' => $isAdmin
            ]);

            return response()->json($users);

        } catch (\Exception $e) {
            Log::error('Error in getAllUsers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error fetching users',
                'details' => $e->getMessage()
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

            return response()->json($messages);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching messages: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch messages'], 500);
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $user->getAvatarUrlAttribute(),
                'role' => $user->role
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user data: ' . $e->getMessage());
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function directChat($sellerId)
    {
        try {
            $seller = User::findOrFail($sellerId);
            
            ChatMessage::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'recipient_id' => $sellerId,
                ],
                [
                    'message' => 'Started a conversation',
                    'created_at' => now()
                ]
            );

            return redirect()->route('chat.index')->with('directChat', [
                'sellerId' => $sellerId,
                'sellerName' => $seller->name
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to start chat with seller');
        }
    }
}
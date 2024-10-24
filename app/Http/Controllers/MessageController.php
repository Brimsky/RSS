<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    // Метод для загрузки сообщений с поддержкой пагинации (старых сообщений)
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'before_message_id' => 'nullable|exists:messages,id', // Опциональный параметр для пагинации
        ]);

        $currentUserId = auth()->id();  // ID текущего пользователя
        $receiverId = $validatedData['receiver_id'];  // ID получателя (собеседник)
        $beforeMessageId = $validatedData['before_message_id'] ?? null;  // ID последнего сообщения

        // Базовый запрос для получения сообщений между пользователями
        $query = Message::where(function ($query) use ($currentUserId, $receiverId) {
            $query->where('sender_id', $currentUserId)
                  ->where('receiver_id', $receiverId);
        })
        ->orWhere(function ($query) use ($currentUserId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $currentUserId);
        });

        // Если передан before_message_id, загружаем сообщения старше этого ID
        if ($beforeMessageId) {
            $lastMessage = Message::findOrFail($beforeMessageId);
            $query->where('created_at', '<', $lastMessage->created_at);
        }

        // Получаем только 20 сообщений, отсортированных по дате (от старых к новым)
        $messages = $query->orderBy('created_at', 'desc')
                          ->limit(20)
                          ->get();

        // Возвращаем сообщения в формате JSON
        return response()->json($messages);
    }

    // Метод для сохранения нового сообщения
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),  // ID отправителя
            'receiver_id' => $validatedData['receiver_id'],  // ID получателя
            'message' => $validatedData['message'],  // Текст сообщения
        ]);

        return response()->json($message);
    }
}



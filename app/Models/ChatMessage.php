<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'message',
        'product_data'
    ];

    protected $with = ['sender', 'recipient'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    protected $casts = [
        'product_data' => 'array'
    ];
}


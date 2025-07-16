<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    public User $userAPI;
    public Event $eventAPI;
    public $id;
    public $user_id;
    public $event_id;
    public $status;
    protected $fillable = [
        'user_id', 'event_id', 'status'
    ];
    protected $table = 'application';
}

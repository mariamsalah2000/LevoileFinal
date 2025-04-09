<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketUser extends Model
{
    use HasFactory;
    protected $table = 'ticket_user';

    protected $fillable = ['user_id', 'ticket_id', 'status', 'content', 'order_number'];

    public function ticket()
{
    return $this->belongsTo(Ticket::class, 'ticket_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function comments()
{
    return $this->hasMany(Comment::class, 'ticket_user_id');
}



}

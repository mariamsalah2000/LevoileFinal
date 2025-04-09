<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'ticket_user_id','user_id'];

    public function ticketUser()
    {
        return $this->belongsTo(TicketUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

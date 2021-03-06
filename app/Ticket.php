<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = ['user_id', 'ticket_id', 'title', 'message', 'file', 'manager_email', 'status'];

    public function getDataForTable($userId, $filter)
    {
        $query = self::select('*');
        if (!empty($userId)) {
            $query = self::where('user_id', $userId);
        }
        if(empty($filter['sort_col']) || !in_array($filter['sort_col'], $this->fillable)) {
            return $query->get();
        }
        $sort = !empty($filter['sort_type']) ? 'desc' : 'asc';
        return $query->orderBy($filter['sort_col'], $sort)->get();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function getCreatedAtAttribute($date)
    {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y H:i');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y');
    }
}

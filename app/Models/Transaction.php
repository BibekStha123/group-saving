<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'created_by',
        'status',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'name', 'email']);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'name', 'email']);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

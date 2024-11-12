<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'destination', 'departure', 'return', 'status'];

    public const STATUS_PENDING = 'pending';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function setDepartureAttribute($value): void
    {
        $this->attributes['departure'] = Carbon::parse($value);
    }

    public function setReturnAttribute($value): void
    {
        $this->attributes['return'] = $value ? Carbon::parse($value) : null;
    }
}

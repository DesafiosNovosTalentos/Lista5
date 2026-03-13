<?php

namespace App\Models;

use App\Domain\NotificationLog\Enum\NotificationEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationLog extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'message',
        'status',
        'attempts',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected function casts(): array
    {
        return [
            'status' => NotificationEnum::class,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NotificationCreated;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

   protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
    ];


    public function notifiable()
    {
        return $this->morphTo();
    }
    // protected static function booted()
    // {

    //     static::created(function ($notification) {
    //         // Fire the NotificationCreated event
    //         event(new NotificationCreated($notification));
    //     });
    // }

     protected static function boot()
    {
        parent::boot();

        // Generate UUID for primary key
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }


}

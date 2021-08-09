<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
        'updated' => UserUpdated::class,
        'deleted' => UserDeleted::class,
    ];

    protected $appends = ['created_since'];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d G:i:s');
    }
    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d G:i:s');
    }
    public function getCreatedSinceAttribute(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}

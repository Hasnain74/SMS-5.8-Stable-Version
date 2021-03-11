<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use HasRole;

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'user_id',
        'school_id',
        'status',
        'photo_id',
        'email',
        'password',
        'api_token'
    ];

    public function photo() {
        return $this->belongsTo('App\Photo');
    }

    public function studentsClass() {
        return $this->belongsTo('App\StudentsClass');
    }

    public function isStudent() {
        if ($this->status == "student") {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin() {
        if ($this->status == "teacher") {
            return true;
        } elseif($this->status == "admin") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}

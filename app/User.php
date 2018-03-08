<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use SoftDeletes;
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $dates = ['deleted_at'];
    protected $table = 'users';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (Auth::check()) {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });
        static::updating(function ($post) {
            if (Auth::check()) {
                $post->updated_by = Auth::user()->id;
            }
        });
    }

    public function Company()
    {
        return $this->belongsTo('App\Company', 'id', 'user_id');
    }

    public function Designation()
    {
        return $this->belongsTo('App\Designation', 'designation_id', 'id');
    }

    public function CompanyUser()
    {
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }

    public function allUser()
    {
        if (Auth::check()) {
            return User::where('company_id', Auth::user()->company_id)
                ->where('user_label', '>', 1)->get();
        }
    }

    public static function UserIdList()
    {
        return User::where('company_id', Auth::user()->company_id)
            ->where('status', 1)
            ->where('user_label', '>', 1)->lists('id');
    }

    public function Messages()
    {
        return $this->hasMany('App\Messages', 'sender_id', 'id');
    }
}

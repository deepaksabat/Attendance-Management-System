<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Leave extends Model {
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'leaves';

    public $timestamps = true;

    protected $fillable = array('*');

    /* Start Boot */
    public static function boot()
    {
        parent::boot();

        static::creating(function($post)
        {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post)
        {
            $post->updated_by = Auth::user()->id;
        });
    }/* END Boot */

    public function LeaveCategories()
    {
        return $this->belongsTo('App\LeaveCategories','leave_category_id','id');
    }

    public function User()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

}

<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Company extends Model {
    protected $table = 'company_info';

    public $timestamps = true;
    protected $fillable = array('*');

    /* Start Boot */
    public static function boot()
    {
        parent::boot();

        static::creating(function($post)
        {
            if (Auth::check()) {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });

        static::updating(function($post)
        {
            $post->updated_by = Auth::user()->id;
        });
    }/* END Boot */
}

<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Designation extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'designation';

    public $timestamps = true;

    protected $fillable = array('*');

    public function newQuery($company_id = true)
    {
        $query = parent::newQuery($company_id);
        $query->where('company_id', '=', Auth::user()->company_id);
        return $query;
    }

    /* Start Boot */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($obj){
            $obj->company_id = Auth::user()->company_id;
        });

        static::creating(function($post)
        {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
            $post->company_id = Auth::user()->company_id;
        });

        static::updating(function($post)
        {
            $post->updated_by = Auth::user()->id;
        });
    }
    /* END Boot */
	//

}

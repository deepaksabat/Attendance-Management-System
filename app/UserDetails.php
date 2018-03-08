<?php namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserDetails extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'user_details';

    public $timestamps = true;

    //
    public static function maxRow($UserId = false)
    {
        $query = new UserDetails();
        if ($UserId)
            $query = $query->where('user_id', $UserId);
        else
            $query = $query->where('user_id', Auth::user()->id);
        return $query->orderBy('id', 'DESC')
            ->first();
    }

    public static function maxRowToday()
    {

        return UserDetails::where('user_id', Auth::user()->id)
            ->where('login_date', date('Y-m-d'))
            ->where('logout_date', '0000-00-00')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public static function currentPunchStatus($id)
    {
        $lastDetails = UserDetails::maxRow($id);
        if ($lastDetails && $lastDetails != null) {
            if ($lastDetails->logout_time == '0000-00-00 00:00:00')
                return 1;
            else
                return 0;
        } else
            return 'not found';
    }

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}

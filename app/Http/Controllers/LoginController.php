<?php namespace App\Http\Controllers;

use App\Company;
use App\User;
use App\UserDetails;
use Auth;
use Request;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int $id
     * @return Response
     */
    public function index()
    {
        return view('loginPage');
    }

    public function postCheckUser()
    {
        $credentials = array(
            'username' => Request::input('username'),
            'password' => Request::input('password'),
            'status' => 1
        );
//         Config::set('auth.model', 'CompanyUser');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->user_label == 2) {
                if (Auth::user()->ip_address) {
                    if (Auth::user()->ip_address != $_SERVER['REMOTE_ADDR']) {
                        Auth::logout();
                        Session::flash('flashError', 'Your Ip is not Valid');
                        return redirect('/');
                    }
                }
                return redirect()->intended('user');
            }
            if (Auth::user()->user_label == 1)
                return redirect()->intended('company');
        } else {
            Session::flash('flashError', 'Your ID or Password Invalid');
            return redirect('/');
        }
    }

    public function anyCreateCompany()
    {
        if (Request::all()) {
            $rules = array(
                'company_name' => 'required',
                'username' => "required|unique:users,username|alpha_dash",
                'password' => 'required|min:6|max:10',
                'company_email' => 'required|email',
                'phone' => 'required',
                'time_zone' => 'required'
            );
            /* Laravel Validator Rules Apply */
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()):
                $validationError = $validator->messages()->first();
                Session::flash('flashError', $validationError);
                return redirect('login/create-company');
            else:
                $userCreate = new \App\User();
                $userCreate->username = trim(Request::input('username'));
                $userCreate->user_label = 1;
                $userCreate->password = Hash::make(trim(Request::input('password')));
                $userCreate->save();

                $companyCreate = new Company();
                $companyCreate->company_name = trim(Request::input('company_name'));
                $companyCreate->company_email = trim(Request::input('company_email'));
                $companyCreate->phone = trim(Request::input('phone'));
                $companyCreate->time_zone = trim(Request::input('time_zone'));
                $companyCreate->user_id = $userCreate->id;
                $companyCreate->save();

                $userUpdate = User::find($userCreate->id);
                $userUpdate->company_id = $companyCreate->id;
                $userUpdate->save();
            endif;
            Session::flash('flashSuccess', 'Welcome in Here.You are now a Company.Create User and Manage Them!!');
            $credentials = array(
                'username' => Request::input('username'),
                'password' => Request::input('password'),
            );
            if (Auth::attempt($credentials)) {
                if (Auth::user()->user_label == 2)
                    return redirect()->intended('user');
                if (Auth::user()->user_label == 1)
                    return redirect()->intended('company');
            }
        } else {
            $data['menu'] = 'Create';
            return view('createCompany', $data)->render();
        }
    }

    public static function autoPunchOutCheck($userIDs = array())
    {
        foreach ($userIDs as $userId) {
            $userLastDetails = UserDetails::maxRow($userId);
            $userInfo = \App\User::find($userId);
            if ($userLastDetails && $userLastDetails->count() > 0 && $userLastDetails->logout_date == '0000-00-00' && $userInfo->auto_punch_out_time != '00:00:00' && strtotime(date($userInfo->auto_punch_out_time)) <= strtotime(date("Y-m-d H:i:s"))) {
                $userLastDetails->logout_date = $userLastDetails->login_date;
                $userLastDetails->logout_time = $userLastDetails->login_date . ' ' . $userInfo->auto_punch_out_time;
                $userLastDetails->save();
                Session::forget('timeTrack');
            } elseif ($userLastDetails && $userLastDetails->count() > 0 && $userLastDetails->logout_date == '0000-00-00' && $userInfo->auto_punch_out_time != '00:00:00' && strtotime(date('Y-m-d')) > strtotime(date("$userLastDetails->login_date"))) {
                $userLastDetails->logout_date = $userLastDetails->login_date;
                $userLastDetails->logout_time = $userLastDetails->login_date . ' ' . $userInfo->auto_punch_out_time;
                $userLastDetails->save();
                Session::forget('timeTrack');
            }
        }
    }
}
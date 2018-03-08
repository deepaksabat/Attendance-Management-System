<?php namespace App\Http\Controllers;

use App\Designation;
use App\HolidayInfo;
use App\Leave;
use App\LeaveCategories;
use App\Messages;
use App\NoticeBoard;
use App\User;
use App\UserDetails;
use App\UserRegistered;
use Auth;
use Request;
use Response;
use Session;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{

    public function __construct()
    {
        date_default_timezone_set(Auth::user()->Company->time_zone);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function  getIndex()
    {
        LoginController::autoPunchOutCheck(\App\User::UserIdList());
        $data['startDate'] = date('Y-m-d');
        $data['endDate'] = date('Y-m-d');
        $data['attendanceReport'] = UserDetails::
        select(DB::raw('timediff(logout_time,login_time) as timediff'),
            'login_date', 'logout_date', 'id', 'login_time', 'logout_time', 'user_id')
            ->whereHas('User', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            })
            ->where('login_date', '>=', $data['startDate'])
            ->where('logout_date', '<=', $data['endDate'])
            ->where('logout_time', '!=', '0000-00-00 00:00:00')
            ->orderBy('id', 'ASC')
            ->get();
        $data['activeUser'] = UserDetails::where('login_date', date('Y-m-d', time()))
            ->whereHas('User', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            })
            ->groupBy('user_id')
            ->orderBy('id')
            ->get();
        $data['lateUser'] = UserDetails::where('login_date', date('Y-m-d', time()))
            ->whereHas('User', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            })
            ->where('status', 'Late')
            ->groupBy('user_id')
            ->orderBy('id')
            ->get();

        $data['totalUser'] = \App\User::where('company_id', Auth::user()->company_id)
            ->where('user_label', '>', 1)->count();
        $data['withLeaveNotification'] = Leave::whereHas('User', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })->where('admin_noti_status', 1)->count();
        $data['allNotice'] = NoticeBoard::orderBy('id', 'DESC')->paginate(10);
        return view('Company.home', $data);
    }

    /**
     * @return Redirect|\Illuminate\View\View
     */
    public function anyCreateUser()
    {
        if (Input::all()) {
            //            return 'fsd';
            $ignoreID = Auth::user()->id;
            $rules = array(
                'username' => "unique:users,username|alpha_dash",
                'password' => 'required|min:6|max:10',
                'ip_address' => 'sometimes|ip'
            );
            /* Laravel Validator Rules Apply */
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()):
                $validationError = $validator->messages()->first();
                Session::flash('flashError', $validationError);
                return redirect('company/create-user');
            else:
                $userCreate = new \App\User();
                $userCreate->username = trim(Input::get('username'));
                $userCreate->password = Hash::make(trim(Input::get('password')));
                $userCreate->ip_address = trim(Input::get('ip_address'));
                $userCreate->company_id = Auth::user()->company_id;
                $userCreate->save();
            endif;
            Session::flash('flashSuccess', 'User Created Successfully');
            return redirect('company/all-user');
        }
        return view('Company.createUser');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getAllUser()
    {
        $user = new \App\User();
        $data['allUser'] = \App\User::where('company_id', Auth::user()->company_id)
            ->where('user_label', '>', 1)->paginate(10);
        $data['userTable'] = view('Company.userTable', $data);
        return view('Company.allUser', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getSearchUser()
    {
        $search = Input::get('search');
        $user = new \App\User();
        $data['allUser'] = $user->whereRaw("username regexp '[[:<:]]$search'")
            ->where('company_id', Auth::user()->company_id)
            ->where('user_label', '>', 1)->paginate(10);
        return view('Company.userTable', $data);
    }

    /**
     * @param null $id
     * @return \Illuminate\View\View|string
     */
    public function anyStatusChange($id = null)
    {
        $status = Input::get('status');
        $user = \App\User::find($id);
        if ($status == 'active')
            $user->status = 1;
        if ($status == 'inactive')
            $user->status = 0;
        $user->save();
        Session::flash('flashSuccess', 'Status Changed');
        return 'true';
        $user = new \App\User();
        $data['allUser'] = $user->allUser();
        return view('Company.allUserAjax', $data);
    }

    /**
     * @return mixed
     */
    public function anyAddIp()
    {
        $response = array();
        $rules = array(
            'ip_address' => 'required|ip'
        );
        /* Laravel Validator Rules Apply */
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            $errorMessage = $validator->messages()->first();
            $response['type'] = 'error';
            $response['info'] = $errorMessage;
            return Response::json($response);
        else:
            $userCreate = \App\User::find(Input::get('id'));
            $userCreate->ip_address = trim(Input::get('ip_address'));
            $userCreate->save();
        endif;
        Session::flash('flashSuccess', 'IP Added Successfully');
        $user = new \App\User();
        $data['allUser'] = $user->allUser();
        $response['type'] = 'success';
        $response['info'] = (String)view('Company.allUserAjax', $data);
        return Response::json($response);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View|string
     */
    public function postRemoveIp($id)
    {
        $user = \App\User::find($id);
        $user->ip_address = '';
        $user->save();
        Session::flash('flashSuccess', 'IP Removed');
        return 'true';
        $user = new \App\User();
        $data['allUser'] = $user->allUser();
        return view('Company.allUserAjax', $data);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function anyUserUpdate($id)
    {
        $data['user'] = \App\User::find($id);
        $data['designations'] = Designation::all();
        return view('Company.userUpdate', $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function postUpdateUserUsername($id)
    {
        $rules = array(
            'username' => "required|alpha_dash|unique:users,username,$id",
        );
        /* Laravel Validator Rules Apply */
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            return $validator->messages()->first();
        else:
            $userUpdate = \App\User::find($id);
            $userUpdate->username = trim(Input::get('username'));
            $userUpdate->save();
        endif;
        return 'true';
    }

    /**
     * @param $id
     * @return string
     */
    public function  postUpdateUserPassword($id)
    {
        $rules = array(
            'password' => "required|min:6|max:10",
        );
        /* Laravel Validator Rules Apply */
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            return $validator->messages()->first();
        else:
            $userUpdate = \App\User::find($id);
            $userUpdate->password = Hash::make(Input::get('password'));
            $userUpdate->save();
        endif;
        return 'true';
    }

    /**
     * @param $id
     * @return string
     */
    public function  postUpdateUserTime($id)
    {
        $rules = array(
            'time' => "required",
        );
        /* Laravel Validator Rules Apply */
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            return $validator->messages()->first();
        else:
            $userUpdate = \App\User::find($id);
            $userUpdate->time = Input::get('time');
            $userUpdate->save();
        endif;
        return 'true';
    }

    /**
     * @param $id
     * @return string
     */
    public function  postUpdateAutoPunchOutTime($id)
    {
        $rules = array(
            'time' => "required|date_format:H:i:s",
        );
        /* Laravel Validator Rules Apply */
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            return $validator->messages()->first();
        else:
            $userUpdate = \App\User::find($id);
            $userUpdate->auto_punch_out_time = Input::get('time');
            $userUpdate->save();
        endif;
        return 'true';
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function anyUpdateMe()
    {
        if (Input::all()) {
            $companyID = Input::get('companyID');
            $id = Auth::user()->id;
            $rules = array(
                'company_name' => "required|unique:company_info,company_name,$companyID",
                'company_email' => "required|email|unique:company_info,company_email,$companyID",
                'phone' => "required",
                'username' => "required|alpha_dash|unique:users,username,$id",
                'user_first_name' => "required",
                'user_last_name' => 'required'
            );
            /* Laravel Validator Rules Apply */
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()):
                return $validator->messages()->first();
            else:
                $userUpdate = \App\User::find($id);
                $userUpdate->username = trim(Input::get('username'));
                $userUpdate->user_first_name = trim(Input::get('user_first_name'));
                $userUpdate->user_last_name = trim(Input::get('user_last_name'));
                $userUpdate->Company->company_name = trim(Input::get('company_name'));
                $userUpdate->Company->company_email = trim(Input::get('company_email'));
                $userUpdate->Company->phone = trim(Input::get('phone'));
                $userUpdate->push();
            endif;
            return 'true';
        } else {
            $data['myInfo'] = \App\User::find(Auth::user()->id);
            return view('Company.companyUpdate', $data);
        }
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function anyChangePassword()
    {
        if (Input::all()) {
            $rules = array(
                'new_password' => 'required|same:confirm_new_password|min:6',
                'current_pass' => 'required|password_check',
            );
            $messages = array(
                'new_password.same' => 'New Password and Confirm password are not Matched',
            );
            /* Laravel Validator Rules Apply */
            $validator = Validator::make(Input::all(), $rules, $messages);
            if ($validator->fails()):
                return $validator->messages()->first();
            else:
                $userUpdate = \App\User::find(Auth::user()->id);
                $userUpdate->password = Hash::make(Input::get('new_password'));
                $userUpdate->save();
            endif;
            return 'true';
        } else {
            return view('Company.passwordChange');
        }
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function anyCreateHoliday()
    {
        if (Input::all()) {
            $holidayList = Input::get('holiday');

            foreach ($holidayList as $holiday) {
                if ($holiday == '')
                    return 'Please Fill All the Field';
                $checkExisting = HolidayInfo::where('holiday', $holiday)
                    ->first();
                if ($checkExisting)
                    return "$holiday has Already Added as a Holiday";
            }

            foreach ($holidayList as $holiday) {
                $saveHoliday = new HolidayInfo();
                $saveHoliday->holiday = $holiday;
                $saveHoliday->save();
            }
            Session::flash('flashSuccess', 'Holiday Created Successfully');
            return 'true';

        } else {
            return view('Company.createHoliday');
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function anyAllHoliday()
    {
        $data['allHoliday'] = HolidayInfo::orderBy('holiday', 'desc')->get();
        return view('Company.allHoliday', $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function anyDeleteHoliday($id)
    {
        $holidayDelete = HolidayInfo::find($id);
        $holidayDelete->delete();
        return 'true';
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getAllLeave()
    {
        Leave::whereHas('User', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })
            ->update(array('admin_noti_status' => 0));
        $data['allLeave'] = Leave::whereHas('User', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })
            ->whereHas('LeaveCategories', function ($q) {
                $q->where('deleted_at');
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        $data['leaveTable'] = view('Company.leaveTable', $data);
        return view('Company.allLeave', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getSearchLeave()
    {
        $data['allLeave'] = Leave::whereHas('User', function ($q) {
            $search = Input::get('search');
            $q->where('company_id', Auth::user()->company_id);
            $q->whereRaw("username regexp '[[:<:]]$search'");
        })
            ->whereHas('LeaveCategories', function ($q) {
                $q->where('deleted_at');
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('Company.leaveTable', $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function getChangeLeaveStatus($id)
    {
        if (Input::get('status') == 'grant') {
            $categoryID = Input::get('categoryID');
            $first_day_this_year = date('Y-01-01');
            $last_day_this_year = date('Y-12-t');
            $leaveNumber = Leave::where('leave_category_id', $categoryID)
                ->where('leave_date', '>=', $first_day_this_year)
                ->where('leave_date', '<=', $last_day_this_year)
                ->where('user_id', Input::get('userID'))
                ->where('leave_status', 1)
                ->count();
            if ($leaveNumber == Input::get('categoryBudget') || $leaveNumber > Input::get('categoryBudget'))
                return 'false';
            $statusChange = Leave::find($id);
            $statusChange->leave_status = 1;
            $statusChange->user_noti_status = 1;
            $statusChange->save();
            Session::flash('success', 'Leave Status Changed');
            return 'true';
        } elseif (Input::get('status') == 'reject') {
            $statusChange = Leave::find($id);
            $statusChange->leave_status = 2;
            $statusChange->user_noti_status = 1;
            $statusChange->save();
            Session::flash('success', 'Leave Status Changed');
            return 'true';
        } elseif (Input::get('status') == 'delete') {
            $leaveDelete = Leave::find($id);
            $leaveDelete->delete();
            return 'true';
        }
        $data['allLeave'] = Leave::whereHas('User', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })->get();
        return (String)view('Company.allLeaveAjax', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function anyLeaveCategory()
    {
        if (Input::all()) {
            $checkExist = LeaveCategories::where('category', Input::get('category'))
                ->where('company_id', Auth::user()->company_id)
                ->first();
            if ($checkExist) {
                $response['type'] = 'error';
                $response['info'] = 'This Category Already Taken';
                return Response::json($response);
            }

            $rules = array(
                'category' => "required|alpha_dash",
                'category_num' => 'required|max:2',
            );
            /* Laravel Validator Rules Apply */
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()):
                $errorMessage = $validator->messages()->first();
                $response['type'] = 'error';
                $response['info'] = $errorMessage;
                return Response::json($response);
            else:
                $categoryCreate = new LeaveCategories();
                $categoryCreate->category = trim(Input::get('category'));
                $categoryCreate->category_num = Input::get('category_num');
                $categoryCreate->save();
                $response['type'] = 'success';
                $response['id'] = $categoryCreate->id;
                $data['allCategory'] = LeaveCategories::all();
//                    $response['info'] = (String) view('Company.leaveCategoryAjax',$data);
                $response['info'] = 'Leave Category Created Successfully';
                return Response::json($response);
            endif;

        } else {
            $data['allCategory'] = LeaveCategories::all();
            return view('Company.leaveCategory', $data);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function getDeleteLeaveCategory($id)
    {
        $leaveCategoriesDelete = LeaveCategories::find($id);
        Leave::where('leave_category_id', $id)->delete();
        $leaveCategoriesDelete->delete();
        return 'true';
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function getReport()
    {
        $data['startDate'] = Input::get('s_date');
        $data['endDate'] = Input::get('e_date');
        $data['id'] = Input::get('id');
        $data['userInfo'] = \App\User::where('company_id', Auth::user()->company_id)
            ->where('id', $data['id'])->first();
        if (!$data['userInfo'])
            return 'There User is Not Your Company';
        $data['attendanceReport'] = UserDetails::
        select(DB::raw('timediff(logout_time,login_time) as timediff'),
            'login_date', 'logout_date', 'id', 'login_time', 'logout_time', 'user_id', 'status')
            ->where('user_id', $data['id'])
            ->where('login_date', '>=', $data['startDate'])
            ->where('logout_date', '<=', $data['endDate'])
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        $data['allDate'] = $this->getDatesFromRange($data['startDate'], $data['endDate']);
        $data['allHoliday'] = HolidayInfo::where('holiday', '>=', $data['startDate'])
            ->where('holiday', '<=', $data['endDate'])
            ->get()
            ->toArray();
        $data['allLeave'] = Leave::where('leave_date', '>=', $data['startDate'])
            ->where('leave_date', '<=', $data['endDate'])
            ->where('user_id', $data['id'])
            ->where('leave_status', 1)
            ->get()
            ->toArray();
        return view('Company.report', $data);

    }

    /**
     * @return \Illuminate\View\View
     */
    public function getSummeryReport()
    {
        $data['startDate'] = Input::get('s_date');
        $data['endDate'] = Input::get('e_date');
        $data['attendanceReport'] = UserDetails::
        select(DB::raw('timediff(logout_time,login_time) as timediff'),
            'login_date', 'logout_date', 'id', 'login_time', 'logout_time', 'user_id')
            ->whereHas('User', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            })
            ->where('login_date', '>=', $data['startDate'])
            ->where('logout_date', '<=', $data['endDate'])
            ->where('logout_time', '!=', '0000-00-00 00:00:00')
            ->orderBy('id', 'ASC')
            ->get();
        return view('Company.summeryReport', $data);
    }

    /**
     * @return Redirect|\Illuminate\View\View
     */
    public function anyReportSummery()
    {
        if (Input::all()) {
            $data['startDate'] = Input::get('from');
            $data['endDate'] = Input::get('to');
            $data['attendanceReport'] = UserDetails::
            select(DB::raw('timediff(logout_time,login_time) as timediff'),
                'login_date', 'logout_date', 'id', 'login_time', 'logout_time', 'user_id')
                ->whereHas('User', function ($q) {
                    $q->where('company_id', Auth::user()->company_id);
                })
                ->where('login_date', '>=', $data['startDate'])
                ->where('logout_date', '<=', $data['endDate'])
                ->where('logout_time', '!=', '0000-00-00 00:00:00')
                ->orderBy('id', 'ASC')
                ->get();
            if ($data['attendanceReport']->isEmpty()) {
                Session::flash('flashError', 'There is no report.Because None of Employee Has Not Work From ' . $data['startDate'] . ' to ' . $data['endDate']);
                return redirect('company/report-summery');
            }

            return view('Company.summeryReport', $data);
        } else {
            return view('Company.summeryReportRequest');
        }
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    public function getDatesFromRange($start, $end)
    {
        $dates = array($start);
        while (end($dates) < $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
        }
        return $dates;
    }

    /**
     * @return Redirect|\Illuminate\View\View
     */
    public function anyFullCalender()
    {
        if (Input::all()) {
            $data['startDate'] = Input::get('from');
            $data['endDate'] = Input::get('to');
            $data['id'] = Input::get('id');
            $data['userInfo'] = \App\User::where('company_id', Auth::user()->company_id)
                ->where('id', $data['id'])->first();
            if (!$data['userInfo']) {
                Session::flash('flashError', 'This User Is Not Your Company');
                return redirect('company/full-calender');
            }

            $data['attendanceReport'] = UserDetails::
            select(DB::raw('timediff(logout_time,login_time) as timediff'),
                'login_date', 'logout_date', 'id', 'login_time', 'logout_time', 'user_id', 'status')
                ->where('user_id', $data['id'])
                ->where('login_date', '>=', $data['startDate'])
                ->where('logout_date', '<=', $data['endDate'])
                ->orderBy('id', 'ASC')
                ->get()
                ->toArray();

            if (!$data['attendanceReport']) {
                Session::flash('flashError', $data['userInfo']->username . ' Has not Any Work From ' . $data['startDate'] . ' to ' . $data['endDate']);
                return redirect('company/full-calender');
            }


            return view('Company.fullCalender', $data);
        } else {
            $user = new \App\User();
            $data['allUser'] = $user->allUser();
            return view('Company.fullCalenderRequest', $data);
        }
    }

    /**
     * @return Redirect|\Illuminate\View\View
     */
    public function anyTableReport()
    {
        if (Input::all()) {
            $data['startDate'] = Input::get('from');
            $data['endDate'] = Input::get('to');
            $data['id'] = Input::get('id');
            $data['userInfo'] = \App\User::where('company_id', Auth::user()->company_id)
                ->where('id', $data['id'])->first();
            if (!$data['userInfo']) {
                Session::flash('flashError', 'This User Is Not Your Company');
                return redirect('company/table-report');
            }
            $data['allDate'] = $this->getDatesFromRange($data['startDate'], $data['endDate']);
            $data['allHoliday'] = HolidayInfo::where('holiday', '>=', $data['startDate'])
                ->where('holiday', '<=', $data['endDate'])
                ->get()
                ->toArray();
            $data['allLeave'] = Leave::where('leave_date', '>=', $data['startDate'])
                ->where('leave_date', '<=', $data['endDate'])
                ->where('user_id', $data['id'])
                ->where('leave_status', 1)
                ->get()
                ->toArray();
            $data['attendanceReport'] = UserDetails::
            select(DB::raw('timediff(logout_time,login_time) as timediff'),
                'login_date', 'logout_date', 'id', 'login_time', 'logout_time', 'user_id', 'status')
                ->where('user_id', $data['id'])
                ->where('login_date', '>=', $data['startDate'])
                ->where('logout_date', '<=', $data['endDate'])
                ->orderBy('id', 'ASC')
                ->get()
                ->toArray();

            if (!$data['attendanceReport']) {
                Session::flash('flashError', $data['userInfo']->username . ' Has not Any Work From ' . $data['startDate'] . ' to ' . $data['endDate']);
                return redirect('company/table-report');
            }

            return view('Company.report', $data);
        } else {
            $user = new \App\User();
            $data['allUser'] = $user->allUser();
            return view('Company.tableReportRequest', $data);
        }
    }

    /**
     * @return Redirect
     */
    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * @param $id
     */
    public function getDeleteUser($id)
    {
        $user = \App\User::find($id);
        UserDetails::where('user_id', $id)->delete();
        Leave::where('user_id', $id)->delete();
        $user->delete();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getNoticeBoardCreate()
    {
        return view('Company.noticeBoardCreate');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getNoticeBoardEdit($id)
    {
        $data['notice'] = NoticeBoard::find($id);
        return view('Company.noticeBoardView', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getNoticeBoard()
    {
        $data['allNotice'] = NoticeBoard::orderBy('id', 'DESC')->paginate(10);
        return view('Company.noticeBoard', $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function putNoticeBoard($id)
    {
        $rules = array(
            'subject' => "required",
            'message' => "required",
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            $errorMessage = $validator->messages()->first();
            $response['type'] = 'error';
            $response['info'] = $errorMessage;
            return Response::json($response);
        else:
            $notice = NoticeBoard::find($id);
            $notice->subject = trim(Input::get('subject'));
            $notice->message = Input::get('message');
            $notice->save();
            $response['type'] = 'success';
            Session::flash('success', 'Notice Updated Successfully');
            return Response::json($response);
        endif;
    }

    /**
     * @return mixed
     */
    public function postNoticeBoard()
    {
        $rules = array(
            'subject' => "required",
            'message' => "required",
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            $errorMessage = $validator->messages()->first();
            $response['type'] = 'error';
            $response['info'] = $errorMessage;
            return Response::json($response);
        else:
            $notice = new NoticeBoard();
            $notice->subject = trim(Input::get('subject'));
            $notice->message = Input::get('message');
            $notice->save();
            $response['type'] = 'success';
            Session::flash('success', 'New Notice Created Successfully');
            return Response::json($response);
        endif;
    }

    /**
     * @param $id
     * @return string
     */
    public function deleteNotice($id)
    {
        $notice = NoticeBoard::find($id);
        $notice->delete();
        return 'true';
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getDesignation()
    {
        $data['designations'] = Designation::paginate(10);
        return view('Company.designation', $data);
    }

    /**
     * @return mixed
     */
    public function postDesignation()
    {
        $exists = Designation::where('name', Input::get('name'))->get();
        if ($exists && $exists->count() > 0) {
            $response['type'] = 'error';
            $response['info'] = 'Already Exists This Designation';
            return Response::json($response);
        }
        $rules = array(
            'name' => "required|alpha_dash_spaces"
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            $errorMessage = $validator->messages()->first();
            $response['type'] = 'error';
            $response['info'] = $errorMessage;
            return Response::json($response);
        else:
            $designation = new Designation();
            $designation->name = trim(Input::get('name'));
            $designation->save();
            $response['type'] = 'success';
            $response['info'] = 'Designation Created Successfully';
            $response['id'] = $designation->id;
            return Response::json($response);
        endif;
    }

    public function getDesignationEdit($id)
    {
        $data['designation'] = Designation::orderBy('id', 'DESC')->find($id);
        return view('Company.designationEdit', $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function putDesignation($id)
    {
        $exists = Designation::where('id', '!=', $id)
            ->where('name', Input::get('name'))->get();
        if ($exists && $exists->count() > 0) {
            $response['type'] = 'error';
            $response['info'] = 'Already Exists This Designation';
            return Response::json($response);
        }
        $rules = array(
            'name' => "required|alpha_dash_spaces"
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()):
            $errorMessage = $validator->messages()->first();
            $response['type'] = 'error';
            $response['info'] = $errorMessage;
            return Response::json($response);
        else:
            $designation = Designation::find($id);
            $designation->name = trim(Input::get('name'));
            $designation->save();
            $response['type'] = 'success';
            Session::flash('success', 'Designation Updated Successfully');
            $response['id'] = $designation->id;
            return Response::json($response);
        endif;
    }

    /**
     * @param $id
     * @return string
     */
    public function postUpdateDesignation($id)
    {
        $designation = \App\User::find($id);
        $designation->designation_id = Input::get('designation_id');
        $designation->save();
        return 'true';

    }

    /**
     * @param $id
     * @return string
     */
    public function deleteDesignation($id)
    {
        $notice = Designation::find($id);
        $notice->delete();
        \App\User::where('designation_id', $id)->update(array('designation_id' => 0));
        return 'true';
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getChat()
    {
        $sortedSenderUser = DB::table('users')
            ->join('messages', 'users.id', '=', 'messages.sender_id', 'left outer')
            ->select(DB::raw('sum(messages.read) as total_read'),
                DB::raw('count(messages.id) as total_messages'),
                'users.id', 'users.username', 'users.user_first_name', 'users.user_last_name', DB::raw('max(messages.created_at) as created_at'))
            ->where('users.company_id', Auth::user()->company_id)
            ->where('messages.receiver_id', Auth::user()->id)
            ->where('users.id', '!=', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->groupBy('users.id')
            ->get();

        $sortedReceiverUser = DB::table('users')
            ->join('messages', 'users.id', '=', 'messages.receiver_id', 'left outer')
            ->select('users.id', 'users.username', 'users.user_first_name', 'users.user_last_name', DB::raw('max(messages.created_at) as created_at'))
            ->where('users.company_id', Auth::user()->company_id)
            ->where('messages.sender_id', Auth::user()->id)
            ->where('users.id', '!=', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->groupBy('users.id')
            ->get();
        $totalSorted = (array)array_merge((array)$sortedSenderUser, (array)$sortedReceiverUser);
        usort($totalSorted, function ($a, $b) {
            return $a->created_at < $b->created_at;
        });
        $uniqueSorted = [];
        foreach ($totalSorted as $row) {
            if (!array_key_exists($row->id, $uniqueSorted))
                $uniqueSorted[$row->id] = $row;
        }
        $existingId = array();
        foreach ($uniqueSorted as $sender) {
            $existingId[] = $sender->id;
        }
        $generalUser = DB::table('users')->select('id', 'username', 'user_first_name', 'user_last_name')
            ->whereNotIn('id', $existingId)
            ->where('id', '!=', Auth::user()->id)
            ->where('company_id', Auth::user()->company_id)
            ->get();
        $data['sorted_user'] = (object)array_merge((array)$uniqueSorted, (array)$generalUser);
        if ($data['sorted_user'] && !empty($data['sorted_user']) && count((array)$data['sorted_user']) > 0) {
            $data['active_message'] = Messages::with('User')->where('sender_id', reset($data['sorted_user'])->id)
                ->where('receiver_id', Auth::user()->id)
                ->orWhere(function ($query) use ($data) {
                    $query->where('sender_id', Auth::user()->id)
                        ->where('receiver_id', reset($data['sorted_user'])->id);
                })
                ->where('company_id', Auth::user()->company_id)
                ->orderBy('id', 'desc')
                ->take(4)->get();
        }
        return view('Company.chat', $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCheckMessage($id)
    {
        Messages::where('sender_id', $id)
            ->where('receiver_id', Auth::user()->id)
            ->update(['read' => 1]);
        return Messages::with('User')
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->where('receiver_id', Auth::user()->id);
            })
            ->orWhere(function ($query) use ($id) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('receiver_id', $id);
            })
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('id', 'desc')->take(4)
            ->get();
    }

    /**
     * @return mixed
     */
    public function postMessageMore()
    {
        $senderId = Input::get('userId');
        return Messages::with('User')
            ->where(function ($query) use ($senderId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', Auth::user()->id);
            })
            ->where('id', '<', Input::get('minRow'))
            ->orWhere(function ($query) use ($senderId) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('receiver_id', $senderId);
            })
            ->where('id', '<', Input::get('minRow'))
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('id', 'desc')->take(4)
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMessageMarkRead($id)
    {
        return Messages::where('sender_id', $id)
            ->where('receiver_id', Auth::user()->id)
            ->update(['read' => 1]);
    }

    /**
     * @return Messages
     */
    public function postMessageSave()
    {
        $message = new Messages();
        $message->message = Input::get('message');
        $message->sender_id = Input::get('sender_id');
        $message->receiver_id = Input::get('receiver_id');
        $message->save();
        return $message;
    }


    public function getForce($id)
    {
        $data['currentPunchStatus'] = UserDetails::currentPunchStatus($id);
        $data['id'] = $id;
        return view('Company.force.index', $data);
    }


    public function postForce($id)
    {
        if (!UserDetails::currentPunchStatus($id)) {
            $userInfo = User::select('username')->where('id', $id)->first();
            $userDetails = new UserDetails();
            $userDetails->user_id = $id;
            $userDetails->user_name = $userInfo->username;
            $userDetails->login_time = Request::get('time');
            $userDetails->login_date = date('Y-m-d', strtotime(Request::get('time')));
            $userDetails->save();

            Session::flash('success', 'Force Punch In Done');
            return \Redirect::back();
        }else{
            $userInfo = User::select('username')->where('id', $id)->first();
            $userDetails = UserDetails::maxRow($id);
            $userDetails->user_id = $id;
            $userDetails->user_name = $userInfo->username;
            $userDetails->logout_time = Request::get('time');
            $userDetails->logout_date = date('Y-m-d', strtotime(Request::get('time')));
            $userDetails->save();
            Session::flash('error', 'Force Punch Out Done');
            return \Redirect::back();
        }
    }

}
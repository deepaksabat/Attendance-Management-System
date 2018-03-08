<?php namespace App\Http\Controllers;
use App\Leave;
use App\LeaveCategories;
use App\UserDetails;
use Auth;
use Request;
use Session;
use DB;
use App\Messages;
use App\HolidayInfo;
use App\NoticeBoard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Symfony\Component\Security\Core\User\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function __construct()
    {
        $this->beforeFilter(function(){
            $this->counter();
        });
        date_default_timezone_set(Auth::user()->CompanyUser->time_zone);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        LoginController::autoPunchOutCheck(array(Auth::user()->id));
        $data = array();
        $data['leaveUpdate'] = Leave::where('user_id', Auth::user()->id)
                                    ->where('user_noti_status', 1)
                                    ->count();
        $data['max_info'] = UserDetails::maxRow();
        if (!empty($data['max_info']) && $data['max_info']->logout_date == '0000-00-00') {
            $data['status'] = 'Punch Out';
        } else {
            $data['status'] = "Punch In";
        }
        $data['allNotice'] = NoticeBoard::orderBy('id', 'DESC')->paginate(10);
        return view('Users.dashBoard', $data);
    }

    /**
     *
     */
    public function counter()
    {
        $maxToday= UserDetails::maxRowToday();
        if($maxToday) {
            $timeDiff = strtotime(date('Y-m-d H:i:s')) - strtotime($maxToday->login_time);
            if($timeDiff == 0)
                $timeDiff = 1;
            Session::put('timeTrack', $timeDiff);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        Session::forget('timeTrack');
        Auth::logout();
        return redirect('/');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getPunchOut()
    {
        $last_id = UserDetails::maxRow();
        if($last_id->logout_date = '0000-00-00'){
            $last_id = $last_id->id;
            $punchOut = UserDetails::find($last_id);
            $punchOut->logout_date = date('Y-m-d', time());
            $punchOut->logout_time = date('Y-m-d H:i:s', time());
            if($punchOut->save()){
                Session::forget('timeTrack');
                Session::flash('punchMessageSuccess', 'You Are Punch Out');
            }
            else{
                Session::flash('punchMessageError', 'There is Error When You Are Punch Out');
            }
        }
        return redirect('user');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getPunchIn()
    {
        $last_id = UserDetails::maxRow();
        if($last_id) {
            if (Auth::user()->time) {
                if (date('Y-m-d', time()) == $last_id->login_date) {
                    $status = 'Present';
                } else {
                    if (date('H:i:s', time()) > date(Auth::user()->time, time())) {
                        $status = 'Late';
                        Session::flash('welcome_message', 'You Are Late Today!');
                    } else {
                        Session::flash('welcome_message', 'Thanks for come in time');
                        $status = 'Present';
                    }
                }
            } else {
                $status = 'Present';
            }
        }
        else {
            $status = 'Present';
        }
        $punchIn = new UserDetails();
        $punchIn->status = $status;
        $punchIn->user_id = Auth::user()->id;
        $punchIn->user_name = Auth::user()->username;
        $punchIn->login_time = date('Y-m-d H:i:s', time());
        $punchIn->login_date = date('Y-m-d', time());
        $punchIn->save();
        Session::flash('punchMessageSuccess', 'You Are Punch In');
        return redirect('user');
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function anyUpdateProfile()
    {
        if(Input::all()){
            $ignoreID = Auth::user()->id;
            $rules = array(
                'user_first_name'  => 'required|alpha_dash',
                'user_last_name'  => 'required|alpha_dash',
                'username'=> "unique:users,username,$ignoreID",
                'user_email' => 'email'
            );
            /* Laravel Validator Rules Apply */
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()):
                return $validator->messages()->first();
            else:
                $userUpdate = \App\User::find($ignoreID);
                $userUpdate->user_first_name = trim(Input::get('user_first_name'));
                $userUpdate->user_last_name = trim(Input::get('user_last_name'));
                $userUpdate->username = trim(Input::get('username'));
                $userUpdate->user_email = trim(Input::get('user_email'));
                $userUpdate->save();
            endif;
            return 'true';
        }
        return view('Users.updateProfile');
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function anyChangePassword()
    {
        if(Input::all()){
            $rules = array(
                'new_password'  => 'sometimes|required|same:confirm_new_password|min:6',
                'password'  => 'sometimes|required|password_check',
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
        }
        return view('Users.changePassword');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function anyApplyLeave()
    {
        $first_day_this_year = date('Y-01-01');
        $last_day_this_year  =date('Y-12-t');
        $leaveByCategory = DB::table('leaves')
            ->join('leave_categories', 'leaves.leave_category_id', '=', 'leave_categories.id')
            ->select(DB::raw('count(leaves.leave_category_id) as category_used'),
            'leave_categories.id','leave_categories.category','leave_categories.category_num')
            ->where('leaves.leave_date','>=', $first_day_this_year)
            ->where('leaves.leave_date','<=', $last_day_this_year)
            ->where('leaves.leave_status', 1)
            ->where('leaves.user_id', Auth::user()->id)
            ->where('leave_categories.company_id', Auth::user()->company_id)
            ->groupBy('leaves.leave_category_id','leave_categories.category','leave_categories.category_num','leave_categories.id')
            ->get();
        $allCategory = LeaveCategories::all();
        $leaveBudget = array();
        foreach($allCategory as $key=>$category):
            $searchId = $category->id;
            $expectedArray = array_filter($leaveByCategory, function($searchArray) use ($searchId) {
                return ($searchArray->id == $searchId);
            });
            $expectedArray = array_merge_recursive($expectedArray);
            $leaveBudget[$category->id]["id"]=$category->id;
            $leaveBudget[$category->id]["category"]=$category->category;
            if(empty($expectedArray)) {
                $leaveBudget[$category->id]["categoryUsed"] = 0;
                $leaveBudget[$category->id]["categoryBudget"] = $category->category_num;
            }
            else {
                $leaveBudget[$category->id]["categoryUsed"] = $expectedArray[0]->category_used;
                $leaveBudget[$category->id]["categoryBudget"] = $category->category_num - $expectedArray[0]->category_used;
            }
            $leaveBudget[$category->id]["categoryTotal"] = $category->category_num;
        endforeach;
            Session::put('checkBudget', $leaveBudget);
        $data['leaveBudget'] = $leaveBudget;
        return view('Users.applyLeave',$data);
    }

    /**
     * @return string
     */
    public function postLeaveApply()
    {
        $checkBudget= Session::get('checkBudget');
        $leaveCause = Input::get('leave_cause');
        $leaveCategoryId = Input::get('leave_category_id');
        $leaveDate = Input::get('leave_date');
        $firstDayThisYear = date('Y-01-01');
        $lastDayThisYear  = date('Y-12-t');

        foreach ($leaveDate as $key=>$singleDate):
            if($singleDate == '') {
                return 'Please fill all Leave date field';
            }
            elseif($singleDate > $lastDayThisYear) {
                return 'You Can Apply leave only for this Year';
            }
            elseif($singleDate < $firstDayThisYear) {
                return 'You Can Apply leave only for this Year';
            }
        endforeach;

        foreach($checkBudget as $budget):
            if($budget['id'] == $leaveCategoryId) {
                $key=$key+1;
                if($budget['categoryBudget']<$key) {
                    return 'You Cross Your Leave Budget.PLease Check Again.';
                }
            }
        endforeach;

        foreach ($leaveDate as $singleDate):
             $checkExisting  = Leave::where('leave_date' , $singleDate)
                ->where('user_id', Auth::user()->id)
                ->first();
            if($checkExisting) {
                return 'You are Already Apply '.$singleDate;
            }
        endforeach;

        foreach ($leaveDate as $singleDate){
            $leaveSave = new Leave();
            $leaveSave->leave_date = $singleDate;
            $leaveSave->user_id = Auth::user()->id;
            $leaveSave->leave_category_id = $leaveCategoryId;
            $leaveSave->leave_cause = $leaveCause;
            $leaveSave->save();
        }
        return 'true';
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getMyLeave()
    {
        Leave::where('user_id', Auth::user()->id)->update(array('user_noti_status' => 0));
        $data['myLeave'] = Leave::where('user_id', Auth::user()->id)->get();
        return view('Users.myLeave', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getReport()
    {
        $data['startDate'] = Input::get('s_date');
        $data['endDate'] = Input::get('e_date');
        $data['id'] = Auth::user()->id;
        $data['userInfo'] = \App\User::find($data['id']);
        $data['attendanceReport'] = UserDetails::
        select(DB::raw('timediff(logout_time,login_time) as timediff'),
            'login_date','logout_date','id','login_time','logout_time','user_id','status')
            ->where('user_id', $data['id'])
            ->where('login_date', '>=',  $data['startDate'])
            ->where('logout_date', '<=', $data['endDate'])
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        $data['allDate']= $this->getDatesFromRange( $data['startDate'], $data['endDate']);
        $data['allHoliday'] = HolidayInfo::where('holiday', '>=',  $data['startDate'])
            ->where('holiday', '<=', $data['endDate'])
            ->get()
            ->toArray();
        $data['allLeave'] = Leave::where('leave_date', '>=',  $data['startDate'])
            ->where('leave_date', '<=', $data['endDate'])
            ->where('user_id', $data['id'])
            ->where('leave_status', 1)
            ->get()
            ->toArray();
        return view('Users.report',$data);

    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function anyFullCalender()
    {
        if(Input::all()) {
            $data['startDate'] = Input::get('from');
            $data['endDate'] = Input::get('to');
            $data['id'] = Auth::user()->id;
            $data['userInfo'] = \App\User::find($data['id']);
            if (!$data['userInfo']) {
                Session::flash('flashError', 'This User Is Not Found');
                return redirect('user/full-calender');
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
                Session::flash('flashError', 'You are not Any Work From '.$data['startDate'].' to '. $data['endDate']);
                return redirect('user/full-calender');
            }
            return view('Users.fullCalender', $data);
        }else{
            $user = new \App\User();
            $data['allUser'] = $user->allUser();
            return view('Users.fullCalenderRequest',$data);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function anyTableReport()
    {
        if(Input::all()) {
            $data['startDate'] = Input::get('from');
            $data['endDate'] = Input::get('to');
            $data['id'] = Auth::user()->id;
            $data['userInfo'] = \App\User::find($data['id']);
            if (!$data['userInfo']) {
                Session::flash('flashError', 'This User Is Not Found');
                return redirect('user/table-report');
            }
            $data['allDate']= $this->getDatesFromRange( $data['startDate'], $data['endDate']);
            $data['allHoliday'] = HolidayInfo::where('holiday', '>=',  $data['startDate'])
                ->where('holiday', '<=', $data['endDate'])
                ->get()
                ->toArray();
            $data['allLeave'] = Leave::where('leave_date', '>=',  $data['startDate'])
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
                Session::flash('flashError', 'You are not Any Work From '.$data['startDate'].' to '. $data['endDate']);
                return redirect('user/table-report');
            }

            return view('Users.report', $data);
        }else {
            $user = new \App\User();
            $data['allUser'] = $user->allUser();
            return view('Users.tableReportRequest', $data);
        }
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    public function getDatesFromRange($start, $end) {
        $dates = array($start);
        while (end($dates) < $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
        }
        return $dates;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getNoticeBoard()
    {
        $data['allNotice'] = NoticeBoard::orderBy('id', 'DESC')->paginate(10);
        return view('Users.noticeBoard', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getChat()
    {
        $sortedSenderUser= DB::table('users')
            ->join('messages', 'users.id', '=', 'messages.sender_id', 'left outer')
            ->select(DB::raw('sum(messages.read) as total_read'),
                DB::raw('count(messages.id) as total_messages'),
                'users.id', 'users.username', 'users.user_first_name', 'users.user_last_name',DB::raw('max(messages.created_at) as created_at'))
            ->where('users.company_id', Auth::user()->company_id)
            ->where('messages.receiver_id', Auth::user()->id)
            ->where('users.id', '!=', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->groupBy('users.id')
            ->get();

        $sortedReceiverUser = DB::table('users')
            ->join('messages', 'users.id', '=', 'messages.receiver_id', 'left outer')
            ->select('users.id', 'users.username', 'users.user_first_name', 'users.user_last_name',DB::raw('max(messages.created_at) as created_at'))
            ->where('users.company_id', Auth::user()->company_id)
            ->where('messages.sender_id', Auth::user()->id)
            ->where('users.id', '!=', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->groupBy('users.id')
            ->get();
        $totalSorted = (array) array_merge((array) $sortedSenderUser, (array) $sortedReceiverUser);
        usort($totalSorted, function($a, $b) {
            return $a->created_at < $b->created_at;
        });
        $uniqueSorted = [];
        foreach($totalSorted as $row){
            if(!array_key_exists($row->id,$uniqueSorted))
                $uniqueSorted[$row->id] = $row;
        }
        $existingId = array();
        foreach($uniqueSorted as $sender){
            $existingId[] = $sender->id;
        }
        $generalUser = DB::table('users')->select('id', 'username', 'user_first_name', 'user_last_name')
                            ->whereNotIn('id', $existingId)
                            ->where('id', '!=', Auth::user()->id)
                            ->where('company_id', Auth::user()->company_id)
                            ->get();
        $data['sorted_user'] = (object) array_merge((array) $uniqueSorted, (array) $generalUser);
        if($data['sorted_user'] && !empty($data['sorted_user']) && count((array)$data['sorted_user']) > 0 ){
            $data['active_message'] = Messages::with('User')->where('sender_id', reset($data['sorted_user'])->id)
                ->where('receiver_id', Auth::user()->id)
                ->orWhere(function($query) use ($data)
                {
                    $query->where('sender_id', Auth::user()->id)
                        ->where('receiver_id', reset($data['sorted_user'])->id);
                })
                ->where('company_id', Auth::user()->company_id)
                ->orderBy('id', 'desc')
                ->take(4)->get();
        }
        return view('Users.chat', $data);
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
            ->where(function($query) use ($id)
            {
                $query->where('sender_id', $id)
                    ->where('receiver_id', Auth::user()->id);
            })
            ->orWhere(function($query) use ($id)
            {
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
            ->where(function($query) use ($senderId)
            {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', Auth::user()->id);
            })
            ->where('id', '<' ,Input::get('minRow'))
            ->orWhere(function($query) use ($senderId)
            {
                $query->where('sender_id', Auth::user()->id)
                    ->where('receiver_id', $senderId);
            })
            ->where('id', '<' ,Input::get('minRow'))
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

}

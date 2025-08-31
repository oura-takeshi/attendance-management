<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance()
    {
        return view('admin.attendance');
    }

    public function staff()
    {
        return view('admin.staff');
    }

    public function list($user_id)
    {
        return view('admin.list');
    }

    public function approval($work_time_request_id)
    {
        return view('admin.approval');
    }
}

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

    public function detail()
    {
        return view('admin.detail');
    }
}

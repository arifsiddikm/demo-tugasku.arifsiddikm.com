<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;

class LandingController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalTasks = Task::count();
        return view('landing.index', compact('totalUsers', 'totalTasks'));
    }
}

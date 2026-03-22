<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Keep existing supervisor route, but use the same dashboard UX as admin.
        return redirect()->route('admin.dashboard');
    }
}

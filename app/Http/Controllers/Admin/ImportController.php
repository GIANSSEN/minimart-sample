<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function importProducts(Request $request)
    {
        return redirect()->back()->with('success', 'Import feature coming soon!');
    }

    public function downloadTemplate()
    {
        return redirect()->back()->with('info', 'Template download coming soon!');
    }
}

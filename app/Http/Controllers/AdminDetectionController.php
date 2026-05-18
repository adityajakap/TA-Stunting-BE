<?php

// App\Http\Controllers\DetectionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDetectionController extends Controller
{
    // Minimal admin controller stub. Actual admin listing and actions are handled
    // in DetectionController; file kept for backward compatibility if referenced.
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.detections.index');
    }
}



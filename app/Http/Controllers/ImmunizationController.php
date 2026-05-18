<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImmunizationController extends Controller
{
    // Feature removed: return 404 for any action
    public function __call($name, $arguments)
    {
        abort(404);
    }
}

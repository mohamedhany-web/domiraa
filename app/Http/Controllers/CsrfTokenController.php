<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CsrfTokenController extends Controller
{
    /**
     * Get fresh CSRF token
     */
    public function getToken()
    {
        return response()->json([
            'token' => csrf_token()
        ]);
    }
}


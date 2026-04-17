<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    /**
     * TAMPILAN PROFIL PETUGAS
     */
    public function profile()
    {
        return view('petugas.profile.index', [
            'user' => Auth::user()
        ]);
    }
}

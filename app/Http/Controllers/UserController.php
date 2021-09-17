<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function home()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        if ($user->role == 'admin-hrd') {
            return redirect()->route('adminhrd-index');
        } elseif ($user->role == 'admin-divisi') {
            return redirect()->route('admindivisi-index');
        } elseif ($user->role == 'employee') {
            return redirect()->route('employee-profile');
        } else {
            return redirect()->route('login');
        }
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}

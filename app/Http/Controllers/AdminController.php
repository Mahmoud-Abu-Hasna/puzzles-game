<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminController extends Controller
{
    use AuthenticatesUsers;
    //
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['adminLogin','postAdminLogin']);
        $this->middleware('guest:admin')->only(['adminLogin','postAdminLogin']);
    }
    public function index(){
        return view('admin.pages.home');
    }
    public function adminLogin(){
        return view('admin.auth.login');
    }

    public function postAdminLogin(){
        // Validate the form data
        $this->validate(request(), [
            'email'   => 'required|email',
            'password' => 'required'
        ]);
        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])) {
            // if successful, then redirect to their intended location
            session()->flash('flash_message', 'Welcome back,'.auth()->user()->username);
            session()->flash('message_type', 'success');
            return redirect()->intended(route('admin.dashboard'));
        }
        // if unsuccessful, then redirect back to the login with the form data
        session()->flash('flash_message', 'Please,Review Your entered Email/Password.');
        session()->flash('message_type', 'danger');
        return redirect()->back()->withInput(request()->only('email'));

    }
    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }
    public function editUserPassword(){
        return view('admin.pages.password');
    }

    public function editUserPasswordPost(){
        $this->validate(request(), [
            'password' => 'required|confirmed',
        ]);

        $admin = Admin::find(auth()->id());
        $admin->password=bcrypt(request('password'));
        $admin->save();
        session()->flash('flash_message', 'The New Password was saved Successfully.');
        session()->flash('message_type', 'success');
        return redirect()->route('admin.dashboard');
    }
}

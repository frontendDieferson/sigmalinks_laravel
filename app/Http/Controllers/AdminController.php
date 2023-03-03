<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Page;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except'=>[
            'login',
            'loginAction',
            'register',
            'registerAction'
        ]]);
    }
    //Processo de Autenticação - Registro, Login e Logout.
    public function login(Request $request) {
        return view('/admin/login', [
            'error' => $request->session()->get('error')
        ]);
    }

    public function loginAction(Request $request) {
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)) {
            return redirect('/admin');
        } else {
            $request->session()->flash('error', 'E-mail e/ou Senha não conferem, por favor tente novamente.');
            return redirect('/admin/login');
        }
    }

    public function register(Request $request) {
        return view('/admin/register', [
            'error' => $request->session()->get('error')
        ]);
    }

    public function registerAction(Request $request) {
        $credentials = $request->only('email', 'password');

        $hasEmail = User::where('email', $credentials['email'])->count();

        if($hasEmail === 0) {

            $newUser = new User();
            $newUser->email = $credentials['email'];
            $newUser->password = password_hash($credentials['password'], PASSWORD_DEFAULT);
            $newUser->save();

            Auth::login($newUser);
            return redirect('/admin');

        } else {
            $request->session()->flash('error', 'Já existe um usuário com este E-mail...');
            return redirect('/admin/register');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('/admin');
    }

    //Dashboard

    public function index() {
        $user = Auth::user();
        $pages = Page::where('id_user', $user->id)->get();

        return view('/admin/index', [
            'pages' => $pages
        ]);
    }

    public function pageLinks($slug) {
        return view('admin/page_links', [
            'menu' => 'links'
        ]);
    }

    public function pageDesign($slug) {
        return view('admin/page_design', [
            'menu' => 'design'
        ]);
    }

    public function pageStats($slug) {
        return view('admin/page_stats', [
            'menu' => 'stats'
        ]);
    }
}

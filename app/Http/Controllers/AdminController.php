<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Page;
use App\Models\Link;

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
        $user = Auth::user();
        $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)
            ->first();

        if($page) {
            $links = Link::where('id_page', $page->id)
                ->orderBy('order', 'ASC')
                ->get();

            return view('admin/page_links', [
                'menu' => 'links',
                'page' => $page,
                'links' => $links
            ]);
        } else {
            return redirect('/admin');
        }
    }

    public function newLinkAction($slug, Request $request) {
        $user = Auth::user();
        $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)
            ->first();

            if($page) {
                $fields = $request->validate([
                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2'],
                    'href' => ['required', 'url'],
                    'op_bg_color' => ['required', 'regex: /^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color' => ['required', 'regex: /^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]
                 ]);

                 $totalLinks = Link::where('id_page', $page->id)->count();
                 $newLink = new Link();
                 $newLink->id_page = $page->id;
                 $newLink->status = $fields['status'];
                 $newLink->order = $totalLinks;
                 $newLink->title = $fields['title'];
                 $newLink->href = $fields['href'];
                 $newLink->op_bg_color = $fields['op_bg_color'];
                 $newLink->op_text_color = $fields['op_text_color'];
                 $newLink->op_border_type = $fields['op_border_type'];
                 $newLink->save();

                 return redirect('/admin/'.$page->slug.'/links');

            } else {
                return redirect('/admin');
            }
    }

    public function editLink($slug, $linkid) {
        $user = Auth::user();
        $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)
            ->first();

            if($page) {
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link) {
                    return view('admin.page_editlink', [
                        'menu' => 'links',
                        'page' => $page,
                        'link' => $link
                    ]);
            }
        }
        return redirect('/admin');
    }

    public function editLinkAction($slug, $linkid, Request $request) {
        $user = Auth::user();
        $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)
            ->first();

            if($page) {
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link) {
                    $fields = $request->validate([
                        'status' => ['required', 'boolean'],
                        'title' => ['required', 'min:2'],
                        'href' => ['required', 'url'],
                        'op_bg_color' => ['required', 'regex: /^[#][0-9A-F]{3,6}$/i'],
                        'op_text_color' => ['required', 'regex: /^[#][0-9A-F]{3,6}$/i'],
                        'op_border_type' => ['required', Rule::in(['square', 'rounded'])]
                     ]);

                 $link->status = $fields['status'];
                 $link->title = $fields['title'];
                 $link->href = $fields['href'];
                 $link->op_bg_color = $fields['op_bg_color'];
                 $link->op_text_color = $fields['op_text_color'];
                 $link->op_border_type = $fields['op_border_type'];
                 $link->save();

                 return redirect('/admin/'.$page->slug.'/links');
            }
        }
        return redirect('/admin');
    }

    public function linkOrderUpdate($linkid, $pos) {
        $user = Auth::user();

        $link = Link::find($linkid);

        $myPages = [];
        $myPagesQuery = Page::where('id_user', $user->id)->get();
        foreach($myPagesQuery as $pageItem) {
            $myPages[] = $pageItem->id;
        }
        if(in_array($link->id_page, $myPages)) {
            if($link->order > $pos) {
                $afterLinks = Link::where('id_page', $link->id_page)
                ->where('order', '>=', $pos)
                ->get();
                foreach($afterLinks as $afterLink) {
                    $afterLink->order++;
                    $afterLink->save();
                }
            } elseif($link->order < $pos) {
                $beforeLinks = Link::where('id_page', $link->id_page)
                ->where('order', '<=', $pos)
                ->get();
                foreach($beforeLinks as $beforeLink) {
                    $beforeLink->order--;
                    $beforeLink->save();
                }
            }
            //Posicionando os Links
            $link->order = $pos;
            $link->save();

            //Corrigindo as posições dos links
            $allLinks = Link::where('id_page', $link->id_page)
                ->orderBy('order', 'ASC')
                ->get();
                foreach($allLinks as $linkKey => $linkItem) {
                    $linkItem->order = $linkKey;
                    $linkItem->save();
                }

        }
        return [];
    }

    public function newLink($slug) {
        $user = Auth::user();
        $page = Page::where('id_user', $user->id)
            ->where('slug', $slug)
            ->first();

        if($page) {
            return view('admin/page_editlink', [
                'menu' => 'links',
                'page' => $page
            ]);
        } else {
            return redirect('/admin');
        }
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

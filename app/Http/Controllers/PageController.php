<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function index($slug) {
       $page = Page::where('slug', $slug)->first();

       if($page) {
        // Background
        $bg = '#FFF';
        switch($page->op_bg_type) {
            case 'image':
                $bg = "url('".url('/media/upload').'/'.$page->op_bg_value."')";
                break;
            case 'color':
                break;
        }
        // Links
        $links = [];
        // Registrar a visualização


        return view('page', [
            'font_color' =>$page->op_font_color,
            'profile_image' => url('/media/uploads').'/'.$page->op_profile_image,
            'title' =>$page->op_title,
            'description' =>$page->op_description,
            'fb_pixel' => $page->op_fb_pixel,
            'bg' =>$bg,
            'links' =>$links

        ]);
       } else {
         return view('notfound');
       }
    }
}

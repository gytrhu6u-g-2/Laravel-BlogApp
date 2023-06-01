<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

use function PHPUnit\Framework\isNull;

class BlogController extends Controller
{
    /**
     * ブログ一覧を表示する
     * 
     * @return view
     */
    public function showList()
    {
        $blogs = Blog::all();

        return view('blog.list', ['blogs' => $blogs]);
    }

    /**
     * ブログ詳細を表示する
     * @param $id
     * @return view
     */
    public function showDetail($id) 
    {
       $blog = Blog::find($id);

       if (is_null($blog)) {
        session()->flash('err_msg','データがありません。');
        return redirect('/');
       }
       
       return view('blog.detail', ['blog' => $blog]);
    }
}

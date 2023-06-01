<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class BlogController extends Controller
{
    /**
     * ブログ一覧を表示する
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
        return redirect(route('blogs'));
       }
       
       return view('blog.detail', ['blog' => $blog]);
    }

    /**
     * ブログ画面を表示する
     * @return view
     */
    public function showCreate()
    {
        return view('blog.form');
    }

    /**
     * ブログを登録する
     * @return view
     */
    public function exeStore(BlogRequest $request)
    {
        // ブログのデータを受け取る
        $input = $request->all();

        DB::beginTransaction();
        try {
            // ブログを登録
            Blog::create($input);
            DB::commit();
        } catch(\Throwable $e) {
            abort(500);
            DB::rollBack();
        }

        session()->flash('err_msg','ブログを登録しました');
        return redirect(route('blogs'));
    }
}

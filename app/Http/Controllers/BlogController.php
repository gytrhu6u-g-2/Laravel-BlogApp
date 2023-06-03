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

    /**
     * ブログ編集フォームを表示する
     * @param $id
     * @return view
     */
    public function showEdit($id)
    {
        $blog = Blog::find($id);

        if(is_null($blog)) {
            session()->flash('err_msg','データがありません。');
            return redirect(route('blogs'));
        };

        return view('blog.edit', ['blog' => $blog]);
    }

    /**
     * ブログをアップデートする
     * @return view
     */
    public function exeUpdate(BlogRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            // ブログを更新
            $blog = Blog::find($input['id']);
            $blog->fill([
                'title' => $input['title'],
                'content' => $input['content']
            ]);
            $blog->save();
            DB::commit();
        } catch(\Throwable $e) {
            abort(500);
            DB::rollBack();
        }

        session()->flash('err_msg', 'データを更新しました。');
        return redirect(route('blogs'));
    }

    /**
     * ブログを削除
     * @param $id
     * @return view
     */
    public function exeDelete($id)
    {
        if(empty($id)) {
            session()->flash('err_msg','データがありません。');
            return redirect(route('blogs'));
        }

        try {
            Blog::destroy($id);
        } catch (\Throwable $e) {
            abort(500);
        }

        session()->flash('err_msg','削除しました。');
        return redirect(route('blogs'));
    }
}

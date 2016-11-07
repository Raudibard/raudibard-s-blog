<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticlesIgnore;
use App\ArticlesLike;
use App\ArticlesPhoto;

use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ArticlesPhotoRequest;

use Auth;
use Carbon\Carbon;
use Image;
use View;

class ArticlesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'render']]);
    }

    /**
     * Show all Articles.
     *
     * @return array
     */
    public function index(Request $request)
    {

        $response = [];

        if (Auth::guest()) {

            $articles = Article::sortedBy($request->sort)->get();

        } else {

            $articles = Article::notIgnored()->sortedBy($request->sort)->get();

        }

        foreach ($articles as $article) {

            $response[] = $this->render($article);

        }

        return $response;
    }

    /**
     * Show single Article.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return $this->render($article);
    }

    /**
     * Show Article edit form.
     *
     * @param integer $id
     * @return array
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        $photos = $article->photos()->get();

        $view = View::make('partials.article.edit', compact('article', 'photos'));

        $response = [
            'html' => $view->render(),
            'content' => $article->content,
            'photosAmount' => $photos->count()
        ];

        return $response;
    }

    /**
     * Save a new Article and assign photos if necessary.
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article = new Article($request->all());

        Auth::user()->articles()->save($article);

        if (count($request->_files)) {

            foreach ($request->_files as $filepath) {

                $photo = ArticlesPhoto::where('filepath', $filepath)->first();

                if ($photo) {

                    $photo->article()->associate($article);

                    $photo->save();

                }

            }

        }

        return $this->show($article->id);
    }

    /**
     * Update an existing Article and assign photos if necessary.
     *
     * @param integer $id
     * @param ArticleRequest $request
     * @return integer
     */
    public function update($id, ArticleRequest $request)
    {
        $article = Article::findOrFail($id);

        $input = $request->all();

        $input['edited_at'] = Carbon::now();

        $article->update($input);

        if (count($request->_files)) {

            foreach ($request->_files as $filepath) {

                $photo = ArticlesPhoto::where('filepath', $filepath)->first();

                if ($photo) {

                    $photo->article()->associate($article);

                    $photo->save();

                }

            }

        }

        return $article->id;
    }

    /**
     * Delete an existing Article and unlink photos if necessary.
     *
     * @param integer $id
     * @param ArticleRequest $request
     * @return string
     */
    public function delete($id/*, ArticleRequest $request*/)
    {
        $article = Article::findOrFail($id);

        $photos = $article->photos()->get();

        if ($photos->count()) {

            foreach ($photos as $photo) {

                if (file_exists(public_path('uploads') . $photo->filepath)) {

                    unlink(public_path('uploads') . $photo->filepath);

                }

                if (file_exists(public_path('uploads') . $photo->filethumb)) {

                    unlink(public_path('uploads') . $photo->filethumb);

                }

                $photo->delete();
                
            }

        }

        $article->delete();

        return '';
    }

    /**
     * Save a new Articles_Ignores entry.
     *
     * @param integer $id
     * @return string
     */
    public function ignore($id)
    {
        $article = Article::findOrFail($id);

        ArticlesIgnore::create(['user_id' => Auth::user()->id, 'article_id' => $article->id]);

        return '';
    }

    /**
     * Save a new Articles_Likes entry.
     *
     * @param integer $id
     * @return integer
     */
    public function like($id)
    {
        $article = Article::findOrFail($id);

        $article_like = Auth::user()->articles_liked()->where('article_id', $article->id)->first();

        if ($article_like) {

            if ($article_like->delete()) {

                $article->counter_likes --;

                $article->save();

            }

        } else {

            $article_like = ArticlesLike::create(['user_id' => Auth::user()->id, 'article_id' => $article->id]);

            if ($article_like) {

                $article->counter_likes ++;

                $article->save();

            }

        }

        return $article->counter_likes;
    }

    /**
     * Render single Article view.
     *
     * @param instance of \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function render($article)
    {
        $author = $article->user()->first();

        if (Auth::guest()) {

            $article->wasLiked = FALSE;

        } else {

            $article->wasLiked = (Auth::user()->articles_liked()->where('article_id', $article->id)->first()) ? TRUE : FALSE;

        }

        $photos = $article->photos()->oldest('id')->get();

        $comments = $article->comments()->oldest()->get();

        foreach ($comments as $comment) {

            $comment->author = $comment->user()->first();

            if (Auth::guest()) {

                $comment->wasLiked = FALSE;

            } else {

                $comment->wasLiked = (Auth::user()->comments_liked()->where('comment_id', $comment->id)->first()) ? TRUE : FALSE;

            }

        }

        $commentsSorted = sortMyRows('id', 'comment_id', NULL, 0, $comments);

        $comments = collect($commentsSorted);
        
        $view = View::make('partials.article', compact('article', 'author', 'photos', 'comments'));

        return $view->render();
    }

    /**
     * Save an ArticlesPhoto and store uploaded file.
     * 
     * @param ArticleRequest $request 
     * @return string
     */
    public function uploadPhoto(ArticlesPhotoRequest $request)
    {
        $filepath = getUploadDirectory();

        $name = time();
        $extension = $request->file->getClientOriginalExtension();

        $filename = $name . '.' . $extension;
        $filethumb = $name . '_thumb.' . $extension;

        if ($request->file->move($filepath, $filename)) {

            $image = Image::make($filepath . '/' . $filename);

            if ($image->width() > 800 || $image->height() > 600) {

                $image->resize(800, 600, function ($constraint){
                    $constraint->aspectRatio();
                });

                $image->save($filepath . '/' . $filename);

            }

            $image->resize(162, null, function($constraint){
                $constraint->aspectRatio();
            });

            $image->save($filepath . '/' . $filethumb);

            $image->destroy();

            $photo = new ArticlesPhoto();

            $photo->filepath = str_replace(public_path('uploads'), '', $filepath) . '/' . $filename;
            $photo->filethumb = str_replace(public_path('uploads'), '', $filepath) . '/' . $filethumb;

            Auth::user()->articles_photos()->save($photo);

        }

        return $photo->filepath;
    }

    /**
     * Delete an ArticlesPhoto and unlink assigned file.
     * 
     * @param ArticleRequest $request 
     * @return string
     */
    public function deletePhoto($id)
    {
        $photo = ArticlesPhoto::findOrFail($id);

        if (file_exists(public_path('uploads') . $photo->filepath)) {

            unlink(public_path('uploads') . $photo->filepath);

        }

        if (file_exists(public_path('uploads') . $photo->filethumb)) {

            unlink(public_path('uploads') . $photo->filethumb);

        }

        $photo->delete();

        return '';
    }
}

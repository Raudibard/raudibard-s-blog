<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\CommentsLike;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

use Auth;
use Carbon\Carbon;
use View;


/**
 * Class CommentsController
 * @package App\Http\Controllers
 */
class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'render']]);
    }

    /**
     * Show single Comment.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::findOrFail($id);

        return $this->render($comment);
    }

    /**
     * Show Comment edit form.
     *
     * @param integer $id
     * @return array
     */
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);

        $view = View::make('partials.comment.edit', compact('comment'));

        $response = [
            'html' => $view->render(),
            'content' => $comment->content
        ];

        return $response;
    }

    /**
     * Save a new Comment.
     *
     * @param CommentRequest $request
     * @return integer
     */
    public function store(CommentRequest $request)
    {
    	$article = Article::findOrFail($request->articleId);

		$comment = new Comment();

		$comment->user()->associate(Auth::user());
		$comment->article()->associate($article);

		if ($request->commentId) {
			
			$parent = Comment::findOrFail($request->commentId);

			$comment->comment()->associate($parent);

		}

		$comment->content = $request->content;

		if ($comment->save()) {

    		$article->counter_comments ++;

    		$article->save();

    		if ($request->commentId) {

    			$parent->counter_comments ++;

    			$parent->save();

    		}

    	}

        return $comment->id;
    }

    /**
     * Update an existing Comment.
     *
     * @param integer $id
     * @param CommentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, CommentRequest $request)
    {
        $comment = Comment::findOrFail($id);

        $input = $request->all();

        $input['edited_at'] = Carbon::now();

        $comment->update($input);

        return $this->show($comment->id);
    }

    /**
     * Delete an existing Comment.
     *
     * @param integer $id
     * @param CommentRequest $request
     * @return array
     */
    public function delete($id, CommentRequest $request)
    {
    	$response = [];

        $comment = Comment::findOrFail($id);

        $article = $comment->article()->first();

        $parent = $comment->comment()->first();

        if ($comment->counter_comments) {

        	$comment->was_removed = TRUE;

        	$action = $comment->save();

        	$response['refresh'] = TRUE;

        } else {

        	$action = $comment->delete();

        	$response['refresh'] = FALSE;

        }

        if ($action) {

        	$article->counter_comments --;

        	$article->save();

        	if ($parent) {

	        	$parent->counter_comments --;

	        	$parent->save();

	        }

        	$response['counter'] = $article->counter_comments;

        }

        return $response;
    }

    /**
     * Save a new Comments_Likes entry.
     *
     * @param integer $id
     * @return integer
     */
    public function like($id)
    {
        $comment = Comment::findOrFail($id);

        $comment_like = Auth::user()->comments_liked()->where('comment_id', $comment->id)->first();

        if ($comment_like) {

            if ($comment_like->delete()) {

                $comment->counter_likes --;

                $comment->save();

            }

        } else {

            $comment_like = CommentsLike::create(['user_id' => Auth::user()->id, 'comment_id' => $comment->id]);

            if ($comment_like) {

                $comment->counter_likes ++;

                $comment->save();

            }

        }

        return $comment->counter_likes;
    }

    /**
     * Render single Comment view.
     *
     * @param instance of \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function render($comment)
    {
        $author = $comment->user()->first();

        if (Auth::guest()) {

            $comment->wasLiked = FALSE;

        } else {

            $comment->wasLiked = (Auth::user()->comments_liked()->where('comment_id', $comment->id)->first()) ? TRUE : FALSE;

        }

        $view = View::make('partials.comment', compact('comment', 'author'));

        return $view->render();
    }
}

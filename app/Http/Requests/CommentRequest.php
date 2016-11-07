<?php

namespace App\Http\Requests;

use App\Comment;

use Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        switch ($request->method) {

            case 'PATCH': case 'DELETE':

                $comment = Comment::findOrFail($request->id);

                if (Auth::user()->id === $comment->user_id) {

                    return true;

                } else {

                    return false;

                }

                break;

            default: return true; break;

        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $return = [];

        switch ($request->method) {

            case 'POST': case 'PATCH':

                $return = [
                    'content' => 'required'
                ];

                break;

            default: break;

        }

        return $return;
    }
}

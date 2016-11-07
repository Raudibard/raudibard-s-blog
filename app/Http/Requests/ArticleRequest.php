<?php

namespace App\Http\Requests;

use App\Article;

use Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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

                $article = Article::findOrFail($request->id);

                if (Auth::user()->id === $article->user_id) {

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
                    'content' => 'required|min:10'
                ];

                break;

            default: break;

        }

        return $return;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'content.min' => 'This text is too short, isn\'t it? It must be at least :min characters!'
        ];
    }
}

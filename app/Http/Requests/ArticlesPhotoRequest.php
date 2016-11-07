<?php

namespace App\Http\Requests;

use App\ArticlesPhoto;

use Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class ArticlesPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
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
        return [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        if (Request::hasFile('file')) {

            $photo = Request::file('file');

            return [
                'file.image' => 'The ' . $photo->getClientOriginalName() . ' must be an image.',
                'file.mimes' => 'The ' . $photo->getClientOriginalName() . ' must be a file of type: :values.',
                'file.max' => 'The ' . $photo->getClientOriginalName() . ' may not be greater than :max kilobytes.'

            ];

        }
    }

    /**
     * Overriding the original function to always return JSON.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(!Gate::authorize('update', $this->route('article'))){
            return false;
        }
        return true;
    }

    public function rules(): array
    {
        return [
            'title'   => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string'],
        ];
    }
}
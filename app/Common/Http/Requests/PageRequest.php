<?php

namespace App\Common\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{

    public function rules(): array {
        return [
            'page' => 'int|min:1',
            'limit' => 'int|min:1',
        ];
    }

}

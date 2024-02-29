<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //customer validation
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'sex' => 'required|string|max:255',
            'age_group' => 'required|string|max:255',
            'client_type' => 'required|string|max:100',
            'digital_literacy' => 'boolean|max:10',
            'pwd' => 'boolean|max:10',
            'pregnant' => 'boolean|max:10',
            'senior_citizen' => 'boolean|max:10',

            //customer attribute rating validation
            'rate_score' => 'nullable|integer|max:10',
            'importance_rate_score' => 'integer|max:10',

            //customer cc rating validation 
            'customer_id' => 'required|exists:customers,id',
            'cc_id' => 'required|exists:cc_questions,id', 
            'answer' => 'nullable|integer|max:255',

            //customer comment validation
            'comment' => 'nullable|string|max:255',
            'is_complaint' => 'boolean',

            //customer toher data validation
            'indication' => 'nullable|string|max:255',
            'recommend_rate_score' => 'required|max:255',
        ];
    }
}

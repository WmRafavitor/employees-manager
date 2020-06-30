<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:4|max:191',
            'date_birth' => 'required|date',
            'sex' => 'required|max:1|in:m,f',
            'role' => 'required|max:80',
            'email' => 'required|email',
            'hired_at' => 'required|date',
            'phone' => 'required|max:15',
            'document' => $this->documentRules()
        ];
    }

    public function validationData()
    {
        $data = $this->all();
        if (isset($data['document'])) {
            $data['document'] = preg_replace('/[^0-9]/', '', $data['document']);
        }
        return $data;
    }

    private function documentRules()
    {
        $uniqueDocumentRule = Rule::unique('employees', 'document');

        if ($this->employee) {
            $uniqueDocumentRule = $uniqueDocumentRule->ignore($this->employee, 'id');
        }

        return [
            'required',
            'cpf',
            $uniqueDocumentRule
        ];
    }
}

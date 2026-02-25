<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $employee = $user->employee;
        
        if ($employee) {
            // If user has employee data, validate both user and employee fields
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($this->user()->id),
                ],
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_initial' => 'nullable|string|max:10',
                'ext_name' => 'nullable|string|max:10',
                'sex' => 'required|string|in:M,F',
                'prefix' => 'nullable|string|max:10',
                'contact_num' => 'nullable|string|max:20',
                'position_name' => 'required|string|max:255',
                'office_id' => 'nullable|exists:offices,id',
                'division_id' => 'nullable|exists:lib_divisions,id_division',
                'unit_id' => 'nullable|exists:lib_units,id_unit',
                'subunit_id' => 'nullable|exists:lib_subunits,id_subunit',
                'class_id' => 'nullable|exists:lib_class,id_class',
                'additional_positions' => 'nullable|array',
                'additional_positions.*.id' => 'nullable|exists:emp_positions,id',
                'additional_positions.*.position_name' => 'nullable|string|max:255',
                'additional_positions.*.office_id' => 'nullable|exists:offices,id',
                'additional_positions.*.division_id' => 'nullable|exists:lib_divisions,id_division',
                'additional_positions.*.unit_id' => 'nullable|exists:lib_units,id_unit',
                'additional_positions.*.subunit_id' => 'nullable|exists:lib_subunits,id_subunit',
                'additional_positions.*.class_id' => 'nullable|exists:lib_class,id_class',
            ];
        } else {
            // If user doesn't have employee data, only validate user fields
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($this->user()->id),
                ],
            ];
        }
    }
}

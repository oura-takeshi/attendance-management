<?php

namespace App\Http\Requests\User;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CorrectionRequest extends FormRequest
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
            'reason' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => '備考を記入してください',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator)
        {
            $string_work_start_time = $this->input('work_start_time');
            $string_work_end_time = $this->input('work_end_time');
            $string_break_times = $this->input('break_time');

            if ($string_work_start_time && $string_work_end_time) {
                $work_start_time = Carbon::createFromFormat('H:i', $string_work_start_time);
                $work_end_time = Carbon::createFromFormat('H:i', $string_work_end_time);

                if ($work_start_time->gt($work_end_time)) {
                    $validator->errors()->add('work_start_time', '出勤時間もしくは退勤時間が不適切な値です');
                }

                foreach ($string_break_times as $index => $string_break_time) {
                    $break_start_time = null;
                    $break_end_time = null;

                    if (!empty($string_break_time['start_time'])) {
                        $break_start_time = Carbon::createFromFormat('H:i', $string_break_time['start_time']);
                    }

                    if (!empty($string_break_time['end_time'])) {
                        $break_end_time = Carbon::createFromFormat('H:i', $string_break_time['end_time']);
                    }

                    if ($break_start_time) {
                        if ($break_start_time->lt($work_start_time) || $break_start_time->gt($work_end_time)) {
                            $validator->errors()->add('break_time.' . $index . '.start_time', '休憩時間が不適切な値です');
                        }
                    }

                    if ($break_end_time) {
                        if ($break_end_time->gt($work_end_time)) {
                            $validator->errors()->add('break_time.' . $index . '.end_time', '休憩時間もしくは退勤時間が不適切な値です');
                        }
                    }
                }
            }
        });
    }
}

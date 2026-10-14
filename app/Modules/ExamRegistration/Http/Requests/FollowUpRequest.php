<?php

namespace App\Modules\ExamRegistration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowUpRequest extends FormRequest
{
    public function authorize()
    {
        $user = $this->user();
        return $user && ($user->isSuperAdmin() || $user->isTeacher());
    }

    public function rules()
    {
        return [
            'followed_at'      => 'nullable|date',
            'channel'          => 'nullable|string|max:50',
            'result'           => 'nullable|string|max:50',
            'notes'            => 'nullable|string|max:2000',
            'follow_up_status' => 'nullable|string|in:FOLLOW_PENDING,FOLLOW_IN_PROGRESS,FOLLOW_SUCCESS,FOLLOW_CANCELLED',
        ];
    }
}

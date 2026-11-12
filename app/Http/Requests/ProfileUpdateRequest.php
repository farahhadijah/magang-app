<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Email hanya divalidasi jika user punya sumber email (mahasiswa/dosen/staff).
     * Unique dicek di table master yang sesuai (bukan di table users).
     */
    public function rules(): array
    {
        $user = $this->user();

        // User tanpa sumber email (pimpinan, mitra, admin):
        // field email harus benar-benar tidak dikirim.
        if (! $user->canEditEmail()) {
            return [
                'email' => ['prohibited'],
            ];
        }

        $owner = $user->profileOwner();
        $table = $user->profileEmailTable();

        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique($table, 'email')->ignore($owner->id),
            ],
        ];
    }

    /**
     * Custom messages (opsional, membantu UX).
     */
    public function messages(): array
    {
        return [
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'email.lowercase'  => 'Email harus menggunakan huruf kecil.',
            'email.unique'     => 'Email ini sudah digunakan.',
            'email.prohibited' => 'Role Anda tidak memiliki akses untuk mengubah email.',
        ];
    }
}
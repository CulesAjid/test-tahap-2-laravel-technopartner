<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use App\Enums\TypeTransaction;
use App\Http\Traits\GenerateCodeTrait;
use App\Http\Traits\HasPhoneNumber;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Subdistrict;
use App\Models\Village;
use App\Services\UniqueCodeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required',
                Rule::in(collect(TypeTransaction::cases())->pluck('value')),
            ],
            'qty' => 'required|int|min:0',
            'price' => 'required|int|min:0',
            'date' => 'required|date',
        ];
    }
}

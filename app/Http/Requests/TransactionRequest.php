<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
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

        ];
    }
}

<?php

namespace App\Rules;

use App\Services\BrasilAPI\BrasilAPI;
use App\Services\BrasilAPI\Exceptions\CNPJNotFound;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidCNPJ implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $cnpj = (new BrasilAPI)->cnpj($value);

            return $cnpj->descricaoSituacaoCadastral == 'ATIVA';
        } catch (CNPJNotFound $th) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}

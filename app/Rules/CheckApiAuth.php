<?php

namespace App\Rules;

use App\Services\VetApiService;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class CheckApiAuth implements DataAwareRule, InvokableRule
{
    /**
     * All the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        try {
            if (VetApiService::authenticateUser($value, $this->data['url'])) {
                return;
            }
        } catch (\Exception $e) {
        }
        $fail('URL & API key do not pass validation. Please, check both corresponding fields');
    }
}

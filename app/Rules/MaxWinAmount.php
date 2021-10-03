<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxWinAmount implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($stake_amount)
    {
        $this->stake_amount = floatval($stake_amount);
        $this->max_win = 20000;
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
        $total_win = 0;
        foreach ($value as $item) {
            $total_win += floatval($item['odds']) * $this->stake_amount;
        }
        return $total_win <= $this->max_win;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 9;
    }
}

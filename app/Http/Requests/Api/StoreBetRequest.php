<?php

namespace App\Http\Requests\Api;

use App\Rules\MaxWinAmount;
use App\Rules\Selections;
use App\Services\SelectionsValidationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;

class StoreBetRequest extends FormRequest
{
    public function __construct()
    {
        $this->messages = new MessageBag;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function getCodeMessage($code)
    {
        switch ($code) {
            case 1:
                return "Betslip structure mismatch";
            case 2:
                return "Minimum stake amount is 0.3";
            case 3:
                return "Maximum stake amount is 10000";
            case 4:
                return "Minimum number of selections is 1";
            case 5:
                return "Maximum number of selections is 20";
            case 9:
                return "Maximum win amount is :max_win_amount";
        }
    }

    public function messages()
    {
        return [
            'required' => 1,
            'regex' => 1,
            'numeric' => 1,
            'array' => 1,
            'stake_amount.max' => 2,
            'stake_amount.min' => 3,
            'selections.min' => 4,
            'selections.min' => 5,
            'selections.*.odds.min' => -1,
            'selections.*.odds.max' => -1,
            'selections.*.id.distinct' => -1,
        ];
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'player_id' => ['required', 'numeric', 'regex:/^\d*$/'],
            'stake_amount' => ['required', 'numeric', 'min:0.3', 'max:10000', 'regex:/^\d*\.?\d{1,2}$/'],
            'selections' => ['required', 'array', 'min:1', 'max:20', new MaxWinAmount($this->stake_amount)],
            'selections.*.id' => ['required', 'numeric', 'regex:/^\d*$/', 'distinct'],
            'selections.*.odds' => ['required', 'numeric', 'min:1', 'max:10000']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($validator->errors()->toArray() as $error) {
                if ($error[0] != -1)
                    $this->messages->add('errors', ['code' => intval($error[0]), 'message' => $this->getCodeMessage($error[0])]);
            }
            if ((new SelectionsValidationService($this->selections))->validateSelections()->getMessages())
                $this->messages->add('selection', (new SelectionsValidationService($this->selections))->validateSelections()->getMessages()['selection']);
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($this->messages->getMessages(), 422));
    }
}

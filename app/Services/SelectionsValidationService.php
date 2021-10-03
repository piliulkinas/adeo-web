<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

class SelectionsValidationService
{
    public function __construct($selections)
    {
        $this->selectionMessages = new MessageBag();
        $this->selections = $selections;
    }

    public function validateSelections()
    {
        $ids = [];
        foreach ($this->selections as $item) {
            $ids[] = $item['id'];
        }
        foreach ($this->selections as $item) {
            $errors = [];
            if (1 < count(array_keys($ids, $item['id'])))
                $errors[] = ['code' => 8, 'message' => 'Duplicate selection found'];
            if ($item['odds'] > 10000) {
                $errors[] = ['code' => 7, 'message' => 'Maximum odds are 10000'];
            } elseif ($item['odds'] < 1) {
                $errors[] = ['code' => 7, 'message' => 'Minimum odds are 1'];
            }
            if (count($errors) > 0)
                $this->selectionMessages->add('selection', ['id' => $item['id'], 'errors' => $errors]);
        }
        return $this->selectionMessages;
    }
}

<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * @param $rules
     * @return mixed
     */
    public static function renderValidationMessages($rules)
    {
        $return = [];

        foreach ($rules as $field => $rule) {

            if (is_string($rule)) {
                $rule = explode("|", $rule);
            }

            foreach ($rule as $r) {
                if (is_object($r)) {
                    continue;
                }
                if (strpos($r, ":")) {
                    $r = substr($r, 0, strpos($r, ":"));
                }
                $key = $field . '.' . $r;
                $fieldWithDashes = str_replace('_', '-', $field);
                $message = 'validation-' . $fieldWithDashes . '-' . $r;
                $return[$key] = $message;
            }

        }

        return $return;
    }

}
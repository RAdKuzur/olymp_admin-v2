<?php

namespace App\Components;

class DynamicFormWidget
{
    public $attribute;
    public function render()
    {
        return view('layouts.dynamic-form-widget', [
            'attribute' => $this->attribute
        ]);
    }
}

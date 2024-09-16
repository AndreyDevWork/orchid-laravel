<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

class CustomField extends Field
{
    /**
     * @var string
     */
    protected $view = 'fields.custom';

    /**
     * @var int[]
     */
    protected $attributes = [
        'count' => 5,
        'step' => 1,
        'readonly' => false
    ];

    /**
     * @var string[]
     */
    protected $inlineAttributes = [
        'title',
        'name',
        'haveRated'
    ];
}

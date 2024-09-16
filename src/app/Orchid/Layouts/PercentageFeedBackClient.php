<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Chart;

class PercentageFeedBackClient extends Chart
{

    protected $title = 'Отзывы клиентов';

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'pie';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

    protected $target = 'percentageFeedBack';
}

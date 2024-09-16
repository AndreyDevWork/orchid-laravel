<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Chart;

class DynamicsInterviewedClients extends Chart
{

    protected $title = "Динамика опрошенных клиентов";
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'line';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

    protected $target = 'interviewedClients';
}

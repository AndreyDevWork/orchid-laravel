<?php

namespace App\Orchid\Layouts;

use App\Orchid\Filters\StatusFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class OperatorSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            StatusFilter::class,
        ];
    }
}

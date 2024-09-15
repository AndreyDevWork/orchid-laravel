<?php

namespace App\Orchid\Layouts\Client;

use App\Models\Client;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ClientListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = '';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('phone', 'Телефон')
                ->width(150)
                ->cantHide(),
            TD::make('status', 'Статус')
                ->render(function (Client $client) {
                    return $client->status === 'interviewed' ? 'Опрошен' : 'Не опрошен';
                })
                ->width(150)
                ->popover('Статус по результатам работы оператора'),
            TD::make('email', 'Почта'),
            TD::make('assessment', 'Оценка')
                ->width(150)
                ->alignRight(),
            TD::make('updated_at', 'Дата обновления')
                ->defaultHidden(),
            TD::make('created_at', 'Дата создания')
                ->defaultHidden()
        ];
    }
}

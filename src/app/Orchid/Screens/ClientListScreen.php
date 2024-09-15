<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ClientListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'clients' => Client::paginate(5)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Клиенты';
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return "Список клиентов";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('clients', [
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
            ])
        ];
    }
}

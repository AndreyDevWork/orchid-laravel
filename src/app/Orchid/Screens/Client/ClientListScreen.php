<?php

namespace App\Orchid\Screens\Client;

use App\Models\Client;
use App\Orchid\Layouts\Client\ClientListTable;

use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

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
        return [
            ModalToggle::make('Создать клиента')
                ->modal('createClient')
                ->method('create')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ClientListTable::class,
            Layout::modal('createClient', Layout::rows([
                Input::make('phone')->required()->title('Телефон'),
                Input::make('name')->required()->title('Имя'),
                Input::make('last_name')->title('Фамилия'),
                Input::make('email')->type('email')->title('E-mail'),
                DateTimer::make('birthday')->format('Y-m-d')->title('Дата рождения')
            ]))
                ->title('Создать клиента')
                ->applyButton('Создать')
                ->closeButton('Отмена')
        ];
    }

    public function create()
    {
        Toast::info("Клиент успешно создан");
    }
}

<?php

namespace App\Orchid\Screens\Client;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Service;
use App\Orchid\Layouts\Client\ClientListTable;

use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
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
            'clients' => Client::filters()->defaultSort('status', 'asc')->paginate(10)
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
                Group::make([
                    Input::make('name')->required()->title('Имя'),
                    Input::make('last_name')->title('Фамилия'),
                ]),
                Input::make('phone')->required()->title('Телефон')->mask('+7(999)-999-99-99'),
                Input::make('email')->type('email')->title('E-mail'),
                DateTimer::make('birthday')->format('Y-m-d')->title('Дата рождения'),
                Relation::make('service_id')->fromModel(Service::class, 'name')->title('Услуга')
            ]))
                ->title('Создать клиента')
                ->applyButton('Создать')
                ->closeButton('Отмена')
        ];
    }

    /**
     * @param ClientRequest $request
     * @return void
     */
    public function create(ClientRequest $request)
    {

        Client::create(array_merge(
            $request->validated(), [
            'status' => 'interviewed'
        ]));
        Toast::info("Клиент успешно создан");
    }
}

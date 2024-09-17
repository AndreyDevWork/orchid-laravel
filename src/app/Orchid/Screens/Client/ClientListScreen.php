<?php

namespace App\Orchid\Screens\Client;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Service;
use App\Orchid\Filters\StatusFilter;
use App\Orchid\Layouts\Client\ClientListTable;

use App\Orchid\Layouts\Client\CreateOrUpdateClient;
use App\Orchid\Layouts\OperatorSelection;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ClientListScreen extends Screen
{

    public function permission(): ?iterable
    {
        return [
            'platform.clients'
        ];
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'clients' => Client::filtersApplySelection(OperatorSelection::class)->filters()->defaultSort('id', 'desc')->paginate(10)
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
                ->method('createOrUpdateClient'),
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
            OperatorSelection::class,
            ClientListTable::class,
            Layout::modal('createClient', CreateOrUpdateClient::class)
                ->title('Создать клиента')
                ->applyButton('Создать')
                ->closeButton('Отмена'),
            Layout::modal('editClient', CreateOrUpdateClient::class)->async('asyncGetClient')
        ];
    }

    /**
     * @param Client $client
     * @return Client[]
     */
    public function asyncGetClient(Client $client): array
    {
        $client->load('attachment');
        return [
            'client' => $client
        ];
    }

    /**
     * @param ClientRequest $request
     * @return void
     */
    public function createOrUpdateClient(ClientRequest $request): void
    {
        $clientId = $request->input('client.id');
        $client = Client::updateOrCreate([
            'id' => $clientId
        ], array_merge($request->validated()['client'], [
            'status' => 'interviewed'
        ]) );

        $client->attachment()->syncWithoutDetaching(
            $request->input('client.attachment', [])
        );
        is_null($clientId) ? Toast::info("Клиент успешно создан") : Toast::info('Клиент успешно обновлен');
    }
}

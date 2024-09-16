<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Orchid\Layouts\DynamicsInterviewedClients;
use App\Orchid\Layouts\PercentageFeedBackClient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsAndReportsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'percentageFeedBack' => Client::whereNotNull('assessment')->countForGroup('assessment')->toChart(),
            'interviewedClients' => [
                Client::whereNotNull('assessment')->countByDays(null, null, 'updated_at')->toChart('Опрошенные клиенты'),
                Client::countByDays()->toChart('Новые клиенты')
            ]
        ];
    }

    public function permission(): array
    {
        return [
            'platform.analytics',
            'platform.reports'
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Аналитика и отчеты';
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return "Аналитика и отчеты для руководителя";
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
            Layout::columns([
                PercentageFeedBackClient::class,
                DynamicsInterviewedClients::class
            ]),
            Layout::tabs([
                'Загрузка новых телефонов' => [
                    Layout::rows([
                        Input::make('file')->type('file')->required()->help('Необходимо загрузить csv с телефонами')->title('Файл с телефонами в формате CSV'),
                        Button::make('Загрузить')->confirm('Вы уверены?')->type(Color::PRIMARY)->method('importClientByPhone')
                    ])
                ],
                'Отчет по клиентам' => [
                    Layout::rows([
                        Button::make('Скачать')->method('exportClients')->rawClick()
                    ])
                ]
            ])
        ];
    }

    /**
     * @param Request $request
     * @return void
     */
    public function importClientByPhone(Request $request):void
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt']
        ]);

        $phones = array_map(function ($rawPhone) {
            return array_shift($rawPhone);
        }, array_map('str_getcsv', file($request->file('file')->path())));

        $foundPhones = Client::whereIn('phone', $phones)->get();

        if($foundPhones->count() > 0) {
            throw ValidationException::withMessages([
                'file' => 'Номера телефонов, которые есть в системе' . PHP_EOL . $foundPhones->implode('phone', '.')
            ]);
        }

        foreach ($phones as $phone) {
            Client::create([
               'phone' => $phone
            ]);
        }

        Toast::info('Новые клиенты успешно загружены');
    }

    /**
     * @return StreamedResponse
     */
    public function exportClients(): StreamedResponse
    {
        $clients = Client::with('service')->get(['phone', 'email', 'status', 'assessment', 'service_id']);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clients.csv'
        ];
        $columns = [
            'Телефон', 'email', 'Статус', 'Оценка', 'Сервис'
        ];
        $callback = function () use($clients, $columns) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $columns);

            foreach ($clients as $client) {
                fputcsv($stream, [
                    'Телефон' => $client->phone,
                    'Email' => $client->email,
                    'Статус' => Client::STATUS[$client->status],
                    'Оценка' => $client->assessment,
                    'Сервис' => $client->service?->name
                ]);
            }
            fclose($stream);
        };

        return response()->stream($callback, 200, $headers);
    }
}

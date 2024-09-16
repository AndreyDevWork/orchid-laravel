<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Orchid\Layouts\DynamicsInterviewedClients;
use App\Orchid\Layouts\PercentageFeedBackClient;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

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
            ])
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make("Клиенты")
                ->icon("user")
                ->route("platform.clients")
                ->permission('platform.clients'),

            Menu::make("Аналитика и отчеты")
                ->icon('chart')
                ->route('platform.analyticsAndReports')
                ->canSee(
                    \Auth::user()->hasAccess('platform.analytics') &&
                    \Auth::user()->hasAccess('platform.reports')
                ),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group('Отзывы клиентов')
                ->addPermission('platform.clients', 'Клиенты')
                ->addPermission('platform.analytics', 'Аналитика')
                ->addPermission('platform.reports', 'Отчеты')

        ];
    }
}

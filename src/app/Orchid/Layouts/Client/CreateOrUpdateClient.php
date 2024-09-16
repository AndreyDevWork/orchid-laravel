<?php

namespace App\Orchid\Layouts\Client;

use App\Models\Service;
use App\Orchid\Fields\CustomField;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CreateOrUpdateClient extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $isClientExists = is_null($this->query->getContent('client')) === false;

        return [
            Input::make('client.id')->hidden(),
            Group::make([
                Input::make('client.name')->required()->title('Имя'),
                Input::make('client.last_name')->title('Фамилия'),
            ]),
            Input::make('client.phone')->required()->title('Телефон')->mask('+7(999)-999-99-99')->disabled($isClientExists),
            Input::make('client.email')->type('email')->title('E-mail'),
            DateTimer::make('client.birthday')->format('Y-m-d')->title('Дата рождения'),
            Relation::make('client.service_id')->fromModel(Service::class, 'name')->title('Услуга'),
            Select::make('client.assessment')->required()->options([
                'Отлично' => 'Отлично',
                'Хорошо' => 'Хорошо',
                'Удовлетворительно' => 'Удовлетворительно',
                'Плохой' => 'Плохой'
            ])->help('Реакция на оказанную услугу')->empty('Не известно', 'Не известно'),
            CustomField::make()->count(999)->title('hello custom field i am title')
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Propaganistas\LaravelPhone\Concerns\PhoneNumberCountry;

class Client extends Model
{
    use Chartable;
    use AsSource;
    use Filterable;
    use Attachable;

    protected $fillable = ['phone', 'name', 'last_name', 'status', 'email', 'birthday', 'service_id', 'assessment', 'invoice_id'];

    protected $allowedSorts = [
        'status'
    ];
    protected $allowedFilters = [
        'phone' => Where::class
    ];

    public const STATUS = [
        'interviewed' => 'Опрошен',
        'not_interviewed' => 'Не опрошен'
    ];

    /**
     * @return array
     */
    protected function casts(): array
    {
        return [
            'id' => 'int'
        ];
    }

    /**
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Attachment::class, 'id', 'invoice_id');
    }
}

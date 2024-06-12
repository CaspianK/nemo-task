<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $airport_id
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Airport $airport
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName query()
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName whereAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AirportName whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AirportName extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ru',
        'name_en'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class);
    }
}

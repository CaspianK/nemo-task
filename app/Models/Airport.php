<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 *
 *
 * @property int $id
 * @property int $city_id
 * @property string $code
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City $city
 * @property-read \App\Models\AirportName|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|Airport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Airport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Airport query()
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Airport extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'city_id',
        'code',
        'name_ru',
        'name_en',
        'latitude',
        'longitude'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function name(): HasOne
    {
        return $this->hasOne(AirportName::class);
    }
}

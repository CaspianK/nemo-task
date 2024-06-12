<?php

namespace App\Http\Controllers;

use App\Helpers\Elastic\AirportElastic;
use App\Http\Requests\Airport\SearchRequest;
use App\Models\Airport;
use Illuminate\Http\JsonResponse;

class AirportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/search",
     *     summary="Поиск аэропортов по части названия",
     *     tags={"Airports"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         description="Поисковый запрос",
     *         @OA\Schema(
     *             type="string",
     *             minLength=1,
     *             maxLength=255
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список аэропортов",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=5374
     *                 ),
     *                 @OA\Property(
     *                     property="code",
     *                     type="string",
     *                     example="NQZ"
     *                 ),
     *                 @OA\Property(
     *                     property="city_name_ru",
     *                     type="string",
     *                     example="Астана"
     *                 ),
     *                 @OA\Property(
     *                     property="city_name_en",
     *                     type="string",
     *                     example="Astana"
     *                 ),
     *                 @OA\Property(
     *                     property="name_ru",
     *                     type="string",
     *                     nullable=true,
     *                     example=null
     *                 ),
     *                 @OA\Property(
     *                     property="name_en",
     *                     type="string",
     *                     nullable=true,
     *                     example=null
     *                 ),
     *                 @OA\Property(
     *                     property="latitude",
     *                     type="string",
     *                     example="51.02222"
     *                 ),
     *                 @OA\Property(
     *                     property="longitude",
     *                     type="string",
     *                     example="71.46694"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *     )
     * )
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $query = $request->get('query');

        if (strlen($query) < 3) {
            $results = Airport::suggest(AirportElastic::index(), $query);
        } else {
            $cacheKey = 'airports_search_' . $query;

            $results = cache()->remember($cacheKey, 60 * 5, function () use ($query) {
                return Airport::suggest(AirportElastic::index(), $query);
            });
        }

        return response()->json($results);
    }
}

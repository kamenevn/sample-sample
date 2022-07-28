<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Class IndexController.
 */
final class IndexController extends Controller
{
    /**
     * @OA\Get (
     *      path="/",
     *      tags={"developers"},
     *      summary="Базовый",
     *      description="Получить Hello world",
     *      @OA\Response(response=200, description="Hello World"),
     *      @OA\Response(response=400, description="Что то не так")
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->response('Запрос успешен', 'Hello World');
    }
}

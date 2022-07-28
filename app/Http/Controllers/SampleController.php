<?php

namespace App\Http\Controllers;

use App\Exceptions\NoticeException;
use App\Models\Sample;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SampleController extends Controller
{
    /**
     * @OA\Get (
     *     path="/samples/",
     *     operationId="all",
     *     tags={"production", "samples"},
     *     summary="Список",
     *     description="Список",
     *     parameters={},
     *     @OA\Response(response=200, description="Список"),
     *     @OA\Response(response=400, description="Ошибка"),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(): JsonResponse
    {
        $samples = Sample::select(Sample::ACTIVE_SELECT);

        return $this->response('Список', $samples->get());
    }

    /**
     * @OA\Get (
     *     path="/samples/active",
     *     tags={"production", "samples"},
     *     summary="Список активных",
     *     description="Список активных",
     *     parameters={},
     *     @OA\Response(response=200, description="Список активных"),
     *     @OA\Response(response=400, description="Ошибка"),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active(): JsonResponse
    {
        $result = Cache::tags(['samples'])->rememberForever(Sample::SAMPLES_ACTIVE_CACHE_KEY, function () {
            $samples = Sample::select(Sample::ACTIVE_SELECT)->active();

            return $samples->get();
        });

        return $this->response('Список активных', $result);
    }

    /**
     * @OA\Get (
     *     path="/sample/{sampleId}",
     *     operationId="show",
     *     tags={"production", "sample"},
     *     summary="Данные по sample",
     *     description="Данные по sample",
     *     parameters={
     *          {
     *              "name": "sampleId",
     *              "in": "path",
     *              "description": "ID sample",
     *              "required": true,
     *              "type": "integer"
     *          }
     *     },
     *     @OA\Response(response=200, description="Данные по sample"),
     *     @OA\Response(response=400, description="Ошибка"),
     * )
     * @param int $sampleId
     * @return \Illuminate\Http\JsonResponse
     * @throws NoticeException
     */
    public function show(int $sampleId): JsonResponse
    {
        $key = Sample::SAMPLE_CACHE_KEY.$sampleId;

        $result = Cache::tags(['sample'])->rememberForever($key, function () use ($sampleId) {
            return Sample::select(Sample::ACTIVE_SELECT)->active()->where('id', $sampleId)->first();
        });

        if (!$result) {
            throw new NoticeException('Sample с таким id не существует', 404);
        }

        return $this->response('Данные по sample', $result);
    }

    /**
     * @OA\Post (
     *      path="/sample/add",
     *      tags={"production", "add"},
     *      summary="Создать",
     *      description="Создать",
     *      parameters={
     *        {"name":"title","in":"body","type":"string","required":true,"description":"Заголовок"},
     *        {"name":"icon","in":"body","type":"string","required":true,"description":"Иконка"},
     *        {"name":"url","in":"body","type":"string","required":true,"description":"Ссылка"},
     *      },
     *      @OA\Response(response=200, description="Создать"),
     *      @OA\Response(response=400, description="Ошибка")
     * )
     *
     * @param Request $request
     * @param Sample $sample
     * @return JsonResponse
     * @throws NoticeException
     */
    public function create(Request $request, Sample $sample): JsonResponse
    {
        $data = $request->all();

        $sample->createValidation($data);

        return $this->response('Sample успешно создан', $sample->modify($data));
    }

    /**
     * @OA\Put (
     *      path="/sample/edit/{id}",
     *      tags={"production", "sample"},
     *      summary="Изменить",
     *      description="Изменить",
     *      parameters={
     *        {"name":"id","in":"query","type":"integer","required":true,"description":"ID sample"},
     *        {"name":"title","in":"body","type":"string","required":true,"description":"Заголовок"},
     *        {"name":"icon","in":"body","type":"string","required":true,"description":"Иконка"},
     *        {"name":"url","in":"body","type":"string","required":true,"description":"Ссылка"},
     *      },
     *      @OA\Response(response=200, description="Изменить"),
     *      @OA\Response(response=400, description="Ошибка")
     * )
     *
     * @param integer $id
     * @param Request $request
     * @param Sample $sample
     * @return JsonResponse
     * @throws NoticeException
     */
    public function edit(int $id, Request $request, Sample $sample): JsonResponse
    {
        $data = $request->all();

        $sample->modifyValidation($data);

        return $this->response('Sample успешно изменен', $sample->modify($data, $id));
    }
}

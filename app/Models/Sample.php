<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\NoticeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Traits\SampleRequestTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * App\Models\Sample.
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $url
 * @mixin \Eloquent
 */

final class Sample extends Model
{
    use SampleRequestTrait;

    public const SAMPLE_CACHE_KEY = 'sample_info_';

    public const SAMPLES_ACTIVE_CACHE_KEY = 'samples_active';

    public const ACTIVE_SELECT = [
        'id', 'title', 'icon', 'url'
    ];

    protected $fillable = ['title', 'icon', 'url'];

    /**
     * Только активные.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', '=', true);
    }

    /**
     * @param array $data
     * @return void
     * @throws NoticeException
     */
    public function createValidation(array $data): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'icon' => 'required|url',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new NoticeException($validator->errors()->first(), 103);
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws NoticeException
     */
    public function modifyValidation(array $data): void
    {
        $validator = Validator::make($data, [
            'id' => 'required|int',
            'title' => 'required|string',
            'icon' => 'required|url',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new NoticeException($validator->errors()->first(), 103);
        }
    }

    /**
     * @param array $data
     * @param int|null $id
     * @return Sample
     * @throws NoticeException
     */
    public function modify(array $data, int $id = null): self
    {
        $sampleDataFromRequest = $this->getSampleRequest();

        // some use $sampleDataFromRequest

        try {
            DB::beginTransaction();

            /**
             * @var self $sample
             */
            $sample = ($id ? self::where('id', $id)->first() : new self());

            if (!$sample) {
                throw new NoticeException('Sample не найдена', 404);
            }

            $sample->fill($data);

            if (!$sample->save()) {
                throw new NoticeException('Sample не удалось сохранить', 103);
            }

            DB::commit();

            return $sample;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new NoticeException($e->getMessage(), 103);
        }
    }
}

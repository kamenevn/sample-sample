<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="Микросервис sample",
 *      version="3.0",
 *      description="Микросервис sample",
 *      @OA\Contact(
 *          email="sample@sample"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * Class Controller
 */
abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, ResponseTrait;
}

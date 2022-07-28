<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    protected array $dontReportSentry = [
        NotFoundHttpException::class,
    ];


    /**
     * Report or log an exception.
     *
     * @param Throwable $e
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $e)
    {
        if ($this->shouldReportSentry($e) && app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }

    protected function shouldReportSentry(Throwable $e): bool
    {
        return null === Arr::first(
            $this->dontReportSentry,
            fn (string $type) => $e instanceof $type
        );
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $e)
    {
        // Modify exception code if it is zero, because it should not happen that in case of exception error code is zero
        if ($e->getCode() === 0) {
            $errorCode = 100;
        } else {
            $errorCode = $e->getCode();
        }
        /** @psalm-var int $errorCode */
        switch (true) {
            case $e instanceof ModelNotFoundException:
                return $this->response('Record not found', null, 404, 'error');
            case $e instanceof NotFoundHttpException:
                return $this->response('Not found', null, 404, 'error');

            // Validatation exceptions
            case $e instanceof ValidationException:
                /** @var Validator $validator */
                $validator = $e->validator;

                $messageArray = collect($validator->messages()->all());

                return $this->response($messageArray->implode(', '), null, 101, 'error');

            // If exception occures which is not handled above
            default:
                // In production just rewrite any exception to server error 500
                if (app()->isProduction()) {
                    return $this->response('Server error', null, 500, 'error');
                }
                // In any other environment render exception as it is
                return $this->response($e->getMessage(), null, $errorCode, 'error');
        }
    }
}

<?php

namespace App\Shared\Infrastructure\Exceptions;

use Error;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (! $request->is('api/*')) {
            return parent::render($request, $exception);
        }

        if (empty($request->user())) {
            return response()->json([
                'success' => false,
                'message' => 'API TOKEN não encontrado ou você não está logado.',
            ]);
        }

        if ($exception instanceof Exception ||
            $exception instanceof Error) {

            $response = [
                'success' => false,
                'message' => $exception->getMessage(),
            ];

            if (App::environment('local', 'development')) {
                // condicional adicionada para retornar informações da linha e arquivo
                // pode facilitar encontrar o problema mais rápido (ou não)
                $response['exception'] = [
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'trace' => $exception->getTraceAsString(),
                ];
            }

            return response()->json($response);
        }

        return parent::render($request, $exception);
    }
}

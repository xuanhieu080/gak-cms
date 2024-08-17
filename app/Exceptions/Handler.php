<?php

namespace App\Exceptions;

use App\Jobs\Logging;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $ex) {
            $errorCode = $ex->getCode();
            $errorCode = empty($errorCode) ? Response::HTTP_BAD_REQUEST : $errorCode;

            //        if (env('APP_DEBUG', false) == true) {
            //            $request = Request::capture();
            //            $param = $request->all();
            //            $data = [
            //                'server'     => ETC_POS::urlBase(),
            //                'time'       => date("Y-m-d H:i:s", time()),
            //
            //                'param'      => json_encode($param),
            //                'file'       => $ex->getFile(),
            //                'line'       => $ex->getLine(),
            //                'error'      => $ex->getMessage(),
            //            ];
            //
            //            //Write Log
            //        }
            $user_id = 0;

            $request = Request::capture();
            $param = $request->all();
            $requestHost = parse_url($request->headers->get('origin'), PHP_URL_HOST);
            $data = [
                'action'  => 'ERROR',
                //            'browser' => "Parent: $parent - Platform: $platform - Browser: $browser",
                'link'    => url()->current(),
                'server'  => env('app_url'),
                'host'    => $requestHost,
                'time'    => Carbon::now()->toString(),
                'user_id' => $user_id,
                'param'   => json_encode($param),
                'file'    => $ex->getFile(),
                'line'    => $ex->getLine(),
                'error'   => $ex->getMessage(),
            ];
            Logging::dispatch($data)->afterResponse();
        });
    }
}

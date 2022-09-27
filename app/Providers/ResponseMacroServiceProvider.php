<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Response::macro('success', function ($data, $status = 200, $code = null){
            $code = is_null($code) ? $status : $code;
            return Response::json([
                'success' => true,
                'code' => $code,
                'data' => $data,
                'errors' => []
            ]);
        });

        Response::macro('error', function ($errors, $status = 422, $code = null){
            $code = is_null($code) ? $status : $code;
            return Response::json([
                'code' => $code,
                'data' => [],
                'errors' => $errors
            ]);
        });
    }
}

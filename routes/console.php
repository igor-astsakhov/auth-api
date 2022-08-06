<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

Artisan::command('test', function () {
    // $this->comment(Inspiring::quote());
    Http::macro( 'authapi', function () {
        return Http::withHeaders([
        'Accept' => 'application/json',
        'Content-type' => 'application/json',
        ])->baseUrl( 'http://auth-api.test' );
    });

    $strUri = '/api/user';
    // $response = Http::authapi()->get( $strUri );
    // $strUri = '/api/logout';
    // $strUri = '/api/login';
    // $strUri = '/api/register';
    // $response = Http::get( $strHost . $strUri );

    $arrParams = [
        // 'name' => 'Another with token tsk XX',
        // 'description' => 'New task creating - ' . uniqid(),

        // 'name' => 'Igor Astakhov',
        'password' => 'Zebra12345',

        // 'password_confirmation' => 'Zebra12345',
        'email' => 'astahov@gmail.com',
        'role' => 'user:r',
    ];
    // $response = Http::authapi()->post( $strUri, $arrParams );

    // $strToken = '3|QwbZ8vjQwvXxqKEyPbUKKN5PWaMXAlAvpowqFaK4';
    $strToken = '4|8qPIr5AHx5KzqA0M14L40Z3mG9yFcFvKngkQZ2KT';
    $response = Http::authapi()->withToken( $strToken )->get( $strUri );
    // $response = Http::authapi()->withToken( $strToken )->delete( $strUri );

    // $response = Http::authapi()->post( $strUri, $arrParams );
    // $response = Http::withToken( $strToken )->post( $strHost . $strUri );

    // $response = Http::put( $strHost . $strUri, $arrParams );
    // $response = Http::delete( $strHost . $strUri );
    $this->comment( $response->status() . PHP_EOL . $response->body() );
})->purpose('Test the api via requests');

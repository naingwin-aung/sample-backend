<?php

function success(array $data = [], string $message = 'Request was successful', int $statusCode = 200)
{
    return response()->json([
        'success' => true,
        'message' => $message,
        'data'    => $data,
    ], $statusCode);
}

function error(string $message = 'Something happened please try again later.', int $statusCode = 500)
{
    return response()->json([
        'success' => false,
        'message' => config('app.debug') ? $message : 'Something happened please try again later.',
    ], $statusCode);
}
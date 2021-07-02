<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{

    /**
     * @param array|null $data
     * @param int $code
     * @param string $message
     * @param string $errorCode
     * @param array $errors
     * @return JsonResponse
     */
    protected function apiResponse(array $data = null, int $code = 200,
                                   string $message = '', string $errorCode = '',
                                   array $errors = []): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'errorCode'=> $errorCode,
            'errors'=> $errors,
        ], $code);
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    protected function apiSuccessResponse($data = null): JsonResponse
    {
        $data = empty($data) ? [] : $data;
        return response()->json([
            'data' => $data,
            'message' => '',
            'errorCode'=> '',
            'errors'=> [],
        ]);
    }

    /**
     * @param null $data
     * @param int $code
     * @param string $message
     * @param string $errorCode
     * @param array $errors
     * @param array $extra
     * @return JsonResponse
     */
    protected function apiPaginationResponse($data = null, int $code = 200,
                                             string $message = '', string $errorCode = '',
                                             array $errors = [], array $extra = []): JsonResponse
    {
        $result = ['message' => $message,
            'errorCode'=> $errorCode,
            'errors'=> $errors,
        ];
        return response()->json(array_merge($data, $result, $extra), $code);
    }

    /**
     * API Error Response
     * @param array $data
     * @param string $message
     * @param string $errorCode
     * @param int $code
     * @param array $errors
     * @return JsonResponse
     */
    protected function apiErrorResponse(array $data = [],
                                        string $message = '', string $errorCode = '',
                                        int $code = Response::HTTP_BAD_REQUEST,
                                        array $errors = []): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'errorCode' => $errorCode,
            'errors' => $errors,
        ], $code);
    }
}

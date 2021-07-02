<?php

namespace App\Http\Controllers\Surveys;

use App\Http\Controllers\ApiRequestController;
use App\Models\Survey\Survey;
use App\Services\ErrorLogs\ErrorLogsService;
use App\Services\Surveys\SurveyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Exceptions\RestException;

class SurveyController extends ApiRequestController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $surveys = new SurveyService();
            $surveys = $surveys->storeSurvey($request);
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getCode(), $exception->getMessage());
        }
        return $this->apiSuccessResponse($surveys);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required|max:100',
                'type' => 'required|integer|between:1,2',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse([], $validator->errors()->first() ?? '', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $surveys = Survey::create($data);
        } catch (RestException $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($surveys);
    }

    /**
     * Display the specified resource.
     *
     * @param Survey $survey
     * @return JsonResponse
     */
    public function show(Survey $survey): JsonResponse
    {
        return $this->apiSuccessResponse($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Survey $survey
     * @return JsonResponse
     */
    public function update(Request $request, Survey $survey): JsonResponse
    {
        try {
            $data = array_filter($request->all());
            $validator = Validator::make($data, [
                'name' => 'required|max:100',
                'type' => 'required|integer|between:1,2',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse([], $validator->errors()->first() ?? '', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $survey->update($data);
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Survey $survey
     * @return JsonResponse
     */
    public function destroy(Survey $survey): JsonResponse
    {
        try {
            $surveys = $survey->delete();
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($surveys);
    }
}

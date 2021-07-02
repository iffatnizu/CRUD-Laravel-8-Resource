<?php

namespace App\Http\Controllers\Surveys;

use App\Http\Controllers\ApiRequestController;
use App\Models\Survey\SurveyQuestion;
use App\Services\ErrorLogs\ErrorLogsService;
use App\Services\Surveys\SurveyQuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Exceptions\RestException;
use Exception;

class SurveyQuestionController extends ApiRequestController
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
            $questions = new SurveyQuestionService();
            $questions = $questions->storeSurveyQuestion($request);
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiResponse([], $exception->getCode(), $exception->getMessage());
        }
        return $this->apiSuccessResponse($questions);
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
                'question' => 'required',
                'survey_id' => 'required|exists:surveys,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse([], $validator->errors()->first() ?? '', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $questions = SurveyQuestion::create($data);
        } catch (RestException $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($questions);
    }

    /**
     * Display the specified resource.
     *
     * @param SurveyQuestion $question
     * @return JsonResponse
     */
    public function show(SurveyQuestion $question): JsonResponse
    {
        return $this->apiSuccessResponse($question);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SurveyQuestion $question
     * @return JsonResponse
     */
    public function update(Request $request, SurveyQuestion $question): JsonResponse
    {
        try {
            $data = array_filter($request->all());
            $validator = Validator::make($data, [
                'question' => 'required',
                'survey_id' => 'required|exists:surveys,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse([], $validator->errors()->first() ?? '', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $question->update($data);
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($question);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SurveyQuestion $question
     * @return JsonResponse
     */
    public function destroy(SurveyQuestion $question): JsonResponse
    {
        try {
            $questions = $question->delete();
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($questions);
    }
}

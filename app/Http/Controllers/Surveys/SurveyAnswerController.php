<?php

namespace App\Http\Controllers\Surveys;

use App\Http\Controllers\ApiRequestController;
use App\Models\Survey\SurveyAnswer;
use App\Services\ErrorLogs\ErrorLogsService;
use App\Services\Surveys\SurveyAnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Exceptions\RestException;
use Exception;

class SurveyAnswerController extends ApiRequestController
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
            $answers = new SurveyAnswerService();
            $answers = $answers->storeSurveyAnswer($request);
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiResponse([], $exception->getCode(), $exception->getMessage());
        }
        return $this->apiSuccessResponse($answers);

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
                'answer' => 'required',
                'call_sid' => 'required',
                'worker_sid' => 'required',
                'survey_question_id' => 'required|exists:survey_questions,id',
                'call_id' => 'required|exists:calls,id',
                'worker_id' => 'required|exists:workers,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse([], $validator->errors()->first() ?? '', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $answers = SurveyAnswer::create($data);
        } catch (RestException $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($answers);
    }

    /**
     * Display the specified resource.
     *
     * @param SurveyAnswer $answer
     * @return JsonResponse
     */
    public function show(SurveyAnswer $answer): JsonResponse
    {
        return $this->apiSuccessResponse($answer);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SurveyAnswer $answer
     * @return JsonResponse
     */
    public function update(Request $request, SurveyAnswer $answer): JsonResponse
    {
        try {
            $data = array_filter($request->all());
            $validator = Validator::make($data, [
                'answer' => 'required',
                'call_sid' => 'required',
                'worker_sid' => 'required',
                'survey_question_id' => 'required|exists:survey_questions,id',
                'call_id' => 'required|exists:calls,id',
                'worker_id' => 'required|exists:workers,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse([], $validator->errors()->first() ?? '', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $answer->update($data);
        } catch (Exception $exception) {

            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($answer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SurveyAnswer $answer
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(SurveyAnswer $answer): JsonResponse
    {
        try {
            $answers = $answer->delete();
        } catch (Exception $exception) {
            ErrorLogsService::store($exception, __METHOD__, true, $request->callSid ?? null);
            return $this->apiErrorResponse([], $exception->getMessage(), $exception->getCode());
        }
        return $this->apiSuccessResponse($answers);
    }
}

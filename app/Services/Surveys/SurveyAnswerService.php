<?php


namespace App\Services\Surveys;

use App\Models\Survey\SurveyAnswer;
use Illuminate\Http\Request;

class SurveyAnswerService
{
    /**
     * Return request dates or first of the month until now range
     * @param Request $request
     * @return mixed
     */
    public static function storeSurveyAnswer(Request $request)
    {
        $id = $request->input('id');
        $callSid = $request->input('call_sid');
        $workerSid = $request->input('worker_sid');
        $surveyQuestionId = $request->input('survey_question_id');
        $callId = $request->input('call_id');
        $workerId = $request->input('worker_id');

        $surveyAnswers = SurveyAnswer::when($id, function ($surveyAnswers, $id) {
            return $surveyAnswers->where('id', $id);
        })->when($callSid, function ($surveyAnswers, $callSid) {
            return $surveyAnswers->where('call_sid', $callSid);
        })->when($workerSid, function ($surveyAnswers, $workerSid) {
            return $surveyAnswers->where('worker_sid', $workerSid);
        })->when($surveyQuestionId, function ($surveyAnswers, $surveyQuestionId) {
            return $surveyAnswers->where('survey_question_id', $surveyQuestionId);
        })->when($callId, function ($surveyAnswers, $callId) {
            return $surveyAnswers->where('worker_id', $callId);
        })->when($workerId, function ($surveyAnswers, $workerId) {
            return $surveyAnswers->where('worker_id', $workerId);
        });
        return $surveyAnswers->get();
    }
}

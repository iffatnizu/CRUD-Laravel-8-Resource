<?php


namespace App\Services\Surveys;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionService
{
    /**
     * Return request dates or first of the month until now range
     * @param Request $request
     * @return mixed
     */
    public static function storeSurveyQuestion(Request $request)
    {
        $id = $request->input('id');
        $surveyId = $request->input('survey_id');
        $question = SurveyQuestion::when($id, function ($question, $id) {
            return $question->where('id', $id);
        })->when($surveyId, function ($question, $surveyId) {
            return $question->where('survey_id', $surveyId);
        });
        return $question->get();
    }
}

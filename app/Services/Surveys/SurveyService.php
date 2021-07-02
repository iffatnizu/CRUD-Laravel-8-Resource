<?php


namespace App\Services\Surveys;

use App\Models\Survey\Survey;
use Illuminate\Http\Request;

class SurveyService
{
    /**
     * Return request dates or first of the month until now range
     * @param Request $request
     * @return mixed
     */
    public static function storeSurvey(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $surveys = Survey::when($id, function ($surveys, $id) {
            return $surveys->where('id', $id);
        })->when($type, function ($surveys, $type) {
            return $surveys->where('type', $type);
        });
        return $surveys->get();
    }
}

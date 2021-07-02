<?php

namespace App\Models\Survey;

use App\Models\Calls\Call;
use App\Models\Workers\Worker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyAnswer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'survey_answers';

    protected $fillable = [
        'survey_question_id',
        'call_id',
        'worker_id',
        'answer',
        'call_sid',
        'worker_sid',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'question' => 'string',
        'answer' => 'string',
        'call_sid' => 'string',
        'worker_sid' => 'string',
    ];

    /**
     * @return BelongsTo
     */
    public function surveyQuestion(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    /**
     * @return BelongsTo
     */
    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }
}

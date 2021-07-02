<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'survey_questions';

    protected $fillable = [
        'question',
        'survey_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'question' => 'string',
    ];

    /**
     * @return BelongsTo
     */
    public function surveys(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}

<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'surveys';

    protected $with = ['surveyQuestions'];

    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'type' => 'integer',
    ];

    /**
     * @return HasMany
     */
    public function surveyQuestions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }


}

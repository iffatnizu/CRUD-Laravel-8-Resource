<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('survey_answers')) {
            Schema::create('survey_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('survey_question_id')->index();
                $table->unsignedBigInteger('call_id')->index();
                $table->unsignedBigInteger('worker_id')->index();
                $table->string('call_sid')->index();
                $table->string('worker_sid')->index();
                $table->string('answer');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('survey_answers')) {
            Schema::dropIfExists('survey_answers');
        }
    }
}

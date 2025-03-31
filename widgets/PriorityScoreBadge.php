<?php

namespace app\widgets;

use app\helpers\App;
use app\models\SocialPensioner;

class PriorityScoreBadge extends BaseWidget
{
    public $model;

    public function run()
    {
        if ($this->model == null) {
           return ;
        }
        return $this->render('priority-score-badge', [
            'model' => $this->model,
            'totalScore' => App::formatter('asNumber', $this->model->priorityScore),
            'PWD_SCORE' => SocialPensioner::PWD_SCORE,
            'SENIOR_SCORE' => SocialPensioner::SENIOR_SCORE,
            'SOLO_PARENT_SCORE' => SocialPensioner::SOLO_PARENT_SCORE,
            'SOLO_MEMBER_SCORE' => SocialPensioner::SOLO_MEMBER_SCORE,
            'ACCESSIBILITY_SCORE' => SocialPensioner::ACCESSIBILITY_SCORE,
        ]);
    }
}

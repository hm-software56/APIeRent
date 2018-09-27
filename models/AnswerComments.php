<?php

namespace app\models;

use Yii;
use \app\models\base\AnswerComments as BaseAnswerComments;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "answer_comments".
 */
class AnswerComments extends BaseAnswerComments
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}

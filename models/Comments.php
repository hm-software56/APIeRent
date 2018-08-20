<?php

namespace app\models;

use Yii;
use \app\models\base\Comments as BaseComments;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comments".
 */
class Comments extends BaseComments
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

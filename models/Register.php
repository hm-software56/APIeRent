<?php

namespace app\models;

use app\models\base\Register as BaseRegister;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "register".
 */
class Register extends BaseRegister
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['phone', 'unique'],
                ['email', 'unique'],
                ['email', 'email'],
            ]
        );
    }
}

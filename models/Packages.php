<?php

namespace app\models;

use Yii;
use \app\models\base\Packages as BasePackages;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "packages".
 */
class Packages extends BasePackages
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

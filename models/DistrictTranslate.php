<?php

namespace app\models;

use Yii;
use \app\models\base\DistrictTranslate as BaseDistrictTranslate;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "district_translate".
 */
class DistrictTranslate extends BaseDistrictTranslate
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

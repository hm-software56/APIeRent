<?php

namespace app\models;

use Yii;
use \app\models\base\ProvinceTranslate as BaseProvinceTranslate;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "province_translate".
 */
class ProvinceTranslate extends BaseProvinceTranslate
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

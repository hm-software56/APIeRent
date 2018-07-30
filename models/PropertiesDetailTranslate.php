<?php

namespace app\models;

use Yii;
use \app\models\base\PropertiesDetailTranslate as BasePropertiesDetailTranslate;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "properties_detail_translate".
 */
class PropertiesDetailTranslate extends BasePropertiesDetailTranslate
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

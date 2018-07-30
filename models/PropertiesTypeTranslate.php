<?php

namespace app\models;

use Yii;
use \app\models\base\PropertiesTypeTranslate as BasePropertiesTypeTranslate;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "properties_type_translate".
 */
class PropertiesTypeTranslate extends BasePropertiesTypeTranslate
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

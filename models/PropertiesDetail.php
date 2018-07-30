<?php

namespace app\models;

use Yii;
use \app\models\base\PropertiesDetail as BasePropertiesDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "properties_detail".
 */
class PropertiesDetail extends BasePropertiesDetail
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

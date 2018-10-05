<?php

namespace app\models;

use Yii;
use \app\models\base\PropertiesType as BasePropertiesType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "properties_type".
 */
class PropertiesType extends BasePropertiesType
{

    public function behaviors()
    {
        return [
            'ml' => [
                'class' => \omgdef\multilingual\MultilingualBehavior::className(),
                'languages' => [
                    'ru' => 'Russian',
                    'en-US' => 'English',
                ],
                'defaultLanguage' => 'la',
                'langForeignKey' => 'properties_type_id',
                'tableName' => "{{%properties_type_translate}}",
                'attributes' => [
                    'name',
                ]
            ],
        ];
    }
    public static function find()
    {
        return new \omgdef\multilingual\MultilingualQuery(get_called_class());
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

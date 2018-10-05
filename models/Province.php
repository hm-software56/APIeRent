<?php

namespace app\models;

use Yii;
use \app\models\base\Province as BaseProvince;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "province".
 */
class Province extends BaseProvince
{

    public function behaviors()
    {
        return [
            'ml' => [
                'class' => \omgdef\multilingual\MultilingualBehavior::className(),
                'languages' => [
                    'la' => 'lao',
                    'en' => 'English',
                ],
                'defaultLanguage' => 'la',
                'langForeignKey' => 'province_id',
                'tableName' => "{{%province_translate}}",
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

<?php

namespace app\models;

use Yii;
use \app\models\base\District as BaseDistrict;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "district".
 */
class District extends BaseDistrict
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
                'langForeignKey' => 'district_id',
                'tableName' => "{{%district_translate}}",
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

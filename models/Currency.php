<?php

namespace app\models;

use Yii;
use \app\models\base\Currency as BaseCurrency;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currency".
 */
class Currency extends BaseCurrency
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
                'langForeignKey' => 'currency_id',
                'tableName' => "{{%currency_translate}}",
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

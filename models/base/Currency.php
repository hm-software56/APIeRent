<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 * @property \app\models\PropertiesDetail[] $propertiesDetails
 * @property string $aliasModel
 */
abstract class Currency extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'code'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'name' => Yii::t('models', 'Name'),
            'code' => Yii::t('models', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertiesDetails()
    {
        return $this->hasMany(\app\models\PropertiesDetail::className(), ['currency_id' => 'id']);
    }




}

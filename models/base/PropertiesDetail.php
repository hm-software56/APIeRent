<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "properties_detail".
 *
 * @property integer $id
 * @property string $details
 * @property integer $status
 * @property double $fee
 * @property integer $properties_id
 * @property string $per
 * @property integer $currency_id
 *
 * @property \app\models\Photo[] $photos
 * @property \app\models\Currency $currency
 * @property \app\models\Properties $properties
 * @property \app\models\PropertiesDetailTranslate[] $propertiesDetailTranslates
 * @property string $aliasModel
 */
abstract class PropertiesDetail extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    const PER_M = 'm';
    const PER_Y = 'y';
    var $enum_labels = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'properties_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['details', 'fee', 'properties_id', 'per', 'currency_id'], 'required'],
            [['details', 'per'], 'string'],
            [['status', 'properties_id', 'currency_id'], 'integer'],
            [['fee'], 'number'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['properties_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Properties::className(), 'targetAttribute' => ['properties_id' => 'id']],
            ['per', 'in', 'range' => [
                    self::PER_M,
                    self::PER_Y,
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'details' => Yii::t('models', 'Details'),
            'status' => Yii::t('models', 'Status'),
            'fee' => Yii::t('models', 'Fee'),
            'properties_id' => Yii::t('models', 'Properties ID'),
            'per' => Yii::t('models', 'Per'),
            'currency_id' => Yii::t('models', 'Currency ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(\app\models\Photo::className(), ['properties_detail_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasOne(\app\models\Properties::className(), ['id' => 'properties_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertiesDetailTranslates()
    {
        return $this->hasMany(\app\models\PropertiesDetailTranslate::className(), ['properties_detail_id' => 'id']);
    }




    /**
     * get column per enum value label
     * @param string $value
     * @return string
     */
    public static function getPerValueLabel($value){
        $labels = self::optsPer();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column per ENUM value labels
     * @return array
     */
    public static function optsPer()
    {
        return [
            self::PER_M => Yii::t('models', self::PER_M),
            self::PER_Y => Yii::t('models', self::PER_Y),
        ];
    }

}

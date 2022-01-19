<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "province_translate".
 *
 * @property integer $id
 * @property string $name
 * @property string $language
 * @property integer $province_id
 *
 * @property \app\models\Province $province
 * @property string $aliasModel
 */
abstract class ProvinceTranslate extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province_translate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id'], 'required'],
            [['province_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 45],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Province::className(), 'targetAttribute' => ['province_id' => 'id']]
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
            'language' => Yii::t('models', 'Language'),
            'province_id' => Yii::t('models', 'Province ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(\app\models\Province::className(), ['id' => 'province_id']);
    }




}
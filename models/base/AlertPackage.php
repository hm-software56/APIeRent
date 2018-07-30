<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "alert_package".
 *
 * @property integer $id
 * @property string $details
 * @property string $date_sms
 * @property integer $status
 * @property integer $properties_id
 * @property integer $user_id
 *
 * @property \app\models\Properties $properties
 * @property \app\models\User $user
 * @property string $aliasModel
 */
abstract class AlertPackage extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alert_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['details'], 'string'],
            [['date_sms'], 'safe'],
            [['status', 'properties_id', 'user_id'], 'integer'],
            [['properties_id', 'user_id'], 'required'],
            [['properties_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Properties::className(), 'targetAttribute' => ['properties_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['user_id' => 'id']]
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
            'date_sms' => Yii::t('models', 'Date Sms'),
            'status' => Yii::t('models', 'Status'),
            'properties_id' => Yii::t('models', 'Properties ID'),
            'user_id' => Yii::t('models', 'User ID'),
        ];
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
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }




}
<?php

namespace app\models;

use app\models\base\Photo as BasePhoto;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "photo".
 */
class Photo extends BasePhoto
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                // custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                // custom validation rules
            ]
        );
    }

    public static function PreviewfiledocURL($id)
    {
        $datas = Photo::find()->where(['properties_detail_id' => $id])->all();
        $initialPreview = [];
        foreach ($datas as $key => $photo) {
            $initialPreview[] = '<img src="'.\Yii::$app->urlManager->baseUrl.'/images/'.$photo->name.'" class="img-responsive"/>';
        }

        return $initialPreview;
    }

    public static function Previewfiledocdelete($id)
    {
        $datas = Photo::find()->where(['properties_detail_id' => $id])->all();
        $initialPreview = [];
        foreach ($datas as $key => $photo) {
            $initialPreview[] = [
                'caption' => $photo->name,
                //'width' => '120px',
                'url' => \yii\helpers\Url::to(['properties/deletefile']),
                'key' => $photo->id,
            ];
        }

        return $initialPreview;
    }
}

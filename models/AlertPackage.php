<?php

namespace app\models;

use app\models\base\AlertPackage as BaseAlertPackage;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "alert_package".
 */
class AlertPackage extends BaseAlertPackage
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

    public static function onesignalnotification($sms, $player_id = null)
    {
        $content = array(
            'en' => $sms,
        );
        if (!empty($player_id)) {
            $player_id_array1 = implode(',', $player_id);
            $player_id_array = explode(',', $player_id_array1);
            $fields = array(
                'app_id' => '321b77aa-e8ff-4922-a91f-c6a9ed89bffe',
                'include_player_ids' => $player_id_array,
                'data' => array('foo' => 'bar'),
                'contents' => $content,
                'small_icon' => 'ic_stat_onesignal_default.png',
                'large_icon' => 'ic_stat_onesignal_default.png',
            );
        } else {
            $fields = array(
                'app_id' => '321b77aa-e8ff-4922-a91f-c6a9ed89bffe',
                'included_segments' => array('All'),
                'data' => array('foo' => 'bar'),
                'contents' => $content,
                'small_icon' => 'ic_stat_onesignal_default.png',
                'large_icon' => 'ic_stat_onesignal_default.png',
            );
        }
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic N2ZlNjI2NjAtZjJlMC00NGUwLWI2ZWMtNTBiODUyZDNlYjQx'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

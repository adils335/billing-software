<?php

namespace app\models;

use Yii;
use \app\models\base\History as BaseHistory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "history".
 */
class History extends BaseHistory
{
    const ACTION_STATUS_INSERT = 1;
    const ACTION_STATUS_UPDATE = 2;
    const ACTION_STATUS_DELETE = 3;

    const STATUS_UNSEEN = 1;
    const STATUS_SEEN = 2;

    const STATUS_UNFLUSH = 1;
    const STATUS_FLUSH = 2;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
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

    public static function buildActionStatus(){
        return [
            self::ACTION_STATUS_INSERT => 'New',
            self::ACTION_STATUS_UPDATE => 'Update',
            self::ACTION_STATUS_DELETE => 'Delete',
        ];
    }
    
    public function getActionStatusLabel(){
        $array = self::buildActionStatus();
        if( isset( $array[$this->action_status] ) ){
           return $array[$this->action_status];
        }
    }

    public static function buildSeenStatus(){
        return [
            self::STATUS_UNSEEN => 'Unseen',
            self::STATUS_SEEN => 'Seen'
        ];
    }
    
    public function getSeenStatusLabel(){
        $array = self::buildSeenStatus();
        if( isset( $array[$this->seen_status] ) ){
           return $array[$this->seen_status];
        }
    }

    public static function buildFlushStatus(){
        return [
            self::STATUS_UNFLUSH => 'Unseen',
            self::STATUS_FLUSH => 'Seen'
        ];
    }
    
    public function getFlushStatusLabel(){
        $array = self::buildFlushStatus();
        if( isset( $array[$this->flush_status] ) ){
           return $array[$this->flush_status];
        }
    }

}

<?php 
namespace app\components;
 
use yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;


class Helper extends Behavior
{   
	public function getDateBySession( $session=null ){
        $output = [
            'from_date' => date("Y-m-d"),
            'to_date' => date("Y-m-d"),
        ];
        if( !$session ){
             $session = \app\models\Session::getCurrentSession();
        }
        $years = explode("-",$session);
        $from_date = $years[0]."-04-01";
        $to_date = substr($years[0],0,2).$years[1]."-03-31";
        $output = [
            'from_date' => $from_date,
            'to_date' => $to_date,
        ];
        return $output;
    }	
}

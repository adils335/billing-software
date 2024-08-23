<?php

namespace app\models;

use Yii;
use \app\models\base\ControllerAction as BaseControllerAction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "controller_action".
 */
class ControllerAction extends BaseControllerAction
{
    
    const ACTION_CREATE = 1;
    const ACTION_UPDATE = 2;
    const ACTION_VIEW = 3;
    const ACTION_DELETE = 4;
    const ACTION_MANAGE = 5;

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

    public static function buildAction(){
            return [
                self::ACTION_CREATE    => 'Create',
                self::ACTION_UPDATE    => 'Update',
                self::ACTION_VIEW      => 'View',
                self::ACTION_DELETE    => 'Delete',
                self::ACTION_MANAGE    => 'Manage',
        ];
    }

    public  function getAction(){
        
            if(isset(self::buildAction()[$this->meta])){
                return self::buildAction()[$this->meta];
            }
        
    }

    public  function getActionLabel($meta){
        
            if(isset(self::buildAction()[$meta])){
                return self::buildAction()[$meta];
            }
        
    }
    
    public  function getActions($controller){

        $model = Self::find()->where(['controller'=>$controller])->one();
        if(empty($model)){
           return [];
        }
        return json_decode($model->action);
        
    }
    
    public function allActions(){

        $controllersList = Self::getAllControllers();
        $list = Self::getAllAction($controllersList);

        $model = [];
        foreach ($list as $key => $controller) {
             
             foreach ($controller as $index => $action) {
               $getMeta = Self::find()->where(['controller'=>$key,'action'=>$action])->one();  
               $meta = 0;
               if($getMeta){
                 $meta = $getMeta->meta;
               }
               $model[$key][$action] = $action; 

             }

        }
        return $model;

    }
     
   public function getAllAction($controllerlist){

        $dir = Yii::getAlias("@app")."/controllers"; 
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen($dir . '/' . $controller."Controller.php", "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[$controller][] = Self::splitCamelCase($display[1]);
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
        return $fulllist;
        
   }

    public static function getAllControllers(){
        
        $app = Yii::getAlias("@app");
        $dir = Yii::getAlias("@app")."/controllers";
        $controllerlist = [];

        if ($handle = opendir($dir)) {

        while (false !== ($controller = readdir($handle))) {
                 if ($controller != "." && $controller != "..") {
                       $controller = str_replace("Controller.php", "", $controller);
                       $controllerlist[] = $controller;
                 }
        }

        closedir($handle);
        }
        
        return $controllerlist;
    }
   
    public function splitCamelCase($word){
        $view = '';
        $re = '/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x';
        $arr = preg_split($re, $word);
        $view = implode('-', $arr);
        return strtolower($view);
    }

}

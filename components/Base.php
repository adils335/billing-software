<?php 
namespace app\components;
 
use yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use app\models\User;

class Base extends Behavior
{   
	
    public function loggedUser(){
        $action = Yii::$app->controller->action->id;
        if ($action != "login" && Yii::$app->user->isGuest) {    
          Yii::$app->user->loginRequired();
          return;
        }
    }
    
    public function checkPermission(){
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        $url = $controller . "/" . $action;
        //if($action != "logout" && !User::canUrlAccess($url)  ){
          //  $this->throwError();
        //}
    }
	

    public function throwError(){
        //echo "<pre>";print_r(Yii::$app->controller->module->id);die();
        if(Yii::$app->controller->module->id != "basic"){
            return true;
        }
        Yii::$app->controller->layout = 'blank';
        echo Yii::$app->controller->render('../site/permission-denied');
        //throw new \yii\web\UnauthorizedHttpException("Permission Denied");
        //header('Location: ' . yii\helpers\Url::toRoute('site/error'));
        exit();      
    }
	
}
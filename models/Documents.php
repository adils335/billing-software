<?php

namespace app\models;

use Yii;
use \app\models\base\Documents as BaseDocuments;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "documents".
 */
class Documents extends BaseDocuments
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    const STATUS_DELETED = 2;
    
    const TYPE_AGREEMENT = 1;
    
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
    
    public static function buildStatus(){
         return [
            self::STATUS_DISABLED=>'Disabled',
            self::STATUS_ENABLED=>'Enabled',
            self::STATUS_DELETED=>'Deleted'
         ];
    }
    
    public function getStatusLabel(){
        $statuses = self::buildStatus();
        if( isset( $statuses[$this->status] ) ){
            return $statuses[$this->status];
        }
    }
    
    public static function buildType(){
         return [
            self::TYPE_AGREEMENT=>'Agreement'
         ];
    }
    
    public function getTypeLabel(){
        $types = self::buildType();
        if( isset( $types[$this->type] ) ){
            return $types[$this->type];
        }
    }
    
    public static function buildPath(){
         return [
            self::TYPE_AGREEMENT=>'agreement/view'
         ];
    }
    
    public function getPath(){
        $paths = self::buildPath();
        if( isset( $paths[$this->type] ) ){
            return $paths[$this->type];
        }
    }
    
    public static function uploadDocuments( $data ){
        
        $model = new Documents;;
       
        if (Yii::$app->request->isPost) {
            
            $model->load(Yii::$app->request->post());
            
            $file = UploadedFile::getInstance($model, 'file');
            $dir = 'upload/document/' . $model->typeLabel;
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $model->file = $dir . "/" . time() . '-' . $file->name . '.' . $file->extension;
           
            if ($model->validate()) {   
                 $file->saveAs($model->file);
                 $model->save();
                 \Yii::$app->session->setFlash('success', 'Document Added successfully.');

            }else{
                $errors = array_map(function($items){
                    return implode(',',$items);
                },$model->getErrors());
                $errors = implode(',',$errors);
                \Yii::$app->session->setFlash('error', $errors);
            }
            return $this->redirect([$model->path,'id'=>$model->source_id]);
        }

        return $this->redirect(['site/index']);
    }
    
}

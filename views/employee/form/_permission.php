<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Permission;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

\yii\web\YiiAsset::register($this);
$permissionModal = [new Permission];
$permission_modal = new Permission;

?>
  <!-- Modal -->
  <div class="modal fade" id="permission-modal" role="dialog">
    <div class="modal-dialog" style="width:95%">
    
      <!-- Modal content-->
      <div class="modal-content">
       
        <?php $form = ActiveForm::begin([
             'id'=>'permission-form',
             'options' => ['enctype' => 'multipart/form-data','method'=>'post'],
              'action' => Yii::$app->urlManager->createUrl(['employee/permission','user_id'=>$model->user_id])]);
        ?>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Permission <strong class="pull-right"><?= $model->emp_name?></strong></h4>
        </div>
        <div class="modal-body">
            <div class="row">
               
        <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 99, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $permissionModal[0],
                'formId' => 'permission-form',
                'formFields' => [
                    'user_id',
                    'controller',
                    'action',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            
                <div class="item panel panel-default"><!-- widgetBody -->
                    
                    <div class="panel-body">
                        
                        <div class="row">
                            <?php foreach ($permission as $i => $permissionData): ?>
                            <div class="col-sm-3">
                              <?= $form->field($permission_modal, "[{$i}]controller")->hiddenInput(['value'=>$permissionData['controller']])->label(false); ?>
                                <?php 
                                      $assign = \app\models\Permission::find()->where(['user_id'=>$model->user_id,'controller'=>$permissionData['controller']])->one();
                                      $action = !empty($assign)?json_decode($assign->action):[];
                                ?>
                                <?= $form->field($permission_modal, "[{$i}]action")->widget(Select2::classname(), [
                                     'data' => \app\models\Employee::buildAction($permissionData['controller']),
                                     'options' => ['placeholder' => 'Select','value'=>$action],
                                     'pluginOptions' => [
                                         'allowClear' => true,
                                         'multiple' => true
                                      ],
                                    ])->label($permissionData['controller']); ?>
                            </div>
                             <?php endforeach; ?>
                        </div><!-- .row -->
                    </div>
                </div>
           
            </div>

            <?php DynamicFormWidget::end(); ?>
               
            </div>
            
        </div>
        <div class="modal-footer">
          <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

        <?php ActiveForm::end(); ?>

      </div>
      
    </div>
  </div>
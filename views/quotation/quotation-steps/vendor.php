<?php

use yii\bootstrap\Html; 
use yii\bootstrap\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Agreement */
$this->title = Yii::t('app', 'Vendor Details');

?>

   
    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i>Vendors</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 5, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $vendors[0],
                'formId' => 'vendor_form',
                'formFields' => [
                    'agreement_id',
                    'vendor_name',
                    'vendor_code',
                    'company_id',
                    'session',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($vendors as $i => $vendor): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">vendor</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $vendor->isNewRecord) {
                                echo Html::activeHiddenInput($vendor, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($vendor, "[{$i}]agreement_id")->hiddenInput(['value'=>$quotation->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-6">
							<?= $form->field($vendor, "[{$i}]vendor_name"); ?>
                            </div>
                            <div class="col-sm-6">
							<?= $form->field($vendor, "[{$i}]vendor_code"); ?>
                            </div>
                        </div><!-- .row -->
                        <div class="row hide">
                            <div class="col-sm-6">
							  <?= $form->field($vendor, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...', 'value'=>$quotation->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Company Name"); ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($vendor, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...', 'value'=>$quotation->session],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Session"); ?>
                            </div>
                           
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
	
	<?php 
	   
	   $this->registerJs("
	   
	     $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
            $('.agreement-id').val($('#agreementvendor-0-agreement_id').val());
         });
	   
	   ");
	   
	?>
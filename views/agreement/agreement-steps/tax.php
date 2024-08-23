<?php

use yii\bootstrap\Html; 
use yii\bootstrap\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Agreement */
$this->title = Yii::t('app', 'Tax Details');

?>
   
    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i>Taxes</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 5, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $taxes[0],
                'formId' => 'tax_form',
                'formFields' => [
                    'agreement_id',
                    'tax_id',
                    'rate',
                    'company_id',
                    'session',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($taxes as $i => $tax): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Tax</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $tax->isNewRecord) {
                                echo Html::activeHiddenInput($tax, "[{$i}]id",['class'=>'tax_id']);
                            }
                        ?>
                        <?= $form->field($tax, "[{$i}]agreement_id")->hiddenInput(['value'=>$agreement->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-6">
							<?= $form->field($tax, "[{$i}]tax_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'tax-name'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Tax Name"); ?>
                            </div>
                            <div class="col-sm-6">
							  <?= $form->field($tax, "[{$i}]rate")->textInput(['class'=>'form-control rate']); ?>
                            </div>
                        </div><!-- .row -->
                        <div class="row hide">
                            <div class="col-sm-6">
							  <?= $form->field($tax, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','value'=>$agreement->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Company Name"); ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($tax, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...','value'=>$agreement->session],
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
            $('.agreement-id').val($('#agreementtax-0-agreement_id').val());
            $('.tax_id:last').val('');
            $(e.target).find('.container-items').find('.item:last').find('.tax-name').val('').trigger('change');
            $(e.target).find('.container-items').find('.item:last').find('.rate').val('');
         });
	   
	   ");
	   
	?>
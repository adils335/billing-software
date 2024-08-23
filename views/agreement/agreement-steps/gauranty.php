<?php

use yii\bootstrap\Html; 
use yii\Helpers\Url; 
use yii\bootstrap\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Agreement */
//echo "<pre>";print_r($Agreement);die();
$this->title = Yii::t('app', 'Gauranty Details');
$formatter = Yii::$app->formatter;

?>

   
    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i>Gauranties</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 50, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $gauranties[0],
                'formId' => 'gauranty_form',
                'formFields' => [
                    'agreement_id',
                    'name',
                    'date',
                    'gauranty_no',
                    'amount',
                    'expire_date',
                    'refund_date',
                    'company_id',
                    'session',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($gauranties as $i => $gauranty): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Gauranty</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $gauranty->isNewRecord) {
                                echo Html::activeHiddenInput($gauranty, "[{$i}]id",['class'=>'gauranty-items-id']);
                            }
                        ?>
                        <?= $form->field($gauranty, "[{$i}]agreement_id")->hiddenInput(['value'=>$agreement->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-6">
							<?= $form->field($gauranty, "[{$i}]name")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\AgreementGaurantyType::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'gauranty-name'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
			     if($gauranty->date){
			        $gauranty->date = $formatter->asDate($gauranty->date,"php:d-m-Y");
			     }
			    ?>
							  <?=  $form->field($gauranty, "[{$i}]date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...','class'=>'date'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                       ]
                                    ]); ?>
                            </div>
                        </div><!-- .row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($gauranty, "[{$i}]gauranty_no")->textInput(['maxlength' => true])->textInput(['class'=>'form-control gauranty-no']) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($gauranty, "[{$i}]amount")->textInput(['maxlength' => true])->textInput(['class'=>'form-control amount']) ?>
                            </div>
                        </div><!-- .row -->
                        <div class="row">
                            <div class="col-sm-6">
                                    <?php
			     if($gauranty->expire_date){
			        $gauranty->expire_date = $formatter->asDate($gauranty->expire_date,"php:d-m-Y");
			     }
			    ?>
							      <?=  $form->field($gauranty, "[{$i}]expire_date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...','class'=>'expire-date'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                       ]
                                    ]); ?>
                            </div>
                            <div class="col-sm-6">
                                         <?php
			     if($gauranty->refund_date){
			        $gauranty->refund_date = $formatter->asDate($gauranty->refund_date,"php:d-m-Y");
			     }
			    ?>
							<?=  $form->field($gauranty, "[{$i}]refund_date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...','class'=>'refund-date'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                       ]
                                    ]); ?>
                            </div>
                        </div><!-- .row -->
						<div class="row hide">
                            <div class="col-sm-6">
							<?= $form->field($gauranty, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','value'=>$agreement->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-6">
							  <?= $form->field($gauranty, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...','value'=>$agreement->session],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
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
            $('.agreement-id').val($('#agreementgauranty-0-agreement_id').val());
            $(e.target).find('.container-items').find('.item:last').find('.gauranty-items-id').val('');
            $(e.target).find('.container-items').find('.item:last').find('.gauranty-name').val('').trigger('change');
            $(e.target).find('.container-items').find('.item:last').find('.date').val('');
            $(e.target).find('.container-items').find('.item:last').find('.gauranty-no').val('');
            $(e.target).find('.container-items').find('.item:last').find('.amount').val('');
            $(e.target).find('.container-items').find('.item:last').find('.expire-date').val('');
            $(e.target).find('.container-items').find('.item:last').find('.refund-date').val('');
            
         });
		 
	   ");
	   
	?>
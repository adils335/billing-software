<?php

use yii\bootstrap5\Html; 
use yii\bootstrap5\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Agreement */
$this->title = Yii::t('app', 'Tax Details');

?>
   <div class="box-header with-border">
			<h4 class="box-title">
				<i class="fa fa-globe"></i> <?= $this->title ?> 
			</h4>
		</div>
   
		<div class="box-body">
    <div class="panel panel-default">
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
                <div class="item panel panel-default">
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $tax->isNewRecord) {
                                echo Html::activeHiddenInput($tax, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($tax, "[{$i}]agreement_id")->hiddenInput(['value'=>$quotation->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-3">
							<?= $form->field($tax, "[{$i}]tax_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Tax Name"); ?>
                            </div>
                            <div class="col-sm-3">
							  <?= $form->field($tax, "[{$i}]rate")->textInput(['class'=>'form-control tax-rate']); ?>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- .row -->
                        <!--<div class="row hide">
                            <div class="col-sm-6">
							  <?
							  /*= $form->field($tax, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...', 'value'=>$quotation->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Company Name"); */
                              ?>
                            </div>
                            <div class="col-sm-6">
                                <?
                                /*= $form->field($tax, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...', 'value'=>$quotation->session],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Session"); */
                              ?>
                            </div>
                        </div>-->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-1">
                    <button type="button" class="add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add</button>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($quotation, "taxable_amount")->textInput(['maxlength' => true,'id'=>'taxable-amount','class'=>'form-control']) ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($quotation, "tax_amount")->textInput(['maxlength' => true,'id'=>'tax-amount','class'=>'form-control']) ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($quotation, "payable_amount")->textInput(['maxlength' => true,'id'=>'payable-amount','class'=>'form-control']) ?>
                </div>
                <div class="col-sm-1">
                    &nbsp;
                </div>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    </div>
<?php 
	$script = <<<JS
        $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
            $(e.target).find('.container-items').find('.item:last').find('input').val('');
            $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
            $('.agreement-id').val($('#agreementtax-0-agreement_id').val());
        });
        $(".dynamicform_wrapper").on("afterDelete", function(e) {
            totalTaxValue();
        });
        $(".dynamicform_wrapper").on("keyup",".tax-rate",function(){
            totalTaxValue();
        });
        function totalTaxValue(){
            let taxable_amount = Number($("#taxable-amount").val());
            let tax_amount = 0;
            let payable_amount = 0;
            $(".tax-rate").each(function(key,item){
                let rate = Number($(item).val());
                tax_amount += taxable_amount*rate/100;
            });
            $("#tax-amount").val( tax_amount.toFixed(2) )
            payable_amount = taxable_amount + tax_amount;
            $("#payable-amount").val( payable_amount.toFixed(2) )
        }
JS;
$this->registerJs($script);
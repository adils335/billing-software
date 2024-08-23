<?php

use yii\bootstrap5\Html; 
use yii\Helpers\Url; 
use yii\bootstrap5\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Agreement */
//echo "<pre>";print_r($Agreement);die();
$this->title = Yii::t('app', 'Work Details');

?>

		<div class="box-header with-border">
			<h4 class="box-title">
				<i class="fa fa-globe"></i> <?= $this->title ?> 
			</h4>
		</div>
   
		<div class="box-body different_billing_address">
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
                'model' => $schedules[0],
                'formId' => 'rate-schedule_form',
                'formFields' => [
                    'agreement_id',
                    'item',
                    'hsn_no',
                    'unit',
                    'quantity',
                    'rate',
                    'amount'
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($schedules as $i => $schedule): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $schedule->isNewRecord) {
                                echo Html::activeHiddenInput($schedule, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($schedule, "[{$i}]agreement_id")->hiddenInput(['value'=>$quotation->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-1">
                                <?= $form->field($schedule, "[{$i}]sno")->textInput(['class'=>'form-control sequence sequence-input']); ?>
                            </div>
                            <div class="col-sm-4">
							<?= $form->field($schedule, "[{$i}]item")->textArea(); ?>
                            </div>
                           <div class="col-sm-1">
                                <?= $form->field($schedule, "[{$i}]hsn_no") ?>
                            </div>
                           <div class="col-sm-1">
                                <?= $form->field($schedule, "[{$i}]unit")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
							<div class="col-sm-1">
                                <?= $form->field($schedule, "[{$i}]quantity")->textInput(['maxlength' => true,'class'=>'quantity form-control']) ?>
                            </div>
                            <div class="col-sm-1">
                                <?= $form->field($schedule, "[{$i}]rate")->textInput(['maxlength' => true,'class'=>'rate form-control']) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($schedule, "[{$i}]amount")->textInput(['maxlength' => true,'class'=>'amount form-control']) ?>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <button type="button" class="add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add</button>
                </div>
                <div class="col-sm-7">
                    &nbsp;
                </div>
                <div class="col-sm-2">
                    <?= $form->field($quotation, "taxable_amount")->textInput(['maxlength' => true,'id'=>'taxable-amount','class'=>'form-control']) ?>
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
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(e.target).find('.container-items').find('.item:last').find('input').val('');
        $(e.target).find('.container-items').find('.item:last').find('textarea').val('');
        $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
        $('.agreement-id').val($('#agreementrateschedule-0-agreement_id').val());
        sortItem();
    });
    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        totalQuotationValue();
        sortItem();
    });
    $(".dynamicform_wrapper").on("keyup",".rate,.quantity",function(){
        totalQuotationValue();
    });
    function totalQuotationValue(){
        let taxable_amount = 0;
        $(".quantity").each(function(key,item){
            let qty = Number($(item).val());
            let rate = Number($(".rate").eq(key).val());
            let amount = qty*rate;
            taxable_amount += amount;
            $(".amount").eq(key).val( amount.toFixed(2) )
        });
        $("#taxable-amount").val( taxable_amount.toFixed(2) )
    }
    function sortItem(){
        $('.sequence').each(function(index){
           $(this).val(index+1);
        });
    }
    $('.container-items').sortable({
        items: '.item:not(.item:first-child)',
        cursor: 'pointer',
        axis: 'y',
        dropOnEmpty: false,
        start: function (e, ui) {
            ui.item.addClass('selected');
        },
        stop: function (e, ui) {
            ui.item.removeClass('selected');
            $(this).find('.item').each(function (index) {
                if (index > 0) {
                    $(this).find('.sequence').val(index+1);
                }
            });
        }
    });
JS;
$this->registerJs($script);
	   
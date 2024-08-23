<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2; 

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-tax',
    'widgetItem' => '.tax-item',
    'limit' => 4,
    'min' => 0,
    'insertButton' => '.add-tax',
    'deleteButton' => '.remove-tax',
    'model' => $itemsTax[0],
    'formId' => 'purchaseForm',
    'formFields' => [
        'tax_id',
        'rate',
        'tax_amount'
    ],
]); ?>

			<div class="pull-right" style="margin-top: -75px;">
            <button type="button" class="add-tax btn btn-success btn-xs"><span class="glyphicon glyphicon-plus">Tax</span></button>
            </div>
            <div class="container-tax"><!-- widgetContainer -->
            
            
<?php foreach ($itemsTax as $indexTax => $tax): ?>
<div class="tax-item"><!-- widgetBody -->
<div class="row">
    <?php
                    // necessary for update action.
                    if (! $tax->isNewRecord) {
                        echo Html::activeHiddenInput($tax, "[{$index}][{$indexTax}]id",['class'=>'inner_item_id']);
                    }
                ?>
    <div class="col-sm-3">
		<?= $form->field($tax, "[{$index}][{$indexTax}]tax_id")->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>1])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select ...','class'=>'tax_id'],
                'pluginLoading' => false,
                'pluginOptions' => [
                             'allowClear' => true,
                     ],
        ]); ?>
    </div>
	<div class="col-sm-2">
	  <?= $form->field($tax, "[{$index}][{$indexTax}]rate")->textInput(['class'=>'form-control rate','data-item'=>"{$index}"]); ?>
    </div>
	<div class="col-sm-3">
	  <?= $form->field($tax, "[{$index}][{$indexTax}]tax_amount")->textInput(['class'=>'form-control tax_amount','data-item'=>"{$index}"]); ?>
    </div>
    <div class="col-sm-1">
        <button type="button" class="remove-tax btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
    </div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php DynamicFormWidget::end(); ?>     

<?php

use yii\helpers\Html;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

?>
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Payable Tax</h2>
	       <hr>
		</div>
	</div>
	 <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper1', 
                'widgetBody' => '.container-items1', 
                'widgetItem' => '.item1', 
                'limit' => 5, 
                'min' => 1, 
                'insertButton' => '.add-item1', 
                'deleteButton' => '.remove-item1', 
                'model' => $billTax[0],
                'formId' => 'vendor-bill',
                'formFields' => [
                    'bill_id',
                    'tax_id',
                    'rate',
                    'amount',
                ],
            ]); ?>

            <div class="container-items1"><!-- widgetContainer -->
            <?php foreach ($billTax as $i => $tax): ?>
			
                <div class="item1 panel"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $tax->isNewRecord) {
                                echo Html::activeHiddenInput($tax, "[{$i}]id",['class'=>'payable-tax-id']);
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-4">
							<?= $form->field($tax, "[{$i}]tax_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>1])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'tax-id'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($tax, "[{$i}]rate")->textInput(['class'=>'form-control tax-rate','onkeyup'=>'billAmount()']); ?>
                            </div>
							
                            <div class="col-sm-3">
                                <?= $form->field($tax, "[{$i}]amount")->textInput(['class'=>'form-control tax-amount','onkeyup'=>'billAmount()']); ?>
                            </div>
							
                            <div class="col-sm-1"><br>
                                <button type="button" class="remove-item1 btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
							
                        </div>
						  
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
	<div class="row">
	
	   <div class="col-sm-12">
              
			  
			<div class="form-group">
             <button type="button" class="add-item1 btn btn-i pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
             </div>
                 
	   </div>
	
	</div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Tax Total</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'tax_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
<?php 
$formatJs = <<< JS

    $(".dynamicform_wrapper1").on("afterInsert", function(e2, item2) {
        console.log(e2);
        $(e2.target).find(".container-items1").find(".item1:last").find("input").val('');
        $(e2.target).find(".container-items1").find(".item1:last").find("select").val('').trigger("change");
    });

JS;
$this->registerJs($formatJs);
	
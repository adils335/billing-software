<?php

use yii\helpers\Html;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

?>
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Deduction Tax</h2>
	       <hr>
		</div>
	</div>
	 <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper3', 
                'widgetBody' => '.container-items3', 
                'widgetItem' => '.item3', 
                'limit' => 5, 
                'min' => 0, 
                'insertButton' => '.add-item3', 
                'deleteButton' => '.remove-item3', 
                'model' => $billDeduction[0],
                'formId' => 'vendor-bill',
                'formFields' => [
                    'bill_id',
                    'tax_id',
                    'rate',
                    'is_rate',
                    'amount',
                ],
            ]); ?>

            <div class="container-items3"><!-- widgetContainer -->
            <?php foreach ($billDeduction as $i => $deduction): ?>
			
                <div class="item3 panel"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $deduction->isNewRecord) {
                                echo Html::activeHiddenInput($deduction, "[{$i}]id",['class'=>'deduction-id']);
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-3">
							<?= $form->field($deduction, "[{$i}]tax_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>2])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php $isChecked = $deduction->is_rate == 1?true:false;
                                      $deductionValue = $deduction->is_rate == 1?1:2;
                                ?>
                                <?= Html::checkbox("VendorBillDeduction[{$i}][is_rate]",$deductionValue,['class'=>'is_rate','checked'=>$isChecked,'id'=>"vendorbilldeduction-{$i}-is_rate"]);?>
                                <label for="vendorbilldeduction-<?= $i?>-is_rate">Is Rate</label>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($deduction, "[{$i}]rate")->textInput(['class'=>'form-control deduction-rate','onkeyup'=>'billAmount()']); ?>
                            </div>
							
                            <div class="col-sm-3">
                                <?= $form->field($deduction, "[{$i}]amount")->textInput(['class'=>'form-control deduction-amount','onkeyup'=>'billAmount()']); ?>
                            </div>
							
                            <div class="col-sm-1"><br>
                                <button type="button" class="remove-item3 btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
							
                        </div>
						  
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
	<div class="row">
	
	   <div class="col-sm-12">
              
			  
			<div class="form-group">
             <button type="button" class="add-item3 btn btn-i pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
             </div>
                 
	   </div>
	
	</div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Deduction Total</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'deduction_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
	<?php 
	   
	   $this->registerJs("
	   
	     $('.dynamicform_wrapper3').on('afterInsert', function (e, item) {
            $('.deduction-id:last').val('');
         });
	   
	   ");
	   
	?>
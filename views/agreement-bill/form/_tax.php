<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
?>
<div id="payable-tax-row">
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Payable Tax</h2>
	       <hr>
		</div>
	</div>
	
	<div class="row">
	    
	    <div class="col-sm-4">
		   <label>Tax</label>
		</div>
		
	    <div class="col-sm-4">
		   <label>Rate</label>
		</div>
		
	    <div class="col-sm-4">
		   <label>Amount</label>
		</div>
		
	</div>
	 <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper_tax', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.tax-item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
                'min' => 0, // 0 or 1 (default 1)
                'insertButton' => '.add-tax-item', // css class
                'deleteButton' => '.remove-tax-item', // css class
                'model' => $billTaxes[0],
                'formId' => 'agreement-bill',
                'formFields' => [
                    'tax_id',
                    'rate',
                    'amount'
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($billTaxes as $i => $bill): ?>
                <div class="tax-item panel panel-default">
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            $bill->agreement_id = $model->agreement_id;
                            echo Html::activeHiddenInput($bill, "[{$i}]agreement_id");
                            if (! $bill->isNewRecord) {
                                echo Html::activeHiddenInput($bill, "[{$i}]id");
                            }
                        ?>
                        
                        <div class="row">
	    
                    	    <div class="col-sm-4">
                    		    <?= $form->field($bill, 'tax_id[]')
                                    ->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->orderBy('id')->asArray()->all(), 'id', 'name'), 
                    			    ['prompt'=>'Select']    
                                )->label(false);?>
                    		</div>
                    		
                    	    <div class="col-sm-3">
                    		    <?= $form->field($bill, 'rate[]')->textInput(['maxlength' => true,'class'=>'form-control tax-rate','onkeyup'=>'billAmount()'])->label(false); ?>
                    		</div>
                    		
                    	    <div class="col-sm-4">
                    		    <?= $form->field($bill, 'amount[]')->textInput(['maxlength' => true,'class'=>'form-control tax-amount','onkeyup'=>'billAmount()'])->label(false); ?>
                    		</div>
                    	    <div class="col-sm-1">
                    		    <button type="button" class="remove-tax-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                    		</div>
                    		
                    	</div>
                        
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <button type="button" class="add-tax-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add</button>
            <?php DynamicFormWidget::end(); ?>
        </div>
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
	
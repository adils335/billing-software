<?php 
use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2; 
?>

	<div class="row">
	    <div class="col-sm-12">
		   <h2>Item Details</h2>
	       <hr>
		</div>
	</div>
	
	<div class="row">
	    
	    <div class="col-sm-1">
		   <label>S. No.</label>
		</div>

	    <div class="col-sm-2">
		   <label>Item</label>
		</div>
		<div class="col-sm-1">
		   <label>Hsn No</label>
		</div>
	    <div class="col-sm-1">
		   <label>UOM</label>
		</div>
		
	    <div class="col-sm-1">
		   <label>Rate</label>
		</div>
	
	    <div class="col-sm-1">
		   <label>Quantity</label>
		</div>
	
	    <div class="col-sm-2">
		   <label>Base Amount</label>
		</div>
	</div>
	
    <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $billItems[0],
                'formId' => 'agreement-bill',
                'formFields' => [
                    'tax_id',
                    'rate',
                    'amount'
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($billItems as $i => $billItem): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <div class="pull-right">
                            
                            
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            $billItem->agreement_id = $model->agreement_id;
                            echo Html::activeHiddenInput($billItem, "[{$i}]agreement_id");
                            if (! $billItem->isNewRecord) {
                                echo Html::activeHiddenInput($billItem, "[{$i}]id");
                            }
                        ?>
                        
                        
	<div class="row">
	    
	    <div class="col-sm-1">
		      <?=  $form->field($billItem, "[{$i}]sno")->textInput(['class'=>'sequence-input form-control'])->label(false);?>
		</div>

	    <div class="col-sm-2">
			<?=  $form->field($billItem, "[{$i}]item")->textarea([])->label(false);?>
		</div>
		
	    <div class="col-sm-1">
		    <?= $form->field($billItem, "[{$i}]hsn_no")->textInput(['class'=>'rate-input item-hsn_no form-control'])->label(false); ?>
		</div>
	    <div class="col-sm-1">
		      <?= 
			     $form->field($billItem, "[{$i}]unit")
                    ->dropDownList(
                      \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'), 
					  ['prompt'=>'Select','class'=>'unit-input form-control']    // options
                )->label(false);
			  ?>
		      
		</div>
		
	    <div class="col-sm-1">
		    <?= $form->field($billItem, "[{$i}]rate")->textInput(['class'=>'rate-input item-rate form-control','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
	
	    <div class="col-sm-1">
		    <?= $form->field($billItem, "[{$i}]quantity")->textInput(['class'=>'form-control quantity-input item-quantity','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	    <div class="col-sm-2">
		    <?= $form->field($billItem, "[{$i}]base_amount")->textInput(['class'=>'form-control amount-input item-base-amount','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		<div class="col-sm-1">
		       <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	
	<div class="row item-tax-row hide">
	    <div class="col-sm-5">&nbsp;</div>
	    <div class="col-sm-1">
		      <?= 
			     $form->field($billItem, "[{$i}]tax_id")
                    ->dropDownList(
                      \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->orderBy('id')->asArray()->all(), 'id', 'name'), 
					  ['prompt'=>'Select','class'=>'form-control item-tax-id']    // options
                );
			  ?>
		</div>
		
	    <div class="col-sm-1">
		      <?=  $form->field($billItem, "[{$i}]tax_rate")->textInput(['class'=>'item-tax-rate form-control','onkeyup'=>'billAmount()']);?>
		</div>
		
	    <div class="col-sm-1">
		    <?= $form->field($billItem, "[{$i}]tax_amount")->textInput(['class'=>'item-tax-amount form-control','onkeyup'=>'billAmount()']); ?>
		</div>
		
	    <div class="col-sm-2">
		    <?= $form->field($billItem, "[{$i}]amount")->textInput(['class'=>'form-control item-amount']); ?>
		</div>
		
	</div>
	<div class="row" style="display:none">
	    <div class="col-sm-6">
                     <?= $form->field($billItem, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','value' => $agreement->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
	     </div>
	     <?php
	        $session = empty($model->session)?\app\models\Session::getCurrentSession():$model->session;
	     ?>
		 <div class="col-sm-6">
                     <?= $form->field($billItem, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...','value' => $session],
                               'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                              ]); ?>
	     </div>
	</div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <button type="button" class="add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add</button>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

	
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Total</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'base_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
<?php 
$formatJs = <<< JS
$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(item).find("input").val("");
        $(item).find("select").val('').trigger("change");

    });
JS;
$this->registerJs($formatJs);
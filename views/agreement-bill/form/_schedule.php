
	<div class="row">
	
	   <div class="col-sm-4">
	     <?= 
			     $form->field($model, 'schedule')
                    ->dropDownList(
                      \app\models\Agreement::buildSchedule(), 
					  ['options' => [$agreement->schedule => ['Selected'=>true]],
					  'prompt'=>'Select']    
                );
			  ?>
	   </div>

	   <div class="col-sm-4">
         <?= $form->field($model, 'schedule_rate')->textInput(['value'=>$agreement->rate]) ?>
	   </div>

	   <div class="col-sm-4">
         <?= $form->field($model, 'schedule_amount')->textInput(['maxlength' => true]) ?>
	   </div>
	</div>
	
	<div class="row related-invoice-div <?= $related_invoice_div?>">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Total Amount</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
	<div class="row related-invoice-div <?= $related_invoice_div?>">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Last Bill Amount</label>
        </div>
	    <div class="col-sm-2">
           <label id="last-bill-amount" class="number-input"><?= $model->lastBillAmount?></label>
        </div>
	</div>
	
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Taxable Amount</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'taxable_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
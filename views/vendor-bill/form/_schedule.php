
	<div class="row">
	
       <div class="col-sm-4">
           <?= $form->field($model, "particular")->textarea(); ?>
       </div>
	   <div class="col-sm-4">
	     <?= 
			     $form->field($model, 'schedule')
                    ->dropDownList(
                      \app\models\VendorBill::buildSchedule(), 
					  ['prompt'=>'Select','onchange'=>'billAmount()']    
                )->label("Discount/Penalty");
			  ?>
	   </div>
       <!--
	   <div class="col-sm-4">
	   	 <?php $model->schedule_rate = $model->isNewRecord?0:$model->schedule_rate?>
         <?= $form->field($model, 'schedule_rate')->textInput(['class'=>'form-control','onkeyup'=>'billAmount()'])->label("Rate") ?>
	   </div>
       -->
	   <div class="col-sm-4">
         <?= $form->field($model, 'schedule_amount')->textInput(['maxlength' => true,'onkeyup'=>'billAmount()'])->label("Amount") ?>
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
	
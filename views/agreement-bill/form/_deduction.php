<?php
use yii\helpers\Html;
?>
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Deduction Details 
		   <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></h2>
	       <hr>
		</div>
	</div>
	
	<div class="row">
	    
	    <div class="col-sm-2">&nbsp;</div>
		
	    <div class="col-sm-3">
		   <label>Tax</label>
		</div>
		
	    <div class="col-sm-1">
		       <label>Is Rate</label>
		</div>
		
	    <div class="col-sm-2">
		   <label>Rate</label>
		</div>
		
	    <div class="col-sm-4">
		   <label>Amount</label>
		</div>
		
	</div>
	
	<?php 
	if($model->isNewRecord || empty($billDeduction) || $billDeduction->isNewRecord){?>
	
	<div class="row clone">
	    
	    <div class="col-sm-2">
           <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
		
	    <div class="col-sm-3">
		    <?= 
			     $form->field($billDeduction, 'tax_id[]')
                    ->dropDownList(
                      \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>2])->orderBy('id')->asArray()->all(), 'id', 'name'), 
					  ['prompt'=>'Select']    
                )->label(false);
			  ?>
		</div>
		
	    <div class="col-sm-1">
             <?= Html::checkbox('BillDeduction[is_rate][]',1,['class'=>'is_rate','checked'=>true]);?>
		</div>
		
	    <div class="col-sm-2">
		   <?= $form->field($billDeduction, 'rate[]')->textInput(['maxlength' => true,'class'=>'form-control deduction-rate','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	    <div class="col-sm-4">
		   <?= $form->field($billDeduction, 'amount[]')->textInput(['maxlength' => true,'class'=>'form-control deduction-amount','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	</div>

    <?php }else{
        
		foreach($billDeduction as $deduction){
		?>
		
	<div class="row clone">
	    
	    <div class="col-sm-2">
           <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
		
	    <div class="col-sm-3">
		    <?= 
			     $form->field($deduction, 'tax_id[]')
                    ->dropDownList(
                      \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>2])->orderBy('id')->asArray()->all(), 'id', 'name'), 
					  ['options'=>[$deduction->tax_id=>['selected'=>true]],
					  'prompt'=>'Select']    
                )->label(false);
			  ?>
		</div>
		
	    <div class="col-sm-1">
	        <?php $is_rate = $deduction->is_rate==1?true:false;?>
	        <?= Html::checkbox('BillDeduction[is_rate][]',$is_rate,['class'=>'is_rate','checked'=>$is_rate]);?>
	       <?// $form->field($deduction, 'is_rate[]')->checkbox(['class'=>'is_rate','checked'=>$is_rate])->label(false); ?>
		</div>
		
	    <div class="col-sm-2">
		   <?= $form->field($deduction, 'rate[]')->textInput(['value'=>$deduction->rate,'maxlength' => true,'class'=>'form-control deduction-rate','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	    <div class="col-sm-4">
		   <?= $form->field($deduction, 'amount[]')->textInput(['value'=>$deduction->amount,'maxlength' => true,'class'=>'form-control deduction-amount','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	</div>

	<?php }}?>	
	
	<div class="row deduction-total">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Deduction Total</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'deduction_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
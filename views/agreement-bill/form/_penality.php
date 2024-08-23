
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Penality 
		   <button type="button" class="add-penality btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></h2>
	       <hr>
		</div>
	</div>
	
	<div class="row">
	    
	    <div class="col-sm-2">&nbsp;</div>
		
	    <div class="col-sm-6">
		   <label>Tax</label>
		</div>
		
	    <div class="col-sm-4">
		   <label>Amount</label>
		</div>
		
	</div>
	
	<?php 
    $penalityCondition = empty($billPenality[0])?true:false;
	if($penalityCondition){?>
	
	<div class="row penality-clone">
	    
	    <div class="col-sm-2">
           <button type="button" class="remove-penality btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
		
	    <div class="col-sm-6">
		    <?= 
			     $form->field($billPenality, 'tax_id[]')
                    ->dropDownList(
                      \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>3])->orderBy('id')->asArray()->all(), 'id', 'name'), 
					  ['prompt'=>'Select']    
                )->label(false);
			  ?>
		</div>
		
	    <div class="col-sm-4">
		   <?= $form->field($billPenality, 'amount[]')->textInput(['maxlength' => true,'class'=>'form-control penality-amount','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	</div>

    <?php }else{

		foreach($billPenality as $penality){
		?>
		
	<div class="row penality-clone">
	    
	    <div class="col-sm-2">
           <button type="button" class="remove-penality btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
		
	    <div class="col-sm-6">
		    <?= 
			     $form->field($penality, 'tax_id[]')
                    ->dropDownList(
                      \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>3])->orderBy('id')->asArray()->all(), 'id', 'name'), 
					  ['options'=>[$penality->tax_id=>['selected'=>true]],
					  'prompt'=>'Select']    
                )->label(false);
			  ?>
		</div>
		
	    <div class="col-sm-4">
		   <?= $form->field($penality, 'amount[]')->textInput(['value'=>$penality->amount,'maxlength' => true,'class'=>'form-control penality-amount','onkeyup'=>'billAmount()'])->label(false); ?>
		</div>
		
	</div>

	<?php }}?>	
	
	<div class="row penality-total">
	    <div class="col-sm-4">
          <?= $form->field($model, 'penality_amount')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-sm-4">
          <?= $form->field($model, 'penality_tax')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-sm-4">
          <?= $form->field($model, 'penality_after_tax')->textInput(['maxlength' => true]) ?>
	    </div>
	</div>
	
	
<?php
$taxes = $model->agreementTaxes;
?>

<h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Tax Details')?></h4>       
     
  <?php foreach($taxes as $tax){?>
	  
	  <div class="row">
	
	      <div class="col-md-6">
		      <label for=""><?= Yii::t('app','Tax Name');?></label> : <?= $tax->tax->name?>
	      </div>
		  
	      <div class="col-md-6">
		      <label for=""><?= Yii::t('app','Rate');?></label> : <?= $tax->rate?>
	      </div>
		  
	   </div>
	  <hr>
  <?php }?>
	 
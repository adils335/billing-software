<?php
$gauranties = $model->agreementGauranties;
?>

<h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Gauranty Details')?></h4>       
     
  <?php foreach($gauranties as $gauranty){?>
	  
	  <div class="row">
	
	      <div class="col-md-4">
		      <label for=""><?=$gauranty->getAttributeLabel('name')?></label> : <?= !empty($gauranty->gaurantyType)?$gauranty->gaurantyType->name:""; ?>
	      </div>
	      <div class="col-md-4">
		      <label for=""><?=$gauranty->getAttributeLabel('date')?></label> : <?=$gauranty->date?>
	      </div>
	      <div class="col-md-4">
		      <label for=""><?=$gauranty->getAttributeLabel('gauranty_no')?></label> : <?=  $gauranty->gauranty_no?>
	      </div>
       
	   </div>
	  
	  <div class="row">
	
	      <div class="col-md-4">
		      <label for=""><?=$gauranty->getAttributeLabel('amount')?></label> : <?= $gauranty->amount?>
	      </div>
	      <div class="col-md-4">
		      <label for=""><?=$gauranty->getAttributeLabel('expire_date')?></label> : <?=$gauranty->expire_date?>
	      </div>
	      <div class="col-md-4">
		      <label for=""><?=$gauranty->getAttributeLabel('refund_date')?></label> : <?=  $gauranty->refund_date?>
	      </div>
       
	   </div>
	  
	  <hr>
  <?php }?>
	 
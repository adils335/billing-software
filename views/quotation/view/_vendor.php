<?php
$vendors = $model->agreementVendors;
?>

<h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Vendor Details')?></h4>       
     
	  <div class="row">
  <?php foreach($vendors as $vendor){?>
	  
	
	      <div class="col-md-3">
		      <label for=""><?= Yii::t('app','Vendor');?></label> : <?= $vendor->vendor_code?>
	      </div>
		  
  <?php }?>
	 
	   </div>
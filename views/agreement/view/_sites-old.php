<?php
$sites = $model->agreementSites;
?>

<h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Sites Details')?></h4>       

	  <div class="row">
	
  <?php foreach($sites as $site){?>
	  
	      <div class="col-md-3">
		      <label for=""><?= Yii::t('app','Site Name');?></label> : <?= $site->site->name?>
	      </div>
		  
  <?php }?>
	 
	   </div>
	  
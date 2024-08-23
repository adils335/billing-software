<?php
use app\models\AgreementRateSchedule;
$rateSchedule = new AgreementRateSchedule;
$rates = $model->agreementRateSchedule;
?>

<h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Rate Details')?></h4>       
     
  
	  <table class="table">
	      <thead>
	          <tr>
	              <th><?=$rateSchedule->getAttributeLabel('sno')?></th>
	              <th><?=$rateSchedule->getAttributeLabel('item')?></th>
	              <th><?=$rateSchedule->getAttributeLabel('hsn_no')?></th>
	              <th><?=$rateSchedule->getAttributeLabel('unit')?></th>
	              <th><?=$rateSchedule->getAttributeLabel('quantity')?></th>
	              <th><?=$rateSchedule->getAttributeLabel('rate')?></th>
	              <th><?=$rateSchedule->getAttributeLabel('amount')?></th>
	          </tr>
	      </thead>
	      <tbody>
	          <?php foreach($rates as $rate):?>
	          <tr>
	              <td><?= $rate->sno?></td>
	              <td><?= $rate->item?></td>
	              <td><?= $rate->hsn_no?></td>
	              <td><?= $rate->uom->name?></td>
	              <td><?= $rate->quantity?></td>
	              <td><?= $rate->rate?></td>
	              <td><?= $rate->amount?></td>
	          </tr>
	          <?php endforeach;?>
	      </tbody>
	  </table>
	  
	  
	  <hr>
	 
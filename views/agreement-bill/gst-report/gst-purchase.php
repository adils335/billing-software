<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AgreementBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gst Purchase');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="agreement-bill-details box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Gst Purchase')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
             <?php  echo $this->render('../_gst_search', ['model' => $searchModel]); ?>
             
             <table class="table table-bordered">
                 <thead>
                    <tr>
                      <th>S No.</th>
                      <th>Invoice No</th>
                      <th>Name of Seller</th>
				      <th>GSTIN of Seller</th>
				      <th>Invoice Date</th>
				      <th>Particular</th>
				      <th>Invoice Value</th>
				      <th>Taxable Value</th>
				      <?php foreach( $taxes as $tax ){$totalTax[$tax->tax_id] = 0;?>
				        <th><?php echo $tax->tax->name;?></th>
				      <?php }?>
				      <th>Gst Rate</th>
                    </tr>
                 </thead>
                <tbody>
                    <?php 
                    $totalTaxableAmount = 0;
                    foreach($model as $bill):?>
                    
                    <tr>
                      <td><?= ++$counter;?></td>
                      <td><?= $bill->invoice_no?></td>
                      <td><?= $bill->itemName->item?></td>
                      <td><?= $bill->unitName->name?></td>
                      <td><?= $bill->unit?></td>
                      <td><?= $bill->unit?></td>
                      <td><?php echo $bill->invoice->taxable_amount;$totalTaxableAmount += $bill->invoice->taxable_amount;?></td>
				      <?php foreach( $taxes as $tax ){?>
				        <td><?php $taxAmount = $bill->invoice ? $bill->invoice->getTaxAmountById($tax->tax_id):0;echo $taxAmount;$totalTax[$tax->tax_id] +=$taxAmount; ?></td>
				      <?php }?>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="4" style="text-align:right"><b>Total</b></td>
			       <td class="r-data"><b></b></td>
			       <td>&nbsp;</td>
			       <td class="r-data"><b><?= $totalTaxableAmount?></b></td>
				   <?php foreach( $taxes as $tax ){?>
				     <td class="r-data"><b><?php echo $totalTax[$tax->tax_id];?></b></td>
				   <?php }?>
			    </tr>    
				<tr>
				    <td colspan="8" style="text-align:right"><b>Total</b></td>
				    <td class="r-data"><b><?//= array_sum($totalTax);?></b></td>
				</tr>
				
              </tbody>
              </table>
             
			</div>   
		</div>   
	</div>   
</div>   

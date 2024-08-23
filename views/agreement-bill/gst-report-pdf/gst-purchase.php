<?php
use yii\helpers\Html;
use NumberToWords\NumberToWords;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

// create the number to words "manager" class
$numberToWords = new NumberToWords();

// build a new number transformer using the RFC 3066 language identifier
$numberTransformer = $numberToWords->getNumberTransformer('en');

$formatter = Yii::$app->formatter;
$converter = Yii::$app->currency_formator;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title> 
		<style>
		  .tax-table,.main-table {
            border-collapse: collapse;
            overflow: wrap;
          }

          .tax-table th,.main-table th {
            border: 1px solid black;
          }
          .tax-table td,.main-table td {
            border: 1px solid black;
          }
          .tax-table,.main-table{
              font-size:12px;
              width:100%;
          }
		  .bg-blue{
			 background-color:#0066FF !important;
             color:#fff !important;			 
		  }
		  .text-blue{
			  color:#0066FF !important;
		  }
		  .text-red{
			  color:red !important;
		  }
		  .r-text{
			  text-align:right !important;
			  padding-right:5px !important;
		  }
		  .l-text{
			  text-align:left !important;
			  padding-left:5px !important;
		  }
		  .m-text{
			  text-align:center !important;
		  }
		  .v-text{
			  verticle-align:center !important;
			  font-size:36px !important;
		  }
		  .sf{
			  font-size:9px !important;
			  font-weight:bold !important;
		  }
		  .mf{
			  font-size:12px !important;
			  font-weight:bold !important;
		  }
		  .lf{
			  font-size:16px !important;
			  font-weight:bold !important;
		  }
		  .w3{
		      width:3% !important;
		  }
		  .w4{
		      width:4% !important;
		  }
		  .w5{
		      width:5% !important;
		  }
		  .w6{
		      width:6% !important;
		  }
		  .w7{
		      width:7% !important;
		  }
		  .w8{
		      width:8% !important;
		  }
		  .w9{
		      width:9% !important;
		  }
		  .w10{
		      width:10% !important;
		  }
		  .w11{
		     width:11% !important; 
		  }
		  .w12{
		     width:12% !important; 
		  }
		  .w15{
		      width:15% !important;
		  }
		</style>
  </head>
  <body>
           
		<table class="main-table">
		     <thead>
                    <tr>
                      <th class="l-text w4">S No.</th>
                      <th class="l-text">Invoice No</th>
                      <th class="l-text">Name of Seller</th>
				      <th class="l-text">GSTIN of Seller</th>
				      <th class="l-text">Invoice Date</th>
				      <!--<th class="r-text w9">Invoice Value</th>
				      <th class="r-text">Tax Rate</th>-->
				      <th class="r-text w10">Taxable Value</th>
				      <?php foreach( $taxes as $tax ){$totalTax[$tax->tax_id] = 0;?>
				        <th class="r-text w9"><?php echo $tax->tax->name;?></th>
				      <?php }?>
                    </tr>
		     </thead>
		     
                <tbody>
                    <?php 
                    $totalTaxableAmount = 0;
                    foreach($model as $bill):?>
                    
                    <tr>
                      <td class="l-text"><?= ++$counter;?></td>
                      <td class="l-text"><?= $bill->invoice_no?></td>
                      <td class="l-text"><?= $bill->name?></td>
                      <td class="l-text"><?= $bill->gstin?></td>
                      <td class="l-text"><?= \yii::$app->formatter->asDate($bill->date,'php:d-m-Y')?></td>
                      <!--<td class="r-text"><?//= $bill->amount?></td>
                      <td class="r-text"><?//= $bill->taxRate?></td>-->
                      <td class="r-text"><?php echo $bill->amount;$totalTaxableAmount += $bill->amount;?></td>
				      <?php foreach( $taxes as $tax ){?>
				        <td class="r-text"><?php echo $taxAmount = $bill->getTaxAmountById($tax->tax_id);$totalTax[$tax->tax_id] +=$taxAmount; ?></td>
				      <?php }?>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="5" style="text-align:right"><b>Total</b></td>
			       <!--<td>&nbsp;</td>
			       <td>&nbsp;</td>-->
			       <td class="r-text"><b><?= $totalTaxableAmount?></b></td>
				   <?php foreach( $taxes as $tax ){?>
				     <td class="r-text"><b><?php echo $totalTax[$tax->tax_id];?></b></td>
				   <?php }?>
			    </tr>    
				<tr>
				    <td colspan="6" style="text-align:right"><b>Total</b></td>
				    <td class="m-text" colspan="<?= count($totalTax)?>"><b><?= !empty( $totalTax ) ?array_sum($totalTax):0;?></b></td>
				</tr>
				
              </tbody>
		</table>
        
		<h3>Vendors bill</h3>

		<table class="main-table">
		     <thead>
                    <tr>
                      <th class="l-text w4">S No.</th>
                      <th class="l-text">Invoice No</th>
                      <th class="l-text">Name of Seller</th>
				      <th class="l-text">GSTIN of Seller</th>
				      <th class="l-text">Invoice Date</th>
				      <!--<th class="r-text w9">Invoice Value</th>
				      <th class="r-text">Tax Rate</th>-->
				      <th class="r-text w10">Taxable Value</th>
				      <?php foreach( $vendorBillTaxes as $tax ){$vendorTotalTax[$tax->tax_id] = 0;?>
				        <th class="r-text w9"><?php echo $tax->tax->name;?></th>
				      <?php }?>
                    </tr>
		     </thead>
		     
                <tbody>
                    <?php 
                    $totalTaxableAmount = 0;
                    foreach($vendorBillModel as $bill):?>
                    
                    <tr>
                      <td class="l-text"><?= ++$counter;?></td>
                      <td class="l-text"><?= $bill->invoice_no?></td>
                      <td class="l-text"><?= $bill->vendor->name?></td>
                      <td class="l-text"><?= $bill->vendor->gst_no?></td>
                      <td class="l-text"><?= \yii::$app->formatter->asDate($bill->bill_date,'php:d-m-Y')?></td>
                      <!--<td class="r-text"><?//= $bill->amount?></td>
                      <td class="r-text"><?//= $bill->taxRate?></td>-->
                      <td class="r-text"><?php echo $bill->taxable_amount;$totalTaxableAmount += $bill->taxable_amount;?></td>
				      <?php foreach( $vendorBillTaxes as $tax ){?>
				        <td class="r-text"><?php echo $taxAmount = $bill->getTaxAmountById($tax->tax_id);$vendorTotalTax[$tax->tax_id] +=$taxAmount; ?></td>
				      <?php }?>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="5" style="text-align:right"><b>Total</b></td>
			       <!--<td>&nbsp;</td>
			       <td>&nbsp;</td>-->
			       <td class="r-text"><b><?= $totalTaxableAmount?></b></td>
				   <?php foreach( $vendorBillTaxes as $tax ){?>
				     <td class="r-text"><b><?php echo $vendorTotalTax[$tax->tax_id];?></b></td>
				   <?php }?>
			    </tr>    
				<tr>
				    <td colspan="6" style="text-align:right"><b>Total</b></td>
				    <td class="m-text" colspan="<?= count($vendorTotalTax)?>"><b><?= !empty( $vendorTotalTax ) ?array_sum($vendorTotalTax):0;?></b></td>
				</tr>
				
              </tbody>
		</table>
        <h3>Purchase & Vendor Bill Taxes</h3>
		<table class="main-table">
			<thead>
				<tr>
					<th>Type</th>
					<th>Tax</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				<?php
                $sn = 1;
                $grandTax = [];
			    foreach( $taxes as $tax):
				?>
				<tr>
				    <?php if($sn == 1):?><td rowspan="<?= count($totalTax)?>">Purchase</td><?php endif;?>
				    <td class="r-text"><?= $tax->tax->name?></td>
				    <td class="r-text"><?= $totalTax[$tax->tax_id];
	                $grandTax[$tax->tax_id]['name'] = $tax->tax->name;
	                $grandTax[$tax->tax_id]['value'] = $totalTax[$tax->tax_id];?></td>
				</tr>
				<?php $sn++; endforeach;?>
				<tr><td colspan="3">&nbsp;</td></tr>
				<?php
				$sn = 1;
			    foreach( $vendorBillTaxes as $tax):
				?>
				<tr>
				    <?php if($sn == 1):?><td rowspan="<?= count($vendorTotalTax)?>">Vendor Bills</td><?php endif;?>
				    <td class="r-text"><?= $tax->tax->name?></td>
				    <td class="r-text"><?= $vendorTotalTax[$tax->tax_id];
	                $grandTax[$tax->tax_id]['name'] = $tax->tax->name;
	                $grandTax[$tax->tax_id]['value'] += $vendorTotalTax[$tax->tax_id];?></td>
				</tr>
				<?php $sn++; endforeach;?>
				<tr><td colspan="3">&nbsp;</td></tr>
				<?php
                $sn = 1;
                $grandTotal = 0;
			    foreach( $grandTax as $tax):
				?>
				<tr>
				    <?php if($sn == 1):?><td rowspan="<?= count($grandTax) + 1?>">Total</td><?php endif;?>
				    <td class="r-text"><?= $tax['name']?></td>
				    <td class="r-text"><b><?= $tax['value'];
	                $grandTotal += $tax['value'];?></b></td>
				</tr>
				<?php $sn++; endforeach;?>
				<tr>
				    <td class="r-text"><b>Total</b></td>
				    <td class="r-text"><b><?= $grandTotal;?></b></td>
				</tr>
			</tbody>
		</table>
  </body>
</html>

<?php 
$this->endPage() ; ?>

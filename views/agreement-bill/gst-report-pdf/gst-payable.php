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
		  .tax-table,.main-table,.total-table {
            border-collapse: collapse;
            overflow: wrap;
          }

          .tax-table th,.main-table th,.total-table th {
            border: 1px solid black;
          }
          .tax-table td,.main-table td, .total-table td {
            border: 1px solid black;
          }
          .total-table{
             font-size:14px;
             width:50%; 
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
        <h3 class="m-text">Bill Gst</h3> <hr>
		<table class="main-table">
		     <thead>
                    <tr>
                      <th class="l-text w4">S No.</th>
                      <th class="l-text">GSTIN of Recipient</th>
                      <th class="l-text">Invoice Number</th>
				      <th class="l-text">Invoice Date</th>
				      <th class="r-text w9">Invoice Value</th>
				      <th class="l-text">Place of Supply</th>
				      <th class="r-text">Tax Rate</th>
				      <th class="r-text w10">Taxable Value</th>
				      <?php foreach( $taxes as $tax ){$totalTax[$tax->tax_id] = 0;?>
				        <th class="r-text w9"><?php echo $tax->tax->name;?></th>
				      <?php }?>
                    </tr>
		     </thead>
		     
                <tbody>
                    <?php 
                    $totalTaxableAmount = 0;$totalTax= [];
                    foreach($bills as $agreementBill):?>
                    
                    <tr>
                      <td class="l-text"><?= ++$counter;?></td>
                      <td class="l-text"><?= $agreementBill->billing_company_gst?></td>
                      <td class="l-text"><?= $agreementBill->invoiceNo?></td>
                      <td class="l-text"><?= \yii::$app->formatter->asDate($agreementBill->invoice_date,'php:d-m-Y')?></td>
                      <td class="r-text"><?= $agreementBill->payable_amount?></td>
                      <td class="l-text"><?= $agreementBill->billingCompanyState->state?></td>
                      <td class="r-text"><?= $agreementBill->taxRate?></td>
                      <td class="r-text"><?php echo $agreementBill->taxable_amount;$totalTaxableAmount += $agreementBill->taxable_amount;?></td>
				      <?php foreach( $taxes as $tax ){?>
				        <td class="r-text"><?php $taxAmount = $agreementBill->getTaxAmountById($tax->tax_id);echo $taxAmount;$totalTax[$tax->tax_id] +=$taxAmount; ?></td>
				      <?php }?>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="4" style="text-align:right"><b>Total</b></td>
			       <td class="r-text"><b></b></td>
			       <td>&nbsp;</td>
			       <td>&nbsp;</td>
			       <td class="r-text"><b><?= $totalTaxableAmount?></b></td>
				   <?php foreach( $taxes as $tax ){?>
				     <td class="r-text"><b><?php echo $totalTax[$tax->tax_id];?></b></td>
				   <?php }?>
			    </tr>    
				<tr>
				    <td colspan="10" style="text-align:right"><b>Total</b></td>
				    <td class="r-text"><b><?php $billGst = array_sum($totalTax) ;echo $billGst;?></b></td>
				</tr>
				
              </tbody>
		</table>
		
        <h3 class="m-text">Purchase Bill Gst</h3> <hr>
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
				      <?php foreach( $purchase_taxes as $tax ){$totalTax[$tax->tax_id] = 0;?>
				        <th class="r-text w9"><?php echo $tax->tax->name;?></th>
				      <?php }?>
                    </tr>
		     </thead>
		     
                <tbody>
                    <?php 
                    $totalTaxableAmount = 0;$totalTax= [];
                    foreach($purchaseBills as $bill):?>
                    
                    <tr>
                      <td class="l-text"><?= ++$counter;?></td>
                      <td class="l-text"><?= $bill->invoice_no?></td>
                      <td class="l-text"><?= $bill->name?></td>
                      <td class="l-text"><?= $bill->gstin?></td>
                      <td class="l-text"><?= \yii::$app->formatter->asDate($bill->date,'php:d-m-Y')?></td>
                      <!--<td class="r-text"><?//= $bill->amount?></td>
                      <td class="r-text"><?//= $bill->taxRate?></td>-->
                      <td class="r-text"><?php echo $bill->amount;$totalTaxableAmount += $bill->amount;?></td>
				      <?php foreach( $purchase_taxes as $tax ){?>
				        <td class="r-text"><?php $taxAmount = $bill->getTaxAmountById($tax->tax_id);echo $taxAmount;$totalTax[$tax->tax_id] +=$taxAmount; ?></td>
				      <?php }?>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="4" style="text-align:right"><b>Total</b></td>
			       <td class="r-text"><b></b></td>
			       <!--<td>&nbsp;</td>
			       <td>&nbsp;</td>-->
			       <td class="r-text"><b><?= $totalTaxableAmount?></b></td>
				   <?php foreach( $purchase_taxes as $tax ){?>
				     <td class="r-text"><b><?php echo $totalTax[$tax->tax_id];?></b></td>
				   <?php }?>
			    </tr>    
				<tr>
				    <td colspan="7" style="text-align:right"><b>Total</b></td>
				    <td class="r-text"><b><?php $purchaseBillGst = array_sum($totalTax) ;echo $purchaseBillGst;?></b></td>
				</tr>
				
              </tbody>
		</table>
		
		<h3 class="m-text">Payable Gst</h3><hr>
		<table class="total-table">
		    <tr>
		       <th class="l-text">Bill Gst</th> 
		       <th class="r-text"><?= $billGst?></th>
		    </tr>
		    <tr>
		       <th class="l-text">Purchase Bill Gst</th> 
		       <th class="r-text"><?= $purchaseBillGst?></th>
		    </tr>
		    <tr>
		       <th class="l-text">Payable Gst</th> 
		       <th class="r-text"><?= $billGst - $purchaseBillGst?></th>
		    </tr>
		</table>
  </body>
</html>

<?php 
$this->endPage() ; ?>

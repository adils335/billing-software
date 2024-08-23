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
              font-size:10px;
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
                      <th class="l-text w10">Contract Company</th>
                      <th class="l-text w10">Agreement No</th>
				      <th class="l-text">Place of Supply</th>
                      <th class="l-text">GSTIN of Recipient</th>
                      <th class="l-text">Invoice Number</th>
				      <th class="l-text">Invoice Date</th>
				      <th class="r-text w10">Taxable Value</th>
				      <th class="r-text">Tax Rate</th>
				      <?php foreach( $taxes as $tax ){$totalTax[$tax->tax_id] = 0;?>
				        <th class="r-text w7"><?php echo $tax->tax->name;?></th>
						<?php }?>
					  <th class="r-text w9">Invoice Value</th>
                    </tr>
		     </thead>
		     
                <tbody>
                    <?php
                    $totalTaxableAmount = 0;
                    $totalPayableAmount = 0;
                    foreach($model as $agreementBill):?>
                    
                    <tr>
                      <td class="l-text"><?= ++$counter;?></td>
                      <td class="l-text"><?= $agreementBill->agreement->contractCompany->name?></td>
                      <td class="l-text"><?= $agreementBill->agreement->agreement_no?></td>
                      <td class="l-text"><?= $agreementBill->billingCompanyState->state?></td>
                      <td class="l-text"><?= $agreementBill->billing_company_gst?></td>
                      <td class="l-text"><?= $agreementBill->invoiceNo?></td>
                      <td class="l-text"><?= \yii::$app->formatter->asDate($agreementBill->invoice_date,'php:d-m-Y')?></td>
                      <td class="r-text"><?php echo $agreementBill->taxable_amount;$totalTaxableAmount += $agreementBill->taxable_amount;?></td>
                      <td class="r-text"><?= intval($agreementBill->taxRate)?></td>
				      <?php foreach( $taxes as $tax ){?>
				        <td class="r-text"><?php $taxAmount = $agreementBill->getTaxAmountById($tax->tax_id);echo $taxAmount;$totalTax[$tax->tax_id] +=$taxAmount; ?></td>
						<?php }?>
					  <td class="r-text"><?php echo $agreementBill->payable_amount;$totalPayableAmount += $agreementBill->payable_amount;?></td>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="7" style="text-align:right"><b>Total</b></td>
			       <td class="r-text"><b><?= sprintf("%0.2f",$totalTaxableAmount)?></b></td>
			       <td>&nbsp;</td>
				   <?php foreach( $taxes as $tax ){?>
				     <td class="r-text"><b><?php echo sprintf("%0.2f",$totalTax[$tax->tax_id]);?></b></td>
				   <?php }?>
			       <td class="r-text"><b><?= sprintf("%0.2f",$totalPayableAmount);?></b></td>
			    </tr>    
				<tr>
				    <td colspan="<?= 6+count($totalTax)?>" style="text-align:right"><b>Total Tax &nbsp;</b></td>
				    <td class="m-text" colspan="3"><b><?= sprintf("%0.2f",array_sum($totalTax));?></b></td>
					<td>&nbsp;</td>
				</tr>
				
              </tbody>
		</table>
  </body>
</html>

<?php 
$this->endPage() ; ?>

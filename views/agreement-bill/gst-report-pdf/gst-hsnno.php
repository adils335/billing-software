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
          .p-5{
            padding: 5px;
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
		  .w50{
		      width:50% !important;
		  }
		</style>
  </head>
  <body>
           
		<table class="main-table">
		     <thead>
             <tr>
                      <th class="w3">S No.</th>
                      <th>HSN/SAC</th>
                      <th class="w50">Invoices</th>
				      <th>Taxable Value</th>
				      <?php foreach( $taxes as $tax ){$totalTax[$tax->tax_id] = 0;?>
				        <th><?php echo $tax->tax->name;?></th>
				      <?php }?>
                    </tr>
		     </thead>
		     
                <tbody>
                <?php
                $totalTaxableAmount = 0;
                $total_amount = 0;
                foreach ($model as $item):
                    $session = $item->invoice->session;
                    $invoices = $item->getInvoiceNoByInvoiceIds($item->remaining_quantity);
                    $invoices = array_map(function ($arrayValues) use ($session) {
                        return $session ."/". $arrayValues;
                    }, $invoices);
                    $invoices = implode(", ",$invoices);
                    ?>
                    
                    <tr>
                      <td class="m-text"><?= ++$counter;?></td>
                      <td class="m-text"><?= $item->hsn_no?></td>
                      <td class="p-5"><?= $invoices?></td>
                      <td class="r-text"><?php echo $amount = $item ? $item->getAmountByHsnNo($item->remaining_quantity,$item->hsn_no):0;$total_amount += $amount;?></td>
                      <?php foreach( $taxes as $tax ){?>
				        <td class="r-text">
                            <?php $taxAmount = $item ? $item->getTaxAmountByHsnNo($item->remaining_quantity,$item->hsn_no,$tax->tax_id):0;
                            echo $taxAmount;$totalTax[$tax->tax_id] +=$taxAmount; ?>
                        </td>
				      <?php }?>
                    </tr>
				    <?php endforeach;?>
				
			   <tr>
			       <td colspan="3" style="text-align:right"><b>Total</b></td>
			       <td class="r-text"><b><?= sprintf("%0.2f",$total_amount);?></b></td>
			       <?php foreach( $taxes as $tax ){?>
				     <td class="r-data r-text"><b><?php echo sprintf("%0.2f",$totalTax[$tax->tax_id]);?></b></td>
				   <?php }?>
			    </tr>    
				<tr>
				    <td colspan="<?= 1+count($totalTax)?>" style="text-align:right"><b>Total Tax</b></td>
				    <td class="r-data m-text" colspan="3"><b><?= sprintf("%0.2f",array_sum($totalTax) );?></b></td>
				</tr>
				
              </tbody>
		</table>
  </body>
</html>

<?php 
$this->endPage() ; ?>

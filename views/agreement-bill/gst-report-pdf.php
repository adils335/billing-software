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

          .tax-table td,.main-table td {
            border: 1px solid black;
          }
          .tax-table{
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
			  padding-right:3px !important;
		  }
		  .l-text{
			  text-align:left !important;
			  padding-left:3px !important;
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
		  .w7{
		      width:7% !important;
		  }
		  .w20{
		     width:20% !important; 
		  }
		  .w10{
		     width:10% !important; 
		  }
		  .w12{
		     width:12% !important; 
		  }
		</style>
  </head>
  <body>
           
		<table class="main-table">
		     <thead>
		         <tr>
		             <th class="w7">S.No</th>
		             <th class="w20">Agreement No</th>
		             <th class="w12">Date</th>
		             <?php 
		              $gstcount = 1; $indexGst = [];
		              foreach($gsts as $index => $gst){
		                $indexGst[] = $gst;
		                $gstcount++;
		                ?>
		                <th class="w12"><?= $index?></th>
		             <?php }?>
		             <th class="w12">Total</th>
		         </tr>
		     </thead>
		     <tbody>
		         <?php 
		         $sno = 1;$taxTotal = [];$grandtotal;
		         foreach($model as $bill){
		         ?>
		           <tr>
		               <td class="m-text"><?= $sno++?></td>
		               <td><?= $bill->agreement->agreement_no?></td>
		               <td><?= $bill->invoice_date?></td>
		               <?php 
		               $gsts = $bill->billTaxes;
		               $i = 0;
		               foreach($indexGst as $gst){?>
		                   <td class="r-text"><?php echo $tax = \app\models\BillTax::getTaxValue($bill->id,$gst);$taxTotal[$gst] += $tax;$grandtotal += $tax;?></td>
		               <?php $i++;}?>
		               <td class="r-text"><?= $bill->tax_amount?></td>
		           </tr>
		         <?php }?>
		         <tr>
		             <td colspan="3">Total</td>
		             <?php foreach($taxTotal as $total){?>
		                 <td class="r-text"><?= $total?></td>
		             <?php }?>
		             <td class="r-text"><?php echo $grandtotal;?></td>
		         </tr>
		     </tbody>
		</table>
  </body>
</html>

<?php 
$this->endPage() ; ?>

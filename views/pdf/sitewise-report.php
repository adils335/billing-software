<?php
use yii\helpers\Html;
use NumberToWords\NumberToWords;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
ini_set("pcre.backtrack_limit", "15000000");
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
		  .w5{
		      width:5% !important;
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
		  .w12{
		     width:12% !important; 
		  }
		  .w15{
		     width:15% !important; 
		  }
		  .w20{
		     width:20% !important; 
		  }
		</style>
  </head>
  <body>
      
      <?php $balance = 0;$sno = 1;?>
                 
                     <table class="main-table">
		                <thead>
		                    <tr>
                               <th>S.No</th>
                               <th>District</th>
                               <th>Site</th>
                               <th>Name</th>
                               <th>Date</th>
                               <th>Particular</th>
                               <th class="text-right">Debit</th>
                               <th class="text-right">Credit</th>
                               <th class="text-right">Balance</th>
		                    </tr>
		                </thead>
		                <tbody>
                 <?php  
                         foreach( $data as $payment ):?>
                             <tr>
                               <td class="w5"><?= $sno++;?></td>
                               <td class="w12"><?= $payment->district->district?></td>
                               <td class="w15"><?= $payment->site->name?></td>
                               <td class="w10"><?= $payment->credit? $payment->fromAccount : $payment->toAccount?></td>
                               <td class="w8"><?= Yii::$app->formatter->asDate($payment->date,"php:d-m-Y")?></td>
                               <td class="w25"><?= $payment->particular?></td>
                               <td class="text-right w8"><?= $payment->debit; $balance -= $payment->debit;?></td>
                               <td class="text-right w8"><?= $payment->credit; $balance += $payment->credit;?></td>
                               <td class="text-right w12"><?= $balance<0 ?abs($balance)." Dr": $balance . "Cr"?></td>
                             </tr>
                 <?php endforeach;?>
		                </tbody>
		            </table>
           
  </body>
</html>

<?php 
$this->endPage() ; ?>

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
                    <th>S.No</th>
                    <th>Name</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                    <th class="text-right">Current Balance</th>
		         </tr>
		     </thead>
		     <tbody>
		         
                <?php $counter = 1;
                if(!empty($model))
                foreach($model as $data){
                ?>
                  <tr>
                    <td><?= $counter++;?></td>
                    <td><?= $data['ledger']['name']?></td>
                    <!--<td class="text-right"><?= $data['ledger']['opening_bal']?></td>-->
                    <td class="text-right"><?= $data['ledger']['debit']?></td>
                    <td class="text-right"><?= $data['ledger']['opening_balance']+$data['ledger']['credit']?></td>
                    <td class="text-right"><?= $data['ledger']['bal']?></td>
                  </tr>
                <?php }?>
		     </tbody>
		</table>
  </body>
</html>

<?php 
$this->endPage() ; ?>

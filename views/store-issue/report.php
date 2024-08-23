<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use NumberToWords\NumberToWords;

// create the number to words "manager" class
$numberToWords = new NumberToWords();

// build a new number transformer using the RFC 3066 language identifier
$numberTransformer = $numberToWords->getNumberTransformer('en');

$formatter = Yii::$app->formatter;
$converter = Yii::$app->currency_formator;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Payment */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title> 
        <style>
        .main-table {
            border-collapse: collapse;
            overflow: wrap;
        }

        .main-table td {
            border: 1px solid black;
        }
        .main-table{
            font-size:14px;
            width:100%;
        }
        .bg-blue{
            font-size:14px;
            background-color:#0066FF !important;
            color:#fff !important;			 
        }
        .text-blue{
            font-size:14px;
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
        .w43{
            width:43% !important; 
        }
        .w8{
            width:8% !important;
        }
        .w12{
            width:12% !important;
        }
        </style>
    </head>
    <body>
           
    <table class="main-table">

        <tr>
            <td colspan="12" class="m-text mf text-blue"><span>Store Issue & Balance Statement</span></td>
        </tr>

        <tr>
            <td colspan="8" class="m-text mf text-blue"><span><?= $model->agreement->agreement_no?></span></td>
            <td colspan="4" class="m-text mf text-blue"><span><?= $formatter->asDate($model->date,'php:d-m-Y')?></span></td>

        </tr>
        <tr>
            <td colspan="12" class="l-text text-blue">Site : <span><?= $model->site->name?></span></td>
        </td>

        <tr>
            <td colspan="12" class="l-text text-blue"><span><?= $model->billingCompany->name?></span></td>
        </tr>

        <tr>
        <td colspan="12" class="l-text text-blue"><span><?= $model->comment?></span></td>
        </tr>
        <tr class="bg-blue">
            <td colspan="1" class="m-text w5">S.No</td>
            <!-- <td colspan="1" class="m-text w5">Date</td> -->
            <td colspan="3" class="m-text w8">Gate Pass No</td>
            <td colspan="3" class="m-text w8">Name Of Items</td>
            <td colspan="3" class="m-text w8">UOM</td>
            <td colspan="2" class="m-text w8">Qty</td>
        </tr>
        
        <?php 
            $sn = 1;
            $Items = $model->storeIssueItems;
            foreach($Items as $item){?>
            
        <tr class="item">
            <td colspan="1" class="m-text"><?= $sn++;?></td>
            <td colspan="3" class="m-text"><?= $item->gate_pass_no;?></td>
            <td colspan="3" class="m-text"><?= $item->storeProducts->name;?></td>
            <td colspan="3" class="m-text"><?= $item->uom->name;?></td>
            <td colspan="2" class="m-text"><?= $item->quantity;?></td>
        </tr>
        
        <?php } ?>
    </table>

<?php 
$this->endPage() ; ?>


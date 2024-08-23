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
            <td colspan="12" class="text-red m-text v-text">
                <img src="<?= \Yii::getAlias('@webroot/upload/logo/').$model->company->logo;?>" style="height:30px;">
                <span  style="letter-spacing: 5px;"><?= $model->company->name?></span>
            </td>
        </tr>
        <tr>
            <td colspan="12" class="text-blue m-text"><b><?= $model->company->address.', '.$model->company->districtName->district.', '.$model->company->stateName->state.' ('.$model->company->pincode.')';?></b></td>
        </tr>
        <tr>
            <td colspan="8" class="l-text text-red">
                <span>
                    <?php $vendors = $model->agreement->agreementVendors;
                        $vendorTxt = [];
                        foreach($vendors as $vendor){
                            $vendorTxt[] = $vendor->vendor_name." : ".$vendor->vendor_code;
                        }
                        echo implode(" ,",$vendorTxt);
                    ?>
                </span>
            </td>
                <td colspan="4" class="r-text">Email : <span><?= $model->company->email?></span>
            </td>
        </tr>

        <tr>
            <td colspan="4" class="l-text text-blue">Pancard No : <span><?= $model->company->pancard_no?></span></td>
            <td colspan="4" class="l-text text-red">GST No : <span><?= $model->company->gst_no?></span></td>
            <td colspan="4" class="l-text text-blue">Mobile No : <span><?= $model->company->number?></span></td>
            
        </tr>
        <tr>
            <td colspan="3" class="l-text text-blue">State : <span><?= $model->state->state?></span></td>
            <td colspan="1" class="l-text text-blue">Code : <span><?= $model->state->state_tin?></span></td>
            <td colspan="4" class="l-text text-blue lf">Invoice No : <span><?= $model->session."/"?></span><span class="text-red"><?= sprintf("%02d",$model->invoice_no)?></span></td>
            <td colspan="4" class="l-text text-blue mf">Date : <span><?= $formatter->asDate($model->created_at,'php:d-m-Y')?></span></td>
        </tr>
        
        <tr>
            <td colspan="12" class="m-text mf text-blue"><span>Store Consumed</span></td>
        </tr>

        <tr>
            <td colspan="1" class="l-text text-red">TO<span></span></td>
            <td colspan="4" class="l-text text-blue"><span><?= $model->billingCompany->name?></span></td>
            <td colspan="7" class="l-text text-blue"><span><?= $model->comment?></span></td>
        </tr>

        <tr>
            <td colspan="8" class="l-text text-red"><span><?= $model->bill_no?></span></td>
            <td colspan="4" class="l-text text-blue mf">Invoice Date : <span><?= $formatter->asDate($model->invoice_date,'php:d-m-Y')?></span></td> 
        </tr>

        <tr>
            <td colspan="8" class="l-text text-red"><span><?= $model->agreement->agreement_no?></span></td>
            <td colspan="4" class="l-text text-blue">Site : <span><?= $model->site->name?></span></td>
        </tr>
        <tr class="bg-blue">
            <td colspan="1" class="m-text w5">S.No</td>
            <td colspan="6" class="m-text w8">Name Of Items</td>
            <td colspan="3" class="m-text w8">UOM</td>
            <td colspan="2" class="m-text w8">Qty</td>
        </tr>
        
        <?php 
            $sn = 1;
            $Items = $model->storeConsumedItems;
            foreach($Items as $item){?>
            
        <tr class="item">
            <td colspan="1" class="m-text"><?= $sn++;?></td>
            <td colspan="6" class="m-text"><?= $item->storeProducts->name;?></td>
            <td colspan="3" class="m-text"><?= $item->uom->name;?></td>
            <td colspan="2" class="m-text"><?= $item->quantity;?></td>
        </tr>
        
        <?php } ?>
    </table>

<?php 
$this->endPage() ; ?>

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
              font-size:14px;
              width:100%;
          }
		  .bg-blue{
			 background-color:#0066FF !important;
             color:#fff !important;			 
		  }
		  .text-blue{
			  color:darkblue !important;
		  }
		  .text-red{
			  color:darkred !important;
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
			  font-size:11px !important;
			  font-weight:bold !important;
		  }
		  .mf{
			  font-size:12px !important;
			  font-weight:bold !important;
		  }
		  .lf{
			  font-size:15px !important;
			  font-weight:bold !important;
		  }
		  .w6{
		      width:6% !important;
		  }
		  .w40{
		     width:40% !important; 
		  }
		  .w54{
		     width:54% !important; 
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
		  .w25{
		      width:25% !important;
		  }
		  .w75{
		      width:75% !important;
		  }
		  .w30{
		      width:30% !important;
		  }
		  .w20{
		      width:20% !important;
		  }
		  .logo-table{
              border-collapse: collapse;
              overflow: wrap;
		      width:100%;
		  }
		  .logo-table td{
            border: 1px solid black;
		  }
		</style>
  </head>
  <body>
        
        <table class="logo-table">
            <tr>
                <td class="w25 r-text" style="border-right:none;border-bottom:none;"><img src="<?= \Yii::getAlias('@webroot/upload/logo/').$model->company->logo;?>" width="100" height="50"/></td>
                <td class="w75 text-red v-text" style="border-left:none;border-bottom:none;"><b  style="letter-spacing: 5px;"><?= $model->company->name?></b></td>
            </tr>
        </table>   
		<table class="main-table">
		     
			 <tr>
			     <td colspan="12" class="text-blue m-text"><b><?= $model->company->address.', '.$model->company->districtName->district.', '.$model->company->stateName->state.' ('.$model->company->pincode.')';?></b></td>
			 </tr>
		
			 <tr>
			     <td colspan="12" class="m-text mf text-blue"><span>Tax Invoice</span></td>
			 </tr>
		
			 <tr>
			     <td colspan="4" class="l-text text-red sf">
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
			     <td colspan="5" class="l-text sf">Mobile No : <span><?= $model->company->number?></span></td>
			     <td colspan="3" class="l-text sf">Email : <span><?= $model->company->email?></span></td>
			 </tr>
		
			 <tr>
			     <td colspan="4" class="l-text text-blue sf">Pancard No : <span><?= $model->company->pancard_no?></span></td>
			     <td colspan="5" class="l-text text-red mf">GST No : <span><?= $model->company->gst_no?></span></td>
			     <td colspan="3" class="l-text text-blue sf">State : <span><?= $model->agreement->state->state?></span></td>
			 </tr>
			 
			 <tr>
			     <!--<td colspan="4" class="l-text text-blue sf">State : <span><?//= $model->agreement->state->state?></span></td>
			     <td colspan="1" class="l-text text-blue sf">Code : <span><?//= $model->agreement->state->state_tin?></span></td>-->
			     <td colspan="6" class="l-text text-blue lf">Invoice No : <span><?= $model->session."/"?></span><span class="text-red"><?= sprintf("%02d",$model->invoice_no)?></span></td>
			     <td colspan="6" class="l-text text-blue mf">Invoice Date : <span><?= $formatter->asDate($model->invoice_date,'php:d-m-Y')?></span></td>
			 </tr>
			 
			 <tr>
			     <td colspan="2" class="m-text sf text-blue"><span>Bill To Party</span></td>
			     <td colspan="10" class="m-text sf text-blue">
			         <span class="text-blue">Name/Address : </span>
			         <?php $billToParty = $model->agreement->contractCompany->name."/".$model->agreement->contractCompanyDistrict->district;?>
			         <span><?= $billToParty?></span>
			         <?php $i = intval(strlen($billToParty)/72);?>
			     </td>
			 </tr>
			 
			 <tr>
			     <td colspan="4" class="l-text sf text-red w30">GSTIN: <span><?= $model->agreement->contract_company_gst?></span></td>
			     <td colspan="4" class="l-text sf text-blue w20">State: <span><?= $model->agreement->contractCompanyState->state?></span></td>
			     <td colspan="4" class="l-text sf">
			         <span class="text-blue l-text">Date of Start : </span>
			         <?php if($model->start_date):?>
			         <span><?= $formatter->asDate($model->start_date,'php:d-m-Y')?></span>
			         <?php endif;?>
			     </td>
			 </tr>
			 
			 <tr>
			     <td colspan="2" class="m-text sf text-blue"><span>Ship To Party</span></td>
			     <td colspan="10" class="l-text sf">
			         <span class="text-blue">Name/Address : </span>
			         <?php $shipToParty = $model->billingCompany->name."/".$model->billingCompanyDistrict->district;?>
			         <span><?= $shipToParty?></span>
			         <?php $i = intval(strlen($shipToParty)/72);?>
			     </td>
			 </tr>
		
			 <tr>
			     <td colspan="4" class="l-text sf text-red w30">GSTIN: <span><?= $model->billing_company_gst?></span></td>
			     <td colspan="4" class="l-text sf text-blue w20">State: <span><?= $model->billingCompanyState->state?></span></td>
			     <td colspan="4" class="l-text sf">
			         <?php if($model->complete_date):?>
			         <span class="text-blue">Date of Complete : </span>
			         <span><?= $formatter->asDate($model->complete_date,'php:d-m-Y')?></span>
			         <?php endif;?>
			     </td>
			 </tr>
			 <tr>
			     <td colspan="12" class="l-text sf">
			         <?php if($model->extra_note):?>
			         <?= $model->extra_note?>
			         <?php endif;?>
			     </td>
			 </tr>
			 
			 <tr>
			     <td colspan="12" class="l-text sf text-red w30"><span><?= $model->agreement->agreement_no?></span></td>
			 </tr>
			 <tr>
			     <td colspan="12" class="l-text sf text-blue w30"><span><?= $model->work_name?></span></td>
			 </tr>
		    
			 <tr>
			     <td colspan="1" class="m-text sf w6 text-blue">S.No</td>
			     <td colspan="<?= $model->has_percentage?5:6;?>" class="l-text sf <?= $model->has_percentage?"w36":"w36";?> text-blue">Product Description</td>
			     <td colspan="1" class="m-text sf w10 text-blue">HSN/SAC</td>
			     <td colspan="1" class="m-text sf w8 text-blue">UOM</td>
			     <td colspan="1" class="r-text sf w4 text-blue">Qty</td>
			     <td colspan="1" class="r-text sf w8 text-blue">Rate</td>
			     <?php if( $model->has_percentage ):?>
			     <td colspan="1" class="r-text sf w8 text-blue">Per(%)</td>
			     <?php endif;?>
			     <td colspan="1" class="r-text sf w8 text-blue">Amount</td>
			 </tr>
		     
			 <?php 
			       $sn = 1;
			       $billItems = $model->billItems;
			       foreach($billItems as $item){?>
				   
			 <tr class="item">
			     <td colspan="1" class="m-text sf"><?= $sn++;?></td>
			     <td colspan="<?= $model->has_percentage?5:6;?>" class="l-text sf"><?= htmlspecialchars($item->itemName->item);?></td>
			     <td colspan="1" class="m-text sf"><?= $item->hsn_no;?></td>
			     <td colspan="1" class="m-text sf"><?= $item->unitName->name;?></td>
			     <td colspan="1" class="r-text sf"><?= $item->quantity;?></td>
			     <td colspan="1" class="r-text sf"><?= $item->rate;?></td>
			     <?php if( $model->has_percentage ):?>
			     <td colspan="1" class="r-text sf"><?= $item->percentage;?></td>
			     <?php endif;?>
			     <td colspan="1" class="r-text sf"><?= $item->amount;?></td>
			 </tr>
		     
				   <?php 
				     $i = $i + 1 + intval(strlen($item->itemName->item)/68);
				   } ?>
			    
		     <?php 
			 $billPenalties = $model->billPenalties;
			 if($billPenalties):
			     $i+= 4;
			 foreach($billPenalties as $penality):
			     $i++;
			 endforeach;
			 
			 endif;
			 ?>
			 <?php 
			       while($i < 28){?>
				   
			 <tr class="item">
			     <td colspan="1" class="m-text sf">&nbsp;</td>
			     <td colspan="<?= $model->has_percentage?5:6;?>" class="l-text sf">&nbsp;</td>
			     <td colspan="1" class="m-text sf">&nbsp;</td>
			     <td colspan="1" class="m-text sf">&nbsp;</td>
			     <td colspan="1" class="r-text sf">&nbsp;</td>
			     <td colspan="1" class="r-text sf">&nbsp;</td>
			     <?php if( $model->has_percentage ):?>
			     <td colspan="1" class="r-text sf">&nbsp;</td>
			     <?php endif;?>
			     <td colspan="1" class="r-text sf">&nbsp;</td>
			 </tr>
		     
				   <?php $i++; } ?>
			 <tr>
			     <td colspan="11" class="r-text sf">Total</td>
			     <td colspan="1" class="r-text sf"><?= $model->base_amount;?></td>
			 </tr>
			 
		     <?php if($model->schedule):?>
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Schedule <?= $model->agreement->scheduleLabel;?> @ <?= $model->schedule_rate;?></td>
			     <td colspan="1" class="r-text sf"><?= $model->schedule_amount;?></td>
			 </tr>
		     <?php endif;?>
			 <!--
			 <?php if($model->has_percentage && $model->lastBillInvoiceNo):?>
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Total Amount</td>
			     <td colspan="1" class="r-text sf"><?= $model->total_amount;?></td>
			 </tr>
			 
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Payment of Invoice <?= $model->lastBillInvoiceNo;?></td>
			     <td colspan="1" class="r-text sf"><?= $model->lastBillAmount;?></td>
			 </tr>
			 
			 <?php endif;?>
			 -->
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Taxable Amount</td>
			     <td colspan="1" class="r-text sf"><?= $model->taxable_amount;?></td>
			 </tr>
			 
		     <?php if($model->billTaxes):?>
			 
		     <?php 
			 $billTaxes = $model->billTaxes;
			 foreach($billTaxes as $tax):?>
			 <?php if($tax->amount != 0):?>
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf"><?= $tax->tax->name;?> @ <?= $tax->rate;?></td>
			     <td colspan="1" class="r-text sf"><?= $tax->amount;?></td>
			 </tr>
			 <?php endif;?>
		     <?php endforeach;?>
			 <?php if($model->tax_amount != 0):?>
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Total Tax</td>
			     <td colspan="1" class="r-text sf"><?= $model->tax_amount;?></td>
			 </tr> 
			 <?php endif;?>
			 
		     <?php endif;?>
			 
		     <?php if($model->billPenalties):?>
			 
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Total After Tax</td>
			     <td colspan="1" class="r-text sf"><?= $model->after_tax_total;?></td>
			 </tr>
			 
		     <?php 
			 foreach($billPenalties as $penality):?>
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf"><?= $penality->tax->name;?></td>
			     <td colspan="1" class="r-text sf"><?= $penality->amount;?></td>
			 </tr>
		     <?php endforeach;?>
			 
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Total Penality</td>
			     <td colspan="1" class="r-text sf"><?= $model->penality_amount;?></td>
			 </tr>
			 
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Penality Tax @ 18%</td>
			     <td colspan="1" class="r-text sf"><?= $model->penality_tax;?></td>
			 </tr>
			 
			 <tr>
			     <td colspan="7" class="r-text sf">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Penality After Tax</td>
			     <td colspan="1" class="r-text sf"><?= $model->penality_after_tax;?></td>
			 </tr>
			 
		     <?php endif;?>
		     
		     <?php if($model->advance_paid):?>
		     <tr>
			     <td colspan="7" class="l-text sf text-red">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Otf Advance Paid</td>
			     <td colspan="1" class="r-text sf"><?= $model->advance_paid;?></td>
			 </tr>
			 <?php endif;?>
			 
			 <tr>
			     <td colspan="7" class="l-text sf text-red">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Total Amount</td>
			     <td colspan="1" class="r-text sf"><?= $model->payable_amount;?></td>
			 </tr>
			 
			 <tr>
			     <td colspan="7" class="l-text sf text-blue">&nbsp;</td>
			     <td colspan="4" class="r-text sf">Say Amount</td>
			     <td colspan="1" class="r-text sf"><?= sprintf("%0.2f",round($model->payable_amount));?></td>
			 </tr>
			 
			 <tr>
			     <td colspan="12" class="l-text sf text-red">Amount in Words:- <?= $converter->toWords(round($model->payable_amount));?></td>
			 </tr>
			 
			 <tr>
			     <td colspan="12" class="r-text" style="border:none !important"><?= $model->signature->signature?></td>
			 </tr>
			 
		</table>
		<br>
		<div class="r-text">
		     
	    </div>
	    
	    <!--page Break-->
	    <pagebreak />
	    <!--page Break-->
	    
	    <table>
	        <?php
	         $billBackAll =  $model->billBack;
	         if($billBackAll){     ?>
	            <tr>
	                <td colspan="2"><b>Certified that</b></td>
	            </tr>
	         <?php 
	         foreach($billBackAll as $billBack):
	         ?>
	             
	             <tr>
	                 <td><?= $billBack->sno?>.</td>
	                 <td><?= $billBack->description?></td>
	             </tr>
	             
	         <?php 
	         endforeach;
	         } ?>
	    </table>
	    <div style="position: absolute; bottom: 40mm;">
	            <table width="100%">
	                <tr>
	                    <td width="50%">
	                        <table class="tax-table">
	         
			                    <tr>
			                        <td>Taxable Amount</td>
			                        <td>Rs.</td>
			                        <td class="r-text"><?= $model->taxable_amount;?></td>
			                    </tr>
			 
		                        <?php 
			                    $billTaxes = $model->billTaxes;
		                        if($billTaxes):?>
			                    
		                        <?php 
			                    foreach($billTaxes as $tax):?>
			                    <?php if($tax->amount != 0):?>
			                    <tr>
			                        <td><?= $tax->tax->name;?> @ <?= $tax->rate;?></td>
			                        <td>Rs.</td>
			                        <td class="r-text"><?= $tax->amount;?></td>
			                    </tr>
			                    <?php endif;?>
		                        <?php endforeach;?>
			                    
			                    <tr>
			                        <td>Grand Total</td>
			                        <td>Rs.</td>
			                        <td class="r-text"><?= sprintf("%0.2f",round($model->payable_amount));?></td>
			                    </tr>
			                    
		                        <?php endif;?>
	                        </table>
	                    </td>
	                    <td width="40%"><?= $model->tax_note?></td>
	                </tr>
	                <tr>
	                    <td colspan="2" style="padding-left:50px;">Passed for <b style="min-width:100px">&nbsp;</b> Rs. <?= round($model->payable_amount)?> /- 
	                    <b style="min-width:200px">&nbsp;</b> Rupees <?= $converter->toWords(round($model->payable_amount));?> </td>
	                </tr>
	                
	                <tr>
	                    <td colspan="2" style="height:150px"></td>
	                </tr>
	                
	                <?php $deductions = $model->billDeductions;
	                if($deductions):
	                ?>
	                    <tr>
	                        <td width="50%">
	                            <table class="tax-table">
	                                <?php foreach($deductions as $deduction):?>
	                                   <tr>
			                              <td><?= $deduction->tax->name;?> <?php if($deduction->rate):?>@ <?= $deduction->rate;?><?php endif;?></td>
			                              <td>Rs.</td>
			                              <td class="r-text"><?= $deduction->amount;?></td>
			                           </tr>
	                                <?php endforeach;?>  
			                           <tr>
			                               <td>Total Deduction</td>
			                               <td>Rs.</td>
			                               <td class="r-text"><?= $model->deduction_amount;?></td>
			                           </tr>
	                            </table>
	                        </td>
	                        <td width="40%"><?= $model->deduction_note?></td>
	                    </tr>
	                    <tr>
	                        <td colspan="2" style="padding-left:50px;">Net Payment <b style="min-width:100px">&nbsp;</b> Rs. <?= intval($model->pay_amount)?> /- 
	                        <b style="min-width:200px">&nbsp;</b> Rupees <?= $converter->toWords(intval($model->pay_amount));?></td>
	                    </tr>
	                <?php 
	                endif;?>
	            </table>
	        </div>
  </body>
</html>

<?php 
$this->endPage() ; ?>

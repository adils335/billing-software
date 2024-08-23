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
			  color:#0066FF !important;
		  }
		  .text-red{
			  color:red !important;
		  }
		  .r-text{
			  text-align:right !important;
			  padding-right:10px !important;
		  }
		  .l-text{
			  text-align:left !important;
			  padding-left:10px !important;
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
			  font-size:14px !important;
			  font-weight:bold !important;
		  }
		</style>
  </head>
  <body>
           
		<table class="main-table">
		     
			 <tr>
			     <td colspan="12" class="text-red m-text v-text">
					<img src="<?= \Yii::getAlias('@webroot/upload/logo/').$model->company->logo;?>" width="100" height="50"/>
					<span><?= $model->company->name?></span>
				 </td>
			 </tr>
		
			 <tr>
			     <td colspan="12" class="text-blue m-text"><b><?= $model->company->address.', '.$model->company->districtName->district.', '.$model->company->stateName->state.' ('.$model->company->pincode.')'?></b></td>
			 </tr>
		
			 <tr>
			     <td colspan="12" class="m-text"><b>Purchase / Measurement Document</b></td>
			 </tr>
		
			 <tr>
			     <td colspan="4" class="l-text text-blue sf">Pancard No : <span><?= $model->company->pancard_no?></span></td>
			     <td colspan="4" class="l-text text-red sf">GST No : <span><?= $model->company->gst_no?></span></td>
			     <td colspan="4" class="l-text text-blue sf">Mobile No : <span><?= $model->company->number?></span></td>
			 </tr>
			 
			 <tr>
			     <td colspan="4" class="l-text text-blue sf">Email : <span><?= $model->company->email?></span></td>
			     <td colspan="4" class="l-text text-blue sf">Document No : <span><?= $model->session."/".$model->bill_no?></span></td>
			     <td colspan="4" class="l-text text-blue sf">Document Date : <span><?= $formatter->asDate($model->created_at,'php:d-m-Y')?></span></td>
			 </tr>
			 
			 <tr>
			     <td colspan="12" class="m-text mf bg-blue"><span>Vendor Detail</span></td>
			 </tr>
		
			 <tr>
			     <td colspan="6" class="l-text sf"><span class="text-blue">Name : </span><span><?= $model->vendor->name?></span></td>
			     <td colspan="6" class="l-text sf"><span class="text-blue">Address : </span><span><?= $model->vendor->address?></span></td>
			 </tr>
		
			 <tr>
			     <td colspan="6" class="l-text sf text-red">GSTIN : <span><?= $model->vendor->gst_no?></span></td>
			     <td colspan="6" class="l-text sf text-blue">Pancard No : <span><?= $model->vendor->pancard_no?></span></td>
			 </tr>
		
			 <tr>
			     <td colspan="6" class="l-text sf text-blue">Company Name : <span><?= $model->vendor->company_name?></span></td>
			     <td colspan="6" class="l-text sf text-blue">Company Type : <span><?= $model->vendor->company_type?></span></td>
			 </tr>
		
			 <tr>
			     <td colspan="6" class="l-text sf text-blue">Invoice No : <span class="text-red"><?= $model->invoice_no?></span></td>
			     <td colspan="6" class="l-text sf text-blue">Invoice Date : <span><?= !empty($model->invoice_date)?$formatter->asDate($model->invoice_date,'php:d-m-Y'):"";?></span></td>
			 </tr>
		
			 <tr class="bg-blue">
			     <td colspan="1" class="m-text sf">S.No</td>
			     <td colspan="6" class="l-text sf">Work Description</td>
			     <td colspan="1" class="m-text sf">UOM</span></td>
			     <td colspan="1" class="m-text sf">Hsn No</span></td>
			     <td colspan="1" class="r-text sf">Qty</span></td>
			     <td colspan="1" class="r-text sf">Rate</span></td>
			     <td colspan="1" class="r-text sf">Amount</span></td>
			 </tr>
		     
			 <?php 
			       $sn = 1;
			       $billItems = $model->getVendorBillItems()->groupBy(['district_id','site_id'])->all();
			       foreach($billItems as $filterItems){
                   			$items = $filterItems->itemDistrictSiteWise($filterItems->bill_id,$filterItems->district_id,$filterItems->site_id);
							?>
							
			 <tr>
			     <td colspan="12" class="l-text mf text-red"><span><?= $filterItems->district->district?>/<?= $filterItems->site->name?></span></td>
			 </tr>
		
							<?php
							foreach($items as $item){
					   ?>
				   
			 <tr>
			     <td colspan="1" class="m-text sf"><?= $sn++;?></td>
			     <?php if($item->particular){?>
			     <td colspan="6" class="l-text sf"><?= $item->workName->name . " " . $item->particular;?></td>
			     <td colspan="1" class="m-text sf"><?= $item->uom->name;?></span></td>
			     <?php }else{?>
			     <td colspan="6" class="l-text sf"><?= $item->workName->name;?></td>
			     <td colspan="1" class="m-text sf"><?= $item->workName->unitName->name;?></span></td>
			     <?php }?>
			     <td colspan="1" class="m-text sf"><?= $item->hsn_no;?></span></td>
			     <td colspan="1" class="r-text sf"><?= $item->quantity;?></span></td>
			     <td colspan="1" class="r-text sf"><?= $item->rate;?></span></td>
			     <td colspan="1" class="r-text sf"><?= $item->amount;?></span></td>
			 </tr>
		     
							<?php }} ?>
			    
			 <?php 
			       while($sn < 25){?>
				   
			 <tr>
			     <td colspan="1" class="m-text sf">&nbsp;</td>
			     <td colspan="6" class="l-text sf">&nbsp;</td>
			     <td colspan="1" class="m-text sf">&nbsp;</span></td>
			     <td colspan="1" class="m-text sf">&nbsp;</span></td>
			     <td colspan="1" class="r-text sf">&nbsp;</span></td>
			     <td colspan="1" class="r-text sf">&nbsp;</span></td>
			     <td colspan="1" class="r-text sf">&nbsp;</span></td>
			 </tr>
		     
				   <?php $sn++; } ?>
			 <tr>
			     <td colspan="11" class="r-text sf">Total</td>
			     <td colspan="1" class="r-text sf"><?= $model->base_amount;?></span></td>
			 </tr>
			 
		     <?php if($model->schedule):?>
			 <tr>
			     <td colspan="6" class="r-text sf">&nbsp;</td>
			     <td colspan="5" class="r-text sf"><?= $model->scheduleLabel;?> @ <?= $model->schedule_rate;?></span></td>
			     <td colspan="1" class="r-text sf"><?= $model->schedule_amount;?></span></td>
			 </tr>

			 <tr>
			     <td colspan="6" class="r-text sf">&nbsp;</td>
			     <td colspan="5" class="r-text sf">Taxable Amount</span></td>
			     <td colspan="1" class="r-text sf"><?= $model->taxable_amount;?></span></td>
			 </tr>

		     <?php endif;?>
			 
		     <?php if($model->vendorBillTaxes):?>
			 
		     <?php 
			 $billTaxes = $model->vendorBillTaxes;
			 foreach($billTaxes as $tax):?>
			 <tr>
			     <td colspan="6" class="r-text sf">&nbsp;</td>
			     <td colspan="5" class="r-text sf"><?= $tax->tax->name;?> @ <?= $tax->rate;?></span></td>
			     <td colspan="1" class="r-text sf"><?= $tax->amount;?></span></td>
			 </tr>
		     <?php endforeach;?>
			 
			 <tr>
			     <td colspan="6" class="r-text sf">&nbsp;</td>
			     <td colspan="5" class="r-text sf">Total Tax</span></td>
			     <td colspan="1" class="r-text sf"><?= $model->tax_amount;?></span></td>
			 </tr>
			 
		     <?php endif;?>
			 
			 <tr>
			     <td colspan="6" class="l-text sf text-red">Amount in Words</td>
			     <td colspan="5" class="r-text sf">Total Amount</span></td>
			     <td colspan="1" class="r-text sf"><?= $model->payable_amount;?></span></td>
			 </tr>
			 
			 <tr>
			     <td colspan="6" class="l-text sf text-blue"><?= $numberTransformer->toWords(intval($model->payable_amount));?> Rupees Only</td>
			     <td colspan="5" class="r-text sf">Say Amount</span></td>
			     <td colspan="1" class="r-text sf"><?= sprintf("%0.2f",intval($model->payable_amount));?></span></td>
			 </tr>
			 
		</table>   

		
	    <!--page Break-->
	    <pagebreak />
	    <!--page Break-->
	    
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
			                    $billTaxes = $model->vendorBillTaxes;
		                        if($billTaxes):?>
			                    
		                        <?php 
			                    foreach($billTaxes as $tax):?>
			                    <tr>
			                        <td><?= $tax->tax->name;?> @ <?= $tax->rate;?></td>
			                        <td>Rs.</td>
			                        <td class="r-text"><?= $tax->amount;?></td>
			                    </tr>
		                        <?php endforeach;?>
			                    
			                    <tr>
			                        <td>Grand Total</td>
			                        <td>Rs.</td>
			                        <td class="r-text"><?= $model->payable_amount;?></td>
			                    </tr>
			                    
		                        <?php endif;?>
	                        </table>
	                    </td>
	                    <td width="40%"><?//= $model->tax_note?></td>
	                </tr>
	                <tr>
	                    <td colspan="2" style="padding-left:50px;">Passed for <b style="min-width:100px">&nbsp;</b> Rs. <?= intval($model->payable_amount)?> /- 
	                    <b style="min-width:200px">&nbsp;</b> Rupees <?= $numberTransformer->toWords(intval($model->payable_amount));?> </td>
	                </tr>
	                
	                <tr>
	                    <td colspan="2" style="height:150px"></td>
	                </tr>
	                
	                <?php $deductions = $model->vendorBillDeductions;
	                if($deductions):
	                ?>
	                    <tr>
	                        <td width="50%">
	                            <table class="tax-table">
	                                <?php foreach($deductions as $deduction):?>
	                                   <tr>
			                              <td><?= $deduction->tax->name;?> <?php if($deduction->is_rate == 1):?>@ <?= $deduction->rate;?><?php endif;?></td>
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
	                        <td width="40%"><?//= $model->deduction_note?></td>
	                    </tr>
	                    <tr>
	                        <td colspan="2" style="padding-left:50px;">Net Payment <b style="min-width:100px">&nbsp;</b> Rs. <?= intval($model->pay_amount)?> /- 
	                        <b style="min-width:200px">&nbsp;</b> Rupees <?= $numberTransformer->toWords(intval($model->pay_amount));?></td>
	                    </tr>
	                <?php 
	                endif;?>
	            </table>
	        </div>
		   
  </body>
</html>

<?php 
$this->endPage() ; ?>

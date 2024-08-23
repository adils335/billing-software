<?php
use yii\helpers\Html;
use app\models\AssessmentSteps\Task;
use app\components\MyHelpers;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

$signaturestate='';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title> 
    <!-- <link rel="stylesheet" href="<?=Yii::getAlias('@webroot/css/')?>style.css" media="all" /> -->
  </head>
  <body>
  <style>
	p,span,td,div{
		font-size:13px;
		font-weight: 300;
		font-family: Helvetica;
	}
	
	h1,h2,h3,h4,h4,h5,h6,table,tr{
		font-family: Helvetica;
	}
  </style>
    <table width="100%"  >
		
		<tr>
			<td width="65%" style="vertical-align:top" valign="top">
				<h2 style="color: #c63d3d;"><span style="border-bottom:3px solid #c63d3d;font-size:14px">Fixprice Offer </span></h2>
				<br>
				<br>
				<br>
				<br>
					
				<br>
				<br>
				<p><?=Yii::t('app', ' Junker Umzüge, Kurfürstendamm 234, 10719 Berlin')?></p><br/>
				<?php 
				
					$salutation=trim($assessment->lead->title);
					if($salutation=='Herr.' || $salutation=='Herr' ) 
					$salutation='Mr';
					elseif($salutation=='Frau.' || $salutation=='Frau.' || $salutation=='Frau')
					$salutation='Ms'; 
					
					if($salutation=='firma')$salutation	='';
					
				?>
				<?php if($assessment->different_billing==1) {
				?>
					<p><b><?=!empty($assessment->billing_company)?$assessment->billing_company:''?></b></p>
				
					<div><b><?=$salutation?></b></div>
				
					<p>
					<span><b>
						<?=$assessment->billing_name ?><br />
						<?=$assessment->billing_street_number?>
					</b></span>
					</p>
					<?php 
						$signaturestate=$assessment->billing_city;
					?>
					<p><span ><b><?=$assessment->billing_zipcode." ".$assessment->billing_city?></b></span></p>

				<?php 
				}else{ ?>
					<p><b><?=!empty($assessment->lead->company)?$assessment->lead->company:''?></b></p>
				<?php if(!empty($salutation)){?>
					<div><b><?=$salutation?></b></div>
				<?php }?>
				<p>
					<span ><b>
					<?php if(!empty($assessment->lead->first_name) || !empty($assessment->lead->last_name)){?>
						<?=$assessment->lead->first_name." ".$assessment->lead->last_name ?><br />
					<?php }?>
						<?=$assessment->lead->pickup_address?>
					</b></span>
				</p>
				<p><span ><b><?=$assessment->lead->pickup_zipcode." ".(!empty($assessment->lead->pickupCity->name)?$assessment->lead->pickupCity->name:'')?></b></span></p>
				<?php 
					$signaturestate=(!empty($assessment->lead->pickupCity->name)?$assessment->lead->pickupCity->name:'');
				?>
				<?php } ?>
				
				<?php if(!$without_contact){?>
				<br>
				<br>
				<div><span><b><?=Yii::t('app', 'Tel.: ').$assessment->lead->phone_no ?></b></span></div>
				<?php 
					if(!empty($assessment->lead->additional_phone)):
					$additional_phone =json_decode($assessment->lead->additional_phone,true);
					$i=0;
					foreach($additional_phone as $key=>$value):$i++;
				?>
				<div><span ><b><?=Yii::t('app', 'Tel')." $i: ".$value ?></b></span></div>
				<?php endforeach;endif ?>
				
				<div ><a href="mailto::<?=$assessment->lead->email?>" ><?=$assessment->lead->email?></a></div>
				<?php 
					if(!empty($assessment->lead->additional_email)):
					$additional_email =json_decode($assessment->lead->additional_email,true);
					$i=0;
					foreach($additional_email as $key=>$value):$i++;
				?>
				
				<div> <a href="mailto::<?=$value?>" ><?=$value?></a></div>
				
			
				<?php endforeach;endif ?>
			<?php }?>
			</td>
			<td style="background:#f2f3f4" > 
				<div class="imgpop" >
					<img src="<?= Yii::getAlias('@webroot/images/junker.png') ?>" alt="junker" border="0" style="display: block; border: none; outline: none; text-decoration: none; width:240px;">
					<br/>
					<table style="padding:10px 0px">
						<tr>
							<td width="10%" style="padding-left:10px" >
								<img src="<?= Yii::getAlias('@webroot/images/phone-red.png') ?>" style="width:10px">
							</td>
							<td >
								  030 588 482 00
							</td>
						</tr>
						<tr>
							<td style="padding-left:10px" >
								<img src="<?= Yii::getAlias('@webroot/images/printer-red.png') ?>" style="width:10px">
							</td>
							<td >
								  030-577 026 111
								  
							</td>
						</tr>
						<tr>
							<td  style="padding-left:10px" >
								<img src="<?= Yii::getAlias('@webroot/images/envelope_red.png') ?>" style="width:10px">
							</td>
							<td  >
								  info@umzugsfirma-junker-berlin.de
							</td>
						</tr>
						<tr>
							<td style="padding-left:10px" >
								<img src="<?= Yii::getAlias('@webroot/images/world-red.png') ?>" style="width:10px">
							</td>
							<td  style="font-size:12px;">
								  www.umzugsfirma-junker-berlin.de
								  
							
							</td>
						</tr>
					</table >
					

					
				</div>
            </td>
		</tr>
	</table>

<?php 

if(!empty($assessment->date_not_fixed))$date=empty($assessment->date_not_fixed_text)?"according to the arrangement":$assessment->date_not_fixed_text;
else {
	if(!empty($assessment->drop_date)){
		$date =Yii::$app->formatter->asDate($assessment->pickup_date, 'php:d.m.')."-".Yii::$app->formatter->asDate($assessment->drop_date, 'php:d.m.Y');
	}else{
		$date=!empty($assessment->pickup_date)?Yii::$app->formatter->asDate($assessment->pickup_date, 'php:d.m.Y').' (
according to the arrangement)':'';
	}
	
}
	
?>

<table width="100%" style="margin-bottom:10px">
	<tr>
		<td>
		</td>
		<td align="right">
			 <span ><b><?=Yii::t('app', 'Offer Date:')?></b> <?=Yii::$app->formatter->asDate($lead->quotation_created_at, 'php:d.m.Y');?></span>
		</td>
	</tr>
	<tr>
		<td><b>Moving Date: <?=$date ?>  </b>
		</td>
		<td align="right">
			
			<span style="float:right"><b><?=Yii::t('app', 'Offer Number:')?></b> <?=$lead->setQuotationNoWithoutlastname($assessment->id)?></span>
		
		</td>
	</tr>
	
</table>
<br/>
<?php 
$liefern="No";
$Abholen="Nein";
$umzugs_karton=0;
$bucher_karton=0;
$kleider_box=0;
$luftpolsterfolie=0;
$packpaper=0;
$assessmentTasks=$assessment->assessmentTasks	;
$montage='<table width="100%">';
$montage_data="";
$Arbeiten='';
$einpacken='<table width="100%">';
$einpacken_data='';

if($assessment->abholung==1){
	$Abholen= 'Ja';
}
$bohrarbeiten=array();

foreach($assessmentTasks as $key=>$value){
	if(is_numeric($value->umzugs_karton)){
		$umzugs_karton+=$value->umzugs_karton;
	}
	if(is_numeric($value->umzugs_karton)){
		$bucher_karton+=$value->bucher_karton;
	}
	if(is_numeric($value->kleider_box)){
		$kleider_box+=$value->kleider_box;
	}
	if(is_numeric($value->luftpolsterfolie)){
		$luftpolsterfolie+=$value->luftpolsterfolie;
	}
	
	if(is_numeric($value->packpaper)){
		$packpaper+=$value->packpaper;
	}
	
	if($value->umzugs_karton>0 ||$value->bucher_karton>0 ||$value->kleider_box>0  ||$value->luftpolsterfolie>0 ||$value->packpaper>0 ){
		$liefern= 'Yes';
	}
	
	$taskitems=$value->assessmentTaskItems;
	$montage_room='<tr><td style="padding-top:5px" colspan="3" ><b>'.ucfirst($value->room_name).'</b></td><tr>';
	$montage_room.='<tr><td style="width:20%" ><b>Items</b></td><td style="width:20%"><b>Dismantling</b></td><td><b>Assembly</b></td><tr>';
	$Arbeiten_room='<b>'.ucfirst($value->room_name).' : </b>';
	
	$einpacken_room='<tr><td  style="padding-top:5px"><b>'.ucfirst($value->room_name).'</b></td></tr>';
	$einpacken_room.='<tr><td style="width:20%"><b>Items</b></td><td ><b>Unpacking</b></td></tr>';
	
	$Arbeiten_item='';
	$montage_item='';
	$einpacken_item='';
	foreach($taskitems as  $itemkey=>$taskitem){
		$item=$taskitem->item;
		if($assessment->language_version==2){
			$field=\app\models\Field::find()->where(['name'=>$taskitem->item])->one();
			if($field){
				$item=$field->name_en;
			}
		}
		if($taskitem->type==1){
			if($taskitem->bohrarbeiten==1){
				$bohrarbeiten[]=$item;	
			}
		}
	}
	
	if($montage_item!=''){
		if(!empty($value->task_other_note)){
			$montage_item.='<tr><td><b>Remarks   :</b></td><td colspan="2">'.$value->task_other_note.'</td></tr>';			
		}
		$montage_data.=$montage_room.$montage_item;
	}
	if($Arbeiten_item!=''){
		$Arbeiten.="<br/>".$Arbeiten_room.$Arbeiten_item;
	}
	if($einpacken_item!=''){
		$einpacken_data.=$einpacken_room.$einpacken_item;
	}
	
	
}
if(!empty($montage_data))
	$montage.=$montage_data."</table>";else $montage="";
if(!empty($einpacken_data))
	$einpacken.=$einpacken_data."</table>";else $einpacken="";

$i=0;
$gebraucht=$assessment->gebraucht;
if($gebraucht==1){
	$gebraucht=' (Gebraucht) ';
}else{
	$gebraucht='';
}
$Halteverbotszone=array();
$index1=count($assessment_pickup_address);
foreach ($assessment_pickup_address as $pickup): 
	++$i;
	if($pickup->pickup_parking_zone==$pickup::PARKING_OTHER_ADDRESS){
		$pick_parking_value="Yes (".$pickup->pickup_different_parking_address_text.")";
	//	$Halteverbotszone['Loading address'][$i]="Yes (".$pickup->pickup_different_parking_address_text.")";
		$Halteverbotszone['Loading address'][$i]="Yes ".(!empty($pickup->pickup_different_parking_address_text)?"(".$pickup->pickup_different_parking_address_text.")":'');;
	}elseif($pickup->pickup_parking_zone==$pickup::PARKING_BESONDERHEIT){
		$Halteverbotszone['Loading address'][$i]=$pickup->pickup_note;
	}else{
		$pick_parking_value=$pickup->getParkingConstantEnglish();
		$Halteverbotszone['Loading address'][$i]=$pickup->getParkingConstantEnglish();
		
	}
	
?>
<?php  endforeach;?>
<?php 
$i=0;
$index1=count($assessment_drop_address);
foreach ($assessment_drop_address as $drop): 
	 
	 ++$i;
	if($drop->drop_parking_zone==$drop::PARKING_OTHER_ADDRESS){
		$drop_parking_value="Yes (".$drop->drop_different_parking_address_text.")";
		$Halteverbotszone['Unloading address'][$i]="Yes ".(!empty($drop->drop_different_parking_address_text)?"(".$drop->drop_different_parking_address_text.")":'');
	}elseif($drop->drop_parking_zone==$drop::PARKING_BESONDERHEIT){
		$Halteverbotszone['Unloading address'][$i]=$drop->drop_note;
	}else{
		$drop_parking_value=$drop->getParkingConstantEnglish();
		$Halteverbotszone['Unloading address'][$i]=$drop->getParkingConstantEnglish();
	}
	 
?> 
<?php 
endforeach; 
$indexvalue=0;
if(!empty($Halteverbotszone)){
	$index1=false;
	if(!empty($Halteverbotszone['Auszugsort']) && count($Halteverbotszone['Auszugsort'])>1){
		$index1=true;
	}	
?>
<table  width="100%" style="margin-bottom:10px">
	
	<?php 
	echo '<tr><td ><b>'.++$indexvalue.'.) Parking restrictions: </b></td><tr>';
		
	foreach($Halteverbotszone as $valuekey=>$Halteverbotsvalue){
		echo '<tr>';
		
		foreach($Halteverbotsvalue as $key=>$value){
	?>
	
		<td><?=$valuekey.(($index1)?" $key":'').':'." $value"?> </td>	
		
	
		<?php } echo '<td></td></tr>' ;} ?>
	
</table>
<?php } 

?>

<?php 

	$gebraucht=$assessment->gebraucht;
	if($gebraucht==1){
		$gebraucht=' (Used Cardboard: Yes)';
	}else{
		$gebraucht='';	
	}
//	( Gebraucht Karton : <?=$gebraucht )

?>
<span style="font-family: Arial"><b><?=++$indexvalue.'.) ' ?><?= "Material details".$gebraucht ?>:</b> </span>
<?php 
$materialhtml='';
$materialheader='';
$materialconent='';

if(!empty($assessment_packing_material)){
?>

<?php $materialheader='<table style="margin-bottom:10px" width="100%" align="left" cellspacing="0" cellpadding="0" border="0" class="devicewidth"><tbody> 
        ';
            foreach ($assessment_packing_material as $packing_material){
                $key = $packing_material->packing_material_item_id; 
                $item = $packing_material->getPackingMaterialItem()->andWhere([ 'in', 'id', $key])->one();
				   if(!empty($packing_material->quantity)){
					   $liefern='Yes';
						if($packing_material->quantity>0)
						$quantity=$item->plural_qantity_en;else $quantity=$item->singular_qantity_en;
						$materialconent.='<tr><td width="30%">'.$item->name_en.'</td><td  >: '.$packing_material->quantity." ".$quantity.'</td></tr> ';
				   }
			}
			if(!empty($materialconent)){
				$materialhtml=$materialheader.$materialconent.'</tbody></table> ';
				echo $materialhtml;
			}else{
				echo 'By Customer'."<br/>";
			}
			?>
		
    
<?php }else
	echo "By Customer"."<br/>";
?>  


<?php 
if(!empty($assessment_moving)){
?>
<div style="margin-bottom:10px"><strong><?=++$indexvalue?>.) Moving Details: </strong> 
<table width="100%" style="margin-top:5px">
	<tr>
		<td style="vertical-align: top;width:5%" ><b>Day </b></td>
		<td style="vertical-align: top;width:15%"><b>Date </b></td>
		<td style="vertical-align: top;width:15%"><b>Time</b> </td>
		<td><b>Task</b></td>
		
	</tr>
<?php 
$i = 1;
foreach ($assessment_moving as $moving): ?>   
				
	<?php $moving_task = array(); ?>
		<?php $moving_task[]= !empty($moving->einpacken) ? ' Pack' : '' ?>
		<?php $moving_task[] = !empty($moving->dismantling) ? ' Disassemble' : '' ?>
		<?php $moving_task[] = !empty($moving->loaded) ? 'Load' : '' ?>
		<?php $moving_task[] = !empty($moving->transport) ? ' Transport' : '' ?>
		<?php $moving_task[] = !empty($moving->unloaded) ? ' Unload' : '' ?>
		<?php $moving_task[] = !empty($moving->aufbau) ? ' Assemble' : '' ?>
		<?php $moving_task[] = !empty($moving->unpacking) ? ' Unpack' : '' ?>
		<?php $moving_task[] = !empty($moving->installation_work) ? ' Return' : '' ?>
		<?php $moving_task[] = !empty($moving->note) ? " ".$moving->note : '' ?>
		<?php // $moving_task[] = !empty($moving->beginning_after_consultation) ? 'Beginn nach Rücksprache' : '' ?>
		<?php 
			$moving_task=array_filter($moving_task);
			
		?>
			<tr >
				<td ><strong> <?=$i?>. </strong> </td>
				<?php 
					if(!empty($assessment->date_not_fixed))$date=$assessment->date_not_fixed_text;
					else $date=!empty($moving->moving_date) && $moving->moving_date != "1970-01-01"?Yii::$app->formatter->asDate($moving->moving_date, 'php:d.m.Y'):"";
				?>
				<td ><?=$date?></td>
				<td ><?php 
					if(!empty($moving->timingtype) && $moving->timingtype>0){
						echo $moving->gettimingtypeEnglish();
					}else{
						echo $moving->moving_time;
					}
				?> </td>
				<td ><?=implode(', ',$moving_task);?> </td>
				
			</tr>
		<?php 
			 $i++;
			
		?>
		
    <?php 
    endforeach; ?> 
</table>
</div>
<?php }

?>
<?php 
	if($assessment->assemble_done_by==1){
		?>
		<div style="margin-bottom:10px"><b><?=++$indexvalue?>.) Assembly work : </b><br>
		By Umzugsfirma Junker</div>
		<?php 
	}elseif($assessment->assemble_done_by==2){
		?>
		<div style="margin-bottom:10px"><b><?=++$indexvalue?>.) Assembly work : </b><br>
		Dismantling : By Umzugsfirma Junker<br>
		Assembly : By Customer</div>
		<?php 
	}elseif($assessment->assemble_done_by==3){
		?>
		<div style="margin-bottom:10px"><b><?=++$indexvalue?>.)Assembly work : </b><br>
		Assembly : By Umzugsfirma Junker<br>
		Dismantling : By Customer</div>
		<?php 
	}
?>
<?php 
if($assessmentTasks && (empty($assessment->assemble_done_by) || $assessment->assemble_done_by==4)){
?>
<span><strong><?=++$indexvalue?>.) Assembly work : </strong></span>
	<table style="margin-bottom:10px">
		<tr>
			<td style="width:40%" ></td>
			<td style="width:20%;text-align:center"><b>Disassemble</b></td>
			<td style="width:20%;text-align:center"><b>Assemble</b></td>
			<?php 
				if(!empty($bohrarbeiten)){
			?>
			<td style="text-align:center"><b>Dowel work</b></td>
				<?php } ?>
		</tr>
		
		<?php
		$bohrarbeiten=array();
		$flag=false;
		$ohne='';
		$ohne='';
		foreach($assessmentTasks as $key=>$value){
			$taskitems=$value->assessmentTaskItems;
			$name=!empty($value->room_name)?ucfirst($value->room_name):'';
			if($assessment->language_version==2){
				$field=\app\models\Field::find()->where(['name'=>$value->room_name])->one();
				if($field){
					$name=$field->name_en;
				}
			}
		?>
			<tr >
				<td colspan='4' style="border-top: 1px solid black;"><b><?=$name?></b></td>
			</tr>
			<?php 
				//$flag=false;
				$count=count($taskitems);
				
				foreach($taskitems as $key1=>$value1){
					$item=$value1->item;
					if($assessment->language_version==2){
						$field=\app\models\Field::find()->where(['name'=>$value1->item])->one();
						if($field){
							$item=$field->name_en;
						}
					}
					$class='';
					if($key1+1==$count)$class="lastline";
					if($value1->type==2)continue;
					if($value1->bohrarbeiten==1){
						$bohrarbeiten[]=$item;
						$flag=true;
					}
			?>
					<tr >
						<td ><?=$item?></td>
						<td style="text-align:center"><?=($value1->abbau==1)? '<img src="'.Yii::getAlias('@webroot/images/tick.png').'" style="width:7px"> ':''?></td>
						<td style="text-align:center"><?=($value1->aufbau==1)?'<img src="'.Yii::getAlias('@webroot/images/tick.png').'" style="width:7px"> ':''?></td>
						<?php 
							if(!empty($value1->bohrarbeiten)){
						?>
							<td style="text-align:center"><?=($value1->bohrarbeiten==1)?'<img src="'.Yii::getAlias('@webroot/images/tick.png').'" style="width:7px"> ':''?></td>
						<?php 
							}
						?>
					</tr>
			
		<?php
				}
				
		} 
		if($flag==false){
			$ohne='Excluding';
		}
		?>
	</table>
<?php 
}	
?>

<?php 
if(!empty($ohne)){
	$Arbeiten=$ohne;
}else{
	$Arbeiten=!empty($bohrarbeiten)?implode(',',$bohrarbeiten):'';
}
if((!empty($assessment->assemble_done_by) && ($assessment->assemble_done_by!=4)) || !empty($ohne)){
?>
	<div  style="margin-bottom:10px"><b><?=++$indexvalue?>.) Dowel work: </b> <br>
		<?=	 !empty(trim($Arbeiten))?rtrim($Arbeiten, ','):'By Customer';?>
	</div>
<?php
}
	$einpacken=array();
	$auspacken=array();
	$flag=false;
//	if($assessmentTasks){

		if(!empty($assessment->unpacking_done_by) && ($assessment->unpacking_done_by!=4) )$einpacken[]='By customer';
		if($assessment->unpacking_junker) $einpacken[]='By Umzugsfirma Junker';
		if($assessment->unpacking_other && !empty($assessment->unpacking_text))$einpacken[]= $assessment->unpacking_text;

		if(!empty($assessment->packing_done_by) && ($assessment->packing_done_by!=4) )$auspacken[]='By customer';
		if($assessment->packing_junker) $auspacken[]='By Umzugsfirma Junker';
		if($assessment->packing_other && !empty($assessment->packing_text))$auspacken[]= $assessment->packing_text;
		
		foreach($assessmentTasks as $key=>$value){
			$taskitems=$value->assessmentTaskItems;
				foreach($taskitems as $key1=>$value1){
					$item=$value1->item;
					$field=\app\models\Field::find()->where(['name'=>$value1->item])->one();
					if($field){
						$item=$field->name_en;
					}
				
					if($value1->type==2){
						$einpacken[]=$item;
						if($value1->auspacken==1){
							$flag=true;
							$auspacken[]=$item;	
						}
					}
				} 	

		}
	//}
?>
<span><strong><?=++$indexvalue?>.) Packing service / Unpacking service </strong> <br/>
<?=!empty($einpacken)?'Packing service : '.(implode(", ",$einpacken)):' 
Packing service: customer side'?><br/>

<?=!empty($auspacken)?'Unpacking service : '.(implode(", ",$auspacken)):'Unpacking service: customer side'?><br/>
</span>
<?php if(!empty($assessment->heavy_duty_note)): ?>
<p><strong><?=++$indexvalue?>.) Heavy load: </strong> <br><?=$assessment->heavy_duty_note?>
</p>
<?php endif; ?>
<?php if(!empty($assessment->task_other_note)): ?>
<p><strong><?=++$indexvalue?>.) Others: </strong> <br>
<?=$assessment->task_other_note?>
</p>
<?php endif; ?>

<!-- Packing Material Details --> 

<pagebreak> 
<?php
$index1=count($assessment_pickup_address);
$j=1;
$i=0;
foreach ($assessment_pickup_address as $pickup): 
	$stairs=!empty($pickup->pickup_stairs)?$pickup->pickup_stairs:'';
	
	if(empty($stairs))$stairs='EG';
	elseif(!empty($stairs)){
		$stairs=$stairs.'.OG ';
	}
	$lift=($pickup->pickup_lift==4)?'Elevator: '.$pickup->lift_text_1:(($pickup->pickup_lift==1)?'Elevator: Yes':'Elevator: No');

	
//	$lift=!empty($pickup->pickup_lift)?'ja' :'ohne FS';
	$pickup_building=!empty($pickup->pickup_building)?$pickup::buildingtype($pickup->pickup_building):'';

	$liftstringparam=array();
	if(!empty($stairs))$liftstringparam[]=$stairs;
	if(!empty($pickup_building))$liftstringparam[]=$pickup_building;
	if(!empty($lift))$liftstringparam[]=$lift;
	
	$liftstringparam=(!empty($liftstringparam))?"(".implode("/ ",$liftstringparam).")":'';
	
	
?>
<table  style="border:1px solid black" width="100%">
	<tr>
		<td><h4>Loading Address <?=($index1>1)?" ".(++$i):''?>:</h4></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td style="width:50%"><h4>No parking Area : <?php
		

		if($pickup->pickup_parking_zone==$pickup::PARKING_OTHER_ADDRESS){
			//	$pick_parking_value="Yes (".$pickup->pickup_different_parking_address_text.")";
			$pick_parking_value="Yes ".(!empty($pickup->pickup_different_parking_address_text)?"(".$pickup->pickup_different_parking_address_text.")":'');
			$Halteverbotszone['Auszugsort'][$i]='Yes';
		}elseif($pickup->pickup_parking_zone==$pickup::PARKING_BESONDERHEIT){
			$pick_parking_value=$pickup->pickup_note;
		}else{
			$pick_parking_value=$pickup->getParkingConstantEnglish();
			$Halteverbotszone['Auszugsort'][$i]=$pickup->getParkingConstantEnglish();
		}
		echo $pick_parking_value;
		?></h4></td> 	<!--Pakirking Zone-->
		
		
		<td style="width:49%">
			<?php if($j==1){?>
				<h4><?=($liefern!='No')?'Material delivery : '.$liefern:'' ?> <?=$gebraucht?></h4>
			<?php $j++ ; } ?>
		</td> 	<!--Pakirking Zone-->
		
		
	</tr>
	<tr >
		<td colspan="4" style="border:1px solid black;"><h4><?=$pickup->pickup_address?> <?=$pickup->pickup_zipcode?> <?=(!empty($pickup->pickupCity->name)?", ".$pickup->pickupCity->name:'')?> <?=$liftstringparam?>&nbsp;</h4>
		<?=!empty($pickup->pickup_door_parking_distance)?"<h4>Parking distance: ".$pickup->pickup_door_parking_distance."m</h4>":''?>
		</td> 	<!--Pakirking Zone-->
	
	</tr>
</table>
<?php  endforeach;?>
<br/>
<?php 
$i=0;
$index1=count($assessment_drop_address);
foreach ($assessment_drop_address as $drop): 
	 $assessmentTasks=$assessment->assessmentTasks;
	$stairs=!empty($drop->drop_stairs)?$drop->drop_stairs:'';
	if(empty($stairs))$stairs='EG';
	elseif(!empty($stairs)){
		$stairs=$stairs.'.OG ';
	}
	$lift=($drop->drop_lift==4)?'Elevator: '.$drop->lift_text_1:(($drop->drop_lift==1)?'Elevator: Yes':'Elevator: No');

	$drop_building=!empty($drop->drop_building)?$pickup::buildingtype($drop->drop_building):'';
	
	$liftstringparam=array();
	if(!empty($stairs))$liftstringparam[]=$stairs;
	if(!empty($drop_building))$liftstringparam[]=$drop_building;
	if(!empty($lift))$liftstringparam[]=$lift;
	
	$liftstringparam=(!empty($liftstringparam))?"(".implode("/ ",$liftstringparam).")":'';
	 
?> 
<table  style="border:1px solid black" width="100%">
	<tr>
		<td><h4>Unloading Address <?=($index1>1)?" ".(++$i):''?>:</h4></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td style="width:50%"><h4>No parking Area : <?php 
		

		if($drop->drop_parking_zone==$drop::PARKING_OTHER_ADDRESS){
			//$drop->pickup_different_parking_address_text;
			//$drop_parking_value="Yes (".$drop->drop_different_parking_address_text.")";
			$drop_parking_value="Yes ".(!empty($drop->drop_different_parking_address_text)?"(".$drop->drop_different_parking_address_text.")":'');
			$Halteverbotszone['Einzugsort'][$i]='Yes';
		}elseif($drop->drop_parking_zone==$drop::PARKING_BESONDERHEIT){
			
			$drop_parking_value=$drop->drop_note;
			$Halteverbotszone['Einzugsort'][$i]=$drop->drop_note;
		}else{
			$drop_parking_value=$drop->getParkingConstantEnglish();
			$Halteverbotszone['Einzugsort'][$i]=$drop->getParkingConstantEnglish();
		}
		echo $drop_parking_value;
		?> </h4></td> 	<!--Pakirking Zone-->
		
		<td>
		<?php 
			$Abholen= 'No';
			if($assessment->abholung==1){
					$Abholen= 'Yes';
				if($drop->abholung){
				}
			}
		?>
		<h4>Carton pickup: <?=$Abholen?></h4>
		
		</td> 	<!--Pakirking Zone-->
	
		
	</tr>
	<tr >
		<td colspan="4" style="border:1px solid black;"><h4><?=$drop->drop_address?>, <?=$drop->drop_zipcode?> <?=!empty($drop->dropCity->name)?$drop->dropCity->name:''?> <?=$liftstringparam?>&nbsp;</h4>
		<?=!empty($drop->drop_door_parking_distance)?"<h4>Parking distance: ".$drop->drop_door_parking_distance."m</h4>":''?>
		</td> 	<!--Pakirking Zone-->
		
	</tr>
</table>
<br>
<?php	
endforeach; 
?> 
<?php  
$calculateddistance='';
if(!empty($assessment->distance))$calculateddistance=$assessment->distance;

$vehicle='';
$vehiclelist=array();
if(!empty($assessment->vehicle)){
	$vehicles= json_decode($assessment->vehicle,true);
	if(is_array($vehicles)){
		$vehiclecount=array_count_values($vehicles);
		foreach($vehiclecount as $value=>$list)
			$vehiclelist[]=(($list>1))?($list ." <b>X</b> ". ($assessment::getVehiclesEnglish($value))):$assessment::getVehiclesEnglish($value);
		
		$vehicle=implode(", ",$vehiclelist);
		
	}
	else{
			$vehicle=$assessment::getVehiclesEnglish($assessment->vehicle);
	}
}
	//echo $vehicle;
?>

<table width="100%" border="1" cellspacing="0" cellpadding="0" style="padding:0px;" >
	<tr >
		<td  style="border-bottom:1px solid black; border-left:0px; border-right:0px; border-top:0px; padding:9px"><h4>Price and Services (included in this offer):</h4></td>
	</tr>
	<tr>
		<td style="border-bottom:1px solid black; border-left:0px; border-right:0px; border-top:0px; padding:9px;">Team: Load IN with    <?=!empty($assessment->einladen)?$assessment->einladen:'0'?>  Movers/Load OUT  with    <?=!empty($assessment->ausladen)?$assessment->ausladen:'0'?> movers</td>
	</tr>
	<tr>
		<td style="border-bottom:1px solid black; border-left:0px; border-right:0px; border-top:0px; padding:9px;">Transport Volume
  : <?=!empty($assessment->transport_volume)?$assessment->transport_volume:'0'?>cbm<sup>3</sup> Moving volume</td>
	</tr>
	<tr>
		<td style="border-bottom:1px solid black; border-left:0px; border-right:0px; border-top:0px; padding:9px;">Vehicle  : <?=$vehicle?></td>
	</tr>
	<?php 
		if(!empty($calculateddistance)): 
	?>
	<tr>
		<td style="border-bottom:1px solid black; border-left:0px; border-right:0px; border-top:0px; padding:9px;">Distance: <?=$calculateddistance?></td>
	</tr>
	<?php 
		endif;
	?>
	
	<tr>
		<td style="border-bottom:1px solid black; border-left:0px; border-right:0px; border-top:0px;padding:9px;">Equipment : ( Covers , tension belts , tie down belt , rolling boards )</td>
	</tr>
	<tr>
		<td style="border-bottom:0px; border-left:0px; border-right:0px; border-top:0px;padding:9px;">Transport and Insurance with 620€/ m³ included</td>
	</tr>
</table>	
<br/>
<br/>
<?php 
	$total = "";
	if(!empty($assessment->price)){
		$price = $assessment->price;
		$total = $price;
	}    
	$price_type='';
	if(!empty($assessment->price_type)){
		$price_type =$assessment->getEnglishPriceType();
	}
	$price_txt_note = '';
	if($assessment->price_txt)
		$price_txt_note = $assessment->price_txt_note;

	if($assessment->price_type==$assessment::PRICE_ANDRE)$price_type=$assessment->price_type_text;
	
	$Gesamtbruttopreis='Price included VAT';
	if($assessment->price_type==1 || $assessment->price_type==4)
		$Gesamtbruttopreis='Price without VAT';


	if($assessment->price_tag==1)
		$Gesamtbruttopreis=$assessment->price_tag_value;
	
?>

<h2 style="border-bottom:1px solid black;font-size:16px"><?=$Gesamtbruttopreis?> : <?=str_replace('€', '', Yii::$app->formatter->asCurrency($total, '')) ?> &euro; <?=$price_type?>&nbsp;<?=$price_txt_note?>. 
</h2>
<?php 
	if(!empty($assessment->ohne_ust)):
?>
<p>
Service is exempt from VAT according to §4 No. 3 UStG import / export.</p>

<?php 
	endif;
?>

<p style="font-family:arial; ; 
color: #dd4b39; text-align:left; line-height: 24px;"><span style="border-bottom:1px solid #dd4b39"><b>** 
The insurance form [HGB] is very important to regulate a damage<br/>
If we have not the signed document, the client lost the insurance protection
The insurance document is already send with the offer. <span></b>
</p>

<p style="font-family: arial; font-size: 12px;  text-align:left; line-height: 24px;"> 
<?php 
	
?>
We (Customer) confirm the AGB and the offer from the moving company Junker. The payment is after finishing the work in cash<span style="border-bottom:1px solid black">  <b> <?=!empty($assessment->payment_method)?($assessment::getPaymentMethodEnglish($assessment->payment_method)).'.':''?></b></span>
</p>
<p>
	<b><?=(!empty($signaturestate)?$signaturestate.", ":'').Yii::t('app', 'the_______________________________________________')?></b>
</p>
<p style="text-align:center;margin-right:200px"><b><?=Yii::t('app', '(Signature)')?></b></p>
<p><b>The moving company Junker confirm your assignment per Email.</b></p>

<pagebreak>  
<div class="terms-conditions">
    <h3 style="font-size:10px; font-family:  arial, sans-serif;"><?=Yii::t('app', 'General terms and conditions (GTC) of Junker Berlin moving company ')?></h3>
    <ol style="font-size:10px; font-family: arial, sans-serif;">
       <li><strong>Commissioning a further carrier:</strong><br>
            To carry out the job the moving company may use a further carrier.               
       </li>                     
       <li><strong>Additional services: </strong><br>
			The moving company shall carry out its obligations with the customary care of a proper moving company 
			for a fee while protecting the interests of the sender. Any particular services and expenses not predicted at the 
			time the contract was concluded shall be payable on top. 
			The same applies if the scope of service is extended by the sender after the contract was concluded.
		</li>                     
       <li><strong>Collective transport: : </strong><br>
           The move may also be carried out with collective transport.
       </li> 
       <li><strong>Tips: </strong><br>
           Tips cannot be offset against the moving company’s invoice.
       </li>
       <li><strong>Reimbursement of relocation expenses:</strong><br>
           Insofar as the sender is eligible for relocation expenses from his work place or employer, he shall instruct them to, upon due demand, pay the agreed and due relocation expenses less any payments made or part payments directly to the moving company. Either a confirmation of cost coverage from social services or a written order confirmation from the job center must be presented before the move.
	   </li>                     
       <li><strong> Securing transport: </strong><br>
          The sender shall secure any moveable or electronic parts of highly sensitive devices, e.g. washing machines, record players, TVs, radios and HiFi equipment, IT equipment etc. correctly for transport. The moving company is under no obligation to check that items are correctly secured for transport.
	  </li>                     
       <li><strong>Electrical and installation work:   </strong><br>
            Unless otherwise agreed the moving company employees are not authorized to carry out any work involving electrics, gas, Raw plugs or other installation work.
		</li>                     
       <li><strong>Liaising with workmen:: </strong><br>
            The moving company is only liable for the careful work of additional workmen brought in.
       </li>                     
       <li><strong>Compensation: </strong><br>
            For any claims made against the moving company, compensation is only acceptable with due counter claims, which have been legally established and are uncontested.   
       </li>                     
       <li><strong>Transfer to a replacement:: </strong><br>
           Should a replacement be demanded then the moving company shall transfer any rights entitled to it through the insurance contract to be concluded to the replacement.             
       </li>                     
       <li><strong>Misunderstandings: </strong><br>
          The risk misunderstanding other than written order confirmations. The moving company takes no responsibility for instructions and information from the sender and others given to persons unauthorized to receive them.
		</li>                     
       <li><strong>Verification by the sender: </strong><br>
				Upon collection of the relocation goods the sender shall make sure that no item or piece of equipment has been taken along or left behind by mistake.
		</li>                     
       <li><strong>Due date for the agreed fees: </strong><br>
           The invoice amount is due in cash or similar means of payment: if national transport before unloading has been completed, if international transport before loading has begun. Foreign currency cash payments shall be paid according to the calculated exchange rate. Should the sender withhold payment then the moving company reserves the right to intercept the relocation goods or, where transport has begun, to store them in a warehouse at the sender’s cost according to § 419 HGB (German commercial code).    </li>                     
       <li><strong>Packing boxes: : </strong><br>
            Once the order has been placed packing boxes will be sold to the customer for the valid price at that time.
			</li>                     
       <li><strong>Withdrawal from contract:</strong><br>
           Clause 6.6 DIN EN ISO 12522/1 is replaced by the relevant provisions of the BGB (German civil code) and HGB (German commercial code), in particular by §§ 415 HGB, 346 ff. BGB.   </li>                     
       <li><strong> Place of jurisdiction:: </strong><br>
The jurisdiction for legal disputes with registered traders based on this contract and those over claims for other legal reasons in connection with the transport assignment, is solely the one in the district in which the moving company branch which the sender commissioned is located. This sole jurisdiction for legal disputes with those other than registered traders only applies when the sender has moved his place of residence or his usual whereabouts abroad after concluding the contract or when his place of residence or personal whereabouts is unknown at the time of indictment.      
	  </li>                     
       <li><strong>Liability: </strong><br>
           The service already includes transport insurance. The insurance pays for any possible damages. Pictures and all types of picture frames must not be packed into boxes. Fragile items must be clearly marked as such on the packaging. Otherwise they will be excluded from liability in the case of damage. </li>                     
       <li><strong>Choice of law: </strong><br>
            German law shall apply.
       </li>                 
    </ol>
    <p style="font-size:10px; font-family: arial, sans-serif;">The customer hereby confirms that he/she has read and understood the GTCs.
Furthermore § 451 g HGB (German commercial code) has been read and accepted.

</p>
 <br/>
<p  ><b><?=!empty($signaturestate)?$signaturestate.",":''?> ____________________________________(signature) (the customer)</b></p>
</div>

	  
  </body>
</html>

<?php //die();
$this->endPage() ; ?>

<?php 
use yii\helpers\Html;
use yii\helpers\Url;
$doc = $data['DocDtls'];
$companyAddress = $data['SellerDtls'];
$contractCompanyAddress = $data['BuyerDtls'];
$billingCompanyAddress = $data['ShipDtls'];
$items = $data['ItemList'];
$valDtls = $data['ValDtls'];
$valid = "<i class='fa fa-check-circle text-success pull-right'></i>";
$notValid = "<i class='fa fa-exclamation-circle text-danger pull-right' title='{{title}}'></i>";
$notValidMsg = [];
if( !empty( $validate['SellerDtls'] ) ){
    foreach($validate['SellerDtls'] as $sellerKey=>$sellerDtls){
        $notValidMsg['msg_SellerDtls'][$sellerKey] = str_replace("{{title}}",$sellerDtls,$notValid); 
    }
}
//echo "<pre>";print_r( $notValidMsg );die();
if( !empty( $validate['BuyerDtls'] ) ){
    foreach($validate['BuyerDtls'] as $buyerKey=>$buyerDtls){
        $notValidMsg['msg_BuyerDtls'][$buyerKey] = str_replace("{{title}}",$buyerDtls,$notValid); 
    }
}
if( !empty( $validate['ShipDtls'] ) ){
    foreach($validate['ShipDtls'] as $shipKey=>$shipDtls){
        $notValidMsg['msg_ShipDtls'][$shipKey] = str_replace("{{title}}",$shipDtls,$notValid); 
    }
}
if( !empty( $validate['ItemList'] ) ){
    foreach($validate['ItemList'] as $itemIndex=>$ItemListArray){
        foreach($ItemListArray as $itemsKey=>$itemsVal){
            $notValidMsg['msg_items'][$itemIndex][$itemsKey] = str_replace("{{title}}",$itemsVal,$notValid); 
        }
    }
}
if( !empty( $validate['ValDtls'] ) ){
    foreach($validate['ValDtls'] as $valDtlsKey=>$valDtlsVal){
        $notValidMsg['msg_ValDtls'][$valDtlsKey] = str_replace("{{title}}",$valDtlsVal,$notValid); 
    }
}
//echo "<pre>";print_r( $data );die();
?>
<div class="row">
    <div class="col-sm-4">
        <label>Doc Typ:<?= $doc['Typ'];?><label>
    </div>
    <div class="col-sm-4">
        <label>Doc No:<?= $doc['No'];?><label>
    </div>
    <div class="col-sm-4">
        <label>Doc Dt:<?= $doc['Dt'];?><label>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <h4>Seller</h4>
        <p><label>Gstin:<?= $companyAddress['Gstin'];?></label><?= empty( $validate['SellerDtls']['Gstin'] )?$valid:$notValidMsg['msg_SellerDtls']['Gstin'];?></p>
        <p><label>LglNm:<?= $companyAddress['LglNm'];?></label><?= empty( $validate['SellerDtls']['LglNm'] )?$valid:$notValidMsg['msg_SellerDtls']['LglNm'];?></p>
        <p><label>TrdNm:<?= $companyAddress['TrdNm'];?></label><?= empty( $validate['SellerDtls']['TrdNm'] )?$valid:$notValidMsg['msg_SellerDtls']['TrdNm'];?></p>
        <p><label>Addr1:<?= $companyAddress['Addr1'];?></label><?= empty( $validate['SellerDtls']['Addr1'] )?$valid:$notValidMsg['msg_SellerDtls']['Addr1'];?></p>
        <p><label>Addr2:<?= $companyAddress['Addr2'];?></label><?= empty( $validate['SellerDtls']['Addr2'] )?$valid:$notValidMsg['msg_SellerDtls']['Addr2'];?></p>
        <p><label>Loc:<?= $companyAddress['Loc'];?></label><?= empty( $validate['SellerDtls']['Loc'] )?$valid:$notValidMsg['msg_SellerDtls']['Loc'];?></p>
        <p><label>Pin:<?= $companyAddress['Pin'];?></label><?= empty( $validate['SellerDtls']['Pin'] )?$valid:$notValidMsg['msg_SellerDtls']['Pin'];?></p>
        <p><label>Stcd:<?= strval( $companyAddress['Stcd'] );?></label><?= empty( $validate['SellerDtls']['Stcd'] )?$valid:$notValidMsg['msg_SellerDtls']['Stcd'];?></p>
        <p><label>Ph:<?= strval( $companyAddress['Ph'] );?></label><?= empty( $validate['SellerDtls']['Ph'] )?$valid:$notValidMsg['msg_SellerDtls']['Ph'];?></p>
        <p><label>Em:<?= strval( $companyAddress['Em'] );?></label><?= empty( $validate['SellerDtls']['Em'] )?$valid:$notValidMsg['msg_SellerDtls']['Em'];?></p>
    </div>
    <div class="col-sm-4">
        <h4>Buyer</h4>
        <p><label>Gstin:<?= $contractCompanyAddress['Gstin'];?></label><?= empty( $validate['BuyerDtls']['Gstin'] )?$valid:$notValidMsg['msg_BuyerDtls']['Gstin'];?></p>
        <p><label>LglNm:<?= $contractCompanyAddress['LglNm'];?></label><?= empty( $validate['BuyerDtls']['LglNm'] )?$valid:$notValidMsg['msg_BuyerDtls']['LglNm'];?></p>
        <p><label>TrdNm:<?= $contractCompanyAddress['TrdNm'];?></label><?= empty( $validate['BuyerDtls']['TrdNm'] )?$valid:$notValidMsg['msg_BuyerDtls']['TrdNm'];?></p>
        <p><label>Pos:<?= strval( $billingCompanyAddress['Pos'] );?></label><?= empty( $validate['BuyerDtls']['Pos'] )?$valid:$notValidMsg['msg_BuyerDtls']['Pos'];?></p>
        <p><label>Addr1:<?= $contractCompanyAddress['Addr1'];?></label><?= empty( $validate['BuyerDtls']['Addr1'] )?$valid:$notValidMsg['msg_BuyerDtls']['Addr1'];?></p>
        <p><label>Addr2:<?= $contractCompanyAddress['Addr2'];?></label><?= empty( $validate['BuyerDtls']['Addr2'] )?$valid:$notValidMsg['msg_BuyerDtls']['Addr2'];?></p>
        <p><label>Loc:<?= $contractCompanyAddress['Loc'];?></label><?= empty( $validate['BuyerDtls']['Loc'] )?$valid:$notValidMsg['msg_BuyerDtls']['Loc'];?></p>
        <p><label>Pin:<?= $contractCompanyAddress['Pin'];?></label><?= empty( $validate['BuyerDtls']['Pin'] )?$valid:$notValidMsg['msg_BuyerDtls']['Pin'];?></p>
        <p><label>Stcd:<?= strval( $contractCompanyAddress['Stcd'] );?></label><?= empty( $validate['BuyerDtls']['Stcd'] )?$valid:$notValidMsg['msg_BuyerDtls']['Stcd'];?></p>
        <p><label>Ph:<?= strval( $contractCompanyAddress['Ph'] );?></label><?= empty( $validate['BuyerDtls']['Ph'] )?$valid:$notValidMsg['msg_BuyerDtls']['Ph'];?></p>
        <p><label>Em:<?= strval( $contractCompanyAddress['Em'] );?></label><?= empty( $validate['BuyerDtls']['Em'] )?$valid:$notValidMsg['msg_BuyerDtls']['Em'];?></p>
    </div>
    <div class="col-sm-4">
        <h4>ShipDtls</h4>
        <p><label>Gstin:<?= $billingCompanyAddress['Gstin'];?></label><?= empty( $validate['ShipDtls']['Gstin'] )?$valid:$notValidMsg['msg_ShipDtls']['Gstin'];?></p>
        <p><label>LglNm:<?= $billingCompanyAddress['LglNm'];?></label><?= empty( $validate['ShipDtls']['LglNm'] )?$valid:$notValidMsg['msg_ShipDtls']['LglNm'];?></p>
        <p><label>TrdNm:<?= $billingCompanyAddress['TrdNm'];?></label><?= empty( $validate['ShipDtls']['TrdNm'] )?$valid:$notValidMsg['msg_ShipDtls']['TrdNm'];?></p>
        <p><label>Addr1:<?= $billingCompanyAddress['Addr1'];?></label><?= empty( $validate['ShipDtls']['Addr1'] )?$valid:$notValidMsg['msg_ShipDtls']['Addr1'];?></p>
        <p><label>Addr2:<?= $billingCompanyAddress['Addr2'];?></label><?= empty( $validate['ShipDtls']['Addr2'] )?$valid:$notValidMsg['msg_ShipDtls']['Addr2'];?></p>
        <p><label>Loc:<?= $billingCompanyAddress['Loc'];?></label><?= empty( $validate['ShipDtls']['Loc'] )?$valid:$notValidMsg['msg_ShipDtls']['Loc'];?></p>
        <p><label>Pin:<?= $billingCompanyAddress['Pin'];?></label><?= empty( $validate['ShipDtls']['Pin'] )?$valid:$notValidMsg['msg_ShipDtls']['Pin'];?></p>
        <p><label>Stcd:<?= strval( $billingCompanyAddress['Stcd'] );?></label><?= empty( $validate['ShipDtls']['Stcd'] )?$valid:$notValidMsg['msg_ShipDtls']['Stcd'];?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SlNo</th>
                    <th>PrdDesc</th>
                    <th>IsServc</th>
                    <th>HsnCd</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>UnitPrice</th>
                    <th>TotAmt</th>
                    <th>AssAmt</th>
                    <th>GstRt</th>
                    <th>IgstAmt</th>
                    <th>CgstAmt</th>
                    <th>SgstAmt</th>
                    <th>CesRt</th>
                    <th>CesAmt</th>
                    <th>CesNonAdvlAmt</th>
                    <th>StateCesRt</th>
                    <th>StateCesAmt</th>
                    <th>StateCesNonAdvlAmt</th>
                    <th>OthChrg</th>
                    <th>TotItemVal</th>
                    <th>OrdLineRef</th>
                    <th>OrgCntry</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $key => $item):?>
                <tr>
                    <td><?= $item['SlNo']?><?= empty( $validate['items'][$key]['SlNo'] )?$valid:$notValidMsg['msg_items'][$key]['SlNo'];?></td>
                    <td><?= $item['PrdDesc'];?><?= empty( $validate['items'][$key]['PrdDesc'] )?$valid:$notValidMsg['msg_items'][$key]['PrdDesc'];?></td>
                    <td><?= $item['IsServc'];?><?= empty( $validate['items'][$key]['IsServc'] )?$valid:$notValidMsg['msg_items'][$key]['IsServc'];?></td>
                    <td><?= $item['HsnCd'];?><?= empty( $validate['items'][$key]['HsnCd'] )?$valid:$notValidMsg['msg_items'][$key]['HsnCd'];?></td>
                    <td><?= $item['Qty'];?><?= empty( $validate['items'][$key]['Qty'] )?$valid:$notValidMsg['msg_items'][$key]['Qty'];?></td>
                    <td><?= $item['Unit'];?><?= empty( $validate['items'][$key]['Unit'] )?$valid:$notValidMsg['msg_items'][$key]['Unit'];?></td>
                    <td><?= $item['UnitPrice'];?><?= empty( $validate['items'][$key]['UnitPrice'] )?$valid:$notValidMsg['msg_items'][$key]['UnitPrice'];?></td>
                    <td><?= $item['TotAmt'];?><?= empty( $validate['items'][$key]['TotAmt'] )?$valid:$notValidMsg['msg_items'][$key]['TotAmt'];?></td>
                    <td><?= $item['AssAmt'];?><?= empty( $validate['items'][$key]['AssAmt'] )?$valid:$notValidMsg['msg_items'][$key]['AssAmt'];?></td>
                    <td><?= $item['GstRt'];?><?= empty( $validate['items'][$key]['GstRt'] )?$valid:$notValidMsg['msg_items'][$key]['GstRt'];?></td>
                    <td><?= $item['IgstAmt'];?><?= empty( $validate['items'][$key]['IgstAmt'] )?$valid:$notValidMsg['msg_items'][$key]['IgstAmt'];?></td>
                    <td><?= $item['CgstAmt'];?><?= empty( $validate['items'][$key]['CgstAmt'] )?$valid:$notValidMsg['msg_items'][$key]['CgstAmt'];?></td>
                    <td><?= $item['SgstAmt'];?><?= empty( $validate['items'][$key]['SgstAmt'] )?$valid:$notValidMsg['msg_items'][$key]['SgstAmt'];?></td>
                    <td><?= $item['CesAmt'];?><?= empty( $validate['items'][$key]['CesAmt'] )?$valid:$notValidMsg['msg_items'][$key]['CesAmt'];?></td>
                    <td><?= $item['CesNonAdvlAmt'];?><?= empty( $validate['items'][$key]['CesNonAdvlAmt'] )?$valid:$notValidMsg['msg_items'][$key]['CesNonAdvlAmt'];?></td>
                    <td><?= $item['StateCesRt'];?><?= empty( $validate['items'][$key]['StateCesRt'] )?$valid:$notValidMsg['msg_items'][$key]['StateCesRt'];?></td>
                    <td><?= $item['StateCesAmt'];?><?= empty( $validate['items'][$key]['StateCesAmt'] )?$valid:$notValidMsg['msg_items'][$key]['StateCesAmt'];?></td>
                    <td><?= $item['StateCesNonAdvlAmt'];?><?= empty( $validate['items'][$key]['StateCesNonAdvlAmt'] )?$valid:$notValidMsg['msg_items'][$key]['StateCesNonAdvlAmt'];?></td>
                    <td><?= $item['OthChrg'];?><?= empty( $validate['items'][$key]['OthChrg'] )?$valid:$notValidMsg['msg_items'][$key]['OthChrg'];?></td>
                    <td><?= $item['TotItemVal'];?><?= empty( $validate['items'][$key]['TotItemVal'] )?$valid:$notValidMsg['msg_items'][$key]['TotItemVal'];?></td>
                    <td><?= $item['OrdLineRef'];?><?= empty( $validate['items'][$key]['OrdLineRef'] )?$valid:$notValidMsg['msg_items'][$key]['OrdLineRef'];?></td>
                    <td><?= $item['OrgCntry'];?><?= empty( $validate['items'][$key]['OrgCntry'] )?$valid:$notValidMsg['msg_items'][$key]['OrgCntry'];?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
     <div class="col-sm-2"><label>AssVal:<?= $valDtls['AssVal']?><?= empty( $validate['ValDtls']['AssVal'] )?$valid:$notValidMsg['msg_ValDtls']['AssVal'];?></label></div>
     <div class="col-sm-2"><label>TotInvVal:<?= $valDtls['TotInvVal']?><?= empty( $validate['ValDtls']['TotInvVal'] )?$valid:$notValidMsg['msg_ValDtls']['TotInvVal'];?></label></div>
     <div class="col-sm-2"><label>IGST:<?= $valDtls['IgstVal']?><?= empty( $validate['ValDtls']['IgstVal'] )?$valid:$notValidMsg['msg_ValDtls']['IgstVal'];?></label></div>
     <div class="col-sm-2"><label>CgstVal:<?= $valDtls['CgstVal']?><?= empty( $validate['ValDtls']['AssVal'] )?$valid:$notValidMsg['msg_ValDtls']['CgstVal'];?></label></div>
     <div class="col-sm-2"><label>SgstVal:<?= $valDtls['SgstVal']?><?= empty( $validate['ValDtls']['AssVal'] )?$valid:$notValidMsg['msg_ValDtls']['SgstVal'];?></label></div>
</div>
<?php
if( $validate_status ){
    if( $model->isGeneratedIrnNo() ){
        echo Html::a('<i class="fa fa-sync" aria-hidden="true"></i> Sync Invoice',
        ['#'],['bill_id'=>$model->id ,'class'=>'sync-btn btn btn-warning','url'=>Url::to(['//einvoice/sync-by-doc']),'title'=>'Sync to einvoice portal']);
    }else{
        echo Html::a('<i class="fa fa-sync" aria-hidden="true"></i> Sync Invoice',
        ['#'],['bill_id'=>$model->id ,'class'=>'sync-btn  btn btn-warning','url'=>Url::to(['//einvoice/create-irn']),'title'=>'Sync to einvoice portal']);
    }
}

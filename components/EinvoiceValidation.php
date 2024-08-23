<?php

namespace app\components;

use yii;
use yii\helpers\Url;
use app\models\EinvoiceAuth;
use app\models\AgreementBill;

class EinvoiceValidation extends yii\base\BaseObject{
    
    private $field_rules; 
    
    public function __construct(){
        $this->field_rules = [
            'TranDtls'=> [
                'TaxSch' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 10,
                    'pattern' => '^(GST)$',
                    'sample' => 'GST',
                    'options' => [
                        'GST'
                    ],
                    'description' => 'GST- Goods and Services Tax Scheme'
                ],
                'SupTyp' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 10,
                    'pattern' => '(?i)^((B2B)|(SEZWP)|(SEZWOP)|(EXPWP)|(EXPWOP)|(DEXP))$',
                    'sample' => 'B2B',
                    'options' => [
                        "B2B",
					    "SEZWP",
					    "SEZWOP",
					    "EXPWP",
					    "EXPWOP",
					    "DEXP"
                    ],
                    'description' => 'Type of Supply: B2B-Business to Business, SEZWP - SEZ with payment, SEZWOP - SEZ without payment, EXPWP - Export with Payment, EXPWOP - Export without payment,DEXP - Deemed Export'
                ],
                'RegRev' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 1,
                    'pattern' => '^([Y|N]{1})$',
                    'sample' => 'Y',
                    'options' => [
                        "Y",
					    "N"
                    ],
                    'description' => 'Y- whether the tax liability is payable under reverse charge'
                
                ],
                'EcmGstin' => [
                    'required' => false,
                    'min' => 15,
                    'max' => 15,
                    'pattern' => '^([0-9]{2}[0-9A-Z]{13})$',
                    'sample' => '36AAACI4798L1Z0',
                    'options' => NULL,
                    'description' => 'GSTIN of e-Commerce operator'
                ],
                'IgstOnIntra' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 1,
                    'pattern' => '^([Y|N]{1})$',
                    'sample' => 'N',
                    'options' => [
                        "Y",
					    "N"
                    ],
                    'description' => 'Y- indicates the supply is intra state but chargeable to IGST'
                ]
            ],
            'DocDtls'=>[
                'Typ' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 10,
                    'pattern' => '(?i)^((INV)|(CRN)|(DBN))$',
                    'sample' => 'INV',
                    'options' => [
                            "INV",
                            "CRN",
                            "DBN"
                    ],
                    'description' => 'Document Type: INVOICE, CREDIT NOTE, DEBIT NOTE'
                ],
                'No' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 16,
                    'pattern' => '^([a-zA-Z1-9]{1}[a-zA-Z0-9\/-]{0,15})$',
                    'sample' => 'EINVTST01',
                    'options' =>NULL,
                    'description' => 'Document Number'
                ],
                'Dt' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 16,
                    'pattern' => '^[0-3][0-9]\/[0-1][0-9]\/[2][0][1-2][0-9]$',
                    'sample' => date("d/m/Y"),
                    'options' =>NULL,
                    'description' => 'Document Date'
                ],
            ],
            'SellerDtls' => [
                'Gstin' => [
                    'required' => true,
                    'min' => 15,
                    'max' => 15,
                    'pattern' => '([0-9]{2}[0-9A-Z]{13})',
                    'sample' => '36AAACI4798L1Z0',
                    'options' =>NULL,
                    'description' => 'GSTIN of supplier'
                ],
                'LglNm' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Tera Sofware',
                    'options' =>NULL,
                    'description' => 'Legal Name'
                ],
                'Addr1' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Addr2' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Loc' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 50,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Pin' => [
                    'required' => true,
                    'min' => 6,
                    'max' => 6,
                    'pattern' => '',
                    'sample' => '500070'
                ],
                'Stcd' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 2,
                    'pattern' => '^(?!0+$)([0-9]{1,2})$',
                    'sample' => '36'
                ],
                'TrdNm' => [
                    'required' => false,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Tera Sofware'
                ],
                'Ph' => [
                    'required' => false,
                    'min' => 6,
                    'max' => 12,
                    'pattern' => '^([0-9]{6,12})$',
                    'sample' => '9872626253'
                ],
                'Em' => [
                    'required' => false,
                    'min' => 6,
                    'max' => 100,
                    'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                    'sample' => 'abc@gmail.com'
                ]
            ],
            'BuyerDtls' => [
                'Gstin' => [
                    'required' => true,
                    'min' => 15,
                    'max' => 15,
                    'pattern' => '^(([0-9]{2}[0-9A-Z]{13})|URP)$',
                    'sample' => '36AAACI4798L1Z0'
                ],
                'LglNm' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Tera Sofware'
                ],
                'Pos' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 2,
                    'pattern' => '^(?!0+$)([0-9]{1,2})$',
                    'sample' => '36'
                ],
                'Addr1' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Addr2' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Loc' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 50,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Pin' => [
                    'required' => true,
                    'min' => 6,
                    'max' => 6,
                    'pattern' => '',
                    'sample' => '500070'
                ],
                'Stcd' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 2,
                    'pattern' => '^(?!0+$)([0-9]{1,2})$',
                    'sample' => '36'
                ],
                'TrdNm' => [
                    'required' => false,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Tera Sofware'
                ],
                'Ph' => [
                    'required' => false,
                    'min' => 6,
                    'max' => 12,
                    'pattern' => '^([0-9]{6,12})$',
                    'sample' => '9872626253'
                ],
                'Em' => [
                    'required' => false,
                    'min' => 6,
                    'max' => 100,
                    'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                    'sample' => 'abc@gmail.com'
                ]
            ],
            'ShipDtls' => [
                'Gstin' => [
                    'required' => false,
                    'min' => 3,
                    'max' => 15,
                    'pattern' => '^(([0-9]{2}[0-9A-Z]{13})|URP)$',
                    'sample' => '36AAACI4798L1Z0'
                ],
                'LglNm' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Tera Sofware'
                ],
                'Addr1' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Addr2' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Loc' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 50,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Hyderabad'
                ],
                'Pin' => [
                    'required' => true,
                    'min' => 6,
                    'max' => 6,
                    'pattern' => '',
                    'sample' => '500070'
                ],
                'Stcd' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 2,
                    'pattern' => '^(?!0+$)([0-9]{1,2})$',
                    'sample' => '36'
                ],
                'TrdNm' => [
                    'required' => false,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Tera Sofware'
                ]
            ],
            'ItemList'=>[
                'SlNo' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 6,
                    'pattern' => '^([0-9]{1,6})$',
                    'sample' => '1'
                ],
                'IsServc' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 1,
                    'pattern' => '^([Y|N]{1})$',
                    'sample' => 'Y'
                ],
                'HsnCd' => [
                    'required' => true,
                    'min' => 4,
                    'max' => 8,
                    'pattern' => '^(?!0+$)([0-9]{4}|[0-9]{6}|[0-9]{8})$',
                    'sample' => '1001'
                ],
                'UnitPrice' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '^(?!0+$)([0-9]{4}|[0-9]{6}|[0-9]{8})$',
                    'sample' => '20'
                ],
                'TotAmt' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '^(?!0+$)([0-9]{4}|[0-9]{6}|[0-9]{8})$',
                    'sample' => '2000'
                ],
                'AssAmt' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '^(?!0+$)([0-9]{4}|[0-9]{6}|[0-9]{8})$',
                    'sample' => '2000'
                ],
                'GstRt' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 99.99,
                    'pattern' => '^(?!0+$)([0-9]{4}|[0-9]{6}|[0-9]{8})$',
                    'sample' => '3'
                ],
                'TotItemVal' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '^(?!0+$)([0-9]{4}|[0-9]{6}|[0-9]{8})$',
                    'sample' => '2000'
                ],
                'PrdDesc' => [
                    'required' => false,
                    'min' => 3,
                    'max' => 100,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'Item'
                ],
                'Barcde' => [
                    'required' => false,
                    'min' => 3,
                    'max' => 30,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => '400'
                ],
                'Qty' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'FreeQty' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'Unit' => [
                    'required' => true,
                    'min' => 3,
                    'max' => 8,
                    'pattern' => '',
                    'sample' => 'BAGS'
                ],
                'UnitPrice' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'Discount' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'PreTaxVal' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'IgstAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'CgstAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'SgstAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'CesRt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 99.99,
                    'pattern' => '',
                    'sample' => '9'
                ],
                'CesAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'CesNonAdvlAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'StateCesRt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'StateCesAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'StateCesNonAdvlAmt' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'OthChrg' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '40'
                ],
                'OrdLineRef' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 50,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => 'abc'
                ],
                'OrgCntry' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 2,
                    'pattern' => '^([A-Z|a-z]{2})$',
                    'sample' => 'AI'
                ],
                'PrdSlNo' => [
                    'required' => false,
                    'min' => 1,
                    'max' => 2,
                    'pattern' => '^([^\\\"])*$',
                    'sample' => '12345'
                ],
            ],
            'ValDtls'=>[
                'AssVal' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'CgstVal' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'SgstVal' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'IgstVal' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'CesVal' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'StCesVal' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'Discount' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'OthChrg' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '35'
                ],
                'RndOffAmt' => [
                    'required' => false,
                    'min' => -99.99,
                    'max' => 99.99,
                    'pattern' => '',
                    'sample' => '90'
                ],
                'TotInvVal' => [
                    'required' => true,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
                'TotInvValFc' => [
                    'required' => false,
                    'min' => 0,
                    'max' => 999999999999.999,
                    'pattern' => '',
                    'sample' => '3500'
                ],
            ]
        ]; 
    }

    public function validateField( $data, $assignSample = false  ){
        $rules = $this->field_rules;
        $response['status'] = true;
        $response['data'] = null;
        //echo "<pre>";print_r( $data );die();
        foreach( $data as $key => $items ){
            if( !is_array( $items ) ){
                continue;
            }
            foreach( $items as $index => $item ){
                if( is_array( $item ) ){
                    foreach( $item as $counter => $item_val ){
                        if( isset( $rules[$key][$index]['required'] ) && $rules[$key][$index]['required'] && empty( $item_val ) ){
                            $response['status'] = false;
                            $msg = $index . " must not be empty.";
                        }
                        if(!empty( $item_val ) && isset( $rules[$key][$index]['min'] ) && isset( $rules[$key][$index]['max'] ) 
                           && ( strlen( $item_val ) < $rules[$key][$index]['min'] || strlen( $item_val ) > $rules[$key][$index]['max'] ) ){
                            $response['status'] = false;
                            $msg .= $index . " length should be between ".$rules[$key][$index]['min'] ." to ".$rules[$key][$index]['max'].".";
                        }
                        if(!empty( $item_val ) && !empty( $rules[$key][$index]['pattern'] ) && !preg_match( "/".$rules[$key][$index]['pattern']."/", $item_val ) ){
                            $response['status'] = false;
                            $msg .= $index . " should be in valid format.";
                        }   
                        $response['data'][$key][$index][$counter] = $msg;
                    }
                }else{
                    if( isset( $rules[$key][$index]['required'] ) && $rules[$key][$index]['required'] && empty( $item ) ){
                        $response['status'] = false;
                        $msg = $index . " must not be empty.";
                    }
                    if(!empty( $item ) && isset( $rules[$key][$index]['min'] ) && isset( $rules[$key][$index]['max'] ) 
                       && ( strlen( $item ) < $rules[$key][$index]['min'] || strlen( $item ) > $rules[$key][$index]['max'] ) ){
                        $response['status'] = false;
                        $msg .= $index . " length should be between ".$rules[$key][$index]['min'] ." to ".$rules[$key][$index]['max'].".";
                    }
                    if(!empty( $item ) && !empty( $rules[$key][$index]['pattern'] ) && !preg_match( "/".$rules[$key][$index]['pattern']."/", $item ) ){
                        $response['status'] = false;
                        $msg .= $index . " should be in valid format.";
                    }   
                    $response['data'][$key][$index] = $msg;
                }
            }
        }
        /* foreach( $this->field_rules as $key => $attribute_rules ){
            if( is_array( $attribute_rules ) ){
                foreach( $attribute_rules as $index => $val ){
                    $msg = "";
                    if( $key == "ItemList" ){
                       var_dump( $data[$key] );die();
                    }
                    if( is_array( $data[$key][$index] ) ){
                        foreach( $data[$key][$index] as $counter=>$items ){
                            if( isset( $val['required'] ) && $val['required'] && empty( $data[$key][$index][$counter] ) ){
                                $response['status'] = false;
                                $msg = $index . " must not be empty.";
                            }
                            if(!empty( $data[$key][$index][$counter] ) && isset( $val['min'] ) && isset( $val['max'] ) 
                               && ( strlen( $data[$key][$index][$counter] ) < $val['min'] || strlen( $data[$key][$index][$counter] ) > $val['max'] ) ){
                                $response['status'] = false;
                                $msg .= $index . " length should be between ".$val['min'] ." to ".$val['max'].".";
                            }
                            if(!empty( $data[$key][$index][$counter] ) && !empty( $val['pattern'] ) && !preg_match( "/".$val['pattern']."/", $data[$key][$index][$counter] ) ){
                                $response['status'] = false;
                                $msg .= $index . " should be in valid format.";
                            }   
                            $response['data'][$key][$index][$counter] = $msg;
                        }
                    }else{
                        if( isset( $val['required'] ) && $val['required'] && empty( $data[$key][$index] ) ){
                            $response['status'] = false;
                            $msg = $index . " must not be empty.";
                        }
                        if(!empty( $data[$key][$index] ) && isset( $val['min'] ) && isset( $val['max'] ) 
                           && ( strlen( $data[$key][$index] ) < $val['min'] || strlen( $data[$key][$index] ) > $val['max'] ) ){
                            $response['status'] = false;
                            $msg .= $index . " length should be between ".$val['min'] ." to ".$val['max'].".";
                        }
                        if(!empty( $data[$key][$index] ) && !empty( $val['pattern'] ) && !preg_match( "/".$val['pattern']."/", $data[$key][$index] ) ){
                            $response['status'] = false;
                            $msg .= $index . " should be in valid format.";
                        }   
                        $response['data'][$key][$index] = $msg;
                    }
                }
            }
        } */
        //echo "<pre>";print_r( $response );die();
        return $response;
    }

    private function setValidateErrors(){

    }

}
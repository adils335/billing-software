<?php 
use app\models\Ledger;
$modules = new \app\models\ErpModules;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> <?= \app\models\Session::getCurrentSession();?></a>
            </div>
        </div>


        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
				
                    [
                        'label' => 'Dashboard',
                        'icon' => 'file-code-o',
                        'url' => Yii::getAlias('@web').'/site/index',
                        'visible'=>Yii::$app->user->identity?!Yii::$app->user->identity->isSelf():false,
                    ],
                    [
                        'label' => 'Employee Ledger',
                        'icon' => 'file-code-o',
                        'url' => Yii::getAlias('@web').'/ledger?'.$modules->isEmployee(),
                        'visible'=>Yii::$app->user->identity?Yii::$app->user->identity->isSelf():false,
                    ],
                    [
                        'visible'=>true,
                        'label' => 'Accounts',
                        'icon' => 'file',
                        'url' => '#',
                        'items' => [
                            [
                             'label' => 'Employee',
                             'icon' => 'users',
                             'url' => '#',
                             'items' => [
                                 ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/employee',],
                                 ['label' => 'Leave', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/employee-leave',],
                                 ['label' => 'Salary', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/employee/salary-record',],
                                 ['label' => 'Balance Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/balance-report?Ledger[status]=2&Ledger[type]='.Ledger::TYPE_EMPLOYEE.'&Ledger[account_type]=1',],
                              ],
                              'visible'=>true,
                            ],
                            [
                                'label' => 'Vendors',
                                'icon' => 'users',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Vendor', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/vendor','visible'=>true,],
                                    ['label' => 'Vendor Bill', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/vendor-bill','visible'=>true,],
                                    ['label' => 'Work Rate', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/vendor-work-rate','visible'=>true,],
                                    ['label' => 'Work', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/work','visible'=>true,],
                                    ['label' => 'Work Type', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/work-type','visible'=>true,],
                                    ['label' => 'Balance Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/balance-report?Ledger[status]=2&Ledger[type]='.Ledger::TYPE_VENDOR,],
                              ],
                                'visible'=>true,
                            ],
                            [
                             'label' => 'Accounts',
                             'icon' => 'users',
                             'url' => '#',
                             'items' => [
                                 ['label' => 'Account', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/bank-account',],
                              ],
                              'visible'=>true,
                            ],
                            [
                                'label' => 'Worker',
                                'icon' => 'users',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/worker',],
                                    ['label' => 'Leave', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/worker-leave',],
                                    ['label' => 'Salary', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/worker/salary-record',],
                                    ['label' => 'Balance Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/balance-report?Ledger[status]=2&Ledger[type]='.Ledger::TYPE_WORKER,],
                                ],
                              'visible'=>true,
                            ],
                            [
                                'label' => 'Worker Vendor',
                                'icon' => 'users',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/worker-vendor',],    
                                    ['label' => 'Balance Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/balance-report?Ledger[status]=2&Ledger[type]='.Ledger::TYPE_WORKER_VENDOR,],    
                                ],
                              'visible'=>true,
                            ],
                            [
                                'label' => 'Site Dues',
                                'icon' => 'users',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/site-dues',],
                                    ['label' => 'Balance Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/balance-report?Ledger[status]=2&Ledger[type]='.Ledger::TYPE_SITE_DUES,],
                                ],
                              'visible'=>true,
                            ],
                            [
                                'label' => 'Company Dues',
                                'icon' => 'users',
                                'url' => '#',
                                'items' => [        
                                    ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/company-dues',],       
                                    ['label' => 'Balance Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/balance-report?Ledger[status]=2&Ledger[type]='.Ledger::TYPE_COMPANY_DUES,], 
                                ],
                              'visible'=>true,
                            ],
                        ],
                    ],
                    [
                        'label' => 'Ledger',
                        'icon' => 'file-code-o',
                        'url' => Yii::getAlias('@web').'/ledger',
                        'visible'=>true,
                    ],
                    [
                        'label' => 'Payment',
                        'icon' => 'file-code-o',
                        'url' => Yii::getAlias('@web').'/payment',
                        'visible'=>true,
                    ],
                    [
                        'label' => 'Purchase Bill',
                        'icon' => 'file-code-o',
                        'url' => Yii::getAlias('@web').'/purchase-bill',
                        'visible'=>true,
                    ],

					[
                        'visible'=>true,
                        'label' => 'Agreements',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Agreement',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/agreement',],
                                ],
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Quotation',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'List', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/quotation',],
                                ],
                                'visible'=>true,
                            ],
                            [
                                'label' => 'General Bill',
                                'icon' => 'file-code-o',
                                'url' => Yii::getAlias('@web').'/general-bill',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Schedule Rate Master',
                                'icon' => 'file-code-o',
                                'url' => Yii::getAlias('@web').'/schedule-rate-master',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Bill Back Master',
                                'icon' => 'file-code-o',
                                'url' => Yii::getAlias('@web').'/bill-back-master',
                                'visible'=>true,
                            ],
                        ],
                    ],

                    [
                        'visible'=>true,
                        'label' => 'Store',
                        'icon' => 'circle-o',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Store Products',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/store-products',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Agreement Product',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/agreement-product',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Store Indents',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/store-indents',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Store Issue',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/store-issue',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Store Consumed',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/store-consumed',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Purchase Product',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/purchase-product',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Report',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/store-consumed/statement',
                                'visible'=>true,
                            ],
                        ]
                    ],
                    
                    [
                        'visible'=>true,
                        'label' => 'Reports',
                        'icon' => 'file',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Sitewise Report',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/payment/sitewise-report',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Bills',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Active Bill', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/agreement-bill/bill-summary?AgreementBill[status]='.(\app\models\AgreementBill::STATUS_ACTIVE),],
                                    ['label' => 'Cancel Bill', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/agreement-bill/bill-summary?AgreementBill[status]='.(\app\models\AgreementBill::STATUS_DELETE),],
                                    ['label' => 'Credit Note', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/agreement-bill/bill-summary?AgreementBill[status]='.(\app\models\AgreementBill::STATUS_CREDIT_NOTE),],
                                    ['label' => 'Deleted Bill', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/agreement-bill/bill-summary?AgreementBill[status]='.(\app\models\AgreementBill::STATUS_PERMANENT_DELETE),],
                                ],
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Gst Report',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/agreement-bill/gst-report',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'GST Report',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Piad Gst', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/gst-paid','visible'=>true,],
                                    ['label' => 'Hsn Wise Gst', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/gst-hsnno','visible'=>true,],
                                    ['label' => 'Penality Gst', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/gst-penality','visible'=>true,],
                                    ['label' => 'Cancelled Invoice', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/cancelled-invoice','visible'=>true,],
                                    ['label' => 'Invoice Report', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/invoices','visible'=>true,],
                                    ['label' => 'TDS Gst', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/gst-tds'],
                                    ['label' => 'Payable Gst', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/gst-payable'],
                                    ['label' => 'Gst of payment', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/reports/gst-payments'],
                              ],
                                'visible'=>true,
                            ],
                        ],
                    ],
                    [
                        'visible'=>true,
                        'label' => 'Verify',
                        'icon' => 'cog',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Ledger',
                                'icon' => 'circle-o',
                                'items' => [
                                    ['label' => 'Account', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=1&Ledger[company_id]=3'],
                                    ['label' => 'Employee', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=2&Ledger[company_id]=3'],
                                    ['label' => 'Vendor', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=3&Ledger[company_id]=3'],
                                    ['label' => 'Worker Vendor', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=4&Ledger[company_id]=3'],
                                    ['label' => 'Worker', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=5&Ledger[company_id]=3'],
                                    ['label' => 'Site Dues', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=6&Ledger[company_id]=3'],
                                    ['label' => 'Company Dues', 'icon' => 'circle-o', 'url' => Yii::getAlias('@web').'/ledger/unverify-list?Ledger[type]=7&Ledger[company_id]=3'],
                                ],
                                'visible'=>true,
                            ],
                        ],
                    ],
					[
                        'visible'=>true,
                        'label' => 'Setting',
                        'icon' => 'cog',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Roles',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/roles',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Session',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/session',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'State',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/state',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'District',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/district',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Company Type',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/company-type',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Designation',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/designation',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Tax',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/tax',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Company',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/company',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Contract Company',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/contract-company',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Billing Party',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/billing-company',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Gauranty Type',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/agreement-gauranty-type',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'UOM',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/uom',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Allowance Master',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/allowance-master',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Deduction Master',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/deduction-master',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Signature Type',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/signature-type',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Signature Master',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/signature-master',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Holidays',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/holidays',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Backup',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/Backup/default/backup',
                                'visible'=>true,
                            ],
                            [
                                'label' => 'Actions',
                                'icon' => 'circle-o',
                                'url' => Yii::getAlias('@web').'/controller-action/create',
                                'visible'=>true,
                            ],
                        ],
                    ],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],    
                ],
            ]
        ) ?>

    </section>

</aside>

<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Transaction;
use app\models\form\export\ExportCsvForm;
use app\models\form\export\ExportExcelForm;
use app\models\form\export\ExportPdfForm;
use app\models\search\ReportSearch;
use app\models\search\TransactionSearch;
use app\widgets\AicsSummary;
use app\widgets\StaffSummary;
use app\widgets\ClientcategorySummary;
use app\widgets\CertificationSummary;
use app\widgets\EmergencyWelfareProgramSummary;
use app\widgets\ExportContent;
use app\widgets\TransactionTypeSummary;

class ReportController extends Controller
{
    public function actionEwpFindByKeywords($keywords)
    {
        $searchModel = new ReportSearch();
        $daterange = $searchModel->currentYear();
        $hours = App::formatter()->asDateToTimezone(date("Y-m-d H:i:s"), "P");

        return $this->asJson(
            Transaction::findByKeywords($keywords, [
                'm.qr_id',
                'm.first_name',
                'm.middle_name',
                'm.last_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                't.remarks'
            ], 10, [
                'and', [
                    'or', 
                    [
                        't.emergency_welfare_program' => [
                            Transaction::AICS_MEDICAL,
                            Transaction::AICS_LABORATORY_REQUEST,
                            Transaction::BALIK_PROBINSYA_PROGRAM,
                        ]
                    ],
                    [
                        't.transaction_type' => [
                            Transaction::DEATH_ASSISTANCE,
                            Transaction::SOCIAL_PENSION,
                        ]
                    ]
                ],
                [
                    "between", 
                    "date(DATE_ADD(t.created_at,INTERVAL '{$hours}' HOUR_MINUTE))", 
                    App::formatter()->asDaterangeToSingle($daterange, 'start'), 
                    App::formatter()->asDaterangeToSingle($daterange, 'end'), 
                ]
            ])
        );
    }

    public function actionCertificationFindByKeywords($keywords)
    {
        $searchModel = new ReportSearch();
        $daterange = $searchModel->currentYear();
        $hours = App::formatter()->asDateToTimezone(date("Y-m-d H:i:s"), "P");

        return $this->asJson(
            Transaction::findByKeywords($keywords, [
                'm.qr_id',
                'm.first_name',
                'm.middle_name',
                'm.last_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                't.remarks'
            ], 10, ['and', 
                [
                    't.transaction_type' => [
                        Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                        Transaction::CERTIFICATE_OF_INDIGENCY,
                        Transaction::FINANCIAL_CERTIFICATION,
                        Transaction::SOCIAL_CASE_STUDY_REPORT,
                    ]
                ],
                [
                    "between", 
                    "date(DATE_ADD(t.created_at,INTERVAL '{$hours}' HOUR_MINUTE))", 
                    App::formatter()->asDaterangeToSingle($daterange, 'start'), 
                    App::formatter()->asDaterangeToSingle($daterange, 'end'), 
                ]
            ])
        );
    }

    public function actionAicsFindByKeywords($keywords)
    {
        $searchModel = new ReportSearch();
        $daterange = $searchModel->currentYear();
        $hours = App::formatter()->asDateToTimezone(date("Y-m-d H:i:s"), "P");

        return $this->asJson(
            Transaction::findByKeywords($keywords, [
                'm.qr_id',
                'm.first_name',
                'm.middle_name',
                'm.last_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                't.remarks'
            ], 10, ['and', 
                [
                    't.emergency_welfare_program' => [
                        Transaction::AICS_MEDICAL,
                        Transaction::AICS_FINANCIAL,
                        Transaction::AICS_LABORATORY_REQUEST,
                    ]
                ],
                [
                    "between", 
                    "date(DATE_ADD(t.created_at,INTERVAL '{$hours}' HOUR_MINUTE))", 
                    App::formatter()->asDaterangeToSingle($daterange, 'start'), 
                    App::formatter()->asDaterangeToSingle($daterange, 'end'), 
                ]
            ])
        );
    }


    public function actionTransactionTypeFindByKeywords($keywords)
    {
        $searchModel = new ReportSearch();
        $daterange = $searchModel->currentYear();
        $hours = App::formatter()->asDateToTimezone(date("Y-m-d H:i:s"), "P");

        return $this->asJson(
            Transaction::findByKeywords($keywords, [
                'm.qr_id',
                'm.first_name',
                'm.middle_name',
                'm.last_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                't.remarks'
            ], 10, [
                "between", 
                "date(DATE_ADD(t.created_at,INTERVAL '{$hours}' HOUR_MINUTE))", 
                App::formatter()->asDaterangeToSingle($daterange, 'start'), 
                App::formatter()->asDaterangeToSingle($daterange, 'end'), 
            ])
        );
    }


    public function actionEmergencyWelfareProgram()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->emergency_welfare_program_search(['ReportSearch' => App::queryParams()]);
        
  

        return $this->render('emergency-welfare-program', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransactionType()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->transaction_type_search(['ReportSearch' => App::queryParams()]);
        $searchModel->searchTemplate = 'report/_search-transaction-type';
        $searchModel->searchAction = ['report/transaction-type'];

        return $this->render('transaction-type', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPrint()
    {
        $params = App::queryParams();

        if (isset($params['type']) && $params['type'] == 'summary') {
            $searchModel = new ReportSearch([
                'date_range' => App::get('date_range'),
                'current_date'=>App::get('current_date')
            ]);
            if (isset($params['report']) && $params['report'] == 'transaction-type') {
                return $this->render('/layouts/_print', [
                    'content' => $this->renderPartial('_print-transaction-type', [
                        'searchModel' => $searchModel,
                        'scenario' => 'print'
                    ])
                ]);
            }

            if (isset($params['report']) && $params['report'] == 'aics') {
                return $this->render('/layouts/_print', [
                    'content' => $this->renderPartial('_print-aics', [
                        'searchModel' => $searchModel,
                        'scenario' => 'print'
                    ])
                ]);
            }

            if (isset($params['report']) && $params['report'] == 'certification') {
                return $this->render('/layouts/_print', [
                    'content' => $this->renderPartial('_print-certification', [
                        'searchModel' => $searchModel,
                        'scenario' => 'print'
                    ])
                ]);
            }
            
            
            
             if (isset($params['report']) && $params['report'] == 'staff') {
                return $this->render('/layouts/_print', [
                    'content' => $this->renderPartial('_print-staff', [
                        'searchModel' => $searchModel,
                        'scenario' => 'print'
                    ])
                ]);
            }
            
            
            if (isset($params['report']) && $params['report'] == 'client-category') {
                return $this->render('/layouts/_print', [
                    'content' => $this->renderPartial('_print-client-category', [
                        'searchModel' => $searchModel,
                        'scenario' => 'print'
                    ])
                ]);
            }
            
            return $this->render('/layouts/_print', [
                'content' => $this->renderPartial('_print-emergency-welfare-program', [
                    'searchModel' => $searchModel,
                        'scenario' => 'print'
                ])
            ]);
        }

        $searchModel = new ReportSearch();
        if (isset($params['report']) && $params['report'] == 'transaction-type') {
            return $this->render('/layouts/_print', [
                'content' => ExportContent::widget([
                    'file' => 'pdf',
                    'reportName' => 'Transaction Type Summary',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'transactionTypeExportColumns',
                    'tableColumnsName' => 'transactionTypeColumns',
                    'dataProvider' => $searchModel->transaction_type_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ])
            ]);
        }

        if (isset($params['report']) && $params['report'] == 'aics') {
            return $this->render('/layouts/_print', [
                'content' => ExportContent::widget([
                    'file' => 'pdf',
                    'reportName' => 'AICS Summary',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'aicsExportColumns',
                    'tableColumnsName' => 'aicsColumns',
                    'dataProvider' => $searchModel->aics_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ])
            ]);
        }

        if (isset($params['report']) && $params['report'] == 'certification') {
            return $this->render('/layouts/_print', [
                'content' => ExportContent::widget([
                    'file' => 'pdf',
                    'reportName' => 'Certification Summary',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'certificationExportColumns',
                    'tableColumnsName' => 'certificationColumns',
                    'dataProvider' => $searchModel->certification_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ])
            ]);
        }

        return $this->render('/layouts/_print', [
            'content' => ExportContent::widget([
                'file' => 'pdf',
                'reportName' => 'Emergency Welfare Program Summary',
                'searchModel' => $searchModel,
                'exportColumnsName' => 'ewpExportColumns',
                'tableColumnsName' => 'ewpColumns',
                'dataProvider' => $searchModel->emergency_welfare_program_search([
                    'ReportSearch' => App::queryParams()
                ])
            ])
        ]);
    }

    public function actionExportPdf()
    {
        $params = App::queryParams();
        if (isset($params['type']) && $params['type'] == 'summary') {
            if (isset($params['report']) && $params['report'] == 'transaction-type') {
                $model = new ExportPdfForm([
                    'content' => TransactionTypeSummary::widget([
                        'title' => 'Transaction Type Summary',
                        'cgrey' => 'color:#d9d9d9 !important',
                        'tableClass' => 'table table-bordered',
                        'td' => 'padding: 3px 5px !important;color: #000 !important;'
                    ])
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'aics') {
                $model = new ExportPdfForm([
                    'content' => AicsSummary::widget([
                        'title' => 'AICS Summary',
                        'cgrey' => 'color:#d9d9d9 !important',
                        'tableClass' => 'table table-bordered',
                        'td' => 'padding: 3px 5px !important;color: #000 !important;'
                    ])
                ]);
                return $model->export();
            }
            
            
             if (isset($params['report']) && $params['report'] == 'staff') {
                $model = new ExportPdfForm([
                    'content' => StaffSummary::widget([
                        'title' => 'Staff Summary',
                        'cgrey' => 'color:#d9d9d9 !important',
                        'tableClass' => 'table table-bordered',
                        'td' => 'padding: 3px 5px !important;color: #000 !important;'
                    ])
                ]);
                return $model->export();
            }
            
            
             if (isset($params['report']) && $params['report'] == 'client-category') {
                $model = new ExportPdfForm([
                    'content' => ClientcategorySummary::widget([
                        'title' => 'Client Category Summary',
                        'cgrey' => 'color:#d9d9d9 !important',
                        'tableClass' => 'table table-bordered',
                        'td' => 'padding: 3px 5px !important;color: #000 !important;'
                    ])
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'certification') {
                $model = new ExportPdfForm([
                    'content' => CertificationSummary::widget([
                        'title' => 'Certification Summary',
                        'cgrey' => 'color:#d9d9d9 !important',
                        'tableClass' => 'table table-bordered',
                        'td' => 'padding: 3px 5px !important;color: #000 !important;'
                    ])
                ]);
                return $model->export();
            }

            $model = new ExportPdfForm([
                'content' => EmergencyWelfareProgramSummary::widget([
                    'title' => 'Emergency Welfare Program Summary',
                    'cgrey' => 'color:#d9d9d9 !important',
                    'tableClass' => 'table table-bordered',
                    'td' => 'padding: 3px 5px !important;color: #000 !important;'
                ])
            ]);
            return $model->export();
        }

        $searchModel = new ReportSearch();
        if (isset($params['report']) && $params['report'] == 'transaction-type') {
            $model = new ExportPdfForm([
                'content' => ExportContent::widget([
                    'file' => 'pdf',
                    'searchModel' => $searchModel,
                    'reportName' => 'Transaction Type Summary',
                    'exportColumnsName' => 'transactionTypeExportColumns',
                    'tableColumnsName' => 'transactionTypeColumns',
                    'dataProvider' => $searchModel->transaction_type_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ])
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'aics') {
            $model = new ExportPdfForm([
                'content' => ExportContent::widget([
                    'file' => 'pdf',
                    'searchModel' => $searchModel,
                    'reportName' => 'AICS Summary',
                    'exportColumnsName' => 'aicsExportColumns',
                    'tableColumnsName' => 'aicsColumns',
                    'dataProvider' => $searchModel->aics_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ])
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'certification') {
            $model = new ExportPdfForm([
                'content' => ExportContent::widget([
                    'file' => 'pdf',
                    'searchModel' => $searchModel,
                    'reportName' => 'Certification Summary',
                    'exportColumnsName' => 'certificationExportColumns',
                    'tableColumnsName' => 'certificationColumns',
                    'dataProvider' => $searchModel->certification_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ])
            ]);
            return $model->export();
        }

        $model = new ExportPdfForm([
            'content' => ExportContent::widget([
                'file' => 'pdf',
                'searchModel' => $searchModel,
                'reportName' => 'Emergency Welfare Program Summary',
                'exportColumnsName' => 'ewpExportColumns',
                'tableColumnsName' => 'ewpColumns',
                'dataProvider' => $searchModel->emergency_welfare_program_search([
                    'ReportSearch' => App::queryParams()
                ])
            ])
        ]);
        return $model->export();
    }

    public function actionExportCsv()
    {
        $params = App::queryParams();
        if (isset($params['type']) && $params['type'] == 'summary') {
            if (isset($params['report']) && $params['report'] == 'transaction-type') {
                $model = new ExportCsvForm([
                    'content' => TransactionTypeSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ])
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'aics') {
                $model = new ExportCsvForm([
                    'content' => AicsSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ])
                ]);
                return $model->export();
            }
            
            
             if (isset($params['report']) && $params['report'] == 'staff') {
                $model = new ExportCsvForm([
                    'content' => StaffSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ])
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'certification') {
                $model = new ExportCsvForm([
                    'content' => CertificationSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ])
                ]);
                return $model->export();
            }
            
            $model = new ExportCsvForm([
                'content' => EmergencyWelfareProgramSummary::widget([
                    'w25p' => 'width:300px;',
                    'w5p' => 'width:50px;',
                    'bt' => '',
                    'bb' => '',
                    'bl' => '',
                    'br' => '',
                    'cgrey' => 'color: transparent !important;',
                    'td' => 'padding: 3px 5px;color: #000;',
                    'default' => '-0'
                ])
            ]);
            return $model->export();
        }
        
        $searchModel = new ReportSearch();
        if (isset($params['report']) && $params['report'] == 'transaction-type') {
            $model = new ExportCsvForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'transactionTypeExportColumns',
                    'excelIgnoreAttributesName' => 'transactionTypeExcelIgnoreAttributes',
                    'tableColumnsName' => 'transactionTypeColumns',
                    'dataProvider' => $searchModel->transaction_type_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ]),
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'aics') {
            $model = new ExportCsvForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'aicsExportColumns',
                    'excelIgnoreAttributesName' => 'aicsExcelIgnoreAttributes',
                    'tableColumnsName' => 'aicsColumns',
                    'dataProvider' => $searchModel->aics_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ]),
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'certification') {
            $model = new ExportCsvForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'certificationExportColumns',
                    'excelIgnoreAttributesName' => 'certificationExcelIgnoreAttributes',
                    'tableColumnsName' => 'certificationColumns',
                    'dataProvider' => $searchModel->certification_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ]),
            ]);
            return $model->export();
        }

        $model = new ExportCsvForm([
            'content' => ExportContent::widget([
                'file' => 'excel',
                'searchModel' => $searchModel,
                'exportColumnsName' => 'ewpExportColumns',
                'excelIgnoreAttributesName' => 'ewpExcelIgnoreAttributes',
                'tableColumnsName' => 'ewpColumns',
                'dataProvider' => $searchModel->emergency_welfare_program_search([
                    'ReportSearch' => App::queryParams()
                ])
            ]),
        ]);
        return $model->export();
    }

    public function actionExportXls()
    {
        $params = App::queryParams();
        if (isset($params['type']) && $params['type'] == 'summary') {
            if (isset($params['report']) && $params['report'] == 'transaction-type') {
                $model = new ExportExcelForm([
                    'content' => TransactionTypeSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: #d9d9d9 !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xls'
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'aics') {
                $model = new ExportExcelForm([
                    'content' => AicsSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: #d9d9d9 !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xls'
                ]);
                return $model->export();
            }
            
            
             if (isset($params['report']) && $params['report'] == 'staff') {
                $model = new ExportExcelForm([
                    'content' => StaffSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: #d9d9d9 !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xls'
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'certification') {
                $model = new ExportExcelForm([
                    'content' => CertificationSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: #d9d9d9 !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xls'
                ]);
                return $model->export();
            }
            
            $model = new ExportExcelForm([
                'content' => EmergencyWelfareProgramSummary::widget([
                    'w25p' => 'width:300px;',
                    'w5p' => 'width:50px;',
                    'bt' => '',
                    'bb' => '',
                    'bl' => '',
                    'br' => '',
                    'cgrey' => 'color: #d9d9d9 !important;',
                    'td' => 'padding: 3px 5px;color: #000;',
                    'default' => '-0'
                ]),
                'type' => 'xls'
            ]);
            return $model->export();
        }

        $searchModel = new ReportSearch();
        if (isset($params['report']) && $params['report'] == 'transaction-type') {
            $model = new ExportExcelForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                        'exportColumnsName' => 'transactionTypeExportColumns',
                        'excelIgnoreAttributesName' => 'transactionTypeExcelIgnoreAttributes',
                        'tableColumnsName' => 'transactionTypeColumns',
                        'dataProvider' => $searchModel->transaction_type_search([
                            'ReportSearch' => App::queryParams()
                        ])
                ]), 
                'type' => 'xls'
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'aics') {
            $model = new ExportExcelForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                        'exportColumnsName' => 'aicsExportColumns',
                        'excelIgnoreAttributesName' => 'aicsExcelIgnoreAttributes',
                        'tableColumnsName' => 'aicsColumns',
                        'dataProvider' => $searchModel->aics_search([
                            'ReportSearch' => App::queryParams()
                        ])
                ]), 
                'type' => 'xls'
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'certification') {
            $model = new ExportExcelForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                        'exportColumnsName' => 'certificationExportColumns',
                        'excelIgnoreAttributesName' => 'certificationExcelIgnoreAttributes',
                        'tableColumnsName' => 'certificationColumns',
                        'dataProvider' => $searchModel->certification_search([
                            'ReportSearch' => App::queryParams()
                        ])
                ]), 
                'type' => 'xls'
            ]);
            return $model->export();
        }

        $model = new ExportExcelForm([
            'content' => ExportContent::widget([
                'file' => 'excel',
                'searchModel' => $searchModel,
                    'exportColumnsName' => 'ewpExportColumns',
                    'excelIgnoreAttributesName' => 'ewpExcelIgnoreAttributes',
                    'tableColumnsName' => 'ewpColumns',
                    'dataProvider' => $searchModel->emergency_welfare_program_search([
                        'ReportSearch' => App::queryParams()
                    ])
            ]), 
            'type' => 'xls'
        ]);
        return $model->export();
    }

    public function actionExportXlsx()
    {
        $params = App::queryParams();
        if (isset($params['type']) && $params['type'] == 'summary') {
            if (isset($params['report']) && $params['report'] == 'transaction-type') {
                $model = new ExportExcelForm([
                    'content' => TransactionTypeSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xlsx'
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'aics') {
                $model = new ExportExcelForm([
                    'content' => AicsSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xlsx'
                ]);
                return $model->export();
            }
            
            
            if (isset($params['report']) && $params['report'] == 'staff') {
                $model = new ExportExcelForm([
                    'content' => StaffSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xlsx'
                ]);
                return $model->export();
            }

            if (isset($params['report']) && $params['report'] == 'certification') {
                $model = new ExportExcelForm([
                    'content' => CertificationSummary::widget([
                        'w25p' => 'width:300px;',
                        'w5p' => 'width:50px;',
                        'bt' => '',
                        'bb' => '',
                        'bl' => '',
                        'br' => '',
                        'cgrey' => 'color: transparent !important;',
                        'td' => 'padding: 3px 5px;color: #000;',
                        'default' => '-0'
                    ]),
                    'type' => 'xlsx'
                ]);
                return $model->export();
            }

            $model = new ExportExcelForm([
                'content' => EmergencyWelfareProgramSummary::widget([
                    'w25p' => 'width:300px;',
                    'w5p' => 'width:50px;',
                    'bt' => '',
                    'bb' => '',
                    'bl' => '',
                    'br' => '',
                    'cgrey' => 'color: transparent !important;',
                    'td' => 'padding: 3px 5px;color: #000;',
                    'default' => '-0'
                ]),
                'type' => 'xlsx'
            ]);
            return $model->export();
        }

        $searchModel = new ReportSearch();

        if (isset($params['report']) && $params['report'] == 'transaction-type') {
            $model = new ExportExcelForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'transactionTypeExportColumns',
                    'excelIgnoreAttributesName' => 'transactionTypeExcelIgnoreAttributes',
                    'tableColumnsName' => 'transactionTypeColumns',
                    'dataProvider' => $searchModel->transaction_type_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ]), 
                'type' => 'xlsx'
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'aics') {
            $model = new ExportExcelForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'aicsExportColumns',
                    'excelIgnoreAttributesName' => 'aicsExcelIgnoreAttributes',
                    'tableColumnsName' => 'aicsColumns',
                    'dataProvider' => $searchModel->aics_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ]), 
                'type' => 'xlsx'
            ]);
            return $model->export();
        }

        if (isset($params['report']) && $params['report'] == 'certification') {
            $model = new ExportExcelForm([
                'content' => ExportContent::widget([
                    'file' => 'excel',
                    'searchModel' => $searchModel,
                    'exportColumnsName' => 'certificationExportColumns',
                    'excelIgnoreAttributesName' => 'certificationExcelIgnoreAttributes',
                    'tableColumnsName' => 'certificationColumns',
                    'dataProvider' => $searchModel->certification_search([
                        'ReportSearch' => App::queryParams()
                    ])
                ]), 
                'type' => 'xlsx'
            ]);
            return $model->export();
        }

        $model = new ExportExcelForm([
            'content' => ExportContent::widget([
                'file' => 'excel',
                'searchModel' => $searchModel,
                'exportColumnsName' => 'ewpExportColumns',
                'excelIgnoreAttributesName' => 'ewpExcelIgnoreAttributes',
                'tableColumnsName' => 'ewpColumns',
                'dataProvider' => $searchModel->emergency_welfare_program_search([
                    'ReportSearch' => App::queryParams()
                ])
            ]), 
            'type' => 'xlsx'
        ]);
        return $model->export();
    }

    public function actionAics()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->aics_search(['ReportSearch' => App::queryParams()]);
        $searchModel->searchTemplate = 'report/_search-aics';
        $searchModel->searchAction = ['report/aics'];

        return $this->render('aics', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
     public function actionStaff()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->staff_search(['ReportSearch' => App::queryParams()]);
        $searchModel->searchTemplate = 'report/_search-staff';
        $searchModel->searchAction = ['report/staff'];

        return $this->render('staff', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
     public function actionClientCategory()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->client_category_search(['ReportSearch' => App::queryParams()]);
        $searchModel->searchTemplate = 'report/_search-staff';
        $searchModel->searchAction = ['report/client-category'];

        return $this->render('client-category', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCertification()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->certification_search(['ReportSearch' => App::queryParams()]);
        $searchModel->searchTemplate = 'report/_search-certification';
        $searchModel->searchAction = ['report/certification'];

        return $this->render('certification', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
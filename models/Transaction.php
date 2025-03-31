<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\File;
use app\models\TransactionLog;
use app\widgets\Anchor;
use app\widgets\EligibleForAicsNotice;
use app\widgets\Table;
use app\widgets\TinyMce;
use app\widgets\TransactionStatus;
use yii\helpers\ArrayHelper;
use app\widgets\QRCode;
use app\widgets\ReportTemplate;

/**
 * This is the model class for table "{{%transactions}}".
 *
 * @property int $id
 * @property int $member_id
 * @property int $transaction_type
 * @property int $emergency_welfare_program
 * @property int $status
 * @property string|null $remarks
 * @property string|null $files
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Transaction extends ActiveRecord
{
    const NEW_TRANSACTION = 1;
    const MHO_APPROVED = 2;
    const MHO_DECLINED = 3;
    const MSWDO_HEAD_APPROVED = 4;
    const MSWDO_HEAD_DECLINED = 5;
    const MAYOR_APPROVED = 6;
    const MAYOR_DECLINED = 7;
    const BUDGET_OFFICER_CERTIFIED = 8;
    const DISBURSED = 9;
    const COMPLETED = 10;
    const WHITE_CARD_CREATED = 11;
    const CERTIFICATE_CREATED = 12;
    const MSWDO_CLERK_APPROVED = 13;
    const ACCOUNTING_COMPLETED = 14;

    const MHO_PROCESSING = 15;
    const MSWDO_CLERK_PROCESSING = 16;
    const MSWDO_HEAD_PROCESSING = 17;
    const MAYOR_PROCESSING = 18;
    const BUDGET_OFFICER_PROCESSING = 19;
    const ACCOUNTING_OFFICER_PROCESSING = 20;
    const DISBURSING_OFFICER_PROCESSING = 21;
    const ACCOUNTING_OFFICER_PROOFING = 22;
    const TREASURER_PROCESSING = 23;
    const PAYMENT_COMPLETED = 24;
    const ID_RELEASED = 25;
    const SOCIAL_PENSION_RECEIVED = 26;
    const FOR_WHITE_CARD_CREATION = 27;
    const MSWDO_CLERK_DECLINED = 28;


    const EMERGENCY_WELFARE_PROGRAM = 1;
    const SENIOR_CITIZEN_ID_APPLICATION = 2;
    const SOCIAL_PENSION = 3;
    const DEATH_ASSISTANCE = 4;
    const CERTIFICATE_OF_INDIGENCY = 5;
    const FINANCIAL_CERTIFICATION = 6;
    const SOCIAL_CASE_STUDY_REPORT = 7;
    const CERTIFICATE_OF_MARRIAGE_COUNSELING = 8;
    const CERTIFICATE_OF_COMPLIANCE = 9;
    const CERTIFICATE_OF_APPARENT_DISABILITY = 10;

    // EMERGENCY WELFARE PROGRAM
    const AICS_FINANCIAL = 1;
    const AICS_MEDICAL = 2;
    const AICS_LABORATORY_REQUEST = 3;
    const BALIK_PROBINSYA_PROGRAM = 4;
    const EDUCATIONAL_ASSISTANCE = 5;
    const FOOD_ASSISTANCE = 6;
    const FINANCIAL_AND_OTHER_ASSISTANCE = 7;
    const AICS_MEDICAL_MEDICINE = 1;

    const SOCIAL_PENSION_PENDING = 0;
    const SOCIAL_PENSION_CLAIMED = 1;

    const DOCUMENT_PENDING = 0;
    const DOCUMENT_FOR_REVIEW = 1; // head
    const DOCUMENT_REVIEWED = 2; // head
    const DOCUMENT_FOR_APPROVAL = 3; // mayor
    const DOCUMENT_APPROVED = 4; // mayor
    const DOCUMENT_CLERK_CREATED = 5; // clerk


    // RELATION TYPES
    const CLIENT_IS_PATIENT = 1;
    const MEMBER_OF_HOUSEHOLD = 2;


    // recommended_services_assistance
    const COUNSELING = 1;
    const LEGAL_ASSISTANCE = 2;
    const MEDICAL_ASSISTANCE_CASH = 3;
    const MEDICAL_ASSISTANCE_LAB_REQUEST = 4;
    const MEDICAL_ASSISTANCE_MEDICINE = 5;
    const BURIAL_ASSISTANCE = 6;
    const TRANSPORTATION_ASSISTANCE = 7;
    const OTHER_RSA = 8;

    // masterlist status
    const MASTERLIST_PENDING = 0;
    const MASTERLIST_ADDED = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transactions}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'transaction',
            'mainAttribute' => 'memberFullname',
            'paramName' => 'token',
            'relatedModels' => ['transactionLogs']
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['fulldate'] = 'fulldate';
        $fields['transactionTypeName'] = 'transactionTypeName';
        $fields['viewUrl'] = 'viewUrl';
        $fields['updateUrl'] = 'updateUrl';

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['member_id', 'transaction_type'], 'required'],
            [['emergency_welfare_program'], 'required', 'when' => function($model) {
                return $model->transaction_type == self::EMERGENCY_WELFARE_PROGRAM;
            }],
            [['member_id', 'transaction_type', 'emergency_welfare_program', 'status'], 'integer'],
            [['remarks'], 'string'],
            [['amount'], 'number'],
            [['files', 'content'], 'safe'],
            ['status', 'default', 'value' => self::NEW_TRANSACTION],
            ['member_id', 'exist', 'targetRelation' => 'member'],
            ['transaction_type', 'in', 
                'range' => array_keys(App::keyMapParams('transaction_types'))
            ],

            ['emergency_welfare_program', 'in', 
                'range' => array_keys(App::keyMapParams('emergency_welfare_programs')),
                'when' => function($model) {
                    return $model->emergency_welfare_program;
                }
            ],
            
            ['status', 'in',
                'range' => array_keys(App::keyMapParams('transaction_status'))
            ],
            [['white_card', 'general_intake_sheet', 'obligation_request', 'petty_cash_voucher', 'senior_citizen_intake_sheet', 'social_pension_application_form'], 'safe'],
            // ['amount', 'validateAmount'],
            // ['social_pension_status', 'integer'],
            // ['social_pension_status', 'default', 'value' => self::SOCIAL_PENSION_PENDING],
            // ['social_pension_status', 'in', 'range' => array_keys(App::keyMapParams('social_pension_status'))],
            ['social_pension_photo', 'safe'],
            [['social_pension_status', 'white_card_status', 'general_intake_sheet_status', 'obligation_request_status', 'petty_cash_voucher_status', 'senior_citizen_intake_sheet_status'], 'integer'],
            [['social_pension_status', 'white_card_status', 'general_intake_sheet_status', 'obligation_request_status', 'petty_cash_voucher_status', 'senior_citizen_intake_sheet_status'], 'default', 'value' => self::DOCUMENT_PENDING],
            [
                ['name_of_deceased', 'caused_of_death', 'id_of_deceased'], 
                'required', 
                'when' => function($model) {
                    return $model->transaction_type == self::DEATH_ASSISTANCE;
                }
            ],
            [['claimant', 'name_of_deceased'], 'string', 'max' => 225],
            ['caused_of_death', 'safe'],
            ['id_of_deceased', 'integer'],
            ['id_of_deceased', 'validateIdOfDeceased'],
            [['patient_name', 'relation_to_patient', 'diagnosis'], 'string', 'max' => 225],
            [['diagnosis'], 'required', 
                'when' => function($model) {
                    return $model->isMedicalTransaction;
                }
            ],
            [['client_category'], 'safe'],
            ['recommended_services_assistance', 'integer'],
            ['recommended_services_assistance', 'default', 'value' => self::OTHER_RSA],
            ['relation_type', 'integer'],
            ['relation_type', 'default', 'value' => self::CLIENT_IS_PATIENT],
            ['relation_type', 'in', 'range' => array_keys(App::keyMapParams('patient_relation_types'))],
            ['relation_to_patient', 'required', 'when' => function($model) {
                return $model->relation_type != self::CLIENT_IS_PATIENT;
            }],
            ['patient_id', 'integer'],
            ['patient_id', 'validatePatientId'],
            [
                [
                    'medical_procedure_requested' , 
                    'laboratory_procedure_requested', 
                    'destination_province', 
                    'destination_municipality',
                    'referral_to',
                    'other_rsa',
                ], 
                'string', 'max' => 225
            ],
            [
                [
                    'medical_procedure_requested' , 
                    'laboratory_procedure_requested', 
                    'destination_province', 
                    'destination_municipality',
                    'referral_to',
                    'other_rsa',
                ], 
                'safe'
            ],
            ['masterlist_status', 'integer'],
            ['masterlist_status', 'default', 'value' => self::MASTERLIST_PENDING],
            ['masterlist_status', 'in', 'range' => array_keys(App::keyMapParams('masterlist_status'))],
        ]);
    }

    public function validatePatientId($attribute, $params)
    {
        if ($this->patient_id) {
            $patient = Member::findOne($this->patient_id);

            if ($patient == null) {
                $this->addError($attribute, 'Patient id invalid');
            }
        }
    }

    public function getIsMedicalTransaction()
    {
        return ($this->transaction_type == self::EMERGENCY_WELFARE_PROGRAM) 
            && (
                $this->emergency_welfare_program == self:: AICS_MEDICAL
                || $this->emergency_welfare_program == self:: AICS_LABORATORY_REQUEST
                || $this->emergency_welfare_program == self:: AICS_MEDICAL_MEDICINE
            );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'member_id' => 'Member ID',
            'memberFullname' => 'Member Name',
            'transaction_type' => 'Transaction Type',
            'emergency_welfare_program' => 'Assistance Type',
            'status' => 'Status',
            'remarks' => 'Remarks',
            'files' => 'Files',
            'amount' => 'Amount (Php)',
            'memberFullname' => 'Member Name',
            'transactionTypeName' => 'Transaction Type',
            'assistanceTypeName' => 'Assistance Type',
            'transactionStatusLabel' => 'Status',
            'statusBadge' => 'Status',
            'claimant' => 'Claimant/Client',
            'diagnosis' => 'Medical Problem',
            'caused_of_death' => 'Cause of Death'
        ]);
    }

    public function validateIdOfDeceased($attribute, $params)
    {
        if ($this->transaction_type == self::DEATH_ASSISTANCE) {
            if ($this->id_of_deceased) {
                if (($member = Member::findOne($this->id_of_deceased)) == null) {
                    $this->addError($attribute, 'No member found.');
                }
            }
        }
    }

    public function validateAmount($attribute, $params)
    {
        $budget = App::setting('budget');

        if ($this->amount > $budget->totalAmount) {
            $this->addError($attribute, 'Amount is greater than the usable budget for this year.');
        }
    }

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->remarks = 'New Transaction';
            $this->files = [];
            $this->status = self::NEW_TRANSACTION;
            $this->social_pension_status = self::SOCIAL_PENSION_PENDING;
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TransactionQuery(get_called_class());
    }

    /*public function getFooterGridColumns()
    {
        $columns = parent::getFooterGridColumns();

        if (isset($columns['active'])) {
            unset($columns['active']);
        }

        return $columns;
    }*/
     
    public function gridColumns()
    {
        return [
            'member_name' => [
                'attribute' => 'memberFullname', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->memberFullname,
                        'link' => $model->viewUrl,
                        'text' => true
                    ])
                    .'<div class="text-muted small">'.$model->member->household->no.' | '.$model->member->qr_id.'</div>'
                    ;
                }
            ],
            
            
            
            
            'barangay' => [
                'label' => 'Barangay',
               // 'attribute' => 'createdByName',
                'format' => 'raw',
                 'value' => function($model) {
                    return $model->member->barangayName;
                 },
            ],
            
            
            
            'transaction_type' => [
                'attribute' => 'transaction_type',
                'value' => function($model) {
                    return $model->getTransactionTag('<br>');
                }, 
                'format' => 'raw'
            ],
            // 'emergency_welfare_program' => [
            //     'attribute' => 'emergency_welfare_program', 
            //     'value' => 'assistanceTypeName', 
            //     'format' => 'raw'
            // ],
            'status' => [
                'attribute' => 'status', 
                'value' => 'statusBadge', 
                'format' => 'raw',
                'value' => function($model) {
                    return implode('<br>', [
                        $model->statusBadge,
                        $model->secondaryLabel,
                    ]);
                }
            ],
            
             'created_by' => [
                'label' => 'Staff',
               // 'attribute' => 'createdByName',
                'format' => 'raw',
                 'value' => function($model) {
                    return $model->createdByName;
                 },
            ],
            
            
             'amount' => [
                'label' => 'Amount Disbursed',
               // 'attribute' => 'createdByName',
                'format' => 'raw',
                 'value' => function($model) {
                    return  number_format($model->amount, 2);
                 },
            ],
            
            
            
            
            // 'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
            // 'amount' => ['attribute' => 'amount', 'format' => 'raw'],
        ];
    }
    
    
    
    
     public function exportColumns()
    {
        return [
            'member_name' => [
                'attribute' => 'memberFullname', 
                'format' => 'raw',
                'value' => function($model) {
                    return $model->memberFullname;
                }
            ],
            
            'hs_no' => [
                //'attribute' => 'memberFullname', 
                'label' => 'Household No',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->member->household->no;
                }
            ],
            
             'qr_id' => [
                //'attribute' => 'memberFullname', 
                'label' => 'ID No',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->member->qr_id;
                }
            ],
            
            
            
            'barangay' => [
                'label' => 'Barangay',
               // 'attribute' => 'createdByName',
                'format' => 'raw',
                 'value' => function($model) {
                    return $model->member->barangayName;
                 },
            ],
            
            
            'transaction_type' => [
                'attribute' => 'transaction_type',
                'value' => function($model) {
                    return $model->transactionTypeName; //$model->getTransactionTag(' | ');
                    
                }, 
                'format' => 'raw'
            ],
            
            'aics_type' => [
                'attribute' => 'emergency_welfare_program',
                'value' => function($model) {
                    
                   
                    return $model->assistanceTypeName; //$model->getTransactionTag(' | ');
                    
                }, 
                'format' => 'raw'
            ],
            // 'emergency_welfare_program' => [
            //     'attribute' => 'emergency_welfare_program', 
            //     'value' => 'assistanceTypeName', 
            //     'format' => 'raw'
            // ],
            'status' => [
                'attribute' => 'status', 
                'value' => 'statusBadge', 
                'format' => 'raw',
                'value' => function($model) {
                    return implode('', [
                        $model->statusBadge,
                        $model->secondaryLabel,
                    ]);
                }
            ],
            
             'created_by' => [
                'label' => 'Staff',
               // 'attribute' => 'createdByName',
                'format' => 'raw',
                 'value' => function($model) {
                    return $model->createdByName;
                 },
            ],
            
            
            'amount' => [
                'label' => 'Amount Disbursed',
               // 'attribute' => 'createdByName',
                'format' => 'raw',
                 'value' => function($model) {
                    return $model->amount;
                 },
            ],
            
            
            'created_at' => [
                'attribute' => 'created_at',
               // 'format' => 'fulldate'
                
                 'value' => function($model) {
                     // asDateToTimezone($date='', $format='F d, Y h:i A', $timezone="")
                   //  echo App::formatter()->asDateToTimezone('2014-03-12 12:30:00','Y-m-d H:i:s');
                      $date = App::formatter()->asDateToTimezone($model->created_at,'Y-m-d');
                     return  $date;
                 },
            ],
            
            'time' => [
                'label' => 'Time',
               // 'format' => 'fulldate'
                 'value' => function($model) {
                     // asDateToTimezone($date='', $format='F d, Y h:i A', $timezone="")
                      $date = App::formatter()->asDateToTimezone($model->created_at,'H:i:s');
                     return  $date;
                 },
            ],
            
            // 'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
            // 'amount' => ['attribute' => 'amount', 'format' => 'raw'],
        ];
    }


    public function getSecondaryLabel()
    {
        if (isset($this->transactionStatus['secondaryLabel'])) {
            return Html::tag('span', $this->transactionStatus['secondaryLabel'], [
                'class' => 'text-muted'
            ]);
        }
    }

    public function getFooterDetailColumns()
    {
        $columns = parent::getFooterDetailColumns();

        unset($columns['recordStatusHtml']);

        return $columns;
    }

    public function getMedicines()
    {
        return $this->hasMany(Medicine::className(), ['transaction_id' => 'id']);
    }

    public function getMedicineTags()
    {
        if (($medicines = $this->medicines) != null) {
            $data = [];

            foreach ($medicines as $medicine) {
                $data[] = "{$medicine->quantity} {$medicine->unit} of {$medicine->name}";
            }

            return App::formatter('asImplode', $data);
        }
    }

    public function getMedicinesTable()
    {
        if (($medicines = $this->medicines) != null) {
            $td = [];

            foreach ($medicines as $medicine) {
                $td[] = [
                    $medicine->name,
                    $medicine->price,
                ];
            }

            return $td ? Table::widget([
                'th' => ['Name', 'Price'],
                'td' => $td
            ]): '';
        }
    }

    public function detailColumns()
    {
        $columns = [
            'status' => [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return implode('<br>', [
                        $model->statusBadge,
                        $model->secondaryLabel,
                    ]);
                }
            ],
            
            'member' => [
                'label' => 'Claimant / Client',
                'attribute' => 'memberFullname',
                'format' => 'raw',
            ],
            
            
             'member_id' => [
                'label' => 'QR ID',
                //'attribute' => 'memberFullname',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->member->qr_id;
                }
            ],
            
            'household_no' => [
                'label' => 'Household No.',
                //'attribute' => 'memberFullname',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->member->household->no;
                }
            ],
            
            
            
            'transaction' => [
                'attribute' => 'transactionTypeName',
                'format' => 'raw',
            ],
            'assistance' => [
                'attribute' => 'assistanceTypeName',
                'format' => 'raw',
            ],
            // 'remarks' => [
            //     'attribute' => 'remarks',
            //     'format' => 'raw',
            // ],
            'amount' => [
                'attribute' => 'amount',
                'format' => 'number',
            ]
        ];

        if ($this->isMedicalTransaction) {
            $columns['patient_name'] = [
                'attribute' => 'patient_name',
                'format' => 'raw',
            ];

            if ($this->relation_type != self::CLIENT_IS_PATIENT) {
                $columns['relation_to_patient'] = [
                    'attribute' => 'relation_to_patient',
                    'format' => 'raw',
                ];
            }

            $columns['relation_type'] = [
                'attribute' => 'relation_type',
                'format' => 'raw',
                'value' => function($model) {
                    return App::keyMapParams('patient_relation_types')[$this->relation_type] ?? '';
                }
            ];
            
            $columns['diagnosis'] = [
                'attribute' => 'diagnosis',
                'format' => 'raw',
            ];
        }

        if ($this->emergency_welfare_program == self::AICS_MEDICAL_MEDICINE) {
            $columns['medical_procedure_requested'] = [
                'attribute' => 'medical_procedure_requested',
                'format' => 'raw',
            ];
        }
        
        if ($this->emergency_welfare_program == self::AICS_LABORATORY_REQUEST) {
            $columns['laboratory_procedure_requested'] = [
                'attribute' => 'laboratory_procedure_requested',
                'format' => 'raw',
            ];
        }

        if ($this->emergency_welfare_program == self::BALIK_PROBINSYA_PROGRAM) {
            $columns['destination_province'] = [
                'attribute' => 'destination_province',
                'format' => 'raw',
            ];
            $columns['destination_municipality'] = [
                'attribute' => 'destination_municipality',
                'format' => 'raw',
            ];
        }


        $columns['client_category'] = [
            'attribute' => 'client_category',
            'format' => 'raw',
            'value' => function($model) {
                if ($model->client_category && is_array($model->client_category)) {
                    return Html::ul($model->client_category);
                }
                return ;
            }
        ];
        $columns['recommended_services_assistance'] = [
            'attribute' => 'rsa',
            'label' => 'recommended services assistance',
            'format' => 'raw',
        ];

        // if ($this->medicines) {
        //     $columns['medicine'] = [
        //         'attribute' => 'medicine',
        //         'format' => 'raw',
        //         'value' => function($model) {
        //             return $model->medicinesTable;
        //         }
        //     ];
        // }


        if ($this->isSeniorCitizenIdApplication) {
            unset(
                $columns['assistance'],
                $columns['amount'],
                $columns['client_category'],
                $columns['recommended_services_assistance']
            );
        }

        if ($this->isDeathAssistance) {
            $columns['name_of_deceased'] = [
                'attribute' => 'name_of_deceased',
                'format' => 'raw',
            ];
            $columns['caused_of_death'] = [
                'attribute' => 'caused_of_death',
                'format' => 'raw',
            ];

            $columns['relation_to_patient'] = [
                'attribute' => 'relation_to_patient',
                'format' => 'raw',
            ];

            $columns['relation_type'] = [
                'attribute' => 'relation_type',
                'format' => 'raw',
                'value' => function($model) {
                    return App::keyMapParams('patient_relation_types')[$this->relation_type] ?? '';
                }
            ];
        }
        

        if ($this->isSocialPension) {
            unset(
                $columns['assistance'],
                $columns['amount'],
                $columns['client_category'],
                $columns['recommended_services_assistance']
            );
        }
        
        
        
         $columns['address'] = [
                //'attribute' => 'relation_type',
                'label' => 'Address',
                'format' => 'raw',
                'value' => function($model) {
                    return   $model->member->address; //App::keyMapParams('patient_relation_types')[$this->relation_type] ?? '';
                }
            ];
        

        return $columns;
    }

    public function getTransactionLogs()
    {
        return $this->hasMany(TransactionLog::className(), ['transaction_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getFilePreview()
    {
        if (($photos = $this->files) != null) {
            $images = [];

            foreach ($photos as $photo) {
                $images[] = Html::image($photo, ['w' => 100, 'h' => 100, 'ratio' => 'false'], [
                    'class' => 'img-thumbnail'
                ]);
            }

            return implode(' ', $images);
        }
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }


    public function getMemberDetailView($withTransactionBtn = true)
    {
        if (($member = $this->member) != null) {
            return $member->getDetailView($withTransactionBtn);
        }
    }

    public function getMemberFullname()
    {
        if (($model = $this->member) != null) {
            return $model->fullname;
        }
    }

    public function getClaimantName()
    {
        return $this->claimant ?: $this->memberFullname;
    }

    public function getTransactionStatus()
    {
        return App::params('transaction_status')[$this->status];
    }

    public function getTransactionStatusLabel()
    {
        return $this->transactionStatus['label'];
    }

    public function getStatusLabel()
    {
        return $this->transactionStatus['label'];
    }

    public function getTransactionStatusClass()
    {
        return $this->transactionStatus['class'];
    }

    public function getTransactionType()
    {
        return App::params('transaction_types')[$this->transaction_type];
    }

    public function getTransactionTypeName()
    {
        return $this->transactionType['label'];
    }

    public function getFormattedAmount()
    {
        return App::formatter('asNumber', $this->amount);
    }

    public function getAssistanceType()
    {
        if ($this->emergency_welfare_program) {
            return App::params('emergency_welfare_programs')[$this->emergency_welfare_program];
        }
    }

    public function getAssistanceTypeName()
    {
        if (isset($this->assistanceType['label'])) {
            return $this->assistanceType['label'];
        }

        $transaction_types = [
            self::DEATH_ASSISTANCE,
            // self::SOCIAL_PENSION,
        ];

        if (in_array($this->transaction_type, $transaction_types)) {
            return 'Cash';
        }
    }

    public static function findByKeywords($keywords='', $attributes='', $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('t')
                ->joinWith('member m')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'files', 
            'client_category'
        ];

        $behaviors['TransactionBehavior'] = [
            'class' => 'app\behaviors\TransactionBehavior', 
        ];

        return $behaviors;
    }

    public function getImageFile()
    {
        if (($photos = $this->files) != null) {
            if (($file = File::findByToken($this->files[0])) != null) {
                return $file;
            }
        }
    }

    public function getImageFiles()
    {
        if (($photos = $this->files) != null) {
            $files = File::find()
                ->where(['token' => $photos])
                ->orderBy(['id' => SORT_DESC])
                ->all();

            // foreach ($photos as $token) {
            //     if (($file = File::findByToken($token)) != null) {
            //         $files[] = $file;
            //     }
            // }

            return $files;
        }
    }

    public function getDate()
    {
        return App::formatter()
            ->asDateToTimezone($this->created_at, 'm/d/Y');
    }

    public function getFulldate()
    {
        return App::formatter()->asFulldate($this->created_at);
    }

    public function getStatusBadge()
    {
        return Html::tag('label', $this->transactionStatusLabel, [
            'class' => 'badge badge-' . $this->transactionStatusClass
        ]);
    }

    public function getLabel()
    {
        return Html::tag('span', $this->transactionStatus['label'], [
            'class' => 'label font-weight-bolder label-inline ml-2 label-light-' . $this->transactionStatus['class']
        ]);
    }
    
    public function getViewBtn()
    {
        return Html::a('View', $this->viewUrl, [
            'class' => 'btn btn-light-primary font-weight-bolder font-size-sm btn-sm',
            'target' => '_blank'
        ]);
    }

    public function saveNoMemberId()
    {
        if (($member = $this->memberByQrId) != null) {
            $this->member_id = $member->id;
        }

        if ($this->validate()) {
            return $this->save();
        }

        return false;
    }

    public function getUpdateUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('update')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'update']),
                $paramName => $this->{$paramName},
                'type' => App::params('transaction_types')[$this->transaction_type]['slug']
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getAgo()
    {
        return App::formatter('asAgo', $this->created_at);
    }

    public static function recent($limit = 6)
    {
        return self::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function getStatusList()
    {
        $data = [];

        foreach (App::params('transaction_status') as $id => $t) {
            $data[] = Html::a($t['label'], '#', [
                'class' => 'dropdown-item transaction-status-item',
                'data-id' => $id,
            ]);
        }

        return implode('', $data);
    }

    public function getSingleFileDisplayPath()
    {
        if (($file = $this->imageFile) != null) {
            return $file->displayPath;
        }
    }

    public function getTransactionTag($glue = ' | ')
    {
        $arr[] = $this->transactionTypeName;

        if (($assistanceTypeName = $this->assistanceTypeName) != null) {
            $arr[] = Html::tag('label', $assistanceTypeName, [
            'class' => 'badge badge-secondary'
           ]);
        }

        return implode($glue, $arr);
    }

    public static function certificateFilters()
    {
        $types = [
            self::CERTIFICATE_OF_INDIGENCY,
            self::FINANCIAL_CERTIFICATION,
        ];

        $data = [];

        foreach ($types as $type) {
            $data[$type] = App::params('transaction_types')[$type]['label'] ?? '';
        }

        return  $data;
    }

    public static function typeFilters()
    {
        $types = [
            self::EMERGENCY_WELFARE_PROGRAM,
            self::SENIOR_CITIZEN_ID_APPLICATION,
            self::SOCIAL_PENSION,
            self::DEATH_ASSISTANCE,
        ];

        $data = [];

        foreach ($types as $type) {
            $data[$type] = App::params('transaction_types')[$type]['label'] ?? '';
        }

        return  $data;
    }

    public function getIsMedical()
    {
        if ($this->transaction_type == self::EMERGENCY_WELFARE_PROGRAM) {
            return $this->assistanceType['medical'];
        }

        return false;
    }

    public function getIsReport()
    {
        return in_array($this->transaction_type, [
            self::CERTIFICATE_OF_INDIGENCY,
            self::FINANCIAL_CERTIFICATION,
            self::SOCIAL_CASE_STUDY_REPORT
        ]);
    }

    public function getTransactionTypeTag()
    {
        if ($this->isReport) {
            return $this->transactionTypeName;
        }

        return implode(' ', array_filter([
            $this->transactionTag,
        ]));
    }

    public function getViewUrlWhiteCard($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'white-card'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlGeneralIntakeSheet($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'general-intake-sheet'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlSeniorCitizenIntakeSheet($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'senior-citizen-intake-sheet'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlSocialPensionApplicationForm($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'social-pension-application-form'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlObligationRequest($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'obligation-request-form'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlPettyCashVoucher($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'petty-cash-voucher'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlDetails($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'details'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }


    public function getViewUrlMemberProfile($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'member-profile'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlDocuments($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'documents'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getWhiteCardButton()
    {
        if (! App::identity()->can('create-white-card', 'transaction')) {
            return;
        }

        if ($this->isMedical) {
            if ($this->white_card) {
                $status = [
                    self::NEW_TRANSACTION,
                    self::WHITE_CARD_CREATED,
                    self::MHO_APPROVED,
                    self::MSWDO_CLERK_APPROVED,
                ];

               // if (in_array($this->status, $status)) {
                    return Html::button('Update White Card', [
                        'class' => 'btn btn-light-primary font-weight-bolder btn-update-white-card'
                    ]);
               // }
            }
            else {
                return Html::button('Create White Card', [
                    'class' => 'btn btn-light-primary font-weight-bolder btn-create-white-card'
                ]);
            }
        }
    }

    public function getGisButton()
    {
        if (! App::identity()->can('create-general-intake-sheet', 'transaction')) {
            return;
        }

        if ($this->general_intake_sheet) {
            return Html::button('Update General Intake Sheet', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-update-general-intake-sheet'
            ]);
        }
        else {
            return Html::button('Create General Intake Sheet', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-create-general-intake-sheet'
            ]);
        }
    }

    public function getSpafButton()
    {
        if (! App::identity()->can('create-social-pension-application-form', 'transaction')) {
            return;
        }

        if ($this->social_pension_application_form) {
            return Html::button('Update Social Pension Application Form', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-update-social-pension-application-form'
            ]);
        }
        else {
            return Html::button('Create Social Pension Application Form', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-create-social-pension-application-form'
            ]);
        }
    }

    public function getScisButton()
    {
        if (! App::identity()->can('create-senior-citizen-intake-sheet', 'transaction')) {
            return;
        }

        if ($this->senior_citizen_intake_sheet) {
            if ($this->status == self::ID_RELEASED) {
                return ;
            }
            return Html::button('Update Intake Sheet', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-update-senior-citizen-intake-sheet'
            ]);
        }
        else {
            return Html::button('Create Intake Sheet', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-create-senior-citizen-intake-sheet'
            ]);
        }
    }

    public function getOrfButton()
    {
        if (! App::identity()->can('create-obligation-request', 'transaction')) {
            return;
        }

        if ($this->obligation_request) {
            return Html::button('Update Obligation Request', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-update-obligation-request'
            ]);
        }
        else {
            return Html::button('Create Obligation Request', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-create-obligation-request'
            ]);
        }
    }

    public function getPcvButton()
    {
        if (! App::identity()->can('create-petty-cash-voucher', 'transaction')) {
            return;
        }
        
        if ($this->petty_cash_voucher) {
            return Html::button('Update Petty Cash Voucher', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-update-petty-cash-voucher'
            ]);
        }
        else {
            return Html::button('Create Petty Cash Voucher', [
                'class' => 'btn btn-light-primary font-weight-bolder btn-create-petty-cash-voucher'
            ]);
        }
    }

    public function getStatusAction()
    {
        return TransactionStatus::widget([
            'model' => $this,
            'template' => 'separate'
        ]);
    }

    public function getCanUpdate()
    {
        // $types = [
        //     self::CERTIFICATE_OF_INDIGENCY,
        //     self::FINANCIAL_CERTIFICATION,
        //     self::SOCIAL_CASE_STUDY_REPORT
        // ];

        // if (in_array($this->transaction_type, $types)) {
        //     return false;
        // }

        return true;
    }

    public function getEligibleForAicsTag()
    {
        return EligibleForAicsNotice::widget([
            'model' => $this->member,
            'transaction_id' => $this->id,
            'tagOnly' => true
        ]);
    }

    public function getEligibleForAicsNotice($transaction_id='')
    {
        $transaction_id = $transaction_id ?: $this->id;
        
        if (($member = $this->member) != null) {
            return $member->getEligibleForAicsNotice($transaction_id);
        }
    }

    public function documentBadge($data)
    {
        $status = App::params('transaction_document_status')[$data];

        $label = $status['label'];
        if ($status['id'] == self::DOCUMENT_PENDING) {
            $label = $status['label'];
        }

        return Html::tag('span', $label, [
            'class' => 'document-badge font-weight-bold label-inline label label-light-' . $status['class']
        ]);
    }

    public function getWhiteCardBadgeStatus()
    {
        $status = App::params('transaction_document_status')[$this->white_card_status];

        $label = ($this->white_card_status == self::DOCUMENT_PENDING)? 'Pending': $status['label'];

        return Html::tag('span', $label, [
            'class' => 'document-badge font-weight-bold label-inline label label-light-' . $status['class']
        ]);
    }

    public function getGeneralIntakeSheetBadgeStatus()
    {
        return $this->documentBadge($this->general_intake_sheet_status);
    }

    public function getSocialPensionApplicationFormBadgeStatus()
    {
        return $this->documentBadge($this->social_pension_status);
    }

    public function getSeniorCitizenIntakeSheetBadgeStatus()
    {
        return $this->documentBadge($this->senior_citizen_intake_sheet_status);
    }

    public function getSeniorCitizenIdBadgeStatus($id='')
    {
        $id = $id ?: $this->member->senior_citizen_id;

        return Html::tag('span', $id ? 'Created': 'None', [
            'class' => 'document-badge font-weight-bold label-inline label label-light-' . (($id)? 'success': 'danger')
        ]);
    }

    public function getObligationRequestBadgeStatus()
    {
        return $this->documentBadge($this->obligation_request_status);
    }

    public function getPettyCashVoucherBadgeStatus()
    {
        return $this->documentBadge($this->petty_cash_voucher_status);
    }

    public function getQrId()
    {
        if (($member = $this->member) != null) {
            return $member->qr_id;
        }
    }

    public function getRequirements()
    {
        $requirements = [];
        $assistanceType = $this->assistanceType;

        if ($assistanceType) {
            $requirements = $assistanceType['requirements'] ?? $requirements;
        }

        if ($this->isSeniorCitizenIdApplication) {
            $requirements = [
                [
                    'name' => 'Birth Certificate, LCR/PSA Copy (1 Photocopy)',
                    'where_to_secure' => 'Local Civil Registrar/Philippine Statistics Authority'
                ],
                [
                    'name' => 'Membership Application form',
                    'where_to_secure' => 'Office of the Senior Citizen’s Affairs (OSCA)'
                ],
                [
                    'name' => '(2) copies of recent 1x1 picture',
                    'where_to_secure' => 'Senior Citizen Applicant'
                ]
            ];
        }

        if ($this->getIsDeathAssistance) {
        }

        return App::controller()->renderPartial('/transaction/_requirements', [
            'requirements' => $requirements
        ]);
    }

    protected function insertLog()
    {
        $log = new TransactionLog([
            'transaction_id' => $this->id,
            'status' => $this->status,
            'remarks' => $this->remarks
        ]);

        if ($log->save()) {
            // code...
        }
        else {
            $this->owner->addError('transaction_log', $log->errors);
        }
    }

    public function process()
    {
        $user = App::identity();

        switch ($user->role_id) {
            case Role::MSWDO_CLERK:
                if ($this->status == self::NEW_TRANSACTION
                    || $this->status == self::MSWDO_HEAD_PROCESSING) {
                    
                    $this->status = self::MSWDO_CLERK_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;

            case Role::MSWDO_HEAD:
                if ($this->status == self::NEW_TRANSACTION
                    || $this->status == self::MSWDO_CLERK_PROCESSING) {
                    
                    $this->status = self::MSWDO_HEAD_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;

            case Role::TREASURER:
                if ($this->status == self::MSWDO_CLERK_APPROVED) {
                    $this->status = self::TREASURER_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;

            case Role::MHO:
                if ($this->status == self::FOR_WHITE_CARD_CREATION) {
                    $this->status = self::MHO_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;

            case Role::MAYOR:
                if ($this->status == self::MSWDO_HEAD_APPROVED) {
                    $this->status = self::MAYOR_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";

                    if ($this->isEmergencyWelfareProgram) {
                        if ($this->isMedical) {
                            if ($this->white_card_status == self::DOCUMENT_REVIEWED) {
                                $this->white_card_status = self::DOCUMENT_FOR_APPROVAL;
                            }
                        }

                        if ($this->general_intake_sheet_status == self::DOCUMENT_REVIEWED) {
                            $this->general_intake_sheet_status = self::DOCUMENT_FOR_APPROVAL;
                        }

                        if ($this->obligation_request_status == self::DOCUMENT_REVIEWED) {
                            $this->obligation_request_status = self::DOCUMENT_FOR_APPROVAL;
                        }

                        if ($this->petty_cash_voucher_status == self::DOCUMENT_REVIEWED) {
                            $this->petty_cash_voucher_status = self::DOCUMENT_FOR_APPROVAL;
                        }
                    }

                    if ($this->isDeathAssistance) {
                        if ($this->general_intake_sheet_status == self::DOCUMENT_REVIEWED) {
                            $this->general_intake_sheet_status = self::DOCUMENT_FOR_APPROVAL;
                        }

                        if ($this->obligation_request_status == self::DOCUMENT_REVIEWED) {
                            $this->obligation_request_status = self::DOCUMENT_FOR_APPROVAL;
                        }

                        if ($this->petty_cash_voucher_status == self::DOCUMENT_REVIEWED) {
                            $this->petty_cash_voucher_status = self::DOCUMENT_FOR_APPROVAL;
                        }
                    }

                    $this->save(false);
                    $this->insertLog();
                }
                break;

            case Role::BUDGET_OFFICER:
                if ($this->status == self::MAYOR_APPROVED) {
                    $this->status = self::BUDGET_OFFICER_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;

            case Role::ACCOUNTING_OFFICER:
                if ($this->status == self::BUDGET_OFFICER_CERTIFIED) {
                    $this->status = self::ACCOUNTING_OFFICER_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }

                if ($this->status == self::DISBURSED) {
                    $this->status = self::ACCOUNTING_OFFICER_PROOFING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;
                
            case Role::DISBURSING_OFFICER:
                if ($this->status == self::ACCOUNTING_COMPLETED) {
                    $this->status = self::DISBURSING_OFFICER_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                }
                break;
            
            default:
                
                 if (($this->status == self::NEW_TRANSACTION || $this->status == self::MSWDO_HEAD_PROCESSING)
                 && $user->can('can-completed', 'transaction') 
                 && $this->created_by==$user->id
                 ) {
                    
                    $this->status = self::MSWDO_CLERK_PROCESSING;
                    $this->remarks = "Transaction was set to {$this->statusLabel}";
                    $this->save(false);
                    $this->insertLog();
                 }
                
                
                break;
        }
    }

    public function getCanMhoApproved() 
    {
        return App::identity()->can('can-mho-approved', 'transaction')
            && $this->isMedical && $this->white_card;
    }

    public function getCanMhoDeclined() 
    {
        return App::identity()->can('can-mho-declined', 'transaction')
            && $this->isMedical;
    }

    public function getCanMswdoHeadApproved() 
    {
        if ($this->isEmergencyWelfareProgram) {
            if ($this->isMedical) {
                return App::identity()->can('can-mswdo-head-approved', 'transaction')
                    && $this->white_card_status == self::DOCUMENT_REVIEWED
                    && $this->general_intake_sheet_status == self::DOCUMENT_REVIEWED
                    && $this->obligation_request_status == self::DOCUMENT_REVIEWED
                    && $this->petty_cash_voucher_status == self::DOCUMENT_REVIEWED;
            }

            return App::identity()->can('can-mswdo-head-approved', 'transaction')
                && $this->general_intake_sheet_status == self::DOCUMENT_REVIEWED
                && $this->obligation_request_status == self::DOCUMENT_REVIEWED
                && $this->petty_cash_voucher_status == self::DOCUMENT_REVIEWED;
        }

        if ($this->isDeathAssistance) {
            return App::identity()->can('can-mswdo-head-approved', 'transaction')
                && $this->general_intake_sheet_status == self::DOCUMENT_REVIEWED
                && $this->obligation_request_status == self::DOCUMENT_REVIEWED
                && $this->petty_cash_voucher_status == self::DOCUMENT_REVIEWED;
        }
    }

    public function getCanMswdoHeadDeclined() 
    {
        return App::identity()->can('can-mswdo-head-declined', 'transaction');
    }

    public function getCanIdReleased() 
    {
        return App::identity()->can('can-id-released', 'transaction');
    }

    public function getCanMayorApproved() 
    {
        if ($this->isEmergencyWelfareProgram) {
            if ($this->isMedical) {
                return App::identity()->can('can-mayor-approved', 'transaction')
                    && $this->white_card_status == self::DOCUMENT_APPROVED
                    && $this->general_intake_sheet_status == self::DOCUMENT_APPROVED
                    && $this->obligation_request_status == self::DOCUMENT_APPROVED
                    && $this->petty_cash_voucher_status == self::DOCUMENT_APPROVED;
            }
            return App::identity()->can('can-mayor-approved', 'transaction')
                && $this->general_intake_sheet_status == self::DOCUMENT_APPROVED
                && $this->obligation_request_status == self::DOCUMENT_APPROVED
                && $this->petty_cash_voucher_status == self::DOCUMENT_APPROVED;
        }

        if ($this->isDeathAssistance) {
            return App::identity()->can('can-mayor-approved', 'transaction')
                && $this->general_intake_sheet_status == self::DOCUMENT_APPROVED
                && $this->obligation_request_status == self::DOCUMENT_APPROVED
                && $this->petty_cash_voucher_status == self::DOCUMENT_APPROVED;
        }
    }

    public function getCanMayorDeclined() 
    {
        return App::identity()->can('can-mayor-declined', 'transaction');
    }

    public function getCanBudgetOfficerCertified() 
    {
        return App::identity()->can('can-budget-officer-certified', 'transaction');
    }

    public function getCanDisbursed() 
    {
        return App::identity()->can('can-disbursed', 'transaction');
    }

    public function getCanCompleted() 
    {
        if ($this->isEmergencyWelfareProgram) {
            if ($this->isMedical) {
                return App::identity()->can('can-completed', 'transaction')
                    && $this->white_card_status == self::DOCUMENT_CLERK_CREATED
                    && $this->general_intake_sheet_status == self::DOCUMENT_CLERK_CREATED
                    && $this->obligation_request_status == self::DOCUMENT_CLERK_CREATED
                    && $this->petty_cash_voucher_status == self::DOCUMENT_CLERK_CREATED;
            }

            return App::identity()->can('can-completed', 'transaction')
                && $this->general_intake_sheet_status == self::DOCUMENT_CLERK_CREATED
                && $this->obligation_request_status == self::DOCUMENT_CLERK_CREATED
                && $this->petty_cash_voucher_status == self::DOCUMENT_CLERK_CREATED;
        }

        if ($this->isDeathAssistance) {
            return App::identity()->can('can-completed', 'transaction')
                //&& $this->general_intake_sheet_status == self::DOCUMENT_CLERK_CREATED
                && $this->obligation_request_status == self::DOCUMENT_CLERK_CREATED
               // && $this->petty_cash_voucher_status == self::DOCUMENT_CLERK_CREATED
                ;
        }

        if ($this->isSeniorCitizenIdApplication) {
            return App::identity()->can('can-completed', 'transaction')
                && $this->senior_citizen_intake_sheet_status == self::DOCUMENT_CLERK_CREATED;
        }

        if ($this->isSocialPension) {
            return App::identity()->can('can-completed', 'transaction')
                && $this->social_pension_status == self::DOCUMENT_CLERK_CREATED;
        }

        return App::identity()->can('can-completed', 'transaction');
    }

    public function getCanClerkDeclined() 
    {
        return App::identity()->can('can-clerk-declined', 'transaction');
    }

    public function getCanMswdoClerkApproved() 
    {
        if ($this->isEmergencyWelfareProgram) {
            if ($this->isMedical) {
                return App::identity()->can('can-mswdo-clerk-approved', 'transaction')
                    && $this->white_card_status == self::DOCUMENT_FOR_REVIEW
                    && $this->general_intake_sheet_status == self::DOCUMENT_FOR_REVIEW
                    && $this->obligation_request_status == self::DOCUMENT_FOR_REVIEW
                    && $this->petty_cash_voucher_status == self::DOCUMENT_FOR_REVIEW;
            }

            return App::identity()->can('can-mswdo-clerk-approved', 'transaction')
                && $this->general_intake_sheet_status == self::DOCUMENT_FOR_REVIEW
                && $this->obligation_request_status == self::DOCUMENT_FOR_REVIEW
                && $this->petty_cash_voucher_status == self::DOCUMENT_FOR_REVIEW;
        }

        if ($this->isDeathAssistance) {
            return App::identity()->can('can-mswdo-clerk-approved', 'transaction')
               // && $this->general_intake_sheet_status == self::DOCUMENT_FOR_REVIEW
                && $this->obligation_request_status == self::DOCUMENT_FOR_REVIEW
               // && $this->petty_cash_voucher_status == self::DOCUMENT_FOR_REVIEW
                ;
        }

        if ($this->isSeniorCitizenIdApplication) {
            return App::identity()->can('can-mswdo-clerk-approved', 'transaction')
                && $this->senior_citizen_intake_sheet_status == self::DOCUMENT_CLERK_CREATED;
        }
    }

    public function getCanAccountingCompleted() 
    {
        return App::identity()->can('can-accounting-completed', 'transaction');
    }

    public function getCanPaymentCompleted() 
    {
        return App::identity()->can('can-payment-completed', 'transaction')
            && $this->senior_citizen_intake_sheet_status == self::DOCUMENT_CLERK_CREATED;
    }

    public function getIsDeathAssistance()
    {
        return $this->transaction_type == self::DEATH_ASSISTANCE;
    }

    public function getIsSeniorCitizenIdApplication()
    {
        return $this->transaction_type == self::SENIOR_CITIZEN_ID_APPLICATION;
    }

    public function getIsSocialPension()
    {
        return $this->transaction_type == self::SOCIAL_PENSION;
    }

    public function getIsEmergencyWelfareProgram()
    {
        return $this->transaction_type == self::EMERGENCY_WELFARE_PROGRAM;
    }

    public function getViewTabs()
    {
        $tabs = [
            'details' => [
                'label' => 'Transaction Details',
                'description' => 'Primary Information',
                'icon' => '<i class="flaticon2-open-text-book text-danger"></i>',
                'status' => '',
                'action' => '',
            ],
            'member-profile' => [
                'label' => 'Member Profile',
                'description' => 'Profile Information',
                'icon' => '<i class="flaticon2-user text-success"></i>',
                'status' => '',
                'action' => '',
            ],
            'documents' => [
                'label' => 'Documents & Requirements',
                'description' => 'Scan Documents',
                'icon' => '<i class="flaticon2-image-file text-warning"></i>',
                'status' => '',
                'action' => '',
            ],
            'white-card' => [
                'label' => 'White Card',
                'description' => 'Scan Documents',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->whiteCardBadgeStatus,
                'action' => Html::tag('span', 'Create', [
                    'class' => 'font-weight-bold label-inline label label-primary action-badge'
                ])
            ],
            'general-intake-sheet' => [
                'label' => 'General Intake Sheet',
                'description' => 'Document',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->generalIntakeSheetBadgeStatus,
                'action' => '',
            ],
            'obligation-request-form' => [
                'label' => 'Obligation Request Form',
                'description' => 'Document',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->obligationRequestBadgeStatus,
                'action' => '',
            ],
            'petty-cash-voucher' => [
                'label' => 'Petty Cash Voucher',
                'description' => 'Document',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->pettyCashVoucherBadgeStatus,
                'action' => '',
            ],
        ];

        if (! $this->isMedical) {
            unset($tabs['white-card']);
        }
        
        
        if ($this->isDeathAssistance) {
            unset($tabs['petty-cash-voucher'],$tabs['general-intake-sheet']); 
        }

        if ($this->isSeniorCitizenIdApplication) {
            unset(
                $tabs['general-intake-sheet'],
                $tabs['obligation-request-form'], 
                $tabs['petty-cash-voucher']
            );

            $tabs['senior-citizen-intake-sheet'] = [
                'label' => 'Intake Sheet',
                'description' => 'Document',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->seniorCitizenIntakeSheetBadgeStatus,
                'action' => '',
            ];

            $tabs['senior-citizen-id'] = [
                'label' => 'Senior Citizen ID',
                'description' => 'Document',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->seniorCitizenIdBadgeStatus,
                'action' => '',
            ];

            if ($this->member->currentAge < 60) {
                unset($tabs['senior-citizen-id']);
            }
        }

        if ($this->isSocialPension) {
            $tabs['general-intake-sheet'] = [
                'label' => 'Social Pension Application Form',
                'description' => 'Document',
                'icon' => '<i class="flaticon2-file text-primary"></i>',
                'status' => $this->socialPensionApplicationFormBadgeStatus,
                'action' => '',
            ];

            $tabs = $this->change_key($tabs, 'general-intake-sheet', 'social-pension-application-form');

            unset($tabs['obligation-request-form'], $tabs['petty-cash-voucher']);

        }

        if ($this->recommended_services_assistance == self::MEDICAL_ASSISTANCE_MEDICINE) {
            $tabs['medicine'] = [
                'label' => 'Medicine',
                'description' => 'Medicines listed',
                'icon' => '<i class="fas fa-briefcase-medical text-danger"></i>',
                'status' => '',
                'action' => '',
            ];
        }

        return $tabs;
    }

    public function change_key( $array, $old_key, $new_key ) 
    {

        if( ! array_key_exists( $old_key, $array ) )
            return $array;

        $keys = array_keys( $array );
        $keys[ array_search( $old_key, $keys ) ] = $new_key;

        return array_combine( $keys, $array );
    }

    public function getIsCompleted()
    {
        return $this->status == self::COMPLETED
            || $this->status == self::ID_RELEASED;
    }

    public function getCertificateStatus()
    {
        return [
            self::CERTIFICATE_OF_INDIGENCY,
            self::FINANCIAL_CERTIFICATION,
            self::SOCIAL_CASE_STUDY_REPORT,
            self::CERTIFICATE_OF_APPARENT_DISABILITY,
            self::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            self::CERTIFICATE_OF_COMPLIANCE,
        ];
    }

    public function getBeforeCanUpdate()
    {
        if ($this->isDeclineStatus) {
            return;
        }

        if (in_array($this->transaction_type, $this->certificateStatus)) {
            return true;
        }
        
        if ($this->isCompleted) {
            return false;
        }
        return true;
    }

    public function getBeforeCanDelete()
    {
        return false;
    }

    public function getSocialPensionViewUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', ['social-pension', 'view']),
                $paramName => $this->{$paramName}
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public static function filterStatus($condition=[])
    {
        $status = self::filter('status', $condition);
        $ts = App::keyMapParams('transaction_status');

        $data = [];
        foreach ($status as $key => $s) {
            $data[$key] = $ts[$key] ?? '';
        }

        return $data;
    }

    public function getClaimedPhoto()
    {
        return File::findByToken($this->social_pension_photo);
    }

    public static function getFilterData()
    {
        $model = self::find()
            ->select(['COUNT("*") AS total', 'status'])
            ->groupBy('status')
            ->asArray()
            ->all();

        $param = App::params('transaction_status');
        foreach ($model as &$m) {
            $m['statusName'] = $param[$m['status']]['label'] ?? '';
            $m['class'] = $param[$m['status']]['class'] ?? '';
            $m['sort'] = $param[$m['status']]['sort'] ?? '';
            $m['url'] = Url::to(['transaction/index', 'status' => $m['status']]);
        }
        usort(
            $model, 
            fn(array $a, array $b): int => $a['sort'] <=> $b['sort']
        );

        return $model;
    }

    public function getIsDeclineStatus()
    {
        return in_array($this->status, $this->declineStatus);
    }

    public function getDeclineStatus()
    {
        $declineStatus = [
            self::MHO_DECLINED,
            self::MSWDO_HEAD_DECLINED,
            self::MAYOR_DECLINED,
            self::MSWDO_CLERK_DECLINED,
        ];

        return $declineStatus;
    }

    public function getActionButton()
    {
        if ($this->isDeclineStatus) {
            
             $actions[] =  Html::a('Cancel', ['cancel','token'=>$this->token], [
                'data-method' => 'post',
                'data-confirm' => 'Are you sure to cancel this transaction?',
                'class' => 'btn btn-warning font-size-sm font-weight-bolder'
            ]);
            
             return implode(' ', array_filter($actions));
            //return;
        }

        $identity = App::identity();

        if ($this->isEmergencyWelfareProgram) {
            $actions = [];
            if ($this->isMedical) {
                if ($identity->can('create-white-card', 'transaction')) {
                    if ($this->white_card_status == self::DOCUMENT_PENDING) {
                        $actions[] = Html::a('Create/Upload Whitecard', "{$this->viewUrlWhiteCard}", [
                            'class' => 'btn btn-success font-size-sm font-weight-bolder'
                        ]);
                    }
                }
            }
            if ($identity->can('create-general-intake-sheet', 'transaction')) {
                $actions[] = ($this->general_intake_sheet_status == self::DOCUMENT_PENDING)? Html::a('Create Intake Sheet', "{$this->viewUrlGeneralIntakeSheet}&create_document=gis", [
                        'class' => 'btn btn-success font-size-sm font-weight-bolder'
                    ]): '';
            }
            if ($identity->can('create-obligation-request', 'transaction')) {
                $actions[] = ($this->obligation_request_status == self::DOCUMENT_PENDING)? Html::a('Create Obligation Request', "{$this->viewUrlObligationRequest}&create_document=orf", [
                        'class' => 'btn btn-success font-size-sm font-weight-bolder'
                    ]): '';
            }
            if ($identity->can('create-petty-cash-voucher', 'transaction')) {
                $actions[] = ($this->petty_cash_voucher_status == self::DOCUMENT_PENDING)? Html::a('Create Petty Cash', "{$this->viewUrlPettyCashVoucher}&create_document=pcv", [
                    'class' => 'btn btn-success font-size-sm font-weight-bolder'
                ]): '';
            }
            
               
                                      
            $actions[] =  Html::a('Cancel', ['cancel','token'=>$this->token], [
                'data-method' => 'post',
                'data-confirm' => 'Are you sure to cancel this transaction?',
                'class' => 'btn btn-warning font-size-sm font-weight-bolder'
            ]);
             

            return implode(' ', array_filter($actions));
        }

        if ($this->isDeathAssistance) {
            $actions = [];
            /*
            if ($identity->can('create-general-intake-sheet', 'transaction')) {
                $actions[] = ($this->general_intake_sheet_status == self::DOCUMENT_PENDING)? Html::a('Create Intake Sheet', "{$this->viewUrlGeneralIntakeSheet}&create_document=gis", [
                        'class' => 'btn btn-success font-size-sm font-weight-bolder'
                    ]): '';
            }
            */
            
            if ($identity->can('create-obligation-request', 'transaction')) {
                $actions[] = ($this->obligation_request_status == self::DOCUMENT_PENDING)? Html::a('Create Obligation Request', "{$this->viewUrlObligationRequest}&create_document=orf", [
                        'class' => 'btn btn-success font-size-sm font-weight-bolder'
                    ]): '';
            }
            
            /*
            if ($identity->can('create-petty-cash-voucher', 'transaction')) {
                $actions[] = ($this->petty_cash_voucher_status == self::DOCUMENT_PENDING)? Html::a('Create Petty Cash', "{$this->viewUrlPettyCashVoucher}&create_document=pcv", [
                    'class' => 'btn btn-success font-size-sm font-weight-bolder'
                ]): '';
            }
            */
            
            $actions[] =  Html::a('Cancel', ['cancel','token'=>$this->token], [
                'data-method' => 'post',
                'data-confirm' => 'Are you sure to cancel this transaction?',
                'class' => 'btn btn-warning font-size-sm font-weight-bolder'
            ]);

            return implode(' ', array_filter($actions));
        }

        if ($this->isSeniorCitizenIdApplication) {
            if ($identity->can('create-senior-citizen-intake-sheet', 'transaction')) {
                return ($this->senior_citizen_intake_sheet_status == self::DOCUMENT_PENDING)? Html::a('Create Intake Sheet', "{$this->viewUrlSeniorCitizenIntakeSheet}&create_document=intake-sheet", [
                    'class' => 'btn btn-success font-size-sm font-weight-bolder'
                ]): '';
            }
        }

        if ($this->isSocialPension) {
            if ($identity->can('create-social-pension-application-form', 'transaction')) {
                return ($this->social_pension_status == self::DOCUMENT_PENDING)? Html::a('Create Social Pension Application Form', "{$this->viewUrlSocialPensionApplicationForm}&create_document=spaf", [
                    'class' => 'btn btn-success font-size-sm font-weight-bolder'
                ]): '';
            }
        }
    }

    public function getWhiteCardActionBtn()
    {
        if ($this->white_card && $this->white_card_status == self::DOCUMENT_FOR_REVIEW) {
            return Html::a('Set as Reviewed', ['transaction/review-white-card', 'token' => $this->token], [
                'data-confirm' => 'Set as Reviewed?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }

        if ($this->white_card && $this->white_card_status == self::DOCUMENT_FOR_APPROVAL) {
            return Html::a('Approve', ['transaction/approve-white-card', 'token' => $this->token], [
                'data-confirm' => 'Set as Approved?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }
    }

    public function getGeneralIntakeSheetActionBtn()
    {
        if ($this->general_intake_sheet && $this->general_intake_sheet_status == self::DOCUMENT_FOR_REVIEW) {
            return Html::a('Set as Reviewed', ['transaction/review-general-intake-sheet', 'token' => $this->token], [
                'data-confirm' => 'Set as Reviewed?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }

        if ($this->general_intake_sheet && $this->general_intake_sheet_status == self::DOCUMENT_FOR_APPROVAL) {
            return Html::a('Approve', ['transaction/approve-general-intake-sheet', 'token' => $this->token], [
                'data-confirm' => 'Set as Approved?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }
    }

    public function getObligationRequestFormActionBtn()
    {
        if ($this->obligation_request && $this->obligation_request_status == self::DOCUMENT_FOR_REVIEW) {
            return Html::a('Set as Reviewed', ['transaction/review-obligation-request-form', 'token' => $this->token], [
                'data-confirm' => 'Set as Reviewed?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }

        if ($this->obligation_request && $this->obligation_request_status == self::DOCUMENT_FOR_APPROVAL) {
            return Html::a('Approve', ['transaction/approve-obligation-request-form', 'token' => $this->token], [
                'data-confirm' => 'Set as Approved?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }
    }

    public function getPettyCashVoucherActionBtn()
    {
        if ($this->petty_cash_voucher && $this->petty_cash_voucher_status == self::DOCUMENT_FOR_REVIEW) {
            return Html::a('Set as Reviewed', ['transaction/review-petty-cash-voucher', 'token' => $this->token], [
                'data-confirm' => 'Set as Reviewed?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }

        if ($this->petty_cash_voucher && $this->petty_cash_voucher_status == self::DOCUMENT_FOR_APPROVAL) {
            return Html::a('Approve', ['transaction/approve-petty-cash-voucher', 'token' => $this->token], [
                'data-confirm' => 'Set as Approved?',
                'data-method' => 'post',
                'class' => 'btn btn-sm btn-primary font-weight-bolder font-size-sm'
            ]);
        }
    }

    public function getIsWhitecardReviewed()
    {
        return $this->white_card_status == self::DOCUMENT_REVIEWED;
    }

    public function getIsGisReviewed()
    {
        return $this->general_intake_sheet_status == self::DOCUMENT_REVIEWED;
    }

    public function getIsOrfReviewed()
    {
        return $this->obligation_request_status == self::DOCUMENT_REVIEWED;
    }

    public function getIsPcvReviewed()
    {
        return $this->petty_cash_voucher_status == self::DOCUMENT_REVIEWED;
    }

    public function getIsWhitecardApproved()
    {
        return $this->white_card_status == self::DOCUMENT_APPROVED;
    }

    public function getIsGisApproved()
    {
        return $this->general_intake_sheet_status == self::DOCUMENT_APPROVED;
    }

    public function getIsOrfApproved()
    {
        return $this->obligation_request_status == self::DOCUMENT_APPROVED;
    }

    public function getIsPcvApproved()
    {
        return $this->petty_cash_voucher_status == self::DOCUMENT_APPROVED;
    }

    public function getTotalMedicinePrice()
    {
        $model = Medicine::find()
            ->select(['SUM(price) AS total'])
            ->where(['transaction_id' => $this->id])
            ->asArray()
            ->one();

        return $model['total'] ?? 0;
    }

    public function getWhitecardFile()
    {
        return File::findByToken($this->whitecard_file);
    }

    public function getCreateTransactionLink($type='')
    {
        if (($member = $this->member) != null) {
            return $member->getCreateTransactionLink($type);
        }
    }

    public function getPatient()
    {
        if ($this->relation_type == self::CLIENT_IS_PATIENT) {
            return $this->hasOne(Member::className(), ['id' => 'member_id']);
        }
        
        return $this->hasOne(Member::className(), ['id' => 'patient_id']);
    }
    
    
    public function getDeceased()
    {

        return $this->hasOne(Member::className(), ['id' => 'id_of_deceased']);
    }
    
    

    public function getMedicineNames()
    {
        $models = Medicine::filter('name', ['transaction_id' => $this->id]);

        if ($models) {
            return App::formatter('asImplode', array_keys($models));
        }
    }

    public function getDestination()
    {
        return implode(', ', [
            $this->destination_municipality,
            $this->destination_province,
        ]);
    }

    public function getRsa()
    {
        if ($this->recommended_services_assistance == self::OTHER_RSA) {
            return $this->other_rsa;
        }

        return App::keyMapParams('recommended_services_assistance')[$this->recommended_services_assistance] ?? '';
    }

    public function getIsAicsMedicalMedicine()
    {
        return $this->emergency_welfare_program == self::AICS_MEDICAL_MEDICINE;
    }

    public function getIsAicsMedical()
    {
        return $this->emergency_welfare_program == self::AICS_MEDICAL;
    }

    public function getIsLaboratoryRequest()
    {
        return $this->emergency_welfare_program == self::AICS_LABORATORY_REQUEST;
    }

    public function getVerificationToken()
    {
        return Url::toRoute(['site/certificate-verification', 'token' => $this->token], true);
    }

    public function getQrCode()
    {
        return QRCode::widget(['token' => $this->verificationToken]);
    }

    public function getQrCodeImage($options=['width' => 200, 'height' => 200])
    {
        return Html::img($this->qrCode, $options);
    }

    public function getContentWithQrCode()
    {
        $style = 'position: fixed;bottom: 20px; right: 10px;';
        $style_text = 'position: fixed; bottom: 10px; right: 10px; font-size: 11px;font-weight: bold;';

        switch ($this->transaction_type) {
            case  self::FINANCIAL_CERTIFICATION:
            case  self::CERTIFICATE_OF_COMPLIANCE:
                 $style = 'position: fixed;bottom: 250px; right: 10px;';
                 $style_text = 'position: fixed; bottom: 240px; right: 10px; font-size: 11px;font-weight: bold;';
                break;
            
            default:
                // code...
                break;
        }

        return implode(' ', [
            $this->content,
            $this->getQrCodeImage([
                'width' => 150, 
                'height' => 150,
                'style' => $style
            ]) .'<div style="'.$style_text.'">Scan QR code for authenticity</div>'
        ]);
    }
    
    
    public function getContentWithFooterQrCode()
    {
        $style = 'position: fixed;bottom: 15px; right: 5px; z-index:5;';
        $style_text = 'position: fixed; z-index:10; bottom: 5px; right: 0px; font-size: 10px;font-weight: bold;';
        
        $file = File::controllerFind(App::setting('image')->footer_image, 'token');
        
        $header ='<header class="header-report-cert"><div>'.ReportTemplate::widget(['template'=>'header_new']).'</div></header>';

       $footer= '<footer class="footer-report-cert" style="background-image: url('.$file->displayPath.'); background-position: center bottom;background-repeat: no-repeat; background-size: cover; height: 200px; width: 100%;"></footer>';  

      $this->content = '<div class="content-report-cert"><div class="cert-content">'.$this->content.'</div></div>';


        switch ($this->transaction_type) {
            case  self::FINANCIAL_CERTIFICATION:
            case  self::CERTIFICATE_OF_COMPLIANCE:
                 $style = 'position: fixed;bottom: 250px; right: 10px;';
                 $style_text = 'position: fixed; bottom: 240px; right: 10px; font-size: 11px;font-weight: bold;';
                break;
            
            default:
                // code...
                break;
        }
        
        
        $qr=$this->getQrCodeImage([
                'width' => 100, 
                'height' => 100,
                'style' => $style
            ]) .'<div style="'.$style_text.'">Scan QR code for authenticity</div>';
        
        $content=  '
    <table class="table-print" style="color:#000000;">
	<thead>
	 <tr><th class="header-cert">
	 <div class="header-report-cert">'.ReportTemplate::widget(['template'=>'header_new']).'</div>
	 </th></tr>
	</thead>
	
	<tbody>
	  <tr>
	  <td class="content-td">
	   <div class="content-report-cert">
	       '.$this->content.'
	    </div>

       </td>
	  </tr>
	</tbody>
	<tfoot>
    <tr>
      <td class="footer-td">
        
        <div class="footer-report-cert" style="background-image: url('.$file->displayPath.'); position: fixed;bottom: 0px; right: 0px; left:0px; background-position: center bottom;background-repeat: no-repeat; background-size: cover; height: 235px; width: 100%;"></div>
        '.$qr.'
      </td>
    </tr>
    </tfoot>
	
	
	</table>
	   ';
        
      return  $content;

        return implode(' ', [
            $header,
            $this->content,
            $footer,
            $this->getQrCodeImage([
                'width' => 100, 
                'height' => 100,
                'style' => $style
            ]) .'<div style="'.$style_text.'">Scan QR code for authenticity</div>'
        ]);
    }
    
}
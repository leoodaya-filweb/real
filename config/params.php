<?php

$params = [
    'tech_issue_status' => [
        0 => ['id' => 0, 'label' => 'Open','class' => 'success'],
        1 => ['id' => 1, 'label' => 'Ongoing', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Closed', 'class' => 'danger'],
        // 3 => ['id' => 3, 'label' => 'Unsolved', 'class' => 'danger'],
    ],
    'tech_issue_types' => [
        0 => ['id' => 0, 'label' => 'Report Bug','class' => 'danger'],
        1 => ['id' => 1, 'label' => 'Audit Logs', 'class' => 'success'],
    ],
    
    'scholarship_status' => [
        0 => ['id' => 0, 'label' => 'Pending','class' => 'warning'],
        1 => ['id' => 1, 'label' => 'For Interview', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Rejected', 'class' => 'danger'],
        3 => ['id' => 3, 'label' => 'Approved', 'class' => 'success'],
    ],
    
    'user.passwordResetTokenExpire' => 36000,
    'pagination' => [25 => 25, 50 => 50, 75 => 75, 100 => 100,],
    'is_senior' => [
        0 => ['id' => 0, 'label' => 'No', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'success'],
    ],
    'type_of_activitites' => [
        'Disaster Mitigation and Preparedness',
        'Disaster Response and Recovery',
        'Emergency Shelter Assistance',
        'Family and Community Disaster Awareness',
        'Crisis Intervention',
        'Training and Capacity Building',
    ],
    'pwd_form' => [
        'type' => [
            'New Applicant',
            'Renewal'
        ],
        'sex' => [
            'Female',
            'Male'
        ],
        'civil_status' => [
            'Single',
            'Separated',
            'Cohabitation',
            'Married',
            'Widow/er',
        ],
        'type_of_disability' => [
            [
                'Deaf or Hard of Hearing',
                'Intellectual Disability',
                'Learning Disability',
                'Mental Disability',
                'Physical Disability',
            ],
            [
                'Psychosocial Disability',
                'Speech and Language Impairment',
                'Visual Disability',
                'Cancer (RA11215)',
                'Rare Disease (RA10747)'
            ]
        ],
        'cause_of_disability' => [
            [
                'type' => 'Congenital / Inborn',
                'cause' => [
                    'Autism',
                    'ADHD',
                    'Cerebral Palsy',
                    'Down Syndrome'
                ]
            ],
            [
                'type' => 'Acquired',
                'cause' => [
                    'Chronic Illness',
                    'Cerebral Palsy',
                    'Injury'
                ]
            ]
        ],

        'educational_attainment' => [
            [
                'None',
                'Kindergarten',
                'Elementary',
                'Junior High School',
            ],
            [
                'Senior High School',
                'College',
                'Vocational',
                'Post Graduate'
            ]
        ],
        'status_of_employment' => [
            'Employed',
            'Unemployed',
            'Self-employed',
        ],
        'types_of_employment' => [
            'Permanent / Regular',
            'Seasonal',
            'Casual',
            'Emergency',
        ],
        'category_of_employment' => [
            'Government',
            'Private',
        ],
        'occupation' => [
            'Managers',
            'Professionals',
            'Technicians and Associate Professionals',
            'Clerical Support Workers',
            'Service and Sales Workers',
            'Skilled Agricultural, Forestry and Fishery Workers',
            'Craft and Related Trade Workers',
            'Plant and Machine Operators and Assemblers',
            'Elementary Occupations',
            'Armed Forces Occupations',
        ],
        'accomplished_by' => [
            'Applicant',
            'Guardian',
            'Representative'
        ]
    ],
    'masterlist_status' => [
        0 => ['id' => 0, 'label' => 'Pending', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'Added', 'class' => 'success'],
    ],
    'religions' => [
        'Roman Catholic',
        'Islam',
        'Evangelicals (PCEC)',
        'Iglesia ni Cristo',
        'Protestant (NCCP)',
        'Aglipayan',
        'Seventh-day Adventist',
        'Bible Baptist Church',
        'United Church of Christ in the Philippines',
        'Jehovah\'s Witnesses',
        'None',
    ],
    'ethnicity' => [
        'Tagalog',
        'Cebuano',
        'Badjao',
        'Ilokano',
        'Waray',
        'Yakan',
        'Kapampangan',
        'Ilonggo',
        'B\'laan',
        'Bikolano',
        'Ati',
        'Maranao',
        'Aeta',
        'Suludnon',
        'T\'boli',
        'Igorot',
        'Tausug',
        'Ivatan',
        'Bagobo',
        'Mangyan'
    ],

    'survey_color' => [
        ['id' => 1, 'color'=>'#5096f2', 'label' => 'Blue', 'class' => '', 'priority' => 3],
        ['id' => 2, 'color'=>'#E4E6EF', 'label' => 'Gray', 'class' => '', 'priority' => 4],
        ['id' => 3, 'color'=>'#000000', 'label' => 'Blackx', 'class' => '', 'priority' => 1],
        ['id' => 4, 'color'=>'#404040', 'label' => 'Blacky', 'class' => '', 'priority' => 2],
        ['id' => 5, 'color'=>'#404040', 'label' => 'Blacku', 'class' => '', 'priority' => 2],
    ],

    'voters' => [
        1 => ['id' => 1, 'label' => 'Yes'],
        2 => ['id' => 2, 'label' => 'No'],
    ],
    'social_pension_funds' => [
        0 => ['id' => 0, 'label' => 'N/A', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Local', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'National', 'class' => 'warning'],
    ],
    'event_category_types_list' => [
        0 => ['id' => 0, 'label' => 'Default', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Un Planned Attendees', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Social Pension', 'class' => 'warning'],
    ],
    'priority_sector' => [
        ['id' => 1,'code'=>'SC', 'label' => 'Senior Citizen', 'class' => 'primary' ],
        ['id' => 2, 'code'=>'SLP',  'label' => 'Sustainable Livelihood Program', 'class' => 'success'],
        ['id' => 3, 'code'=>'IP',  'label' => 'Indigenous Peoples', 'class' => 'secondary'],
        ['id' => 4, 'code'=>'SP',  'label' => 'Solo Parent', 'class' => 'danger'],
        ['id' => 5, 'code'=>'PWD',  'label' => 'Persons with Disabilities', 'class' => 'warning'],
        ['id' => 6, 'code'=>'Kalipi',  'label' => 'Kalipunan ng Liping Pilipino', 'class' => 'info'],
        ['id' => 7, 'code'=>'PYAP',  'label' => 'Pag-asa Youth Association of the Philippines', 'class' => 'dark'],
        ['id' => 8, 'code'=>'BAKTOM',  'label' => 'BAKTOM LGBTQ Organization', 'class' => 'success'],
    ],
    'units' => [
        1 => ['id' => 1, 'label' => 'Tablet'],
        2 => ['id' => 2, 'label' => 'Pieces'],
        3 => ['id' => 3, 'label' => 'Capsul'],
        4 => ['id' => 4, 'label' => 'Milliliter'],
        5 => ['id' => 5, 'label' => 'Liters'],
        6 => ['id' => 6, 'label' => 'Syrup'],
        7 => ['id' => 7, 'label' => 'Pack'],
    ],
    'patient_relation_types' => [
        1 => ['id' => 1, 'label' => 'Client is a patient.'],
        2 => ['id' => 2, 'label' => 'Patient is a member of my household'],
        3 => ['id' => 3, 'label' => 'Patient is not a member of my household but is my relative.'],
        4 => ['id' => 4, 'label' => 'Patient is not related to the client by familial ties.'],
    ],
    'attendees_types' => [
        0 => ['id' => 0, 'label' => 'Planned'],
        1 => ['id' => 1, 'label' => 'Un Planned'],
    ],

    'document_status' => [
        0 => ['id' => 0, 'label' => 'Pending'],
        1 => ['id' => 1, 'label' => 'For Review'],
        2 => ['id' => 2, 'label' => 'Reviewed'],
        3 => ['id' => 3, 'label' => 'For Approval'],
        4 => ['id' => 4, 'label' => 'Approved'],
        5 => ['id' => 5, 'label' => 'Created'],
    ],
    'fourPs' => [
        0 => ['id' => 0, 'label' => 'No'],
        1 => ['id' => 1, 'label' => 'Yes'],
    ],
    'client_categories' => [
        1 => ['id' => 1, 'label' => 'Children in need of special protection'],
        2 => ['id' => 2, 'label' => 'Youth in need of special protection'],
        3 => ['id' => 3, 'label' => 'Women in especially difficult circumstances'],
        4 => ['id' => 4, 'label' => 'Person with disability'],
        5 => ['id' => 5, 'label' => 'Senior citizen'],
        6 => ['id' => 6, 'label' => 'Family head and other needy adult'],
        7 => ['id' => 7, 'label' => 'Solo Parent'],
    ],
    
     'client_categories_sp' => [
        1 => ['id' => 1, 'code'=>'a1', 'label' => 'a1 - Birth of a Child as a consequence of rape'],
        2 => ['id' => 2, 'code'=>'a2', 'label' => 'a2 - Death of the spouse'],
        3 => ['id' => 3, 'code'=>'a3', 'label' => 'a3 - Detention of spouse at least three months'],
        4 => ['id' => 4, 'code'=>'a4', 'label' => 'a4 - Physical or mental incapacity of the spouse'],
        5 => ['id' => 5, 'code'=>'a5', 'label' => 'a5 - Legal separation or de facto separation for at least six months'],
        6 => ['id' => 6, 'code'=>'a6', 'label' => 'a6 - Declaration of nullity or annulment of marriage'],
        7 => ['id' => 7, 'code'=>'a7', 'label' => 'a7 - Abandonment by the spouse for at least six months'],
        8 => ['id' => 8, 'code'=>'b', 'label' => 'b - Spouse/Relative of the OFW'],
        9 => ['id' => 9, 'code'=>'c', 'label' => 'c- Unmarriage mother or father who keeps and rears his/her child or children'],
        10 => ['id' => 10, 'code'=>'d', 'label' => 'd - Legal guardian, adoptive or foster parent who solely provides parental care and support to a children or child'],
        11 => ['id' => 11, 'code'=>'e', 'label' => 'e - Any relative within the fourth(4th) civil degree of consanguinity or affinity'],
        12 => ['id' => 12, 'code'=>'f', 'label' => 'f - Pregnant woman who provides sole parental care and support to her unborn child or children'],
    ],
    
    
     'benefit_code' => [
        1 => ['id' => 1, 'code'=>'A', 'label' => 'A - The subsidy, Authomatic Coverage to PhilHealth, Prioritization in reentering the workforce, and allocation in housing projects **For solo parents earning equal or to below minimum wage only'],
        2 => ['id' => 2, 'code'=>'B',  'label' => 'B - 10% Discaount and VAT Exemption on selected items **For solo parent earning below P250,000/year with children six years old and below only'],
     ],
     
     'application_status' => [
        1 => ['id' => 1, 'label' => 'New', 'class' => 'warning'],
        2 => ['id' => 2, 'label' => 'Approved', 'class' => 'primary'],
        3 => ['id' => 3, 'label' => 'Renewal', 'class' => 'success'],
        4 => ['id' => 4, 'label' => 'Disapproved', 'class' => 'danger']
    ],
    
    'recommended_services_assistance' => [
        1 => ['id' => 1, 'label' => 'Counseling'],
        2 => ['id' => 2, 'label' => 'Legal assistance'],
        3 => ['id' => 3, 'label' => 'Medical assistance (Cash)'],
        4 => ['id' => 4, 'label' => 'Medical assistance (Laboratory Request)'],
        5 => ['id' => 5, 'label' => 'Medical assistance (Medicine)'],
        6 => ['id' => 6, 'label' => 'Burial assistance'],
        7 => ['id' => 7, 'label' => 'Transportation assistance'],
        9 => ['id' => 9, 'label' => 'Educational Assistance'],
        10 => ['id' => 10, 'label' => 'Food Assistance'],
        11 => ['id' => 11, 'label' => 'Financial and Other Assistance'],
        8 => ['id' => 8, 'label' => 'Others'],
    ],
    'transaction_document_status' => [
        0 => ['id' => 0, 'label' => 'Pending', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'For Review (MSWDO Head)', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Reviewed (MSWDO Head)', 'class' => 'success'],
        3 => ['id' => 3, 'label' => 'For Approval (Mayor)', 'class' => 'primary'],
        4 => ['id' => 4, 'label' => 'Approved (Mayor)', 'class' => 'success'],
        5 => ['id' => 5, 'label' => 'Completed', 'class' => 'success'],
    ],
    'event_assistance_types' => [
        0 => ['id' => 0, 'label' => 'Default', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Cash', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'In-kind', 'class' => 'primary'],
    ],
    'solo_member' => [
        0 => ['id' => 0, 'label' => 'Not Set', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'danger'],
        2 => ['id' => 2, 'label' => 'No', 'class' => 'secondary'],
    ],

    'event_category_types' => [
        0 => ['id' => 0, 'label' => 'Default', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Disaster', 'class' => 'danger'],
    ],
    'social_pension_status' => [
        0 => ['id' => 0, 'label' => 'No', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'success'],
    ],
    'pwd' => [
        0 => ['id' => 0, 'label' => 'Not Set', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'No', 'class' => 'danger'],
    ],
    'solo_parent' => [
        0 => ['id' => 0, 'label' => 'Not Set', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'No', 'class' => 'danger'],
    ],
    'living_status' => [
        0 => ['id' => 0, 'label' => 'Not Set', 'class' => 'secondary'],
        1 => ['id' => 1, 'label' => 'Alive', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'Deceased', 'class' => 'danger'],
    ],
    'budget_specific_to' => [
        0 => ['id' => 0, 'label' => 'Initial', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Transaction', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'Event', 'class' => 'success'],
    ],
    'budget_actions' => [
        0 => ['id' => 0, 'label' => 'Initial', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Additional', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Disbursed', 'class' => 'warning'],
    ],
    
    'assistance_status' => [
        0 => ['id' => 0, 'label' => 'Unclaim', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'Claimed', 'class' => 'success'],
    ],
    'enable_visitor' => [
        0 => ['id' => 0, 'label' => 'Disable', 'class' => 'danger'],
        1 => ['id' => 1, 'label' => 'Enable (require internet connection)', 'class' => 'success'],
    ],
    /*'event_categories' => [
        1 => ['id' => 1, 'label' => 'Disaster Mitigation and Preparedness', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'Disaster Response and Recovery', 'class' => 'success'],
        3 => ['id' => 3, 'label' => 'Emergency Shelter Assistance', 'class' => 'success'],
        4 => ['id' => 4, 'label' => 'Family and Community Disaster Awareness', 'class' => 'success'],
        5 => ['id' => 5, 'label' => 'Crisis Intervention', 'class' => 'success'],
        6 => ['id' => 6, 'label' => 'Training and Capacity Building', 'class' => 'success'],
        7 => ['id' => 7, 'label' => 'Others', 'class' => 'success'],
    ],*/
    'event_status' => [
        0 => ['id' => 0, 'label' => 'Pending', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'Ongoing', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Completed', 'class' => 'success'],
        3 => ['id' => 3, 'label' => 'Cancelled', 'class' => 'danger'],
    ],
    'event_member_status' => [
        0 => ['id' => 0, 'label' => 'Unclaim', 'class' => 'warning'],
        1 => ['id' => 1, 'label' => 'Claimed', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Attended', 'class' => 'success'],
        3 => ['id' => 3, 'label' => 'Unattended', 'class' => 'danger'],
    ],
    'whitelist_ip_only' => [
        0 => ['id' => 0, 'label' => 'All', 'class' => 'danger'],
        1 => ['id' => 1, 'label' => 'Whitelist Only', 'class' => 'success'],
    ],
    'record_status' => [
        0 => ['id' => 0, 'label' => 'In-active', 'class' => 'danger'],
        1 => ['id' => 1, 'label' => 'Active', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'Draft', 'class' => 'secondary'],
    ],
    'ip_types' => [
        0 => ['id' => 0, 'label' => 'Black List', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'White List', 'class' => 'danger'],
    ],
    'notification_status' => [
        0 => ['id' => 0, 'label' => 'New', 'class' => 'danger'],
        1 => ['id' => 1, 'label' => 'Read', 'class' => 'success'],
    ],
    'notification_types' => [
        0 => [
            'id' => 0, 
            'type' => 'notification_change_password', 
            'label' => 'Password Changed',
            'secondaryLabel' => 'Password Changed'
        ],
        1 => [
            'id' => 1, 
            'type' => 'new_transaction', 
            'label' => 'New Transaction',
            'secondaryLabel' => 'New Transaction'
        ],
        2 => [
            'id' => 2, 
            'type' => 'mho_transaction', 
            'label' => 'MHO Approved Transaction',
            'secondaryLabel' => 'For MSWDO Clerk\'s Approval Transaction',
        ],
        3 => [
            'id' => 3, 
            'type' => 'clerk_transaction', 
            'label' => 'MSWDO Clerk Approved Transaction',
            'secondaryLabel' => 'For MSWDO Head\'s Approval Transaction',
        ],
        4 => [
            'id' => 4, 
            'type' => 'mswdo_head_transaction', 
            'label' => 'MSWDO Head Approved Transaction',
            'secondaryLabel' => 'For Mayor\'s Approval Transaction',
        ],
        5 => [
            'id' => 5, 
            'type' => 'mayor_transaction', 
            'label' => 'Mayor Approved Transaction',
            'secondaryLabel' => 'For Budget Officer\'s Certification Transaction',
        ],
        6 => [
            'id' => 6, 
            'type' => 'budget_officer_transaction', 
            'label' => 'Budget Officer Certified Budget for the Transaction',
            'secondaryLabel' => 'For Accounting Officer\'s Approval Transaction',
        ],
        7 => [
            'id' => 7, 
            'type' => 'accounting_officer_transaction', 
            'label' => 'Accounting Officer set transaction for disbursement',
            'secondaryLabel' => 'For Disbursing Officer\'s Disbursement Transaction',
        ],
        8 => [
            'id' => 8, 
            'type' => 'disbursing_officer_transaction', 
            'label' => 'Disbursing Officer disburse the assistance',
            'secondaryLabel' => 'For Accounting Officer\'s Proofing Transaction',
        ],
        9 => [
            'id' => 9, 
            'type' => 'treasurer_transaction', 
            'label' => 'Treasurer Completed Transaction',
            'secondaryLabel' => 'Treasurer Completed Transaction'
        ],
        10 => [
            'id' => 10, 
            'type' => 'import_household', 
            'label' => 'Household Imported',
            'secondaryLabel' => 'Household Imported'
        ],
        11 => [
            'id' => 11, 
            'type' => 'import_member', 
            'label' => 'Member Imported',
            'secondaryLabel' => 'Member Imported'
        ],
        12 => [
            'id' => 12, 
            'type' => 'import_survey', 
            'label' => 'Survey Imported',
            'secondaryLabel' => 'Survey Imported'
        ],
        13 => [
            'id' => 13, 
            'type' => 'import_database', 
            'label' => 'Database Imported',
            'secondaryLabel' => 'Database Imported'
        ],
    ],
    'user_status' => [
        0 => ['id' => 0, 'label' => 'Archived', 'class' => 'danger'],
        9 => ['id' => 9, 'label' => 'Not Verified', 'class' => 'warning'],
        10 => ['id' => 10, 'label' => 'Active', 'class' => 'success'],
    ],
    'user_block_status' => [
        0 => ['id' => 0, 'label' => 'Allowed', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Blocked', 'class' => 'danger'],
    ],
    'visit_log_actions' => [
        0 => ['id' => 0, 'label' => 'Login', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Logout', 'class' => 'danger'],
    ],
    'transaction_status' => [
        1 => [
            'id' => 1, 
            'label' => 'New Transaction', 
            'class' => 'warning status-new-transaction',
            'actionLabel' => 'Set as New Transaction',
            'secondaryLabel' => 'For MSWDO Clerk Verification',
            'sort' => 1,
        ],
        2 => [
            'id' => 2, 
            'label' => 'MHO Approved', 
            'class' => 'primary',
            'actionLabel' => 'Approve',
            'secondaryLabel' => 'For MSWDO Clerk\'s Approval',
            'sort' => 5,
        ],
        3 => [
            'id' => 3, 
            'label' => 'MHO Declined', 
            'class' => 'danger',
            'actionLabel' => 'Decline',
            'sort' => 6,
        ],
        4 => [
            'id' => 4, 
            'label' => 'MSWDO Head Approved', 
            'class' => 'facebook',
            'actionLabel' => 'Approve',
            'secondaryLabel' => 'For Mayor\'s Approval',
            'sort' => 10,
        ],
        5 => [
            'id' => 5, 
            'label' => 'MSWDO Head Declined', 
            'class' => 'danger status-mswdo-head-declined',
            'actionLabel' => 'Decline',
            'sort' => 11,
        ],
        6 => [
            'id' => 6, 
            'label' => 'Mayor Approved', 
            'class' => 'twitter',
            'actionLabel' => 'Approve',
            'secondaryLabel' => 'For Budget Officer\'s Approval',
            'sort' => 13,
        ],
        7 => [
            'id' => 7, 
            'label' => 'Mayor Declined', 
            'class' => 'danger status-mayor-declined',
            'actionLabel' => 'Decline',
            'sort' => 14,
        ],
        8 => [
            'id' => 8, 
            'label' => 'Budget Officer Certified', 
            'class' => 'info status-budget-officer-certified',
            'actionLabel' => 'Certify',
            'secondaryLabel' => 'For Accounting Officer\'s Approval',
            'sort' => 16,
        ],
        9 => [
            'id' => 9, 
            'label' => 'Disbursed', 
            'class' => 'primary status-disbursed',
            'actionLabel' => 'Disburse',
            'secondaryLabel' => 'For Accounting Officer\'s Proofing',
            'sort' => 20,
        ],
        10 => [
            'id' => 10, 
            'label' => 'Completed', 
            'class' => 'success status-completed',
            'actionLabel' => 'Complete',
            'sort' => 22,
        ],
        11 => [
            'id' => 11, 
            'label' => 'White Card Created', 
            'class' => 'success',
            'actionLabel' => 'White Card Created',
            'sort' => 4,
        ],
        12 => [
            'id' => 12, 
            'label' => 'Certificate Created', 
            'class' => 'success status-certificate-created',
            'actionLabel' => 'Certificate Created',
            'secondaryLabel' => '',
            'sort' => 23,
        ],
        13 => [
            'id' => 13, 
            'label' => 'MSWDO Clerk Approved', 
            'class' => 'success status-mswdo-clerk-approved',
            'actionLabel' => 'Approve',
            'secondaryLabel' => 'For MSWDO Head\'s Approval',
            'sort' => 8,
        ],
        14 => [
            'id' => 14, 
            'label' => 'Accounted: For Disbursement', 
            'class' => 'success status-for-disbursement',
            'actionLabel' => 'Approve',
            'secondaryLabel' => 'For Disbursement Officer\'s Approval',
            'sort' => 18,
        ],
        15 => [
            'id' => 15, 
            'label' => 'MHO Processing', 
            'class' => 'warning status-mho-processing',
            'actionLabel' => 'MHO Processing',
            'sort' => 3,
        ],
        16 => [
            'id' => 16, 
            'label' => 'MSWDO Clerk Processing', 
            'class' => 'warning status-mswdo-clerk-processing',
            'actionLabel' => 'MSWDO Clerk Processing',
            'sort' => 7,
        ],
        17 => [
            'id' => 17, 
            'label' => 'MSWDO Head Processing', 
            'class' => 'warning status-mswdo-head-processing',
            'actionLabel' => 'MSWDO Head Processing',
            'sort' => 9,
        ],
        18 => [
            'id' => 18, 
            'label' => 'Mayor Processing', 
            'class' => 'warning status-mayor-processing',
            'actionLabel' => 'Mayor Processing',
            'sort' => 12,
        ],
        19 => [
            'id' => 19, 
            'label' => 'Budget Officer Processing', 
            'class' => 'warning status-budget-officer-processing',
            'actionLabel' => 'Budget Officer Processing',
            'sort' => 15,
        ],
        20 => [
            'id' => 20, 
            'label' => 'Accounting Officer Processing', 
            'class' => 'warning status-accounting-officer-processing',
            'actionLabel' => 'Accounting Officer Processing',
            'sort' => 17,
        ],
        21 => [
            'id' => 21, 
            'label' => 'Disbursing Officer Processing', 
            'class' => 'warning status-disbursing-officer-processing',
            'actionLabel' => 'Disbursing Officer Processing',
            'sort' => 19,
        ],
        22 => [
            'id' => 22, 
            'label' => 'Accounting Officer Proofing', 
            'class' => 'warning status-accounting-officer-proofing',
            'actionLabel' => 'Accounting Officer Proofing',
            'sort' => 21,
        ],
        23 => [
            'id' => 23, 
            'label' => 'Treasurer Processing', 
            'class' => 'warning status-treasurer-processing',
            'actionLabel' => 'Treasurer Processing',
            'sort' => 24,
        ],
        24 => [
            'id' => 24, 
            'label' => 'Payment Completed', 
            'class' => 'success status-payment-completed',
            'actionLabel' => 'Payment Complete',
            'sort' => 25,
        ],
        25 => [
            'id' => 25, 
            'label' => 'ID Released', 
            'class' => 'success status-id-released',
            'actionLabel' => 'ID Release',
            'sort' => 26,
        ],
        26 => [
            'id' => 26, 
            'label' => 'Social Pension Received', 
            'class' => 'success',
            'actionLabel' => 'Social Pension Receive',
            'sort' => 27,
        ],
        27 => [
            'id' => 27, 
            'label' => 'For Uploading of Whitecard', 
            'class' => 'warning status-for-white-card-creation',
            'actionLabel' => 'Upload Whitecard',
            'secondaryLabel' => '',
            'sort' => 2,
        ],
        28 => [
            'id' => 28, 
            'label' => 'MSDWO Clerk Declined', 
            'class' => 'danger status-mswdo-clerk-declined',
            'actionLabel' => 'Decline',
            'secondaryLabel' => '',
            // 'sort' => 2,
        ],
        29 => [
            'id' => 29, 
            'label' => 'Cancelled', 
            'class' => 'secondary status-cancelled',
            'actionLabel' => 'Cancelled',
            'secondaryLabel' => '',
            // 'sort' => 2,
        ],
    ],
    'school_attend' => [
        1 => ['id' => 1, 'label' => 'No', 'class' => 'warning'],
        2 => ['id' => 2, 'label' => 'Yes', 'class' => 'warning'],
    ],
    'industries' => [
        'Advertising and marketing',
        'Aerospace',
        'Agriculture',
        'Computer and technology',
        'Construction',
        'Education',
        'Energy',
        'Entertainment',
        'Fashion',
        'Finance and economic',
        'Food and beverage',
        'Health care',
        'Hospitality',
        'Manufacturing',
        'Media and news',
        'Mining',
        'Pharmaceutical',
        'Telecommunication',
        'Transportation',
    ],
    'pensioners' => [
        0 => ['id' => 0, 'label' => 'No', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'success'],
    ],
    'source_of_incomes' => [
        1 => ['id' => 1, 'label' => 'Salary', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'Business', 'class' => 'success'],
    ],
    'pensioner_from' => [
        1 => ['id' => 1, 'label' => 'PVAO', 'class' => 'success'],
        2 => ['id' => 2, 'label' => 'SSS', 'class' => 'success'],
        3 => ['id' => 3, 'label' => 'GSIS', 'class' => 'success'],
    ],
    'family_head' => [
        0 => ['id' => 0, 'label' => 'No', 'class' => 'success'],
        1 => ['id' => 1, 'label' => 'Yes', 'class' => 'success'],
    ],
    'event_types' => [
        1 => ['id' => 1, 'label' => 'Seminar', 'class' => 'primary'],
        2 => ['id' => 2, 'label' => 'Training', 'class' => 'success'],
        3 => ['id' => 3, 'label' => 'Event', 'class' => 'secondary'],
        4 => ['id' => 4, 'label' => 'Assistance', 'class' => 'danger'],
        // 5 => ['id' => 5, 'label' => 'Social Pension', 'class' => 'danger'],
    ],
    'transaction_types' => [
        1 => [
            'id' => 1,
            'label' => 'Emergency Welfare Program',
            'slug' => 'emergency-welfare-program',
            'objective' => 'Provision of timely and appropriate assistance to individuals in crisis situation to help alleviate the condition, solution of disturbed, displace individuals or families and those who are victims of disasters, who are in need of food, clothing, temporary shelter and other emergency needs.', 
            'class' => 'primary',
        ],
        2 => [
            'id' => 2,
            'label' => 'Senior Citizen ID Application',
            'slug' => 'senior-citizen-id-application',
            'objective' => 'Issuance of identification card for the elderly residents of the municipality as proof of eligibility per Article 6 of Rule IV (Privileges for the Senior Citizen) of Implementing Rules and Regulations of Republic Act No. 9994 known as the “expanded Senior Citizens Act of 2010." Issued by the Office of the Senior Citizen Affairs through MSWD personnel in‐charge of the Senior Citizens.', 
            'class' => 'success',
        ],
        3 => [
            'id' => 3,
            'label' => 'Social Pension',
            'slug' => 'social-pension',
            'objective' => 'Provision of monthly stipend to augment the daily subsistence and medical needs of select indigent citizens of Real, the most vulnerable sector, through social protection.',
            'class' => 'warning'
        ],
        4 => [
            'id' => 4,
            'label' => 'Death Assistance',
            'slug' => 'death-assistance',
            'objective' => 'Provision of death benefit assistance to every Realeño available through a beneficiary as the nearest surviving relative by consanguinity.',
            'class' => 'secondary'
        ],
        5 => [
            'id' => 5,
            'label' => 'Certificate of Indigency',
            'slug' => 'certificate-of-indigency',
            'objective' => 'Certificate of Indigency is issued to requesting clients that their household is classified as an indigent family based on verification, interview, and claim of no financial capacity to pay for services.',
            'class' => 'danger'
        ],
        6 => [
            'id' => 6,
            'label' => 'Financial Certification',
            'slug' => 'financial-certification',
            'objective' => 'Financial Certification is issued to requesting clients having been assessed in accordance with DOH Classification on Indigence using the DSWD Assessment Tool.',
            'class' => 'danger'
        ],
        7 => [
            'id' => 7,
            'label' => 'Social Case Study Report',
            'slug' => 'social-case-study-report',
            'objective' => 'This includes identifying information, family composition, problems presented, background information, assessment and recommendations.',
            'class' => 'info',
            'user_access'=>["admin.account","admin", "mswdohead", "LarrylynC", "Staff"] //"admin.account","admin",
        ],
        8 => [
            'id' => 8,
            'label' => 'Certificate of Marriage Counseling',
            'slug' => 'certificate-of-marriage-counseling',
            'objective' => 'Certificate of Marriage Counselling is required and issued to prospective couples with at least one partner being in the age range of 18‐24 years old who have completed marriage counselling activities.',
            'class' => 'info'
        ],
        9 => [
            'id' => 9,
            'label' => 'Certificate of Compliance',
            'slug' => 'certificate-of-compliance',
            'objective' => 'Certificate of Compliance is issued to prospective couples who have been provided couples with information and guidance performing their roles as husband and wife, and prepare them for the challenges of married life and their responsibilities as spouses, family members, and future parents.',
            'class' => 'info'
        ],
        10 => [
            'id' => 10,
            'label' => 'Certificate of Apparent Disability',
            'slug' => 'certificate-of-apparent-disability',
            'objective' => 'Certificate of Apparent Disability is issued to clients having had manifested the presence of physical Impairment and impaired mobility or function such as totally blind, missing limbs, limping and the likes after undergoing interview and assessment.',
            'class' => 'info'
        ],
    ],
    'emergency_welfare_programs' => [
        1 => [
            'id' => 1,
            'label' => 'AICS (Medical - Medicine)',
            'medical' => true,
            'requirements' => [
                [
                    'name' => 'Barangay where the client is presently residing',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'White Card (1 Original)',
                    'where_to_secure' => 'Municipal Health Office'
                ],
                [
                    'name' => 'Clinical Abstract/Medical Certificate signed by a Licensed Physician within the last 3 months (1 Photocopy), if necessary',
                    'where_to_secure' => 'Attending physician or from Medical Records of the Hospital/ Clinic.'
                ],
                [
                    'name' => 'Hospital Bill or Statement of Account for those who were confined, if applicable (1 Photocopy)',
                    'where_to_secure' => 'Hospital where the client/patient is confined.'
                ],
                [
                    'name' => 'Medical prescription with the name, license number Signature of the requesting physician',
                    'where_to_secure' => 'Attending physician from a hospital/ clinic.'
                ]
            ]
        ],
        2 => [
            'id' => 2,
            'label' => 'AICS (Medical - Medical Procedure)',
            'medical' => true,
            'requirements' => [
                [
                    'name' => 'Barangay where the client is presently residing',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'White Card (1 Original)',
                    'where_to_secure' => 'Municipal Health Office'
                ],
                [
                    'name' => 'Clinical Abstract/Medical Certificate signed by a Licensed Physician within the last 3 months (1 Photocopy), if necessary',
                    'where_to_secure' => 'Attending physician or from Medical Records of the Hospital/ Clinic.'
                ],
                [
                    'name' => 'Hospital Bill or Statement of Account for those who were confined, if applicable (1 Photocopy)',
                    'where_to_secure' => 'Hospital where the client/patient is confined.'
                ],
                [
                    'name' => 'Medical prescription with the name, license number Signature of the requesting physician',
                    'where_to_secure' => 'Attending physician from a hospital/ clinic.'
                ]
            ]
        ],
        3 => [
            'id' => 3,
            'label' => 'AICS (Medical - Laboratory Request)',
            'medical' => true,
            'requirements' => [
                [
                    'name' => 'Barangay Clearance (1 Original)',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'White Card (1 Original)',
                    'where_to_secure' => 'Municipal Health Office'
                ],
                [
                    'name' => 'Laboratory request with the name, license number Signature of the requesting physician',
                    'where_to_secure' => 'Attending physician from a hospital/ clinic.'
                ],
            ]
        ],
        4 => [
            'id' => 4,
            'label' => 'Balik Probinsya Program',
            'medical' => false,
            'requirements' => [
                [
                    'name' => 'Barangay Clearance (1 Original)',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'Social Case Study Report (1 Original Copy)',
                    'where_to_secure' => 'Municipal Social Welfare and Development Office (MSWDO)'
                ],
            ]
        ],
         5 => [
            'id' => 5,
            'label' => 'AICS (Educational Assistance)',
            'medical' => false,
            'requirements' => [
                [
                    'name' => 'Barangay Clearance (1 Original)',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'Certificate of Indigency (1 Original Copy)',
                    'where_to_secure' => 'Municipal Social Welfare and Development Office (MSWDO)'
                ],
                 [
                    'name' => 'Certificate of Enrollment or Registration',
                    'where_to_secure' => ''
                ],
                   [
                    'name' => '**Only parents/students 18 yrs old and above can claim/request for this assistance',
                    'where_to_secure' => ''
                ],
                
            ]
        ],
        
         6 => [
            'id' => 6,
            'label' => 'AICS (Food Assistance)',
            'medical' => false,
            'requirements' => [
                [
                    'name' => 'Barangay Clearance (1 Original)',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'Certificate of Indigency (1 Original Copy)',
                    'where_to_secure' => 'Municipal Social Welfare and Development Office (MSWDO)'
                ],
                [
                    'name' => 'Referral letter from Barangay (1 Original Copy)',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'For admitted patients, Certificate of Confinement from the Hospital',
                    'where_to_secure' => 'From the Hospital'
                ],
            ]
        ],
        
        
         7 => [
            'id' => 7,
            'label' => 'AICS (Financial and Other Assistance)',
            'medical' => false,
            'requirements' => [
                [
                    'name' => 'Barangay Clearance (1 Original)',
                    'where_to_secure' => 'Barangay where the client is presently residing'
                ],
                [
                    'name' => 'Certificate of Indigency (1 Original Copy)',
                    'where_to_secure' => 'Municipal Social Welfare and Development Office (MSWDO)'
                ],
                [
                    'name' => 'Fire incident report from BFP',
                    'where_to_secure' => 'Nearest location of Bureau of Fire Protection'
                ],
                [
                    'name' => 'Passport/Travel Documents for repatriated OFW and victims of Human Trafficking',
                    'where_to_secure' => ''
                ],
                [
                    'name' => 'Spot Report/ Endorsement from PNP',
                    'where_to_secure' => ''
                ],
                 [
                    'name' => 'Certification from MDRRMO for victims of calamities and natural disasters',
                    'where_to_secure' => ''
                ],
            ]
        ],
        
        
        
        
        
    ]
];

$params['transaction_types_menu'][1] = $params['transaction_types'][1];
$params['transaction_types_menu'][2] = $params['transaction_types'][2];
$params['transaction_types_menu'][3] = $params['transaction_types'][3];
$params['transaction_types_menu'][4] = $params['transaction_types'][4];
$params['transaction_types_menu'][5] = [
    'id' => 5,
    'label' => 'Certification',
    'slug' => 'certification',
    'objective' => 'Transactions for request of the following certifications: Certificate of Indigency, Certificate of Financial Capacity, Certificate of Apparent Disability.',
    'class' => 'danger'
];

$params['transaction_types_menu'][7] = [
    'id' => 7,
    'label' => 'Social Case Study Report',
    'slug' => 'social-case-study-report',
    'objective' => 'Generate report that describes the present situation of a needy individual and to justify the current condition of a client or patient to be eligible for assistance from sponsoring agencies in the form of financial assistance, hospitalization assistance, medical intervention.',
    'class' => 'info',
    'user_access'=>["admin.account","admin", "mswdohead", "LarrylynC", "Staff"] //"admin.account","admin",
];

$params['transaction_types_menu'][8] = [
    'id' => 7,
    'label' => 'Pre-Marriage Certification',
    'slug' => 'marriage-certification',
    'objective' => 'Transactions for request of the following: Certificate of Marriage Counseling, Certificate of Compliance.',
    'class' => 'warning'
];


return $params;
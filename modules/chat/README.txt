1. Add `modules` folder in `<project>/app/`

2. Add module in config (web.php)

    $config = [
        ...
        'modules' => [
            ...
            'chat' => [
                'class' => 'app\modules\chat\Module',
            ],
        ],
        ...
    ],

3. Add urlManager (web.php)

    'urlManager' => [
        'enablePrettyUrl' => true,
        'rules' => [
            ...
            'chat/room/<id>' => 'chat/default/index',
            'chat/<action>/<id>' => 'chat/default/<action>',
            'chat/<action>/' => 'chat/default/<action>',
            '<module/<controller>/<action>/<id>' => '<module>/<controller>/<action>',
            '<module/<controller>/<action>/' => '<module>/<controller>/<action>',
            ...
        ]
    ]

4. Add console (console.php)
    Note: (for migration) cmd command `yii chat-module-migrate`

    $config = [
        ...
        'controllerMap' => [
            'chat-module-migrate' => [
                'class' => 'yii\console\controllers\MigrateController',
                'migrationNamespaces' => ['app\modules\chat\migrations'],
                'migrationTable' => 'migration_module',
                'migrationPath' => null,
            ]
        ],
    ],

5. Mobile view auto-hide sidemenu trigger
    Note: add this html on theme main header layout.
    
    <!--begin::Mobile Toggle-->
    <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
        <span></span>
    </button>
    <!--end::Mobile Toggle-->

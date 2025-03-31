<?php

namespace app\components;

class ConnectionComponent extends \yii\db\Connection
{
    // LOCAL
    // public $dsn = 'mysql:host=localhost;dbname=db_gamis_test';
    // public $dsn = 'mysql:host=localhost;dbname=db_gamis';
    // public $username = 'root';
    // public $password = '';
    // public $charset = 'utf8';
    // public $tablePrefix = 'tbl_';


    // LIVE
    // public $dsn = 'mysql:host=localhost;dbname=db_gamis_test';
    public $dsn = 'mysql:host=localhost;dbname=db_real';
    public $username = 'root';
    public $password = '';
    public $charset = 'utf8';
    public $tablePrefix = 'tbl_';

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
}
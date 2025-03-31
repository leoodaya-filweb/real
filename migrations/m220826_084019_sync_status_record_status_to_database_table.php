<?php

use app\models\Database;
use yii\db\Migration;

/**
 * Class m220826_084019_sync_status_record_status_to_database_table
 */
class m220826_084019_sync_status_record_status_to_database_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Database::updateAllNoLogs(['record_status' => Database::RECORD_INACTIVE], [
            'status' => 'Inactive'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220826_084019_sync_status_record_status_to_database_table cannot be reverted.\n";

        return false;
    }
    */
}

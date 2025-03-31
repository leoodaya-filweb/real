<?php

use app\models\Database;

/**
 * Handles adding columns to table `{{%database}}`.
 */
class m220830_040333_add_columns_to_database_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    public function columns()
    {
        return [
            'is_senior' => $this->tinyInteger(2)->notNull()->defaultValue(0),
        ];

        // FOR SETTING utf
        // ->append('CHARACTER SET utf8 COLLATE utf8_general_ci')
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumns($this->tableName(), $this->columns());

        Database::updateAllNoLogs(
            ['is_senior' => Database::SENIOR_YES],
            ['priority_sector' => Database::SC_ID]
        );

        $senior = Database::find()
            ->select(['CONCAT_WS(" ", first_name, middle_name, last_name, date_of_birth)'])
            ->where([
                'status' => 'Active',
                'priority_sector' => Database::SC_ID
            ]);

        $database = Database::find()
            ->andWhere(['and', 
                ['<>', 'priority_sector', Database::SC_ID],
                ['>=', "age", 60],
                ['IN', "CONCAT(first_name, ' ', middle_name , ' ', last_name, ' ', date_of_birth)", $senior],
            ])->all();

        foreach ($database as $key => $model) {
            $model->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumns($this->tableName(), $this->columns());
    }
}

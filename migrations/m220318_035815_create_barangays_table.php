<?php

use app\models\Barangay;
use app\models\Municipality;
use yii\db\Expression;

/**
 * Handles the creation of table `{{%barangays}}`.
 */
class m220318_035815_create_barangays_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%barangays}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'name' => $this->string()->notNull(),
            'municipality_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'no' => $this->integer()->notNull()->defaultValue(0),
            'priority_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
        ]));

        $this->createIndexes($this->tableName(), [
            'municipality_id' => 'municipality_id',
        ]);

        $rows = [];
        foreach ($this->data() as $data) {
            list($name, $region_no, $province_no, $municipality_no, $no, $priority_score) = $data;
            $municipality = Municipality::findOne(['no' => $municipality_no]);
            $rows[] = [
                'name' => $name,
                'municipality_id' => ($municipality)? $municipality->id: 0,
                'no' => $no,
                'priority_score' => $priority_score,
                'record_status' => Barangay::RECORD_ACTIVE,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => new Expression('UTC_TIMESTAMP'),
                'updated_at' => new Expression('UTC_TIMESTAMP'),
            ];
        }
        $this->batchInsert($this->tableName(), array_keys($rows[0]), $rows);
    }

    public function data()
    {
        return [
            array('Poblacion I (Barangay 1)', 4, 56, 38, 1, 1.0470),
            array('Capalong', 4, 56, 38, 2, 1.4290),
            array('Cawayan', 4, 56, 38, 3, 1.2460),
            array('Kiloloran', 4, 56, 38, 4, 1.2727),
            array('Llavac', 4, 56, 38, 5, 1.3048),
            array('Lubayat', 4, 56, 38, 6, 2.1360),
            array('Malapad', 4, 56, 38, 7, 1.6699),
            array('Maragondon', 4, 56, 38, 8, 1.5978),
            array('Pandan', 4, 56, 38, 9, 1.9514),
            array('Tanauan', 4, 56, 38, 11, 2.1614),
            array('Tignoan', 4, 56, 38, 12, 1.4434),
            array('Ungos', 4, 56, 38, 13, 1.6550),
            array('Poblacion 61 (Barangay 2)', 4, 56, 38, 14, 1.1904),
            array('Maunlad', 4, 56, 38, 15, 1.5620),
            array('Bagong Silang', 4, 56, 38, 16, 2.1587),
            array('Masikap', 4, 56, 38, 17, 3.3167),
            array('Tagumpay', 4, 56, 38, 18, 3.0947)
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName());
    }
}
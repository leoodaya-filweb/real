<?php

namespace app\widgets;

use app\models\Database;
use app\models\search\DatabaseSearch;

class DatabasePrioritySector extends BaseWidget
{
    public $dataProvider;
    public $enableSorting = true;
    public $withCard = false;
    public $template = 'gridview';

    public function init()
    {
        parent::init();

        if ($this->dataProvider == null) {
            $searchModel = new DatabaseSearch(['withBaktom' => false]);
            $this->dataProvider = $searchModel->searchreport(['DatabaseSearch' => []]);
        }

        if ($this->withCard) {
            $this->template = 'card';
        }
    }


    public function run()
    {
        return $this->render("database-priority-sector/{$this->template}", [
            'dataProvider' => $this->dataProvider,
            'enableSorting' => $this->enableSorting,
            'priority_sector' => Database::priorityReIndex()
        ]);
    }
}

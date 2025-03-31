<?php

namespace app\models\query;

use app\models\Database;

/**
 * This is the ActiveQuery class for [[\app\models\Database]].
 *
 * @see \app\models\Database
 */
class DatabaseQuery extends ActiveQuery
{
    public function unregisteredSenior()
    {
        $fn = $this->field('first_name');
        $mn = $this->field('middle_name');
        $ln = $this->field('last_name');
        $bd = $this->field('date_of_birth');

        return $this->addSelect(['*', "CONCAT_WS(' ', {$fn}, {$mn}, {$ln}, {$bd}) as unique_name"])
            ->andWhere(['and', 
                ['<>', $this->field('priority_sector'), Database::SC_ID],
                ['>=', "age", 60],
                [$this->field('is_senior') => Database::SENIOR_NO]
            ])
            ->groupBy('unique_name');
    }
}
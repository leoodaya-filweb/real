<?php

namespace app\commands;

use app\helpers\App;
use app\models\ActiveRecord;
use app\models\Database;
use app\models\Member;
use app\models\SocialPensioner;
use yii\console\Controller;
use yii\console\ExitCode;

class AgeController extends Controller
{
    public function actionIncrement()
    {
        $monthDay = App::formatter()->asDateToTimezone('', 'm-d');
        $memberTableName = Member::tableName();
        $databaseTableName = Database::tableName();
        $socialPensionerTableName = SocialPensioner::tableName();
        $recordActive = ActiveRecord::RECORD_ACTIVE;
        $alive = Member::ALIVE;

        App::execute("UPDATE {$memberTableName} SET `age` = TIMESTAMPDIFF(YEAR, `birth_date`, CURDATE()) WHERE DATE_FORMAT(`birth_date`, '%m-%d') = '{$monthDay}' AND `record_status` = {$recordActive} AND `living_status` = {$alive}");

        App::execute("UPDATE {$databaseTableName} SET `age` = TIMESTAMPDIFF(YEAR, `date_of_birth`, CURDATE()) WHERE DATE_FORMAT(`date_of_birth`, '%m-%d') = '{$monthDay}' AND `record_status` = {$recordActive}");

        App::execute("UPDATE {$socialPensionerTableName} SET `age` = TIMESTAMPDIFF(YEAR, `birth_date`, CURDATE()) WHERE DATE_FORMAT(`birth_date`, '%m-%d') = '{$monthDay}' AND `record_status` = {$recordActive}");
    }
}

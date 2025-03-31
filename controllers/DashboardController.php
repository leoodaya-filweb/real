<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\models\Backup;
use app\models\Event;
use app\models\File;
use app\models\Household;
use app\models\Ip;
use app\models\Log;
use app\models\Member;
use app\models\Notification;
use app\models\Queue;
use app\models\Role;
use app\models\Session;
use app\models\Setting;
use app\models\Theme;
use app\models\User;
use app\models\UserMeta;
use app\models\VisitLog;
use app\models\Visitor;
use app\models\search\DashboardSearch;
use app\models\search\TransactionSearch;
use app\widgets\SearchQrCode;
/**
 * BackupController implements the CRUD actions for Backup model.
 */
class DashboardController extends Controller
{
    public function actionFindByKeywords($keywords='', $event_id='')
    {
        $data = array_merge(
            // File::findByKeywords($keywords, ['name', 'extension', 'token']),
            Household::findByKeywords($keywords, ['b.name', 'h.no', 'h.zone_no', 'h.purok_no', 'h.blk_no', 'h.lot_no', 'h.street']),
            Member::findByKeywordsEvent($event_id, $keywords, [
                'h.no', 
                'm.qr_id', 
                'm.last_name', 
                'm.middle_name', 
                'm.first_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                'b.name',
            ]),
            Event::findByKeywords($keywords, ['name', 'description']),
            // Backup::findByKeywords($keywords, ['filename', 'tables', 'description']),
            Ip::findByKeywords($keywords, ['name', 'description']),
            // Log::findByKeywords($keywords, ['method', 'action', 'controller', 'table_name', 'model_name']),
            Notification::findByKeywords($keywords, ['message']),
            // Queue::findByKeywords($keywords, ['channel', 'job', 'pushed_at']),
            // Role::findByKeywords($keywords, ['name']),
            // Session::findByKeywords($keywords, ['id', 'expire', 'ip', 'browser', 'os', 'device']),
            // Setting::findByKeywords($keywords, ['name', 'value']),
            // Theme::findByKeywords($keywords, ['name', 'description']),
            User::findByKeywords($keywords, ['username', 'email'])
            // UserMeta::findByKeywords($keywords, ['name', 'value']), 
            // VisitLog::findByKeywords($keywords, ['ip']), 
            // Visitor::findByKeywords($keywords, ['expire', 'cookie', 'ip', 'browser', 'os', 'device', 'location'])
        );

        $data = array_unique($data);
        $data = array_values($data);
        sort($data);

        return $this->asJson($data);
    }

    /**
     * Lists all Backup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DashboardSearch();

        if (($queryParams = App::queryParams()) != null) {
            $dataProviders = $searchModel->search(['DashboardSearch' => $queryParams]);

            if ($searchModel->keywords) {
                return $this->render('search_result', [
                    'dataProviders' => $dataProviders,
                    'searchModel' => $searchModel,
                ]);
            }
            else {
                return $this->redirect(['index']);
            }
        }
         
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }

    public function actionSearchQrCode()
    {
        return $this->renderAjax('search-qr-code');
    }

    public function actionEvents()
    {
        return $this->renderPartial('events'); 
    }

    public function actionTransactions()
    {
        return $this->renderPartial('transactions'); 
    }

    public function actionRecentMembers()
    {
        return $this->renderPartial('recent-members'); 
    }

    public function actionRecentHouseholds()
    {
    }

    public function actionDatabasePrioritySector()
    {
    }

    public function actionBudget()
    {

    }

    public function actionTransactionChart()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $date_range = implode(' - ', [
                $post['start'],
                $post['end'],
            ]);

            $data = TransactionSearch::chartData($date_range);

            $response['status'] = 'success';
            $response['data'] = $data;

        }
        else {
            $response['status'] = 'failed';
            $response['message'] = 'No submitted data';
        }

        return $this->asJson($response);

        // return $this->renderAjax('transaction-chart'); 
    }
}
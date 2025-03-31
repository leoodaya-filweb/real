<?php

namespace app\modules\api\v1\controllers;

use app\modules\api\v1\models\sub\AvailableUser;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'app\modules\api\v1\models\User';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'user',
    ];

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['dataFilter'] = [
            'class' => \yii\data\ActiveDataFilter::class,
            // 'attributeMap' => ['username' => 'username'],
            'searchModel' => \app\models\search\UserSearch::class
        ];

        $actions['index']['prepareSearchQuery'] = function($query, $requestParams) {
            $query->andFilterWhere(['or', 
                ['like', 'username', $requestParams['keywords']],  
                ['like', 'email', $requestParams['keywords']],  
            ]);

            return $query;
        };

        return $actions;
    }

    public function actionAvailableUsers()
    {
        // $this->serializer['collectionEnvelope'] = 'availableUsers';
        return new ActiveDataProvider([
            'query' => AvailableUser::find()
                ->alias('u')
                ->available()
                ->joinWith('role r')
                ->active('r'),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    } 

    public function actionRepathNotifications()
    {
        if (($notifications = \app\models\Notification::find()->all()) != null) {
            foreach ($notifications as $notification) {
                $notification->link = str_replace(
                    ['https://www.accessgov.ph', 'https://accessgov.ph', 'https://real.accessgov.ph'], 
                    '', 
                    $notification->link
                );



                $notification->save();
            }
        }
    }

    public function actionTagFiles()
    {
        if (($files = \app\models\File::find()->all()) != null) {
            $replaces = [
                'bulkimporthouseholdform' => 'Household',
                'bulkimportmemberform' => 'Member',
                'imagesettingform' => 'Setting',
                'database' => 'Database',
                'socialpensioner' => 'Social Pensioner',
                'socialpensionevent' => 'Social Pension Event',
                'socialpensionform' => 'Social Pension',
                'specialsurveyimportform' => 'Special Survey',
                'user' => 'User',
                'eventcategory' => 'Event Category',
                'setting' => 'Setting',
                'member' => 'Member',
                'event' => 'Event',
                'emergencywelfareprogramform' => 'Transaction',
                'transaction' => 'Transaction',
                'seniorcitizenidform' => 'Transaction',
                'deathassistanceform' => 'Transaction',
                'unplannedattendeesevent' => 'Open Event',
                'eventmember' => 'Member',
            ];


            foreach ($files as $key => $file) {
                $locations = explode('/', $file->location);

                if ($locations[0] == 'protected') {
                    
                    $file->tag = $replaces[$locations[4]] ?? $locations[4];
                }
                else {
                    $file->tag = 'Setting';
                }

                $file->save();
            }
        }
    }
}
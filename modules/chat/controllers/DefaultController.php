<?php

namespace app\modules\chat\controllers;

use Yii;

use app\modules\chat\helpers\ArrayHelper;
use app\modules\chat\helpers\Html;
use app\modules\chat\helpers\App;

use app\modules\chat\models\User;
use app\modules\chat\models\Space;
use app\modules\chat\models\SpaceGroup;
use app\modules\chat\models\SpaceMessage;
use app\modules\chat\models\SpaceNotification;

use app\modules\chat\models\search\SpaceMessageSearch;

class DefaultController extends Controller
{
    public function actionInitData($token='')
    {
        $identity = App::identity();

        if (($activeSpace = Space::findByToken($token)) != null) {
            SpaceNotification::readBySpace($activeSpace);
        }

        return $this->asJson([
            'status' => 'success',
            'activeSpace' => $activeSpace,
            'spaceMessages' => $activeSpace ? $activeSpace->loadMessages: [],
            'spaceGroups' => $activeSpace ? $activeSpace->spaceGroups: [],
            'availableUsers' => $activeSpace ? $activeSpace->availableUsers: [],
            'spaces' => Space::available(),
            'users' => User::available(),
            'currentUser' => $identity,
            'spaceMessagesTotal' => SpaceMessage::totalMessages(),
        ]);
    }

    public function actionIndex($token='')
    {
        $activeSpace = Space::activeToken($token);

        if ($token && ($activeSpace == null)) {
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'token' => $token,
            'activeSpace' => $activeSpace,
        ]);
    }
    
    
  public function actionNotif()
    {
        session_write_close();
        ignore_user_abort(false);
        //set_time_limit(0);
        
         $space = new Space();
         //$space->totalSpaceNotificationsByUser;
         
         $space_ids = SpaceGroup::find()->select(['space_id'])->andwhere(['user_id' => App::identity('id')]);
         
         $total = SpaceNotification::find()
            ->where([
                'space_id' => $space_ids, //$this->id,
                'user_id' => App::identity('id'),
                'state' => SpaceNotification::STATE_UNREAD
            ])
            ->count();
            
       return   $this->asJson([
              'total'=>$total,
              'message'=>[]
          ]);  
        
       
    }

    public function actionCreateSpace()
    {
        $space = new Space(['type' => Space::TYPE_PUBLIC]);

        if ($space->load(['Space' => App::post()])) {
            $existingSpace = Space::existingPersonal($space->user_id);
            if ($existingSpace && $space->isPersonal) {
                return $this->asJson([
                    'status' => 'success',
                    'activeSpace' => $existingSpace,
                    'existing' => true,
                    'identity' => App::identity(),
                    'spaces' => Space::available()
                ]);
            }

            if ($space->save()) {
                return $this->asJson([
                    'status' => 'success',
                    'activeSpace' => $space,
                    'spaces' => Space::available()
                ]);
            }
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => $space->errorSummary
        ]);
    }

    public function actionViewSpace($token='')
    {
        $space = Space::findByToken($token);

        if ($space) {
            SpaceNotification::readBySpace($space);
            return $this->asJson([
                'status' => 'success',
                'space' => $space,
                'spaceMessages' => $space->loadMessages,
                'spaceGroups' => $space->spaceGroups,
                'availableUsers' => $space->availableUsers,
                'spaces' => Space::available(),
            ]);
        }
        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'No space found'
        ]);
    }

    public function actionRemoveMemberSpace()
    {
        $token = App::post('token');
        $spaceToken = App::post('spaceToken');
        $identity = App::identity();

        if (($space = Space::findByToken($spaceToken)) != null) {
            if ($space->removeSpaceGroups($token)) {
                $space->refresh();
                return $this->asJson([
                    'status' => 'success',
                    'message' => 'Member(s) Removed',
                    'activeSpace' => $space,
                    'spaceGroups' => $space->spaceGroups,
                    'availableUsers' => $space->availableUsers,
                ]);
            }

            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no space group found'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no space found'
        ]);
    }

    public function actionLeaveSpace()
    {
        if (($post = App::post()) != null) {
            if (($user = User::findOne($post['user_id'])) != null) {
                if (($space = Space::findOne($post['space_id'])) != null) {
                    $spaceGroup = SpaceGroup::findOne([
                        'user_id' => $post['user_id'],
                        'space_id' => $post['space_id'],
                    ]);
                    if ($spaceGroup) {
                        $spaceGroup->delete();
                        SpaceMessage::leave($space, $user);

                        return $this->asJson([
                            'status' => 'success',
                            'spaces' => Space::available(),
                        ]);
                    }
                }

                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => 'no space found'
                ]);
            }

            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no user found'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no post data found'
        ]);
    }

    public function actionAddMemberSpace()
    {
        $userId = App::post('userId');
        $spaceToken = App::post('spaceToken');
        $identity = App::identity();

        if (($space = Space::findByToken($spaceToken)) != null) {
            if ($space->addSpaceGroups($userId)) {
                $space->refresh();
                return $this->asJson([
                    'status' => 'success',
                    'message' => 'Added to the space',
                    'activeSpace' => $space,
                    'spaceGroups' => $space->spaceGroups,
                    'availableUsers' => $space->availableUsers,
                ]);
            }

            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'user not found'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no space found'
        ]);
    }


    public function actionUpdateSpace()
    {
        $spaceToken = App::post('spaceToken');

        if (($space = Space::findByToken($spaceToken)) != null) {
            
            if ($space->load(['Space' => App::post()]) && $space->save()) {
                return $this->asJson([
                    'status' => 'success',
                    'message' => 'Space Updated',
                    'spaceGroups' => $space->spaceGroups,
                    'availableUsers' => $space->availableUsers,
                    'activeSpace' => $space,
                    'spaces' => Space::available(),
                ]);
            }
               
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => $space->errorSummary,
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no space found'
        ]);
    }


    public function actionPoll()
    {
        session_write_close();
        ignore_user_abort(false);
        set_time_limit(0);

        try {

            if(($chatState = App::post('chatState')) != null) {

                $noChanges = true;
                $trial = rand(5, 10);

                while($noChanges) {
                    if ($trial == 0) {
                        $response['status'] = 'failed';
                        $response['errorSummary'] = 'no changes';
                        return $this->asJson($response);
                        break;
                    }
                    $response = [];

                    if (($spaces = Space::available()) != null) {
                        $arrayMap = ArrayHelper::map($spaces, 'id', 'timestamp');

                        if ((int) max(array_values($arrayMap)) != (int)$chatState['latestSpaceTimestamp']) {
                            $response['spaces'] = $spaces;
                            $noChanges = false;
                        }
                    }

                    if(count($spaces) != (int)$chatState['spacesCount']) {
                        $response['spaces'] = $spaces;
                        $noChanges = false;
                    }

                    if(isset($chatState['activeSpaceToken'])) {
                        if (($activeSpace = Space::findByToken($chatState['activeSpaceToken'])) != null) {


                            if ($activeSpace->timestamp != (int)$chatState['activeSpaceTimestamp']) {
                                $response['activeSpace'] = $activeSpace;

                                $noChanges = false;
                            }
                            if ($spaces) {
                                $arrayMap = ArrayHelper::map($spaces, 'id', 'timestamp');
                                if (! in_array($activeSpace->id, array_keys($arrayMap))) {
                                    $response['activeSpace'] = null;
                                    $noChanges = false;
                                }
                                
                            }

                            if (($availableUsers = $activeSpace->availableUsers) != null) {
                                if (count($availableUsers) != (int)$chatState['availableUsersCount']) {
                                    $response['availableUsers'] = $availableUsers;
                                    $noChanges = false;
                                }
                            }

                            if (($spaceGroups = $activeSpace->spaceGroups) != null) {
                                if (count($spaceGroups) != (int)$chatState['spaceGroupsCount']) {
                                    $response['spaceGroups'] = $spaceGroups;
                                    $noChanges = false;
                                }
                            }

                            if (($spaceMessages = $activeSpace->getLoadMessages((int)$chatState['maxMessageId'])) != null) {
                                $arrayMap = ArrayHelper::map($spaceMessages, 'id', 'timestamp');

                                if ((int) max(array_keys($arrayMap)) != (int)$chatState['maxMessageId']) {
                                    $response['spaceMessages'] = $spaceMessages;
                                    $noChanges = false;
                                }
                            }
                        }
                        else {
                            $response['activeSpace'] = null;
                            $response['availableUsers'] = [];
                            $response['spaceGroups'] = [];
                            $response['spaceMessages'] = [];
                            $response['spaces'] = $spaces;
                            $noChanges = false;
                        }
                    }

                    if (($spaceMessagesTotal = SpaceMessage::totalMessages()) > 0) {
                        if ($spaceMessagesTotal != (int)$chatState['spaceMessagesTotal']) {
                            $response['spaces'] = $spaces;
                            $response['spaceMessagesTotal'] = $spaceMessagesTotal;
                            if (isset($activeSpace) && $activeSpace) {
                                $response['activeSpace'] = $activeSpace;
                            }
                            $noChanges = false;
                        }
                    }

                    if (isset($response['spaceMessages'])) {
                        $response['spaces'] = $spaces;
                        $noChanges = false;
                    }

                    if (isset($activeSpace) && $activeSpace) {
                        SpaceNotification::readBySpace($activeSpace);
                    }

                    // if (($users = User::available()) != null) {
                    //     if (count($users) != (int)$chatState['usersCount']) {
                    //         $response['users'] = $users;
                    //         $noChanges = false;
                    //     }
                    // }

                    if ((int)App::identity('timestamp') != (int)$chatState['currentUserTimestamp']) {
                        $response['currentUser'] = App::identity();
                        $noChanges = false;
                    }

                    if ($noChanges == false) {
                        $response['status'] = 'success';

                        return $this->asJson($response);
                        break;
                    }

                    if (! $response) {
                        $trial--;
                    }

                    sleep(2);
                }
            }

            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'No Chat State sent'
            ]);

        } 
        catch (\yii\base\ErrorException $e) {
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => $e->message
            ]);
        }
    }


    public function actionSendNewMessage()
    {
        if (($post = App::post()) != null) {
            if (($activeSpace = Space::findByToken($post['token'] ?? '')) != null) {
                $message = new SpaceMessage([
                    'space_id' => $activeSpace->id,
                    'content' => $post['content'],
                    'attachments' => $post['attachments'] ?? []
                ]);

                if ($message->save()) {
                    return $this->asJson([
                        'status' => 'success',
                    ]);
                }

                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => $message->errorSummary
                ]);
            }
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no active space'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'No post data'
        ]);
        
    }

 

    public function actionLoadPreviousSpaceMessages()
    {
        if (($post = App::post()) != null) {
            if (($activeSpace = Space::findByToken($post['token'] ?? '')) != null) {
                if (($spaceMessages = $activeSpace->getLoadMessages((int)$post['minMessageId'], '<')) != null) {
                    return $this->asJson([
                        'status' => 'success',
                        'spaceMessages' => $spaceMessages
                    ]);
                }
                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => 'no messages'
                ]);
            }
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no active space'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'No post data'
        ]);
    }

    public function actionBlockSpace()
    {
        if (($post = App::post()) != null) {
            if (($user = User::findOne($post['user_id'])) != null) {
                if (($space = Space::findOne($post['space_id'])) != null) {
                    $space->is_block = Space::IS_BLOCK_YES;
                    $space->is_block_by = $user->id;

                    if ($space->save()) {
                        SpaceMessage::block($space, $user);

                        return $this->asJson([
                            'status' => 'success',
                            // 'spaces' => Space::available(),
                        ]);
                    }
                }

                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => 'no space found'
                ]);
            }

            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no user found'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no post data found'
        ]);
    }


    public function actionUnblockSpace()
    {
        if (($post = App::post()) != null) {
            if (($user = User::findOne($post['user_id'])) != null) {
                if (($space = Space::findOne($post['space_id'])) != null) {
                    $space->is_block = Space::IS_BLOCK_NO;
                    $space->is_block_by = 0;

                    if ($space->save()) {
                        SpaceMessage::unblock($space, $user);

                        return $this->asJson([
                            'status' => 'success',
                            // 'spaces' => Space::available(),
                        ]);
                    }
                }

                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => 'no space found'
                ]);
            }

            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no user found'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no post data found'
        ]);
    }

    public function actionReplaceSpacePhoto()
    {
        if (($post = App::post()) != null) {
            if (($space = Space::findByToken($post['spaceToken'])) != null) {
                $space->photo = $post['fileToken'];

                if ($space->save()) {
                    SpaceMessage::replacePhoto($space);

                    return $this->asJson([
                        'status' => 'success',
                        // 'spaces' => Space::available(),
                    ]);
                }
            }

        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no post data found'
        ]);
    }










    // public function actionSeed()
    // {
    //     $space = new Space([
    //         'name' => 'test space',
    //         'type' => Space::TYPE_PUBLIC
    //     ]);

    //     $space->save();


    //     $data = [];
    //     $faker = \Faker\Factory::create();
    //     for($i = 0; $i < 500; $i++) {
    //         $data[] = [
    //             'reply_id' => 0,
    //             'space_id' => $space->id,
    //             'content' => "{$i} = {$faker->text}",
    //             'record_status' => SpaceMessage::RECORD_ACTIVE,
    //             'token' => implode('-', [$i, time()]),
    //             'created_by' => App::identity('id'),
    //             'updated_by' => App::identity('id'),
    //             'created_at' => new \yii\db\Expression('UTC_TIMESTAMP'),
    //             'updated_at' => new \yii\db\Expression('UTC_TIMESTAMP'),
    //         ];
    //     }

    //     SpaceMessage::batchInsert($data);
    // }
    

    // public function actionTruncate()
    // {
    //     App::truncateTable(App::tablePrefix() . 'spaces');
    //     App::truncateTable(App::tablePrefix() . 'space_groups');
    //     App::truncateTable(App::tablePrefix() . 'space_messages');
    //     App::truncateTable(App::tablePrefix() . 'space_notifications');
    // }
}

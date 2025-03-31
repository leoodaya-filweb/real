<?php

namespace app\commands;

class AppController extends Controller
{
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
            ];


            foreach ($files as $key => $file) {
                $locations = explode('/', $file->location);

                if ($locations[0] == 'protected') {
                    
                    $file->tag = $replaces[$locations[4]] ?? $locations[4];
                }
                else {
                    $file->tag = 'setting';
                }

                $file->save();
            }
        }
    }
}

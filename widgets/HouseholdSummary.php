<?php

namespace app\widgets;

use Yii;
 
class HouseholdSummary extends BaseWidget
{
    public $action = 'create';

    public $householdContent;
    public $mapContent;
    public $familyHeadContent;
    public $familyCompositionsContent;

    public $household;
    public $familyHead;
    public $familyCompositions;

    public function init() 
    {
        // your logic here
        parent::init();

        $this->householdContent = $this->render('household-summary/household', [
            'household' => $this->household,
        ]);

        $this->mapContent = $this->render('household-summary/map', [
            'household' => $this->household
        ]);

        if (($this->familyHead = $this->household->familyHead) != null) {
            $this->familyHeadContent = $this->render('household-summary/family-head', [
                'action' => $this->action,
                'member' => $this->familyHead,
                'household' => $this->household,
                'key' => false
            ]);
        }

        if (($this->familyCompositions = $this->household->familyCompositions) != null) {
            $this->familyCompositionsContent = $this->render('household-summary/family-composition', [
                'household' => $this->household,
                'action' => $this->action,
                'members' => $this->familyCompositions
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('household-summary/index', [
            'action' => $this->action,
            'householdContent' => $this->householdContent,
            'mapContent' => $this->mapContent,
            'familyHeadContent' => $this->familyHeadContent,
            'familyCompositionsContent' => $this->familyCompositionsContent,
            'household' => $this->household,
            'familyHead' => $this->familyHead,
            'familyCompositions' => $this->familyCompositions,
        ]);
    }
}

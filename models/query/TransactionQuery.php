<?php

namespace app\models\query;

use app\helpers\App;
use app\models\Role;
use app\models\Transaction;

/**
 * This is the ActiveQuery class for [[\app\models\Transaction]].
 *
 * @see \app\models\Transaction
 */
class TransactionQuery extends ActiveQuery
{
    public function medical()
    {
        return $this->andWhere([
            $this->field('emergency_welfare_program') => Transaction::AICS_MEDICAL
        ]);
    }

    public function financial()
    {
        return $this->andWhere([
            $this->field('emergency_welfare_program') => Transaction::AICS_FINANCIAL
        ]);
    }


    public function laboratory_request()
    {
        return $this->andWhere([
            $this->field('emergency_welfare_program') => Transaction::AICS_LABORATORY_REQUEST
        ]);
    }

    public function balik_probinsya()
    {
        return $this->andWhere([
            $this->field('emergency_welfare_program') => Transaction::BALIK_PROBINSYA_PROGRAM
        ]);
    }

    public function emergency_welfare_program()
    {
        return $this->andWhere([
            $this->field('transaction_type') => Transaction::EMERGENCY_WELFARE_PROGRAM
        ]);
    }

    public function assistance()
    {
        return $this->andWhere([
            $this->field('transaction_type') => [
                Transaction::EMERGENCY_WELFARE_PROGRAM,
                // Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                // Transaction::SOCIAL_PENSION,
                // Transaction::DEATH_ASSISTANCE,
            ]
        ]);
    }

    public function certificate()
    {
        return $this->andWhere([
            $this->field('transaction_type') => [
                Transaction::CERTIFICATE_OF_INDIGENCY,
                Transaction::FINANCIAL_CERTIFICATION,
            ]
        ]);
    }

    public function social_pension()
    {
        return $this->andWhere([
            $this->field('transaction_type') => Transaction::SOCIAL_PENSION
        ]);
    }

    public function ifMHO()
    {
        if (App::isLogin()) {
            if (App::identity('role_id') == Role::MHO) {
                $this->andWhere([
                    $this->field('transaction_type') => Transaction::EMERGENCY_WELFARE_PROGRAM,
                    $this->field('emergency_welfare_program') => [
                        Transaction::AICS_MEDICAL_MEDICINE,
                        Transaction::AICS_MEDICAL,
                        Transaction::AICS_LABORATORY_REQUEST,
                    ]
                ]);
            }
        }
    }

    public function count($q = '*', $db = null)
    {
        $this->ifMHO();
        return parent::count($q, $db);
    }

    public function all($db = null)
    {
        $this->ifMHO();
        return parent::all($db);
    }

    public function one($db = null)
    {
        return parent::one($db);
    }

    public function sum($q, $db = null) 
    {
        $this->ifMHO();
        return parent::sum($q, $db);
    }

    public function average($q, $db = null) 
    {
        $this->ifMHO();
        return parent::average($q, $db);
    }

    public function min($q, $db = null) 
    {
        $this->ifMHO();
        return parent::min($q, $db);
    }

    public function max($q, $db = null) 
    {
        $this->ifMHO();
        return parent::max($q, $db);
    }

    public function scalar($db = null) 
    {
        $this->ifMHO();
        return parent::scalar($db);
    }

    public function column($db = null) 
    {
        $this->ifMHO();
        return parent::column($db);
    }

    public function exists($db = null) 
    {
        $this->ifMHO();
        return parent::exists($db);
    }
}
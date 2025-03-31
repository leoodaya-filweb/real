<?php

namespace app\models\query;

use app\models\ActiveRecord;
use app\models\Role;
use app\models\User;

/**
 * This is the ActiveQuery class for [[\app\models\User]].
 *
 * @see \app\models\User
 */
class UserQuery extends ActiveQuery
{
    public function available()
    {
        return $this->andWhere([
            $this->field('record_status') => ActiveRecord::RECORD_ACTIVE,
            $this->field('status') => User::STATUS_ACTIVE,
            $this->field('is_blocked') => User::UNBLOCKED,
        ]);
    }

    public function developer()
    {
        return $this->andWhere([$this->field('role_id') => Role::DEVELOPER]);
    }

    public function superadmin()
    {
        return $this->andWhere([$this->field('role_id') => Role::SUPERADMIN]);
    }

    public function admin()
    {
        return $this->andWhere([$this->field('role_id') => Role::ADMIN]);
    }

    public function mswdo_clerk()
    {
        return $this->andWhere([$this->field('role_id') => Role::MSWDO_CLERK]);
    }
    
    public function mswdo_head()
    {
        return $this->andWhere([$this->field('role_id') => Role::MSWDO_HEAD]);
    }

    public function mho()
    {
        return $this->andWhere([$this->field('role_id') => Role::MHO]);
    }

    public function mayor()
    {
        return $this->andWhere([$this->field('role_id') => Role::MAYOR]);
    }

    public function budget_officer()
    {
        return $this->andWhere([$this->field('role_id') => Role::BUDGET_OFFICER]);
    }

    public function accounting_officer()
    {
        return $this->andWhere([$this->field('role_id') => Role::ACCOUNTING_OFFICER]);
    }

    public function disbursing_officer()
    {
        return $this->andWhere([$this->field('role_id') => Role::DISBURSING_OFFICER]);
    }

    public function treasurer()
    {
        return $this->andWhere([$this->field('role_id') => Role::TREASURER]);
    }
}
<?php
namespace common\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class UserRoleRule extends Rule
{
    public $name = 'userRole';

    /*
     * Verifica o Role do utilizador na base de dados
     * por defeito é user
     */
    public function execute($user, $item, $params)
    {
        if(isset(\Yii::$app->user->identity->role))
            $role = \Yii::$app->user->identity->role;
        else
            return false;

        if ($item->name === 'admin') {
            return $role == 'admin';
        } elseif ($item->name === 'manager') {
            return $role == 'admin' || $role == 'manager';
        } elseif ($item->name === 'user') {
            return $role == 'admin' || $role == 'manager' || $role == 'user' || $role == NULL;
        } else {
            return false;
        }
    }
}

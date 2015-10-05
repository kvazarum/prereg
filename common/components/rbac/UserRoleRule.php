<?php
namespace common\components\rbac;
use Yii;
use yii\rbac\Rule;
use yii\helpers\ArrayHelper;
use common\models\User;
class UserRoleRule extends Rule
{
    public $name = 'userRole';
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $roles = Yii::$app->authManager->getRolesByUser($user);
//            $role = $user-> role; //Значение из поля role базы данных
            if ($item->name === 'admin') {
                return isset($roles[$item->name]);
            } elseif ($item->name === 'moder') {
                //moder является потомком admin, который получает его права
                return isset($roles['admin']) || isset($roles[$item->name]);
            }
            elseif ($item->name === 'user') {
                return isset($roles['admin']) || isset($roles['moder']) || isset($roles[$item->name]);
            }
        }
        return false;
    }
}
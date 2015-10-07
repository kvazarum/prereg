<?php

namespace backend\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use common\models\User;

/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    /**
     * @var User
     */
    public $_user;

    /**
     * @param User $user
     * @param array $config
     * @throws \yii\base\InvalidParamException
     */
//    public function __construct(User $user, $config = [])
    public function __construct($id, $config = [])
    {
//        if (empty($user)) {
//            throw new InvalidParamException('User is empty.');
//        }
        $user = User::findOne($id);
        $this->_user = $user;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['currentPassword', 'validatePassword'],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => 'Новый пароль',
            'newPasswordRepeat' => 'Новый пароль ещё раз',
            'currentPassword' => 'Ваш пароль',
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
            }
        }
    }

    /**
     * @return boolean
     */
    public function changePassword()
    {
        $user = $this->_user;
        $user->setPassword($this->newPassword);
        return $user->save();
    }
}

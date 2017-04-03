<?php
namespace common\models;

use Yii;
use yii\base\Model;

use dektrium\user\Finder;
use dektrium\user\models\User as BaseUser;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use dektrium\user\helpers\Password;

/**
 * User ActiveRecord model.
 *
 * Database fields:
 * @property integer $id
 * @property string  $username
 * @property string  $email
 * @property string  $unconfirmed_email
 * @property string  $password_hash
 * @property string  $auth_key
 * @property integer $registration_ip
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 * @property string  $role
 * @property integer $views
 *
 * Defined relations:
 * @property Account[] $accounts
 * @property Profile   $profile
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class User extends BaseUser implements IdentityInterface
{
    /** @var Profile|null */
    private $_profile;
    
    const ROLE_USER = 'user';
    const ROLE_SELLER = 'seller';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';

    /** @inheritdoc */
    public function attributeLabels()
    {
        $attributes = parent::attributeLabels();
        $attributes['role'] = \Yii::t('user', 'Role');
        return $attributes;
    }
    
     /** @inheritdoc */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['role', 'string', 'max' => 32];
        return $rules;
    }
    
    /** @inheritdoc */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['register'][] ='role';
        $scenarios['create'][] = 'role';
        $scenarios['update'][] = 'role';
        return $scenarios;
    }
    
    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            
            $this->profile->gravatar_email = $this->email;
            $this->profile->save(false);
            
        }
    }
    
    /**
     * Set the user_id if not selected
     * @param type $insert
     * @return type
     */
    public function beforeSave($insert) 
    {
        if(empty($this->role))
            $this->role = 'user';
        return parent::beforeSave($insert);
    }
    
    /**
     * Devolve um array com os valores que o campo role pode ter
     * @return array
     */
    public function getRoleOptions()
    {
        return [
            self::ROLE_USER =>  'user', 
            self::ROLE_SELLER =>  'seller', 
            self::ROLE_MANAGER =>  'manager', 
            self::ROLE_ADMIN =>  'admin', 
        ];
    }
    
     /**
     * Devolve o texto relativo ao role selecionado
     * @return array
     */
    public function getRole($role)
    {
        $array = self::getRoleOptions();
        return $array[$role];
    }
    
     /**
     * Desolve o label do status para usar em gridviews e assim basta meter no attribute roleLabel
     * @return array
     */
    public function getRoleLabel()
    {
        $array = self::getRoleOptions();
        return $array[$this->role];
    }
    
    
    /**
     * Retorna o nome completo do utilizador se não tiver retorna o email
     * @return type
     */
    public function getCompleteName(){
        if(!empty($this->profile->name)){
            return $this->profile->name;
        }else{
            return $this->username;
        }
    }
}

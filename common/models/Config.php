<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "config".
 *
 * @property integer $id
 * @property string $attribute
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['attribute', 'value'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['attribute'], 'string', 'max' => 255]
        ];
    }
    
    /**
    * função dos behaviors, mete automáticamente timesampts no created_at e updated_at
    * @return type
    */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute' => Yii::t('app', 'Attribute'),
            'value' => Yii::t('app', 'Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * After save create/update or remove attribute we clean the cache
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) { 
        \Yii::$app->cache->flush();
        parent::afterSave($insert, $changedAttributes);
    }
    
     /**
     * Return an object with the attribute and the value of all configs
     */
    public static function getConfigs()
    {
        $model = static::getDb()->cache(function ($db) {
            return static::find()->select(['attribute','value' ])->all();
        });
        return $model;

    }
}
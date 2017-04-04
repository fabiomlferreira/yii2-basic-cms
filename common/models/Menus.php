<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property integer $position
 * @property integer $parent_id
 * @property string $title
 * @property string $lang
 * @property string $url
 * @property integer $order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Menus $parent
 * @property Menus[] $menuses
 */
class Menus extends \yii\db\ActiveRecord
{
    const POSITION_MAIN =           1;
    const POSITION_TOP =            2;
    const POSITION_BOTTOM =         3; 
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'order', 'created_at', 'updated_at', 'position'], 'integer'],
            [['title', 'position'], 'required'],
            [['title', 'url'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 10],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menus::className(), 'targetAttribute' => ['parent_id' => 'id']]
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
            'id' => Yii::t('app', 'Position'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'title' => Yii::t('app', 'Title'),
            'lang' => Yii::t('app', 'Lang'),
            'url' => Yii::t('app', 'Url'),
            'order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Menus::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuses()
    {
        return $this->hasMany(Menus::className(), ['parent_id' => 'id'])->orderBy('order');
    }
    
    /**
     * Devolve um array com os items para criar o menu na lingua pedida
     */
    public static function getItems($position = self::POSITION_MAIN)
    {
        $item = [];
        //\Yii::$app->db->beginCache(43600);
        //\Yii::$app->db->enableQueryCache = false;
        $models = static::getDb()->cache(function ($db) use($position) {
            return static::find()->where(['parent_id' => NULL, 'lang' => \Yii::$app->language, 'position' =>$position])->orderBy('order')->all();
        });
        //$models = static::find()->where(['parent_id' => NULL])->orderBy('order')->all();
        //\Yii::$app->db->endCache();
        foreach($models as $model) {
            if(!empty($model->menuses)){
                $subItems = [];
                foreach($model->menuses as $submenu){
                   $subItems[] = ['label' => $submenu->title, 'url' => [$submenu->url]];
                }
                $item[] = ['label' => $model->title, 'items' => $subItems ];

            }else{
                $item[] = ['label' => $model->title, 'url' => [$model->url], 'active'=>Yii::$app->request->getUrl() == \yii\helpers\Url::toRoute([$model->url])];
            }
        }
        if($position == self::POSITION_MAIN && !\Yii::$app->user->isGuest){
             $item[] = ['label' => Yii::t('app', 'Logout'), 'url' => ['/logout'], 'linkOptions' => ['data-method' => 'post']];
        }
        if($position == self::POSITION_MAIN && \Yii::$app->user->isGuest){
             $item[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/login']];
        }
        
        return $item;
    }
    
    /**
     * Devolve o texto relativo à position selecionado
     * @return array
     */
    public function getPosition($position)
    {
        $array = self::getPositionOptions();
        return $array[$position];
    }
    
     /**
     * Desolve o label da position para usar em gridviews e assim basta meter no attribute positionLabel
     * @return array
     */
    public function getPositionLabel()
    {
        $array = self::getPositionOptions();
        return $array[$this->position];
    }
    
    /**
     * Devolve um array com os valores que o campo comment position pode ter
     * @return array
     */
    public function getPositionOptions()
    {
        return [
            self::POSITION_MAIN =>  Yii::t('app', 'Principal'), 
            self::POSITION_TOP =>  Yii::t('app', 'Topo'),
            self::POSITION_BOTTOM =>  Yii::t('app', 'Footer')
        ];
    }
}
<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use fabiomlferreira\filemanager\behaviors\MediafileBehavior;
use fabiomlferreira\filemanager\models\Mediafile;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $content
 * @property string $title
 * @property string $slug
 * @property string $type
 * @property integer $feature_image_id
 * @property integer $comment_status
 * @property integer $comment_count
 * @property integer $post_lang_parent
 * @property string $lang
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Mediafile $featureImage
 * @property Post $postLangParent
 * @property Post[] $posts
 * @property User $user
 * @property PostCategory[] $postCategories
 * @property Category[] $categories
 * @property PostTag[] $postTags
 * @property Tag[] $tags
 */
class Post extends \yii\db\ActiveRecord
{
    public $tagsString;
    public $tagsArray = [];
    private $currentTags = [];
    public $categoriesIds;
    private $categoriesIdsStored; // vai ficar com os ids das categorias que já estavam guardados


    const STATUS_DELETED =          -1;
    const STATUS_DRAFT =            0;
    const STATUS_PUBLISHED =        1;
    const STATUS_SCHEDULED =        2; 
    
    const COMMENT_STATUS_ACTIVATED =          1;
    const COMMENT_STATUS_DEACTIVATED =        0; 
    
    const TYPE_POST =        'post';
    const TYPE_PAGE =        'page'; 
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'feature_image_id', 'comment_status', 'comment_count', 'post_lang_parent', 'status', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'safe'],
            [['content'], 'string'],
            [['title'], 'required'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 64],
            [['lang'], 'string', 'max' => 5],
            [['feature_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mediafile::className(), 'targetAttribute' => ['feature_image_id' => 'id']],
            [['post_lang_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_lang_parent' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['categoriesIds', 'in','range'=>  [0], 'allowArray' => true, 'not'=>true], //todos os numeros que não sao 0 valida isto só serve para o load conseguir ir buscar os dados
            [['tagsString', 'tagsArray'], 'safe']

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
            'mediafile' => [
                'class' => MediafileBehavior::className(),
                'name' => 'post',
                'attributes' => [
                    'feature_image_id',
                ],
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique'  => TRUE,
                'immutable' => true
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'date' => Yii::t('app', 'Date'),
            'content' => Yii::t('app', 'Content'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),
            'type' => Yii::t('app', 'Type'),
            'feature_image_id' => Yii::t('app', 'Feature Image ID'),
            'comment_status' => Yii::t('app', 'Comment Status'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'post_lang_parent' => Yii::t('app', 'Post Lang Parent'),
            'lang' => Yii::t('app', 'Lang'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'tagsString' => Yii::t('app', 'Tags'),
            'tagsArray' => Yii::t('app', 'Tags'),
        ];
    }
    
    /**
     * Carrega automáticamente todas as categories deste array
     */
    public function afterFind()
    {
        
        $this->categoriesIds = [];
        foreach ($this->categories as $r) {
            $this->categoriesIds[] = $r->id;
        }
 
        $this->categoriesIdsStored = $this->categoriesIds;
 
        parent::afterFind();
    }
    public function beforeSave($insert) {
        
        //se a data de publicação for maior que a atuar e o post estiver marcado como publicado muda para agendado
        if((strtotime($this->date) > strtotime("now")) && $this->status == self::STATUS_PUBLISHED){
            $this->status = self::STATUS_SCHEDULED;
        }
        
        return parent::beforeSave($insert);
    }
    
    //3) save the selected categories, remove the unselected categories
    public function afterSave($insert, $changedAttributes) {
        if (!$this->categoriesIds) //if nothing selected set it as an empty array
           $this->categoriesIds = array();
        
        //se for empty e for do tipo post usa a primeira categoria da lingua onde estamos
        if(empty($this->categoriesIds) && $this->type=="post")
            $this->categoriesIds[] = Category::find()->where(['lang' => Yii::$app->language])->one()->id;
        
        if(!is_array($this->categoriesIdsStored))
            $this->categoriesIdsStored = [];    
 
        //save the new selected ids that are not exist in the stored ids
        $ids_to_insert = array_diff($this->categoriesIds, $this->categoriesIdsStored);
 
        foreach ($ids_to_insert as $cat_id) {
            $m = new PostCategory(); 
            $m->post_id = $this->id;
            $m->category_id = $cat_id;
            $m->save();
        }
 
        //remove the stored ids that are not exist in the selected ids
        $ids_to_delete = array_diff($this->categoriesIdsStored, $this->categoriesIds);
 
        foreach ($ids_to_delete as $cat_id) {
            if ($m = PostCategory::findOne(['post_id' => $this->id, 'category_id' => $cat_id])) {
                $m->delete();
            }
        }
 
        parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatureImage()
    {
        return $this->hasOne(Mediafile::className(), ['id' => 'feature_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostLangParent()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_lang_parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['post_lang_parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('post_category', ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('post_tag', ['post_id' => 'id']);
    }
    
    /**
     * Devolve o post parent na linguagem pedida ou false se não existir
     * @return \yii\db\ActiveQuery
     */
    public function getLang($language)
    {
        $model =$this->find()->orWhere(['lang' => $language, 'post_lang_parent' => $this->id])->orWhere(['lang' => $language, 'id' => $this->post_lang_parent])->one();
        if($model === NULL)
            return false; 
        else
            return $model;
    }
    /**
     * Devolve um array com os valores que o campo status pode ter
     * @return array
     */
    public function getStatusOptions()
    {
        return [
            self::STATUS_DELETED =>  Yii::t('app', 'Deleted'), 
            self::STATUS_DRAFT =>  Yii::t('app', 'Draft'), 
            self::STATUS_PUBLISHED =>  Yii::t('app', 'Published'), 
            self::STATUS_SCHEDULED => Yii::t('app', 'Scheduled')
        ];
    }
    
     /**
     * Devolve o texto relativo ao status selecionado
     * @return array
     */
    public function getStatus($status)
    {
        $array = self::getStatusOptions();
        return $array[$status];
    }
    
     /**
     * Desolve o label do status para usar em gridviews e assim basta meter no attribute statusLabel
     * @return array
     */
    public function getStatusLabel()
    {
        $array = self::getStatusOptions();
        return $array[$this->status];
    }
    
    /**
     * Devolve um array com os valores que o campo comment status pode ter
     * @return array
     */
    public function getCommentStatusOptions()
    {
        return [
            self::COMMENT_STATUS_ACTIVATED =>  Yii::t('app', 'Activated'), 
            self::COMMENT_STATUS_DEACTIVATED =>  Yii::t('app', 'Deactivated')
        ];
    }
    
    /**
     * Devolve o texto relativo ao status selecionado
     * @return array
     */
    public function getCommentStatus($status)
    {
        $array = self::getCommentStatusOptions();
        return $array[$status];
    }
    
    /**
     * Desolve o label do status para usar em gridviews e assim basta meter no attribute commentStatusLabel
     * @return array
     */
    public function getCommentStatusLabel()
    {
        $array = self::getCommentStatusOptions();
        return $array[$this->comment_status];
    }
    
    /**
     * Devolve um array com os valores que o campo type pode ter
     * @return array
     */
    public function getTypeOptions()
    {
        return [
            self::TYPE_POST =>  Yii::t('app', 'Post'), 
            self::TYPE_PAGE =>  Yii::t('app', 'Page'), 
        ];
    }
    
    /**
     * Devolve o texto relativo ao type selecionado
     * @return array
     */
    public function getType($type)
    {
        $array = self::getTypeOptions();
        return $array[$type];
    }
    
     /**
     * Desolve o label do type para usar em gridviews e assim basta meter no attribute typeLabel
     * @return array
     */
    public function getTypeLabel()
    {
        $array = self::getTypeOptions();
        return $array[$this->type];
    }
    
    /**
     * Verifica se já temos uma tag se nao tivermos cria e adiciona a este post
     * @param type $tag
     * @return boolean
     */
    public function setTag($tag){
       
        $tagModel = Tag::find()->where(['tag' => $tag, 'lang' => \Yii::$app->language])->one();
        //se não existir
        if($tagModel === null){
            $tagModel = new Tag();
            $tagModel->tag= $tag;
            $tagModel->lang= \Yii::$app->language;
            $tagModel->save();
        }
        // vê se já tem esta tag, pode ser um update, se nao tiver vai adicionar
        if(!PostTag::find()->where(['post_id' => $this->id, 'tag_id' => $tagModel->id])->exists()){
            $tagModel->count = $tagModel->count + 1; //adiciona um
            $tagModel->save();
            
            $postTags = new PostTag();
            $postTags->tag_id = $tagModel->id;
            $postTags->post_id= $this->id;
            return $postTags->save();
        }
        return true;
    }
    
    /**
     * Vai producar uma tag se existir vai ver se está no post e se estiver no post remove
     * @param type $tag
     * @return boolean
     */
    public function removeTag($tag){
       
        $tagModel = Tag::find()->where(['tag' => $tag, 'lang' => \Yii::$app->language])->one();
        //se não existir
        if($tagModel === null){
            return false;
        }

        // vê se já tem esta tag, para a poder retirar
        $postTags = PostTag::find()->where(['post_id' => $this->id, 'tag_id' => $tagModel->id])->one();
        if($postTags !== null){
            $tagModel->count = $tagModel->count - 1; //remove um
            $tagModel->save();
            
            return $postTags->delete();
        }
        return false;
    }
    
    /**
     * Load the tags currently set to an array
     * @return array with the tags
     */
    public function loadCurrentTags(){
        $this->currentTags = ArrayHelper::getColumn($this->tags, 'tag');
        //$this->tagsArray =  ArrayHelper::map($this->tags, 'id', 'tag'); 
        $this->tagsArray =$this->currentTags;
        return $this->currentTags;
    }
    
    /**
     * Update the tags if necessary remove or create new ones
     */
    public function updateTags(){
        $newTagsArray = array_diff($this->tagsArray, $this->currentTags);
        $tagsToRemoveArray = array_diff($this->currentTags, $this->tagsArray);
        
        foreach ($newTagsArray as $tag) {
            $tagTrim = trim($tag); //clean white spaces
            //if the tag is not empty
            if(!empty($tagTrim))
                $this->setTag($tagTrim);
        }
        //remove the tags that should be removed
        foreach($tagsToRemoveArray as $tag){
            $this->removeTag($tag);
        }
    }
    
}
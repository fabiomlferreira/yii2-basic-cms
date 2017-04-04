<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;


/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $category
 * @property integer $parent_category
 * @property string $slug
 * @property string $type
 * @property string $lang
 * @property integer $count
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Category $parentCategory
 * @property Category[] $categories
 * @property PostCategory[] $postCategories
 * @property Post[] $posts
 */
class Category extends \yii\db\ActiveRecord
{
    const TYPE_POST= 'post';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['parent_category', 'count', 'created_at', 'updated_at'], 'integer'],
            [['category'], 'string', 'max' => 128],
            [['type'], 'string', 'max' => 64],
            [['lang'], 'string', 'max' => 5],
            [['parent_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_category' => 'id']],
            [['slug'], 'string', 'max' => 255],
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
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'category',
                'slugAttribute' => 'slug',
                'ensureUnique'  => TRUE,
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
            'category' => Yii::t('app', 'Category'),
            'parent_category' => Yii::t('app', 'Parent Category'),
            'slug' => Yii::t('app', 'Slug'),
            'type' => Yii::t('app', 'Type'),
            'lang' => Yii::t('app', 'Lang'),
            'count' => Yii::t('app', 'Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * Return an array with the Type Options
     * @return array
     */
    public function getTypeOptions()
    {
        return [
            self::TYPE_POST =>  Yii::t('app', 'Post'), 
        ];
    }
    
     /**
     * Return the text for the Type
     * @return array
     */
    public function getType($type)
    {
        $array = self::getTypeOptions();
        return $array[$type];
    }
    
     /**
     * return the label for the type should be used as typeLabel
     * @return array
     */
    public function getTypeLabel()
    {
        $array = self::getTypeOptions();
        return $array[$this->type];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_category' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('post_category', ['category_id' => 'id']);
    }
}
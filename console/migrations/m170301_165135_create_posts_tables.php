<?php

use yii\db\mysql\Schema;
use yii\db\Migration;

class m170301_165135_create_posts_tables extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        //tabela dos posts
        $this->createTable('post', [
            'id'            => Schema::TYPE_PK,
            'user_id'       => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',
            'date'          => Schema::TYPE_TIMESTAMP. ' DEFAULT CURRENT_TIMESTAMP COMMENT "Data a que o post é publicado"',
            'content'       => Schema::TYPE_TEXT,
            'title'         => Schema::TYPE_STRING . '(255)',
            'slug'          => Schema::TYPE_STRING . '(255) COMMENT "Post url"',
            'type'          => Schema::TYPE_STRING . '(64) DEFAULT "post" COMMENT "Can be post or page"',
            'feature_image_id' => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',
            'comment_status'=> Schema::TYPE_BOOLEAN. ' DEFAULT TRUE COMMENT "Tell if the post support comments"',
            'comment_count' => Schema::TYPE_INTEGER.'(5) DEFAULT 0 COMMENT "Number of comments on the post"',
            'post_lang_parent' => Schema::TYPE_INTEGER .' NULL DEFAULT NULL COMMENT "ID of the post in the main language null if dont have"',
            'lang'          => Schema::TYPE_STRING. '(5) DEFAULT "pt-PT"',
            'status'        => Schema::TYPE_SMALLINT . " DEFAULT 0 COMMENT '-1 - removed | 0 - draft | 1 - published | 2 - scheduled'",
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        
        $this->createIndex('user_id', 'post', 'user_id', false); // o false significa que podemos ter vários repetidos
        //$this->createIndex('slug', 'post', 'slug', true); // a slug é unica pode ter que levar o en/nome_ para funcionar
        $this->createIndex('type', 'post', 'type', false);
        $this->createIndex('lang', 'post', 'lang', false);
        $this->createIndex('status', 'post', 'status', false);
        $this->createIndex('post_lang_parent', 'post', 'post_lang_parent', false); 
        $this->addForeignKey('fk_post_post_lang_parent', 'post', 'post_lang_parent', 'post', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_post_user_id', 'post', 'user_id', 'user', 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_post_feature_image_id', 'post', 'feature_image_id', 'filemanager_mediafile', 'id', 'SET NULL', 'RESTRICT');

        
         //tabela das tags
        $this->createTable('tag', [
            'id'            => Schema::TYPE_PK,
            'tag'           => Schema::TYPE_STRING . '(128) NOT NULL',
            'lang'          => Schema::TYPE_STRING. '(5) DEFAULT "pt-PT"',
            'count'         => Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "Number of times that the tag is used"',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            
        ], $tableOptions);
        
        $this->createIndex('tag', 'tag', 'tag', false); 
        $this->createIndex('lang', 'tag', 'lang', false);
        
        //tabela das post_tags
        $this->createTable('post_tag', [
            'tag_id'        => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',
            'post_id'       => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',        
        ], $tableOptions);
        
        $this->addPrimaryKey('PRIMARY_KEY', 'post_tag', ['post_id','tag_id']);
        $this->addForeignKey('fk_post_tag_post_id', 'post_tag', 'post_id', 'post', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_post_tag_feature_tag_id', 'post_tag', 'tag_id', 'tag', 'id', 'CASCADE', 'RESTRICT');
        
        
        //tabela das categorias
        $this->createTable('category', [
            'id'            => Schema::TYPE_PK,
            'category'      => Schema::TYPE_STRING . '(128) NOT NULL',
            'parent_category' => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',
            'slug'          => Schema::TYPE_STRING . '(255) COMMENT "Category url"',
            'type'          => Schema::TYPE_STRING . '(64) DEFAULT "post" COMMENT "Can be: post, page, etc.."',
            'lang'          => Schema::TYPE_STRING. '(5) DEFAULT "pt-PT"',
            'count'         => Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "Number of time tha the category is used"',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            
        ], $tableOptions);
        
        $this->createIndex('category', 'category', 'category', false); 
        $this->createIndex('lang', 'category', 'lang', false);
        $this->createIndex('parent_category', 'category', 'parent_category', false);
        $this->createIndex('type', 'category', 'type', false);
        $this->addForeignKey('fk_category_parent_category', 'category', 'parent_category', 'category', 'id', 'CASCADE', 'RESTRICT');
        
        //tabela dos post_categorys
        $this->createTable('post_category', [
            'category_id'        => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',
            'post_id'       => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',        
        ], $tableOptions);
        
        $this->addPrimaryKey('PRIMARY_KEY', 'post_category', ['post_id','category_id']);
        $this->addForeignKey('fk_post_category_post_id', 'post_category', 'post_id', 'post', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_post_category_category_id', 'post_category', 'category_id', 'category', 'id', 'CASCADE', 'RESTRICT');
       
    }

    public function down() {
        $this->dropForeignKey('fk_post_category_post_id', 'post_category');
        $this->dropForeignKey('fk_post_category_category_id', 'post_category');
        $this->dropTable('post_category');
        
        $this->dropForeignKey('fk_category_parent_category', 'category');
        $this->dropTable('category');
        
        $this->dropForeignKey('fk_post_tag_post_id', 'post_tag');
        $this->dropForeignKey('fk_post_tag_feature_tag_id', 'post_tag');
        $this->dropTable('post_tag');
        
        $this->dropTable('tag');
        
        $this->dropForeignKey('fk_post_user_id', 'post');
        $this->dropForeignKey('fk_post_feature_image_id', 'post');
        $this->dropForeignKey('fk_post_post_lang_parent', 'post');
        $this->dropTable('post');

    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}

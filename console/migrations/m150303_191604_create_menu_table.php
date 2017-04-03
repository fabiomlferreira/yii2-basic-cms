<?php

use yii\db\Schema;
use yii\db\Migration;

class m150303_191604_create_menu_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('menus', [
            'id'            => Schema::TYPE_PK,
            'parent_id'     => Schema::TYPE_INTEGER. ' NULL DEFAULT NULL',
            'title'         => Schema::TYPE_STRING. '(255) NOT NULL',
            'lang'          => Schema::TYPE_STRING . '(10) DEFAULT "pt-PT"',
            'url'           => Schema::TYPE_STRING. '(255) DEFAULT NULL',
            'order'         => Schema::TYPE_INTEGER. '(4) DEFAULT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        
        $this->createIndex('lang', 'menus', 'lang', false); // o false significa que podemos ter vários repetidos
        $this->createIndex('parent_id', 'menus', 'parent_id', false); // o false significa que podemos ter vários repetidos
        $this->addForeignKey('fk_menus_parent_id', 'menus', 'parent_id', 'menus', 'id', 'CASCADE', 'RESTRICT');

    }

    public function down() {
        $this->dropForeignKey('fk_menus_parent_id', 'menus');
        $this->dropTable('menus');
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

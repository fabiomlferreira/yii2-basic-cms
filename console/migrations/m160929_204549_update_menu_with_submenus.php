<?php

use yii\db\Schema;
use yii\db\Migration;

class m160929_204549_update_menu_with_submenus extends Migration
{
    public function up()
    {
        $this->addColumn('menus', 'position', Schema::TYPE_SMALLINT . " NOT NULL DEFAULT 1 COMMENT '1 - principal | 2 - menu de topo | 3 - menu de footer' AFTER id");
        $this->createIndex('position', 'menus', 'position', false); // o false significa que podemos ter vários repetidos
    }

    public function down()
    {
        $this->dropIndex('position', 'menus');
        $this->dropColumn('menus', 'position');
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

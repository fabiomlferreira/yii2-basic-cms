<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `config`.
 */
class m160928_231707_create_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('config', [
            'id'                => $this->primaryKey(),
            'attribute'         => Schema::TYPE_STRING . '(255)',
            'value'             => Schema::TYPE_TEXT,
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        $this->createIndex('attribute', 'config', 'attribute', false);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('config');
    }
}
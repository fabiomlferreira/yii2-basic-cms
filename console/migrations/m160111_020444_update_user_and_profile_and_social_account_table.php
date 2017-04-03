<?php

use yii\db\Schema;
use yii\db\Migration;

class m160111_020444_update_user_and_profile_and_social_account_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        //USER
        $this->addColumn('user', 'role', Schema::TYPE_STRING .'(64) DEFAULT "user"');
        $this->createIndex('role', 'user', 'role', false); //não é unico

        //PROFILE
        /*$this->renameColumn('{{%profile}}', 'name', 'first_name');
        $this->addColumn('{{%profile}}', 'last_name', Schema::TYPE_STRING .'(255) NULL DEFAULT NULL AFTER first_name');
        $this->addColumn('{{%profile}}', 'wedding_date', Schema::TYPE_DATE .' NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'photo', Schema::TYPE_STRING .'(255) NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'gender', 'ENUM("M", "F") NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'birthday', Schema::TYPE_DATE .' NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'phone', Schema::TYPE_STRING .'(15) NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'city', Schema::TYPE_STRING .'(255) NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'country', Schema::TYPE_STRING .'(100) NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'country_code', Schema::TYPE_STRING. '(2) DEFAULT "PT"');
        $this->addColumn('{{%profile}}', 'lat', Schema::TYPE_DECIMAL. '(10, 8) NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'lng', Schema::TYPE_DECIMAL. '(11, 8) NULL DEFAULT NULL');
        $this->addColumn('{{%profile}}', 'newsletter', Schema::TYPE_SMALLINT. " DEFAULT 1 COMMENT ' 0 - not subsctibed | 1 - subscribed'");
        $this->addColumn('{{%profile}}', 'notifications', Schema::TYPE_SMALLINT. " DEFAULT 2 COMMENT '0 - deactivated | 1 - active'");

        $this->createIndex('newsletter', '{{%profile}}', 'newsletter', false); //não é unico
        
        $this->addColumn('{{%social_account}}', 'token', Schema::TYPE_TEXT);*/
    }

    public function down()
    {
        //USER
        $this->dropIndex('role', 'user');
        $this->dropColumn('user', 'role');
        
        //PROFILE
        /*$this->dropIndex('newsletter', '{{%profile}}');
        $this->dropColumn('{{%profile}}', 'last_name');
        $this->renameColumn('{{%profile}}', 'first_name', 'name');
        $this->dropColumn('{{%profile}}', 'wedding_date');
        $this->dropColumn('{{%profile}}', 'photo');
        $this->dropColumn('{{%profile}}', 'gender');
        $this->dropColumn('{{%profile}}', 'birthday');
        $this->dropColumn('{{%profile}}', 'phone');
        $this->dropColumn('{{%profile}}', 'city');
        $this->dropColumn('{{%profile}}', 'country');
        $this->dropColumn('{{%profile}}', 'country_code');
        $this->dropColumn('{{%profile}}', 'lat');
        $this->dropColumn('{{%profile}}', 'lng');
        $this->dropColumn('{{%profile}}', 'newsletter');
        $this->dropColumn('{{%profile}}', 'notifications');
        
        $this->dropColumn('{{%social_account}}', 'token');*/
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

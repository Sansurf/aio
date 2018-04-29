<?php

use yii\db\Migration;

/**
 * Class m180424_123828_insert_into_language
 */
class m180424_123828_insert_into_language extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('language', ['title'], [
            ['Русский'],
            ['English']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('language');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180424_123828_insert_into_language cannot be reverted.\n";

        return false;
    }
    */
}

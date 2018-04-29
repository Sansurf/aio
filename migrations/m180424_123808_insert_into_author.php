<?php

use yii\db\Migration;

/**
 * Class m180424_123808_insert_into_author
 */
class m180424_123808_insert_into_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('author', ['name'], [
            ['CrazyNews'],
            ['Чук и Гек'],
            ['CatFuns'],
            ['CarDriver'],
            ['BestPics'],
            ['ЗОЖ'],
            ['Вася Пупкин'],
            ['Готовим со вкусом'],
            ['Шахтёрская Правда'],
            ['FunScience'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('author', ['name' => [
            'CrazyNews',
            'Чук и Гек',
            'CatFuns',
            'CarDriver',
            'BestPics',
            'ЗОЖ',
            'Вася Пупкин',
            'Готовим со вкусом',
            'Шахтёрская Правда',
            'FunScience',
        ]]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180424_123808_insert_into_author cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 * Has foreign keys to the tables:
 *
 * - `author`
 * - `language`
 */
class m180424_121944_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text' => $this->text(),
            'like' => $this->integer(),
            'author_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull()
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-post-author_id',
            'post',
            'author_id'
        );

        // add foreign key for table `author`
        $this->addForeignKey(
            'fk-post-author_id',
            'post',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );

        // creates index for column `language_id`
        $this->createIndex(
            'idx-post-language_id',
            'post',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-post-language_id',
            'post',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `author`
        $this->dropForeignKey(
            'fk-post-author_id',
            'post'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-post-author_id',
            'post'
        );

        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-post-language_id',
            'post'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-post-language_id',
            'post'
        );

        $this->dropTable('post');
    }
}

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пост', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Удалить всё', ['delete-all'], ['class' => 'btn btn-danger pull-right']) ?>

        <?= Html::a('Генерировать посты!', ['/generator'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'text:ntext',
            [
                'attribute' => 'author_id',
                'value' => 'author.name'
            ],
            [
                'attribute' => 'language_id',
                'value' => 'language.title'
            ],
            [
                'attribute' => 'date',
                'format' => ['date', 'php:d-m-Y']
            ],
            'like',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

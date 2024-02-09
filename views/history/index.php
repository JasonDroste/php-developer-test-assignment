<?php

use app\models\History;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;



/** @var yii\web\View $this */
/** @var app\models\HistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'History';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['id' => 'grid-pjax', 'formSelector' => false]); ?>

<div class="panel panel-primary panel-small m-b-0">
    <div class="panel-body panel-body-selected">

        <div class="pull-sm-right">
            <?php if (!empty($linkExport)) {
                echo Html::a(Yii::t('app', 'CSV'), $linkExport,
                    [
                        'class' => 'btn btn-success',
                        'data-pjax' => 0
                    ]
                );
            } ?>
        </div>

    </div>
</div>

<?php Pjax::end(); ?>
<div class="history-index">

    <h1><?= Html::encode($this->title) ?></h1>

   <p>Please use the search form to pull a specific record.</p>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ins_ts',
            'customer_id',
            'user_id',
            'event',
            'object',
            'object_id',
            'message:ntext',
            'detail:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, History $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

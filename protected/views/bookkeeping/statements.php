<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Statements',
);

$this->menutitle=Yii::t('delt', 'Depth');

$this->menu=array();

for($i=1; $i<=$model->COAMaxLevel; $i++)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Down to Level {number}', array('{number}'=>$i)), 'url'=>array('bookkeeping/statements', 'slug'=>$model->slug, 'level'=>$i));
}

?>
<h1><?php echo Yii::t('delt', 'Statements') ?></h1>

<?php foreach($model->getMainPositions() as $statement): ?>
  <?php echo $this->renderPartial('_statement', array(
    'statement'=>$statement,
    'data'=>$model->getStatement($statement, $level),
    'model'=>$model,
    'level'=>$level,
    'hlevel'=>2,
    'links'=>true,
    )) ?>
<?php endforeach ?>

<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title =>array('exercise/view', 'id'=>$model->id),
);

if($challenges)
{
  $this->breadcrumbs['Report']=array('exercise/report', 'id'=>$model->id);
  $this->breadcrumbs[]='Session';
}
else
{
  $this->breadcrumbs[]='Report';
}


$this->layout = '//layouts/column1_menu_below';

$this->menu=array(
  array('label'=>Yii::t('delt', 'View Exercise'), 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>Yii::t('delt', 'Invite Users'), 'url'=>array('invite', 'slug'=>$model->slug)),
);

$cs = Yii::app()->getClientScript();
$cs->registerScript(
  'usernames-handler',
  '

  $("#usernames").hide();
  var visible = false;
  $("#toggle_usernames").click( function() {
    $("#usernames").toggle("slow");
    return false;
    }
  );
  
  '
  ,
  CClientScript::POS_READY
);


?>

<h1><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)) ?></h1>

<?php if($challenges): ?>
  <?php if(sizeof($challenges)): ?>
    <table>
      <tr>
        <th><?php echo Yii::t('delt', 'Firm') ?></th>
        <th><?php echo Yii::t('delt', 'Owner') ?></th>
        <th><?php echo Yii::t('delt', 'Last Action') ?></th>
        <th><?php echo Yii::t('delt', 'Rate') ?></th>
        <?php foreach($model->transactions as $transaction): ?>
          <th title="<?php echo CHtml::encode($transaction->description) ?>"><?php echo DELT::getDateForFormWidget($transaction->event_date, false) ?></th>
        <?php endforeach ?>
        
      </tr>
    <?php $usernames=array(); foreach($challenges as $challenge): ?>
      <tr>
        <td>
          <?php if($challenge->firm): ?>
          <?php echo CHtml::link(CHtml::encode($challenge->firm->name), array('/firms/' . $challenge->firm->slug, 'challenge'=>$challenge->id)) ?>
          <?php else: ?>
          <em><?php echo $challenge->user ?></em> 
          <?php endif ?>
        </td>
        <td title="<?php echo $challenge->user; $usernames[]= $challenge->user ?>">
          <?php if($challenge->firm): ?>
          <?php echo CHtml::encode($challenge->firm->getOwners(true)) ?>
          <?php endif ?>
        </td>
        <td>
          <?php echo Yii::t('delt', $challenge->getLastAction()['action']) ?><br />
          <small>
            <?php if ($challenge->getLastAction()['nullable']): ?>
              <?php echo CHtml::link($challenge->getLastAction()['at'], '#',
                  array(
                    'submit'=>array('challenge/changestatus', 'id'=>$challenge->id, 'redirect'=>'report', 'session'=>$session),
                    'params'=>array('deletelast'=>'true'),
                    'title'=>Yii::t('delt', 'Delete'),
                    'confirm'=>Yii::t('delt', 'Are you sure?'), 'method'=>'POST')
                  )
              ?>
            <?php else :?>
              <?php echo $challenge->getLastAction()['at']?>
            <?php endif ?>
          </small>
            
        </td>
        <td style="text-align: right"><?php echo Yii::app()->numberFormatter->formatDecimal(round($challenge->rate/10)). '%' ?></td>
        
        <?php foreach(DELT::getValueFromArray($challenge->getResults(), 'transactions', array()) as $t): ?>
          <td style="text-align: right" title="<?php echo implode("\n", $t['errors']) ?>">
            <?php echo $t['points'] ?>
          </td>
        
        <?php endforeach ?>
      </tr>
    <?php endforeach ?>
    </table>
    
    <a href="#" id="toggle_usernames"><?php echo Yii::t('delt', 'Show usernames') ?></a>
    <div id="usernames" style="display:none">
    <textarea rows="<?php echo sizeof($usernames) ?>"><?php echo implode("\n", $usernames)?></textarea>
    </div>
    
  <?php else: ?>
    <div><?php echo Yii::t('delt', 'No one invited.') ?></div>
  <?php endif ?>

<?php endif ?>

<?php if ($sessions): ?>
  <h2><?php echo Yii::t('delt', 'Sessions') ?></h2>
  <?php if(sizeof($sessions)): ?>
    <ul>
    <?php foreach($sessions as $session): ?>
       <li>
          <?php echo CHtml::link($session['session'], array('exercise/report', 'id'=>$model->id, 'session'=>$session['session'])) ?>
          (<?php echo $session['cnt'] ?>)
       </li>
    <?php endforeach ?>
    </ul>
  <?php else: ?>
    <div><?php echo Yii::t('delt', 'No sessions.') ?></div>
  <?php endif ?>

<?php endif ?>

<?php if (!$sessions and !$challenges): ?>
  <p><?php echo Yii::t('delt', 'No sessions.'); ?></p>
<?php endif ?>

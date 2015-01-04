<?php 

$account_dragdrop_url = addslashes($this->createUrl('account/dragdrop'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'drag-and-drop-handler',
  '
    // code here
  
  var account_dragdrop_url = "' . $account_dragdrop_url . '";
  console.log(account_dragdrop_url);
  
  addEventManagers();
  
  var sourceAccountId;
  var targetAccountId;
  var targetName;
  
  function addEventManagers()
  {
    $( ".dragdrop" ).attr("draggable", "true");
    
    $( ".dragdrop" ).on("drop", function(event) {
      event.preventDefault();
      console.log("to: " + event.srcElement.id);
      targetAccountId = event.srcElement.id;
      targetName = $("#"+targetAccountId).attr("_name");
      
      if(targetAccountId && sourceAccountId && (targetAccountId!=sourceAccountId))
      {
        account_dragdrop_url += "?source=" + sourceAccountId.substring(3) + "&target=" + targetAccountId.substring(3);
        $( "#dialog-message" ).html("Do you want the selected account to be a child of «" + targetName.replace(" ", "&nbsp;") + "»?");
        $( "#dialog-confirm" ).dialog({
          resizable: false,
          height: 200,
          width: 500,
          modal: true,
          buttons: {
            "Yes": function() {
              window.location.href = account_dragdrop_url;
            },
            Cancel: function() {
              $( this ).dialog( "close" );
              $( "#" + targetAccountId ).removeClass("flashed");
            }
          },
          close: function() {
            $( "#" + targetAccountId ).removeClass("flashed");
          }
        });
                
      }
      
    } );
    
    $( ".dragdrop" ).on("dragover", function(event) {
      event.preventDefault();
    } );

    $( ".dragdrop" ).on("dragenter", function(event) {
      $("#" + event.srcElement.id).addClass("flashed");
    } );
    
    $( ".dragdrop" ).on("dragleave", function(event) {
      $("#" + event.srcElement.id).removeClass("flashed");
    } );
    
    
    $( ".dragdrop" ).on("dragstart", function(event) {
      console.log("from: " + event.srcElement.id);
      sourceAccountId = event.srcElement.id;
    } );
    
  }

  '
  ,
  CClientScript::POS_READY
);

$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);


$columns = array(
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'code',
      'header'=>Yii::t('delt', 'Code'),
      ),
    array(
      'class'=>'DataColumn',
      'sortable'=>true,
      'name'=>'name',
      'header'=>Yii::t('delt', 'Name'),
      'value'=>array($this, $renderNameCallable),
      // this will call the function RenderName() of the Controller, passing the current object and the row number as parameter
      'type'=>'raw',
      'cssClassExpression'=>'$data->position == \'?\' ? \'unpositioned\' : \'\'',
      'evaluateHtmlOptions'=>true,
      'htmlOptions'=>array('class'=>'"dragdrop"', 'id'=>'"id_{$data->id}"', '_name'=>'"{$data->name}"'),
      ),
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'position',
      'header'=>Yii::t('delt', 'Position'),
      'value'=>array($this, 'RenderPosition'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'centered')
      ),
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'outstanding_balance',
      'header'=>Yii::t('delt', 'Outstanding balance'),
      'value'=>array($this, 'RenderOutstandingBalance'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'centered')
      ),
    );

if($showclasses)
{
  $columns[]='classes';
}
    
$columns[]=
    array(
      // see http://www.yiiframework.com/wiki/106/using-cbuttoncolumn-to-customize-buttons-in-cgridview/
      'class'=>'CButtonColumn',
      'template'=>$buttonsTemplate,
      'viewButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/ledger",array("id"=>$data->primaryKey))',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("account/update",array("id"=>$data->primaryKey))',
      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("account/delete",array("id"=>$data->primaryKey))',
      'headerHtmlOptions'=>array('class'=>'buttons'),
      'htmlOptions'=>array('style'=>'text-align: right; width: 60px', 'class'=>'buttons'),
      'buttons'=>array(
        'new'=>array(
          'label'=>'New',
          'url'=>'Yii::app()->controller->createUrl("account/create",array("slug"=>"' . $model->slug . '","id"=>$data->primaryKey))',
          'imageUrl'=>Yii::app()->request->baseUrl.'/images/new.png',
          'options'=>array('title'=>Yii::t('delt', 'Create a new account as child of this one'), 'class'=>'new'),
        ),
        'view'=>array(
          'visible'=>'$data->is_selectable',
        ),
        'update'=>array(
          'label'=>'Edit',
          'options'=>array('title'=>Yii::t('delt', 'Edit')),
        )
      ),
    );

$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'account-grid',
  'dataProvider'=>$dataProvider,
//  'filter'=>$model,
  'columns'=> $columns,
)); ?>


<div id="dialog-confirm" title="Place the account here?" style="display: none">
  <p><span class="ui-icon ui-icon-circle-plus" style="float:left; margin:0 7px 20px 0;"></span><span id="dialog-message"></span></p>
</div>

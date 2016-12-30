<?php 

// FIXME we should use json variables here...

$account_dragdrop_url = addslashes($this->createUrl('account/dragdrop'));
$confirmation_message = addslashes(Yii::t('delt', 'Do you want «{sourceName}» to be a child of «{{targetName}»?'));
$yes_label = addslashes(Yii::t('delt', 'Yes'));
$cancel_label = addslashes(Yii::t('delt', 'Cancel'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'drag-and-drop-handler',
  '
  
  var account_dragdrop_url = "' . $account_dragdrop_url . '";
  var confirmation_message = "' . $confirmation_message . '";
  var yes_label = "' . $yes_label . '";
  var cancel_label = "' . $cancel_label . '";
  console.log (yes_label);
  console.log (cancel_label);
  
  addEventManagers();
  
  var sourceAccountId;
  var sourceName;
  var targetAccountId;
  var targetName;
  var moving = false;
  
  function addEventManagers()
  {
    $(document).keyup(function(e) {
      if (e.keyCode == 27)
      {  // escape has been pressed
        $("#"+sourceAccountId).removeClass("moving");
        $( ".dragdrop" ).css( "cursor", "pointer" );
      }
    });

    $( ".dragdrop" ).on("dblclick", function(event) {
      sourceAccountId = event.currentTarget.id;
      if(!moving)
      {
        moving = true;
        $("#"+sourceAccountId).addClass("moving");
        $( ".dragdrop" ).css( "cursor", "move" );
        disableLinks();
      }
    });

    $( ".dragdrop" ).on("click", function(event) {
      if (moving)
      {
        targetAccountId = event.currentTarget.id;
        if (targetAccountId == sourceAccountId)
        {
          moving = false;
          $("#"+sourceAccountId).removeClass("moving");
          $( ".dragdrop" ).css( "cursor", "pointer" );
          enableLinks();
        }
        else
        {
          showConfirmationDialog();
        }
      }
    });

    $( ".dragdrop" ).attr("draggable", "true");
    
    $( ".dragdrop" ).on("drop", function(event) {
      event.preventDefault();
      targetAccountId = event.currentTarget.id;
      showConfirmationDialog();
    } );
    
    $( ".dragdrop" ).on("dragover", function(event) {
      event.preventDefault();
    } );

    $( ".dragdrop" ).on("dragenter", function(event) {
      $("#" + event.currentTarget.id).addClass("flashed");
    } );
    
    $( ".dragdrop" ).on("dragleave", function(event) {
      $("#" + event.currentTarget.id).removeClass("flashed");
    } );
    
    
    $( ".dragdrop" ).on("dragstart", function(event) {
      sourceAccountId = event.currentTarget.id;
    } );
    
  }

  function showConfirmationDialog()
  {
      targetName = $("#"+targetAccountId).attr("data-name");
      sourceName = $("#"+sourceAccountId).attr("data-name");
      
      if(targetAccountId && sourceAccountId && (targetAccountId!=sourceAccountId))
      {
        account_dragdrop_url += "?source=" + sourceAccountId.substring(3) + "&target=" + targetAccountId.substring(3);
        $( "#dialog-message" ).html(confirmation_message.replace("{sourceName}", sourceName).replace("{targetName}", targetName));
        $( "#dialog-confirm" ).dialog({
          resizable: false,
          height: 200,
          width: 500,
          modal: true,
          buttons: [
            {
              text: yes_label,
              click: function() {
                window.location.href = account_dragdrop_url;
              }
            },
            {
              text: cancel_label,
              click: function() {
                $( this ).dialog( "close" );
                $( "#" + targetAccountId ).removeClass("flashed");
                $( "#" + sourceAccountId ).removeClass("moving");
                moving = false;
                enableLinks();
              }
            }
          ],
          close: function() {
            $( "#" + targetAccountId ).removeClass("flashed");
            $( "#" + sourceAccountId ).removeClass("moving");
            moving = false;
            enableLinks();
          }
        });
                
      }
      
      else
      {
        $( "#" + targetAccountId ).removeClass("flashed");
        moving = false;
        enableLinks();
      }

  }

  function disableLinks()
  {
    $(".hiddenlink").on("click", function(e){
          e.preventDefault();
    })
  }
  
  function enableLinks()
  {
    $(".hiddenlink").unbind("click");
  }


  '
  ,
  CClientScript::POS_READY
);

$cs->registerScript(
  'event-handler',
  '
    $(".classes").hide();
    
    $("#toggleclasses").click(function() {
      $(".classes").toggle();
      }
    );
    
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
      'htmlOptions'=>array('class'=>'"dragdrop"', 'id'=>'"id_{$data->id}"', 'data-name'=>'"{$data->name}"'),
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
    array(
      'class'=>'CDataColumn',
      'sortable'=>false,
      'name'=>'classes',
      'header'=>Yii::t('delt', 'Classes'),
      'htmlOptions'=>array('class'=>'classes'),
      'headerHtmlOptions'=>array('class'=>'classes'),
      ),
    );
    
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

<p><?php echo Yii::t('delt', 'For the column of classes:') ?> <span id="toggleclasses"><?php echo CHtml::link(Yii::t('delt', 'toggle visibility'), "#") ?></span></p>

<div id="dialog-confirm" title="<?php echo Yii::t('delt', 'Place the account here?') ?>" style="display: none">
  <p><span class="ui-icon ui-icon-circle-arrow-s" style="float:left; margin:0 7px 20px 0;"></span><span id="dialog-message"></span></p>
</div>

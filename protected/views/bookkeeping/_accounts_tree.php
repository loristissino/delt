<?php 

$this->widget('CTreeView',array(
    'id'=>'unit-treeview',
    'url'=>array('bookkeeping/coatree', 'slug'=>$this->firm->slug),
//    'data'=>$this->firm->coatree,
    'control'=>'#treecontrol',
    'animated'=>'fast',
    'collapsed'=>true,
    'htmlOptions'=>array(
        'class'=>'treeview-red'
    )
));

?>

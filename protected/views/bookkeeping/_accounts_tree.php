<?php
/* 
echo '<p><a href="#" onclick="chooseAccount(\'abc\');">abc</a></p>';
echo '<p><a href="#" onclick="chooseAccount(\'def\');">def</a></p>';
echo '<p><a href="#" onclick="chooseAccount(\'ghi\');">ghi</a></p>';
*/
?>

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

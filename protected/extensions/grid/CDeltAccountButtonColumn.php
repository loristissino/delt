<?php
/**
 * CDeltAccountButtonColumn class file.
 *
 * @author Loris Tissino <loris.tissino@gmail.com>
 */

class CDeltAccountButtonColumn extends CButtonColumn
{
	public $viewButtonUrl='Yii::app()->controller->createUrl("bookkeeping/ledger",array("id"=>$data->primaryKey))';
  public $updateButtonUrl='Yii::app()->controller->createUrl("account/edit",array("id"=>$data->primaryKey))';
	public $deleteButtonUrl='Yii::app()->controller->createUrl("account/delete",array("id"=>$data->primaryKey))';
}

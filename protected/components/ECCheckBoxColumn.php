<?php

class ECCheckBoxColumn extends CCheckBoxColumn
{
  public $controller;
  protected function renderDataCellContent($row,$data)
  {
    if($this->controller->isLineShown())
    {
      parent::renderDataCellContent($row,$data);
    }
  }
}

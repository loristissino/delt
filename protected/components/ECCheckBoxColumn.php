<?php
/**
 * ECCheckBoxColumn class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/**
 * This is a class for a customized, extended, CCheckBoxColumn.
 *
 * @package application.components
 * 
 */
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

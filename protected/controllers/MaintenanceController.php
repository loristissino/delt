<?php
/**
 * MaintenanceController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * The MaintenanceController class.
 * 
 * @package application.controllers
 * 
 */
class MaintenanceController extends Controller
{
  public function actionIndex()
  {
    $this->render('index');
  }
}

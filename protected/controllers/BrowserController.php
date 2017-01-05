<?php
/**
 * BrowserController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * The BrowserController class.
 * 
 * @package application.controllers
 * 
 */
class BrowserController extends Controller
{
    public function actionIndex($theme='classic')
    {
      Yii::app()->session['theme'] = $theme;
      $referrer = Yii::app()->request->urlReferrer ? Yii::app()->request->urlReferrer : $this->createUrl('/'); 
      Yii::app()->request->redirect($referrer);
    }
}

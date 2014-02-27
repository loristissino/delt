<?php
/**
 * CI18nViewAction class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/**
 * CI18nViewAction is a customized extension of CViewAction.
 *
 * @package application.components
 * 
 */
class CI18nViewAction extends CViewAction
{

  /**
   * Resolves the user-specified view into a valid view name, and sets
   * the language based on the first part of the page name (eg it.help)
   * @param string $viewPath user-specified view in the format of 'path.to.view'.
   * @return string fully resolved view in the format of 'path/to/view'.
   * @throw CHttpException if the user-specified view is invalid
   */
  protected function resolveView($viewPath)
  {
    parent::resolveView($viewPath);
    // we can be sure that the language will be found, since we are
    // consistent in page naming...
    list($lang, $rest)=explode('.', $viewPath);
    Yii::app()->language=$lang;
    Event::log(Yii::app()->controller->DEUser, null, Event::SITE_PAGE_SEEN, array('viewPath'=>$viewPath));
  }



}

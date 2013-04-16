<?php

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
	}



}

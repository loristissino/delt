<?php

/**
 * ExportbalanceForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/** ExportbalanceForm class.
 * ExportbalanceForm is the data structure for keeping
 * export balance form data.
 * 
 * @package application.forms
 * 
 */

class ExportbalanceForm extends CFormModel
{
  public $delimiter; // ', ", nothing
  public $separator; // tab, comma, colon, semicolon
  public $type;  // signed amount, unsigned amount with extra column, separate columns for debit and credit outstanding balance
  public $charset; 
  public $fruition;
  
  public $delimiters;
  public $separators;
  public $types;
  public $charsets;
  public $fruitions;
   
  public function afterConstruct()
  {
    $this->delimiters = array('"'=>'"', "'"=>"'", 'none'=>'');
    $this->separators = array(','=>',', ';'=>';', ':'=>':', 't'=>'{tab}');
    $this->types = array('S'=>Yii::t('delt', 'signed amount'), 'U'=>Yii::t('delt', 'unsigned amount, with type'), '2'=>Yii::t('delt', 'two columns'));
    $this->charsets = array(

      'big5' => 'Chinese Traditional (Big5)',
      'euc-kr' => 'Korean (EUC)',
      'iso-8859-1' => 'Western Alphabet (ISO 8859-1)',
      'iso-8859-2' => 'Central European Alphabet (ISO 8859-2)',
      'iso-8859-3' => 'Latin 3 Alphabet (ISO 8859-3)',
      'iso-8859-4' => 'Baltic Alphabet (ISO 8859-4)',
      'iso-8859-5' => 'Cyrillic Alphabet (ISO 8859-5)',
      'iso-8859-6' => 'Arabic Alphabet (ISO 8859-6)',
      'iso-8859-7' => 'Greek Alphabet (ISO 8859-7)',
      'iso-8859-8' => 'Hebrew Alphabet (ISO 8859-8)',
      'iso-8859-9' => 'Turkish Alphabet (ISO 8859-9)',
      'iso-8859-10' => 'Nordic Alphabet (ISO 8859-10)',
      'iso-8859-15' => 'Western Alphabet / Euro (ISO 8859-15)',
      'iso-2022-jp' => 'Latin/Japanese part 1 (ISO 2022-JP)',
      'iso-2022-jp-2' => 'Latin/Japanese part 2 (ISO 2022-JP)',
      'iso-2022-kr' => 'Latin/Korean part 1 (ISO 2022-KR)',
      'koi8-r' => 'Cyrillic Alphabet (KOI8-R)',
      'shift-jis' => 'Japanese (Shift-JIS)',
      'x-euc' => 'Japanese (EUC)',
      'utf-8' => 'Universal Alphabet (UTF-8)',
      'windows-1250' => 'Central European Alphabet (Windows 1250)',
      'windows-1251' => 'Cyrillic Alphabet (Windows 1251)',
      'windows-1252' => 'Western Alphabet (Windows 1252)',
      'windows-1253' => 'Greek Alphabet (Windows 1253)',
      'windows-1254' => 'Turkish Alphabet (Windows 1254)',
      'windows-1255' => 'Hebrew Alphabet (Windows 1255)',
      'windows-1256' => 'Arabic Alphabet (Windows 1256)',
      'windows-1257' => 'Baltic Alphabet (Windows 1257)',
      'windows-1258' => 'Vietnamese Alphabet (Windows 1258)',
      'windows-874' => 'Thai (Windows 874)',

    );
    
    $this->fruitions=array('i'=>Yii::t('delt', 'inline'), 'd'=>Yii::t('delt', 'download'));
  }
  
  public function rules()
  {
    return array(
      array('delimiter', 'ArrayValidator', 'values'=>$this->delimiters, 'message'=>'You must select a valid delimiter'),
      array('separator', 'ArrayValidator', 'values'=>$this->separators, 'message'=>'You must select a valid separator'),
      array('type', 'ArrayValidator', 'values'=>$this->types, 'message'=>'You must select a valid type'),
      array('charset', 'ArrayValidator', 'values'=>$this->charsets, 'message'=>'You must select a valid charset'),
      array('fruition', 'ArrayValidator', 'values'=>$this->fruitions, 'message'=>'You must select a valid fruition type'),
    );
  }
  
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'type' => Yii::t('delt', 'Type'),
      'delimiter' => Yii::t('delt', 'Text delimiter'),
      'separator' => Yii::t('delt', 'Field delimiter'),
      'charset' => Yii::t('delt', 'Character set'),
      'fruition' => Yii::t('delt', 'Fruition type'),
    );
  }
  
}

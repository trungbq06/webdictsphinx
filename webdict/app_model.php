<?php
/* SVN FILE: $Id: app_model.php 6311 2008-01-02 06:33:52Z phpnut $ */

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-02 01:33:52 -0500 (Wed, 02 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */
include("utf8.php");
class AppModel extends Model{

	var $idSourceChar 		= "1|2|4|3|6|5|8|7|9|0";
	var $idDestinationChar 	= "b|c|d|e|a|f|g|h|i|j";
	var $setUTF8 = 0;
	var $lower = 'a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|đ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|í|ì|ỉ|ĩ|ị|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|ý|ỳ|ỷ|ỹ|ỵ';
    var $upper = 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Đ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Í|Ì|Ỉ|Ĩ|Ị|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Ý|Ỳ|Ỷ|Ỹ|Ỵ';
    var $arrayUpper;
    var $arrayLower; 
	var $idSource;
	var $idDestination;
	var $usefulCharacters = '0!:"&)(+*-/<>|123456789abcdefghijklmnopqrstuvwxyz áàảãạấầẩẫậâắằẳẵặăéèẻẽẹếềểễệêíìỉĩịóòỏõọốồổỗộôớờởỡợơúùủũụứừửữựưýỳỷỹỵđ';
	var $deleteCharacters = '[]{}()!:/\,.+-_&"@|*<>=^';
	var $srcChar =  'áàảãạấầẩẫậâắằẳẵặăéèẻẽẹếềểễệêíìỉĩịóòỏõọốồổỗộôớờởỡợơúùủũụứừửữựưýỳỷỹỵđ';
	var $destChar = "AIAJAKALAMANAOAPAQBIBJBKBLBMBNBOBPBQCICJCKCLCMCNCOCPCQDIDJDKDLDMDNDODPDQEIEJEKELEMENEOEPEQFIFJFKFLFMFNFOFPFQGIGJGKGLGMGNGOGPGQHIHJHKHL";
	var $utf8;

	function convertToSphinx($str)
	{
		$this->arrayUpper = explode('|',$this->upper);
        $this->arrayLower = explode('|',$this->lower);
		$this->utf8 = new UTF8();

		// To lower
		$str = $this->lower($str);
		// remove unusefull characters
		$tmp = $str;
		for ($i = 0; $i<$this->utf8->strlen($str); $i++)
			if ($this->utf8->strpos($this->usefulCharacters,$this->utf8->substr($str,$i,1))===false)
				$tmp = str_replace($this->utf8->substr($str,$i,1),"",$tmp);
		$str = $tmp;
		// convert vietnamese character to sphinx
		for ($i = 0; $i<$this->utf8->strlen($this->srcChar); $i++)
		if ($this->utf8->strpos(" ".$str,$this->utf8->substr($this->srcChar,$i,1))>0)
			$str = str_replace($this->utf8->substr($this->srcChar,$i,1),$this->utf8->substr($this->destChar,$i*2,2),$str);
		// Correct boolean query
		$str = str_replace('+','&',$str);
		return $str;
	}
	
	function lower($str){
		$this->arrayUpper = explode('|',$this->upper);
        $this->arrayLower = explode('|',$this->lower);
		return str_replace($this->arrayUpper,$this->arrayLower,$str);
    }
    function upper($str){
		$this->arrayUpper = explode('|',$this->upper);
        $this->arrayLower = explode('|',$this->lower);
		return str_replace($this->arrayLower,$this->arrayUpper,$str);
	}
	
	function query($str)
	{
		if ($this->setUTF8==0)
		{
			parent::query("SET NAMES utf8");
			$this->setUTF8 = 1;
		}
		return parent::query($str);
	}

}
?>
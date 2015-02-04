<?php
//require_once 'AppConstants.php';
require_once 'JSON.php';

class CommonServices
{
	public static function getDbFactory()
	{
		$db = Zend_Db::factory('Pdo_Mysql', array(
    		'host'     => 'localhost',
    		'username' => 'root',
    		'password' => '2v8Dbkh2x_kScHPyhQdT',
    		'dbname'   => 'dev-e'
		));
		
		return $db;
	}
	function ToDBString($string, $link, $isNumber=false)
{
    //If $isNumber==true we are specting a number
    if($isNumber)
    {
        //A correct number must be composed of:
        // - Zero or more integers followed by a decimal point and one or more integers (i.e.: .9 (0.9) or 9.9)
        // - One or more integers followed by a decimal point. (i.e.: 9. (9.0))
        // - One or more integers (i.e.: 999)
        if(preg_match("/^d*[.,']d+|d+[.,']|d+$/A", $string))
        //If it's a correct number we change the colon, quote or point ("'", "," or ".") by a decimal piont.
            return preg_replace( array(
                                       "/^(d+)[.,']$/"     , //9.
                                       "/^(d*)[.,'](d+)$/"  //.9 or 9.9
                                      ),
                                 array(
                                       "\1."                ,
                                       "\1.\2"
                                      )
                                 , $string);
        else
        //If it's not a correct number we show ERROR
            die("ERROR: Not a number $string");
    }
    else
     //If $string is a string ($isNumber==false) we return "'$string'" correctly escaped (in this version I also strip HTML tags and modify some things in the string, change it if you wish).
     return "'".mysql_real_escape_string(htmlentities(strtoupper(trim(strip_tags($string)))), $link)."'";
}
	public static function json_encode($arg)
	{
		global $services_json;
		if (!isset($services_json)) {
			$services_json = new Services_JSON();
		}
		return $services_json->encode($arg);
	}
	
	public static function json_decode($arg)
	{
		global $services_json;
		if (!isset($services_json)) {
			$services_json = new Services_JSON();
		}
		return $services_json->decode($arg);
	}
	
	public static function convertDateToDB($date)
	{
		$dateArr = explode(" ",$date); 
		if($dateArr[0] < 10)
			$d = '0'.$dateArr[0];
		else
			$d = $dateArr[0];	

		if($dateArr[1] == 'Jan')
			$m = '01';
		else if($dateArr[1] == 'Feb')
			$m = '02';
		else if($dateArr[1] == 'Mar')
			$m = '03';
		else if($dateArr[1] == 'Apr')
			$m = '04';
		else if($dateArr[1] == 'May')
			$m = '05';
		else if($dateArr[1] == 'Jun')
			$m = '06';
		else if($dateArr[1] == 'Jul')
			$m = '07';
		else if($dateArr[1] == 'Aug')
			$m = '08';
		else if($dateArr[1] == 'Sep')
			$m = '09';
		else if($dateArr[1] == 'Oct')
			$m = '10';
		else if($dateArr[1] == 'Nov')
			$m = '11';
		else if($dateArr[1] == 'Dec')
			$m = '12';

		$y = '20'.$dateArr[2];
		return $y.'-'.$m.'-'.$d;
					
	}
	
	public static function convertDateToDisp($date)
	{
		$dateArr = explode("-",$date);
		$y = substr($dateArr[0],2);
		if($dateArr[1] == '01')
			$m = 'Jan';
		else if($dateArr[1] == '02')
			$m = 'Feb';
		else if($dateArr[1] == '03')
			$m = 'Mar';
		else if($dateArr[1] == '04')
			$m = 'Apr';
		else if($dateArr[1] == '05')
			$m = 'May';
		else if($dateArr[1] == '06')
			$m = 'Jun';
		else if($dateArr[1] == '07')
			$m = 'Jul';
		else if($dateArr[1] == '08')
			$m = 'Aug';
		else if($dateArr[1] == '09')
			$m = 'Sep';
		else if($dateArr[1] == '10')
			$m = 'Oct';
		else if($dateArr[1] == '11')
			$m = 'Nov';
		else if($dateArr[1] == '12')
			$m = 'Dec';
                $dayArr =  explode(" ",$dateArr[2]);
		$d = $dayArr[0];	
		
		return $d.' '.$m.' '.$y;	
	}
        public static function convertspecialchars($str)
	{
             $str1 = str_replace(" amp; ", "&",$str);
             $str2 = str_replace(" gt; ", ">",$str1);
             $str3 = str_replace(" lt; ", "<",$str2);
             $str4 = str_replace(" quot; ", '"',$str3);
             $str5 = str_replace(" sqt; ", "'",$str4);
             $str6 = str_replace(" hash; ", "#",$str5);
              $str7 = str_replace(" plus; ", "+",$str6);
              $str8 = str_replace(" strbrc; ", "[",$str7);
              $str9 = str_replace(" endbrc; ", "]",$str8);
               $str10 = str_replace(" eql; ", "=",$str9);
             return $str10;
        }
        function em($word) {

    $word = str_replace("@","%40",$word);
    $word = str_replace("`","%60",$word);
    $word = str_replace("¢","%A2",$word);
    $word = str_replace("£","%A3",$word);
    $word = str_replace("¥","%A5",$word);
    $word = str_replace("|","%A6",$word);
    $word = str_replace("«","%AB",$word);
    $word = str_replace("¬","%AC",$word);
    $word = str_replace("¯","%AD",$word);
    $word = str_replace("º","%B0",$word);
    $word = str_replace("±","%B1",$word);
    $word = str_replace("ª","%B2",$word);
    $word = str_replace("µ","%B5",$word);
    $word = str_replace("»","%BB",$word);
    $word = str_replace("¼","%BC",$word);
    $word = str_replace("½","%BD",$word);
    $word = str_replace("¿","%BF",$word);
    $word = str_replace("À","%C0",$word);
    $word = str_replace("Á","%C1",$word);
    $word = str_replace("Â","%C2",$word);
    $word = str_replace("Ã","%C3",$word);
    $word = str_replace("Ä","%C4",$word);
    $word = str_replace("Å","%C5",$word);
    $word = str_replace("Æ","%C6",$word);
    $word = str_replace("Ç","%C7",$word);
    $word = str_replace("È","%C8",$word);
    $word = str_replace("É","%C9",$word);
    $word = str_replace("Ê","%CA",$word);
    $word = str_replace("Ë","%CB",$word);
    $word = str_replace("Ì","%CC",$word);
    $word = str_replace("Í","%CD",$word);
    $word = str_replace("Î","%CE",$word);
    $word = str_replace("Ï","%CF",$word);
    $word = str_replace("Ð","%D0",$word);
    $word = str_replace("Ñ","%D1",$word);
    $word = str_replace("Ò","%D2",$word);
    $word = str_replace("Ó","%D3",$word);
    $word = str_replace("Ô","%D4",$word);
    $word = str_replace("Õ","%D5",$word);
    $word = str_replace("Ö","%D6",$word);
    $word = str_replace("Ø","%D8",$word);
    $word = str_replace("Ù","%D9",$word);
    $word = str_replace("Ú","%DA",$word);
    $word = str_replace("Û","%DB",$word);
    $word = str_replace("Ü","%DC",$word);
    $word = str_replace("Ý","%DD",$word);
    $word = str_replace("Þ","%DE",$word);
    $word = str_replace("ß","%DF",$word);
    $word = str_replace("à","%E0",$word);
    $word = str_replace("á","%E1",$word);
    $word = str_replace("â","%E2",$word);
    $word = str_replace("ã","%E3",$word);
    $word = str_replace("ä","%E4",$word);
    $word = str_replace("å","%E5",$word);
    $word = str_replace("æ","%E6",$word);
    $word = str_replace("ç","%E7",$word);
    $word = str_replace("è","%E8",$word);
    $word = str_replace("é","%E9",$word);
    $word = str_replace("ê","%EA",$word);
    $word = str_replace("ë","%EB",$word);
    $word = str_replace("ì","%EC",$word);
    $word = str_replace("í","%ED",$word);
    $word = str_replace("î","%EE",$word);
    $word = str_replace("ï","%EF",$word);
    $word = str_replace("ð","%F0",$word);
    $word = str_replace("ñ","%F1",$word);
    $word = str_replace("ò","%F2",$word);
    $word = str_replace("ó","%F3",$word);
    $word = str_replace("ô","%F4",$word);
    $word = str_replace("õ","%F5",$word);
    $word = str_replace("ö","%F6",$word);
    $word = str_replace("÷","%F7",$word);
    $word = str_replace("ø","%F8",$word);
    $word = str_replace("ù","%F9",$word);
    $word = str_replace("ú","%FA",$word);
    $word = str_replace("û","%FB",$word);
    $word = str_replace("ü","%FC",$word);
    $word = str_replace("ý","%FD",$word);
    $word = str_replace("þ","%FE",$word);
    $word = str_replace("ÿ","%FF",$word);
    return $word;
}
}
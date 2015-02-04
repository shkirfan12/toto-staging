<?php
/*
	 Model fo rcommom classes
*/
class Default_Model_CommonClass
{
	function cleanVars($vars)
	{
		if(!$vars) return;
		$vars = htmlspecialchars($vars);
		$vars = strip_tags($vars);
		return trim($vars);
	}	


        function cleanKeywordVars($vars)

        {

                if(!$vars) return;

                //$patt=array('/','\\');

                /*$specialChars = array("<",">");

                $repChars = array("&lt;","&gt;");*/

                $vars=str_ireplace("<","&lt;",$vars);

                $vars=str_ireplace(">","&gt;",$vars);

                $vars=str_ireplace("&","&#38;",$vars);

                $vars=str_ireplace("+","&#43;",$vars);

                $vars=str_ireplace('"','\\"',$vars);

                //$vars=str_ireplace("'","\'",$vars);

                //$vars=str_ireplace('"',"&quot;",$vars);

        /*      $vars = htmlspecialchars($vars);

                $vars = strip_tags($vars);

                //$vars = addslashes($vars);*/

                return trim($vars);

        }


	function intdata($data)
	{
		if(filter_var($data, FILTER_VALIDATE_INT)) {
			return true;
		}else {
			return false;
		}
	}

	function stringdata($data)
	{
		if(preg_match("/[a-zA-Z0-9@_-]$/",$data))
			return true;
		else
			return false;
	}

	function floatdata($data)
	{
		if(filter_var($data,FILTER_VALIDATE_FLOAT) === false)
			return false;
		else
			return true;
	}

	function urlchk($url)
	{
		if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
			return false;
		else
			return true;
	}
	
	function chkemail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE)
			return false;
		else
			return true;
	}
	
	function getDbToday()
	{
		return date('Y-m-d H:i:s');
	}

	function genPwd()
	{
		$pwd=md5(USER_PWD_ENC_KEY.time());
		return substr($pwd, strlen($pwd)-5, 5);
	}

	function genKey()
	{
		return md5(USER_ENC_KEY.time());
	}

	function d2e($string, $key)
	{
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}

	function e2d($string, $key)
	{
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
	function genFileName($mix)
	{
		$str=md5('om'.time().$mix);
		return substr($str, strlen($str)-7, 7);
	}

	function ucode($s)
	{
		$s = str_replace("+","%20",urlencode(stripslashes($s)));
		return $s;
	}

	function decodeString($vars)
	{
		if(!$vars) return;
		/*$vars=str_ireplace("&lt;","<",$vars);
		$vars=str_ireplace("&gt;",">",$vars);*/
		$vars = stripslashes($vars);
		$vars = html_entity_decode($vars);
		$vars = htmlspecialchars($vars);
		return $vars;
	}
	function blendedScore($companyId)
	{
		$objSetting = new Application_Model_CompanyManagementDbFunctions();
		$listSettings = $objSetting->fetchCompany($companyId);
		//print_r ($listSettings);
		$blend =   $listSettings['fld_excitement_level']+$listSettings['fld_team']+$listSettings['fld_referral_source']+$listSettings['fld_risk']+$listSettings['fld_market_opportunity']+$listSettings['fld_differentiation']+$listSettings['fld_identifiable_painpoint']+$listSettings['fld_returns']+$listSettings['fld_time_path']+$listSettings['fld_deal_term'];
		$score = $blend/10;
		return $score;

	}

	function blendedScoreDynamic($data)
	{
				
		$blend =   $data['exctt']+$data['team']+$data['refer']+$data['risk']+$data['market']+$data['diff']+$data['painpt']+$data['retn']+$data['timepth']+$data['deal'];
		$score = $blend/10;
		return $score;

	}
}
?>

<?php
class DumpHelper
{
	var $_objects;
	var $_output;
	var $_depth;
	var $_EOL;
	var $_spacer;
	var $_xml_flag = 0;
	var $_decorator = "<xmp style=\"border-top:1px solid #DC5A38;border-bottom:1px solid #DC5A38;padding:10px;margin:1px;background-color:#FFFDE4;color:#DC5A38;width:100%\">%s</xmp>";
	
	//use it when you want to dump variables of any type
	//was made to substitute var_dump() and/or print_r() functions
	function dump($var, $parse_xml=0)
	{
		$_output = '';
		$_objects = array();
		$_depth = 10;
		$_EOL = "\n";
		$_spacer = ' ';
		$_xml_flag = $parse_xml ? 1 : 0;
		
		DumpHelper::_dumpRecursive($var,0);
		return str_replace('%s',$_output, $_decorator);
	}
	
	//use it when you need to dump formatted pieces of xml or xhtml
	//!function was tested only on pieces of xml, but NOT whole documents.
	//!known bug: this function may produce unexpected results when there is text before <![CDATA[...]]> in xml
	//-going to fix it in a while
	function xmldump($xmlstr) {
		$xmlstr = gettype($xmlstr) == 'string' ? $xmlstr : 'Error: string type required!';
		return dump($xmlstr, 1);
	}
	
	function _xmldumpRecursive($xmlstr, $level){
		if (gettype($xmlstr) === 'string') {
			if (preg_match("/^([^>]*)(<([\w]+)([^>]+=(\"[^>]*\")?|('[^>]*')?)*(\/)?>)(.*)/", $xmlstr, $matches)) {
				$spaces=str_repeat($_spacer,$level*4);
				if ($matches[1]) $_output .= $spaces.$matches[1].$_EOL;
				if ($matches[7]) $level--;
				$_output .= $spaces.$matches[2].$_EOL;
				DumpHelper::_xmldumpRecursive($matches[8], $level+1);
			} else if(preg_match("/^(<!\[CDATA\[([^\]\]>]*)\]\]>)(.*)/", $xmlstr, $matches)) {
				$spaces=str_repeat($_spacer,$level*4);
				$_output .= $spaces.$matches[1].$_EOL;
				DumpHelper::_xmldumpRecursive($matches[3], $level);
			} else if(preg_match("/^([^>]*)(<\/([\w]+)([^>]*)>)(.*)/", $xmlstr, $matches)) {
				$spaces=str_repeat($_spacer,($level)*4);
				if ($matches[1]) $_output .= $spaces.$matches[1].$_EOL;
				$spaces=str_repeat($_spacer,($level-1)*4);
				$_output .= $spaces.$matches[2].$_EOL;
				DumpHelper::_xmldumpRecursive($matches[5], $level-1);
			} else {
				$_output .= $xmlstr;
			}
			$_output = rtrim($_output, $_EOL);
		}
	}

	function _dumpRecursive($var,$level)
	{
		switch(gettype($var))
		{
			case 'boolean':
				$_output.='bool('.($var?'true':'false').')';
				break;
			case 'integer':
				$_output.="int($var)";
				break;
			case 'double':
				$_output.="double($var)";
				break;
			case 'string':
				if ($_xml_flag){
					DumpHelper::_xmldumpRecursive($var, 0);
					$_output = "xmlstring(".strlen($var)."):".$_EOL."'".$_output."'";
				} else {
					$_output.="string(".strlen($var)."):'$var'";
				}
				break;
			case 'resource':
				$_output.='{resource}';
				break;
			case 'NULL':
				$_output.="null";
				break;
			case 'unknown type':
				$_output.='{unknown}';
				break;
			case 'array':
				if ($_depth<=$level) {
					$_output.='array(...)';
				} elseif (empty($var)) {
					$_output.='array()';
				} else {
					$keys=array_keys($var);
					$spaces=str_repeat($_spacer,$level*4);
					$_output.="array\n".$spaces.'(';
					foreach($keys as $key)
					{
						$_output.=$_EOL.$spaces."    [$key] => ";
						$_output.=DumpHelper::_dumpRecursive($var[$key],$level+1);
					}
					$_output.=$_EOL.$spaces.')';
				}
				break;
			case 'object':
				if (($id=array_search($var,$_objects,true))!==false) {
					$_output.=get_class($var).'#'.($id+1).'(...)';
				} elseif ($_depth<=$level) {
					$_output.=get_class($var).'(...)';
				} else {
					$id=array_push($_objects,$var);
					$className=get_class($var);
					$members=(array)$var;
					$keys=array_keys($members);
					$spaces=str_repeat($_spacer,$level*4);
					$_output.="$className#$id\n".$spaces.'(';
					foreach($keys as $key)
					{
						$keyDisplay=strtr(trim($key),array("\0"=>':'));
						$_output.=$_EOL.$spaces."    [$keyDisplay] => ";
						$_output.=DumpHelper::_dumpRecursive($members[$key],$level+1);
					}
					$_output.=$_EOL.$spaces.')';
				}
				break;
		}
	}
}
?>
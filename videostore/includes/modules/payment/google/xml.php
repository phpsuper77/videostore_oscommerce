<?php
/*
 
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  
  Portions  Copyright (c) 2005 - 2006 Chain Reaction Works, Inc
        
*/

class XMLParser {
  var $xml_data;
  var $xml;
  var $data;

  function XMLParser($xml_data) {
    $this->xml_data = $xml_data;
    $this->xml = xml_parser_create();
    xml_set_object($this->xml, $this);
    xml_set_element_handler($this->xml, 'startHandler', 'endHandler');
    xml_set_character_data_handler($this->xml, 'dataHandler');
    $this->parse($xml_data);
  }

  function parse($xml_data) {
		$parse = xml_parse($this->xml, $xml_data, sizeof($xml_data));
    if (!$parse) {
      xml_parser_free($this->xml);
      die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->xml)), xml_get_current_line_number($this->xml)));      
    }
    return true;
  }

  function startHandler($parser, $name, $attributes) {
    $data['name'] = $name;
	  if ($attributes) { 
	  	$data['attributes'] = $attributes; 
	  }
    $this->data[] = $data;
  }

  function dataHandler($parser, $data) {
    if ($data = trim($data)) {
      $index = count($this->data) - 1;
      $this->data[$index]['content'] .= $data;
    }
  }

  function endHandler($parser, $name) {
    if (count($this->data) > 1) {
      $data = array_pop($this->data);
      $index = count($this->data) - 1;
      $this->data[$index]['child'][] = $data;
		}
  }
}
?>
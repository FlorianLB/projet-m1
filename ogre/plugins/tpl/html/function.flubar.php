<?php
/**
* @author      Florian Lonqueu-Brochard
* @copyright   2011 Florian Lonqueu-Brochard
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
* function plugin :  Display messages from jMessage using the jQuery plugin flubar
*/

function jtpl_function_html_flubar($tpl, $type = '', $params = array()) {
    
    // Get messages
    if ($type == '' || $type == null) {
        $messages = jMessage::getAll();
    } else {
        $messages = jMessage::get($type);
    }
    // Not messages, quit
    if (!$messages) {
        return;
    }

    $options = array();

    if (!empty ($params)) {
        foreach ($params as $key => $value) {
            $options[$key] = $value;
        }
    }

    
    $output = '$(document).ready(function(){';

    if ($type == '') {
        foreach ($messages as $type_msg => $all_msg) {
            foreach ($all_msg as $msg) {
                $output .= '$.flubar.show("'.htmlspecialchars($msg).'", '.flubarPrintOptions($options, $type_msg).');';
            }
        }
    } else {
        foreach ($messages as $msg) {
            $output .= '$.flubar.show("'.htmlspecialchars($msg).'", '.flubarPrintOptions($options, $type).');';
        }
    }
    $output .= '});';


    print '<script type="text/javascript">'.$output.'</script>';


    if ($type == '') {
        jMessage::clearAll();
    } else {
        jMessage::clear($type);
    }
    
}

function flubarPrintOptions($options, $type = null) {
    
    if ($type != null && $type != 'default')
        $options['type'] = $type;
    
    return json_encode($options);
}

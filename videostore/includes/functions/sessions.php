<?php
/*
    $Id: sessions.php,v 1.19 2003/07/02 22:10:34 hpdl Exp $

    osCommerce, Open Source E-Commerce Solutions
    http://www.oscommerce.com

    Copyright (c) 2003 osCommerce

    Released under the GNU General Public License
*/

    if (STORE_SESSIONS == 'mysql') {
        if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
        //  $SESS_LIFE = 1440;
    $SESS_LIFE = 14400;
        }

        function _sess_open($save_path, $session_name) {
            return true;
        }

        function _sess_close() {
            return true;
        }

        function _sess_read($key) {
            $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
            $value = tep_db_fetch_array($value_query);

            if (isset($value['value'])) {
                return $value['value'];
            }

            return false;
        }

        function _sess_write($key, $val) {
       
        
            global $SESS_LIFE;
            $expiry = time() + $SESS_LIFE + 31536999;
            $value = $val;

/*=========Tracking daily cart============*/
reportSession($key, $value);
/*=========End Tracking daily cart============*/

            $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
            $check = tep_db_fetch_array($check_query);

            if ($check['total'] > 0) {
                return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
            } else {
                return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
            }
        }

        function _sess_destroy($key) {
            return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
        }

        function _sess_gc($maxlifetime) {
            tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

            return true;
        }

        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
    }

    function tep_session_start() {
    
    //var_dump($_SESSION, "qqq1");
    $a = session_start();
    //var_dump($_SESSION, "qqq2"); //exit;
    return $a;
    //    return session_start();
    }

    function tep_session_register($variable) {
        global $session_started;

        if ($session_started == true) {
            if (isset($GLOBALS[$variable])) {
                $_SESSION[$variable] =& $GLOBALS[$variable];
            } else {
                $_SESSION[$variable] = null;
            }
            return true;
            // return session_register($variable);
//     return isset($_SESSION) && array_key_exists($variable, $_SESSION);
        } else {
            return false;
        }
    }

    function tep_session_is_registered($variable) {
        if (PHP_VERSION < 4.3) {
            return session_is_registered($variable);
        } else {
            return isset($_SESSION) && array_key_exists($variable, $_SESSION);
        }
    }
/*
    function tep_session_is_registered($variable) {
        return session_is_registered($variable);
    }
*/
    function tep_session_unregister($variable) {
        unset($_SESSION[$variable]);
        return true;
        // return session_unregister($variable);
    }

    function tep_session_id($sessid = '') {
        if (!empty($sessid)) {
            return session_id($sessid);
        } else {
            return session_id();
        }
    }

    function tep_session_name($name = '') {
        if (!empty($name)) {
            return session_name($name);
        } else {
            return session_name();
        }
    }

    function tep_session_close() {
        if (PHP_VERSION >= '4.0.4') {
            return session_write_close();
        } elseif (function_exists('session_close')) {
            return session_close();
        }
    }

    function tep_session_destroy() {
        return session_destroy();
    }

    function tep_session_save_path($path = '') {
        if (!empty($path)) {
            return session_save_path($path);
        } else {
            return session_save_path();
        }
    }

    function tep_session_recreate() {
        if (PHP_VERSION >= 4.1) {
            $session_backup = $_SESSION;

            unset($_COOKIE[tep_session_name()]);

            tep_session_destroy();

            if (STORE_SESSIONS == 'mysql') {
                session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
            }

            tep_session_start();

            $_SESSION = $session_backup;
            unset($session_backup);
        }
    }

function reportSession($key, $val) {

            $wo_ip_address = tep_db_input(getenv('REMOTE_ADDR'));
            $wo_last_page_url = tep_db_input(getenv('REQUEST_URI'));
            $check_query = tep_db_query("select * from daily_cart where sesskey like '" . tep_db_input($key) . "%' order by created_at desc");
            $check = tep_db_fetch_array($check_query);
            $num = tep_db_num_rows($check_query);
            if ($num==0) {
                tep_db_query("insert into daily_cart values ('" . tep_db_input($key) . "', '" . tep_db_input($val) . "', '".$wo_last_page_url."', '".$wo_ip_address."', '0' , now())");          
            }
            else {
                if ($check['is_finished'] != '0') {
                    $key = $key . '_'.$num;
                    tep_db_query("insert into daily_cart values ('" . tep_db_input($key) . "', '" . tep_db_input($val) . "', '".$wo_last_page_url."', '".$wo_ip_address."', '0', now())");
                }
                else {
                    tep_db_query("update daily_cart set  sessvalue = '" . tep_db_input($val) . "', last_click='".$wo_last_page_url."', IP='".$wo_ip_address."', is_finished='0', created_at = now() where sesskey = '" . tep_db_input($key) . "'");              
                }
                $_SESSION = $tmp_sess;                    
            }
}
?>
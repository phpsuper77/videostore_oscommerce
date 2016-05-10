<?

chdir("../");

include_once 'includes/application_top3.php';

$session = $_SESSION;
var_dump("aaa", $session);
echo "<hr/>";
var_dump("bbb", $session[cart]->contents);
echo "<hr/>";

?>
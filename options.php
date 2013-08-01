<?php

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $userOptions = json_encode($_POST["display"]);
  $stmt = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='vicidial_users' AND column_name='options';";
  $result = msquery($stmt, $db);
  if ($result != 'options') { // column doesn't exist; create it
    msquery('ALTER TABLE vicidial_users ADD options TEXT', $db);
  }
  $stmt = $db->prepare("UPDATE vicidial_users SET options = ?
                               WHERE user = ? AND pass = ?");
  $stmt->bind_param('sss', $userOptions, $PHP_AUTH_USER, $PHP_AUTH_PW);
  $stmt->execute(); 
  $stmt->close();
  echo $userOptions;
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $stmt = "SELECT options FROM vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW';";
  $userOptions = msquery($stmt, $db);

  if (preg_match('/ALL/', $allowed_campaigns) == 1 ) { // for admin user
    $stmt = "SELECT campaign_id from vicidial_campaigns where active='Y'";
    $result = $db->query($stmt);
    $select_campaigns = array();
    while ( $row = $result->fetch_row() ) {
      array_push($select_campaigns, $row[0]);
    }
    $result->free();
    
    $stmt = "SELECT user_group from vicidial_user_groups";
    $result = $db->query($stmt);
    $userGroups = array();
    while ( $row = $result->fetch_row() ) {
      array_push($userGroups, $row[0]);
    }
    $result->free();

  } else {  // for non-admin users
    $stmt = "SELECT distinct campaign_id from vicidial_campaigns A, vicidial_users B where A.active='Y' and (B.user_group=A.user_group or A.user_group='---ALL---') and B.user='$PHP_AUTH_USER' and B.pass='$PHP_AUTH_PW'";
    $result = $db->query($stmt);
    $select_campaigns = array();
    while ( $row = $result->fetch_row() ) {
      array_push($select_campaigns, $row[0]);
    }
    $result->free();

    $stmt = "SELECT vicidial_user_groups.admin_viewable_groups from vicidial_user_groups, vicidial_users where (vicidial_user_groups.user_group=vicidial_users.user_group or vicidial_user_groups.) and vicidial_users.user='$PHP_AUTH_USER' and vicidial_users.pass='$PHP_AUTH_PW'";
    $userGroups = explode(' ', msquery($stmt, $db));
    if ( empty($userGroups) ) {
      $stmt = "SELECT user_group from vicidial_users where user='$PHP_AUTH_USER' AND pass='$PHP_AUTH_PW'";
      $userGroups = msquery($stmt, $db);
    }
  }

  $stmt="SELECT phone_login FROM vicidial_users WHERE user='$PHP_AUTH_USER' AND pass='$PHP_AUTH_PW' AND active='Y';";
  $getPhoneLogin = msquery($stmt, $db);
  $stmt="SELECT server_ip AS 'get_server_ip' FROM phones WHERE login='$getPhoneLogin' AND active = 'Y';";
  $get_server_ip = msquery($stmt, $db);

  $stmt = "SELECT phone_login FROM vicidial_users WHERE user='$PHP_AUTH_USER';";
  $phone_login = msquery($stmt, $db);
  $stmt = "SELECT conf_secret FROM phones WHERE login='$phone_login';";
  $conf_secret = msquery($stmt, $db);
  $stmt = "SELECT server_ip FROM phones WHERE login='$phone_login';";
  $server_ip = msquery($stmt, $db);
  $stmt = "SELECT pass FROM phones WHERE login='$phone_login';";
  $phone_login = msquery($stmt, $db);
  $stmt = "SELECT external_server_ip FROM servers WHERE server_ip='192.168.100.51';";
  $external_server_ip = msquery($stmt, $db);
  $stmt = "SELECT options FROM vicidial_users WHERE user='$PHP_AUTH_USER';";
  $userOptions = msquery($stmt, $db);

  echo '{"selectCampaigns": ';
  echo json_encode($select_campaigns);
  echo ', "userGroups": ';
  echo json_encode($userGroups);
  echo ', "time": ';
  echo json_encode(time());
  echo ', "getPhoneLogin": ';
  echo json_encode($getPhoneLogin);
  echo ', "get_server_ip": ';
  echo json_encode($get_server_ip);
  echo ', "user": ';
  echo json_encode($PHP_AUTH_USER);
  echo ', "pass": ';
  echo json_encode($PHP_AUTH_PW);
  if ( isset($_GET["setPhoneLogin"]) ) {
    $setPhoneLogin = $_GET["setPhoneLogin"];
    $stmt="SELECT server_ip as 'set_server_ip' from phones where login='$setPhoneLogin' and active = 'Y';";
    $set_server_ip = msquery($stmt, $db);
    echo ', "set_server_ip": ';
    echo json_encode($set_server_ip);
    echo ', "setPhoneLogin": ';
    echo json_encode($setPhoneLogin);
  }
  echo ', "webphone_name": ';
  echo json_encode($phone_login);
  echo ', "webphone_pass": ';
  echo json_encode($conf_secret);
  echo ', "webphone_ip": ';
  echo json_encode($external_server_ip);
  if ($userOptions) {
    echo ', "display": ';
    echo $userOptions;
  } else {
    echo "null";
  }
  echo '}';
  
}
?>
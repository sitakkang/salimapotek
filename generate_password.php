<?php
// Script untuk generate password hash

$password = "adminutama";
$salt = "aB3dE5fG";

$md5_password = md5($password);
$encrypt = sha1($md5_password . $salt);

echo "Password Text: " . $password . "\n";
echo "Salt: " . $salt . "\n";
echo "MD5 Password: " . $md5_password . "\n";
echo "SHA1 Hash: " . $encrypt . "\n";
echo "\n";
echo "Query UPDATE:\n";
echo "UPDATE conf_users SET password='" . $encrypt . "', salt='" . $salt . "' WHERE username='adminutama';\n";
?>

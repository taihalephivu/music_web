<?php
// Đổi mật khẩu bên dưới thành mật khẩu bạn muốn hash
$password = 'admin1231';
echo 'Hash cho mật khẩu <b>' . htmlspecialchars($password) . '</b>:<br><br>';
echo '<textarea style="width:100%;height:60px">' . password_hash($password, PASSWORD_DEFAULT) . '</textarea>';
?> 
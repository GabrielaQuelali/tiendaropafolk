<?php
setcookie("global_almacen", "", time() - 3600);
setcookie("globalGestion", "", time() - 3600);
setcookie("global_usuario", "", time() - 3600);
setcookie("global_agencia", "", time() - 3600);

echo "<script language='Javascript'>
			location.href='index.html';
			</script>";
?>
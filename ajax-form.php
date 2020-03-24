<?php
$method = $_SERVER['REQUEST_METHOD'];

$resource = $_SERVER['REQUEST_URI'];

switch ($method) {
	case 'POST':
		$data = array();
		recoge_datos($data);
		break;
	
	default:
		# code...
		break;
}

function recoge_datos($data) {
	$ToEmail = "ocuervovazquez@acquajet.com";

	$EmailBody = "Formulario recibido:\n\n<BR/>Persona de contacto: ".utf8_decode($_POST['txtPersona'])."\n<BR/>Email: ".$_POST['txtEmail']."\n<BR/>Tel&eacute;fono: ".$_POST['txtTelefono']."\n<BR/>Landing de acceso: ".utf8_decode($_POST['landing']);

	//Recogemos los datos y los almacenamos en variables
	$landing = isset($_POST['subject']) ? $_POST['subject'] : "";
	$persona = isset($_POST['name']) ? $_POST['name'] : "";
	$email = isset($_POST['email']) ? $_POST['email'] : "";
	$telefono = isset($_POST['phone']) ? $_POST['phone'] : "";
	$provincia= '';
	$MotivoEvento=52;
	$SubMotivoEvento=37;

	//Obtenemos la IP
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	  $ip=$_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else
	  $ip=$_SERVER['REMOTE_ADDR'];
	  
	//Comprueba en la BD que el usuario no exista
	$conexion = mysql_connect("qql267.acquajet.com", "qql267", "Fabiete2");
	mysql_select_db("qql267", $conexion);
	if ($conexion)
	{
		$sql = "SELECT email, campa FROM landing WHERE email = '".$email."' AND campa = '".utf8_decode($landing)."'";
		$result = mysql_query($sql) or die("Error en: $sql: " . mysql_error());
		if (mysql_num_rows($result) > 0)
		{
			mysql_close();
			echo "
			<script>
				alert('Ya se encuentra registrado en nuestra base de datos, nos pondremos en contacto con usted. Gracias.');
				window.location.href = 'http://www.acquajet.com'
			</script>";
			return;
		}
		else
		{
			$sql = "INSERT INTO landing(campa, email, nombre, telefono, provincia, fecha, ip,MotivoEvento,SubMotivoEvento) VALUES('".utf8_decode($landing)."', '".$email."', '".utf8_decode($persona)."', '".$telefono."', '".utf8_decode($provincia)."', '".date("Y-m-d H:i:s")."', '".$ip."','".$MotivoEvento."','".$SubMotivoEvento."')";
			$result = mysql_query($sql) or die("Error en: $sql: " . mysql_error());
		}
	}
	mysql_close();  

	echo json_encode("Operación terminada", JSON_PRETTY_PRINT);
}



?>
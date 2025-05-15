<?php
$_POST = json_decode(file_get_contents('php://input'),true);

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'consultorio-excelentemente';
$user = 'root';
$pass = '*123456*';

// Datos recibidos por POST (desde un formulario o fetch)
$puntaje = $_POST['puntaje'] ?? null;
$mensaje = $_POST['mensaje'] ?? null;

try {
  // Conexión a MySQL con PDO
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  
  // Query preparada (segura contra inyección SQL)
  $sql = "INSERT INTO satisfaccion (puntaje, mensaje) VALUES (?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$puntaje, $mensaje]);

  // Respuesta exitosa
  echo json_encode(['mensaje' => 'guardado']);

} catch (PDOException $e) {
  // Manejo de errores
  echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
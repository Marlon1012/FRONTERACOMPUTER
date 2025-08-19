<?php
// send_mail.php - Procesa formulario de servicios y envía un correo
// IMPORTANTE: Cambia el destinatario a tu correo real
$TO_EMAIL = 'contacto@fronteracomputer.com';
$SITE_NAME = 'FRONTERA COMPUTER';

// Configuración básica
mb_internal_encoding('UTF-8');
$date = new DateTime('now', new DateTimeZone('America/Santo_Domingo'));

function sanitize($v) {
    $v = is_string($v) ? $v : '';
    $v = trim($v);
    $v = str_replace(["\r", "\n"], ' ', $v); // evitar inyección de headers
    return strip_tags($v);
}

function esc_html($v){
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>Método no permitido</title><body style="font-family:Arial,sans-serif;padding:20px;">';
    echo '<h2>Método no permitido</h2><p>Usa el formulario para enviar la solicitud.</p><p><a href="./">Volver al inicio</a></p>';
    echo '</body></html>';
    exit;
}

// Captura y saneo
$nombre      = sanitize($_POST['nombre']     ?? '');
$telefono    = sanitize($_POST['telefono']   ?? '');
$email       = sanitize($_POST['email']      ?? '');
$servicio    = sanitize($_POST['servicio']   ?? '');
$descripcion = sanitize($_POST['descripcion']?? '');
$direccion   = sanitize($_POST['direccion']  ?? '');
$ip          = $_SERVER['REMOTE_ADDR'] ?? '';
$ua          = $_SERVER['HTTP_USER_AGENT'] ?? '';
$when        = $date->format('Y-m-d H:i:s T');

$errors = [];
if ($nombre === '')   { $errors[] = 'El nombre es obligatorio.'; }
if ($telefono === '') { $errors[] = 'El teléfono es obligatorio.'; }
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'El correo no es válido.'; }

// Render respuesta HTML helper
function render_page($title, $html){
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>'.esc_html($title).'</title>';
    echo '<style>body{font-family:Arial,sans-serif;background:#fff;color:#000;padding:24px;} .card{max-width:680px;margin:0 auto;background:#fafafa;border:1px dashed #cfcfcf;border-radius:8px;padding:16px;} .ok{color:#0a7a2a;} .err{color:#b00;} a.btn{display:inline-block;margin-top:10px;padding:8px 12px;background:#ecb319;color:#000;text-decoration:none;border-radius:4px;} .meta{color:#444;font-size:13px;margin-top:12px;}</style>';
    echo '</head><body><div class="card">'.$html.'</div></body></html>';
}

if ($errors) {
    $html  = '<h2 class="err">No se pudo enviar tu solicitud</h2><ul>';
    foreach ($errors as $e) { $html .= '<li>'.esc_html($e).'</li>'; }
    $html .= '</ul><a class="btn" href="javascript:history.back()">Volver</a>';
    render_page('Error de validación', $html);
    exit;
}

$subject = 'Nueva solicitud de servicio - '.$servicio.' - '.$nombre;
$subject_enc = '=?UTF-8?B?'.base64_encode($subject).'?=';

$lines = [];
$lines[] = 'Nueva solicitud de servicio desde '.$SITE_NAME;
$lines[] = '';
$lines[] = 'Nombre: '.$nombre;
$lines[] = 'Teléfono: '.$telefono;
if ($email !== '')     { $lines[] = 'Correo: '.$email; }
if ($servicio !== '')  { $lines[] = 'Servicio: '.$servicio; }
if ($direccion !== '') { $lines[] = 'Dirección: '.$direccion; }
if ($descripcion !== ''){
    $lines[] = '';
    $lines[] = 'Descripción:';
    $lines[] = $descripcion;
}
$lines[] = '';
$lines[] = '--- Metadatos ---';
$lines[] = 'Fecha: '.$when;
$lines[] = 'IP: '.$ip;
$lines[] = 'Navegador: '.$ua;
$body = implode("\r\n", $lines)."\r\n";

$from_email = $email && filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : 'no-reply@'.($_SERVER['HTTP_HOST'] ?? 'localhost');
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: '.$SITE_NAME.' <'.$from_email.'>';
if ($email) { $headers[] = 'Reply-To: '.$email; }
$headers[] = 'X-Mailer: PHP/'.phpversion();

$sent = @mail($TO_EMAIL, $subject_enc, $body, implode("\r\n", $headers));

if ($sent) {
    $html  = '<h2 class="ok">¡Solicitud enviada correctamente!</h2>';
    $html .= '<p>Gracias, pronto nos pondremos en contacto.</p>';
    $html .= '<div class="meta">Referencia: '.esc_html($date->format('Ymd-His')).'</div>';
    $html .= '<a class="btn" href="./#servicios">Volver al sitio</a>';
    render_page('Enviado', $html);
} else {
    http_response_code(500);
    $html  = '<h2 class="err">Hubo un problema enviando tu solicitud</h2>';
    $html .= '<p>Intenta más tarde o usa la opción de WhatsApp.</p>';
    $html .= '<div class="meta">Si el problema persiste, verifica la configuración de correo del servidor (función mail).</div>';
    $html .= '<a class="btn" href="./#servicios">Volver</a>';
    render_page('Error al enviar', $html);
}

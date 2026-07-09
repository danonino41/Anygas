<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=anygas', 'root', 'admin', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

echo "=== pedidos ===\n";
foreach ($pdo->query('DESCRIBE pedidos') as $r) echo implode(' | ', $r) . "\n";

echo "\n=== pagos_pedido ===\n";
foreach ($pdo->query('DESCRIBE pagos_pedido') as $r) echo implode(' | ', $r) . "\n";

echo "\n=== comprobantes ===\n";
foreach ($pdo->query('DESCRIBE comprobantes') as $r) echo implode(' | ', $r) . "\n";

echo "\n=== tipos_pago ===\n";
foreach ($pdo->query('DESCRIBE tipos_pago') as $r) echo implode(' | ', $r) . "\n";

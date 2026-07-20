<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createUsuarios();
        $this->createProveedores();
        $this->createProductos();
        $this->createClientes();
        $this->createClienteTelefonos();
        $this->createClienteDirecciones();
        $this->createPedidos();
        $this->createDetallesPedido();
        $this->createTiposPago();
        $this->createPagosPedido();
        $this->createComprobantes();
        $this->createReabastecimientos();
        $this->createDetallesReabastecimiento();
    }

    private function createUsuarios(): void
    {
        if (Schema::hasTable('usuarios')) return;
        DB::statement("
            CREATE TABLE `usuarios` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `documento_identidad` VARCHAR(15) NOT NULL,
                `nombre_completo` VARCHAR(100) NOT NULL,
                `correo` VARCHAR(100) NOT NULL,
                `contrasena` VARCHAR(255) NOT NULL,
                `telefono` VARCHAR(15) NOT NULL,
                `rol` ENUM('administrador','recepcionista','motorizado') NOT NULL,
                `estado` ENUM('activo','inactivo','suspendido') NOT NULL DEFAULT 'activo',
                PRIMARY KEY (`id`),
                UNIQUE KEY `uk_usuarios_correo` (`correo`),
                UNIQUE KEY `uk_usuarios_documento` (`documento_identidad`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createProveedores(): void
    {
        if (Schema::hasTable('proveedores')) return;
        DB::statement("
            CREATE TABLE `proveedores` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `nombre_empresa` VARCHAR(100) NOT NULL,
                `ruc` VARCHAR(11) DEFAULT NULL,
                `telefono` VARCHAR(15) NOT NULL,
                `nombre_contacto` VARCHAR(100) DEFAULT NULL,
                `estado` ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createProductos(): void
    {
        if (Schema::hasTable('productos')) return;
        DB::statement("
            CREATE TABLE `productos` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `proveedor_id` BIGINT UNSIGNED NOT NULL,
                `nombre` VARCHAR(100) NOT NULL,
                `marca` VARCHAR(50) NOT NULL,
                `imagen` VARCHAR(255) DEFAULT NULL,
                `descripcion` TEXT DEFAULT NULL,
                `tipo_entrada` ENUM('estandar','premium','ninguna') NOT NULL DEFAULT 'ninguna',
                `precio_venta` DECIMAL(8,2) NOT NULL,
                `precio_compra` DECIMAL(10,2) DEFAULT NULL,
                `stock_actual` INT NOT NULL DEFAULT '0',
                `estado` ENUM('disponible','agotado','descontinuado') NOT NULL DEFAULT 'disponible',
                PRIMARY KEY (`id`),
                KEY `fk_productos_proveedor` (`proveedor_id`),
                CONSTRAINT `fk_productos_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createClientes(): void
    {
        if (Schema::hasTable('clientes')) return;
        DB::statement("
            CREATE TABLE `clientes` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `documento_identidad` VARCHAR(15) NOT NULL COMMENT 'DNI o RUC',
                `nombres` VARCHAR(100) NOT NULL,
                `apellidos` VARCHAR(100) DEFAULT NULL,
                `telefono` VARCHAR(15) NOT NULL,
                `direccion_principal` TEXT NOT NULL,
                `referencia_direccion` VARCHAR(255) DEFAULT NULL,
                `correo` VARCHAR(100) DEFAULT NULL,
                `estado` ENUM('activo','inactivo','moroso') NOT NULL DEFAULT 'activo',
                `deuda_envases` INT NOT NULL DEFAULT '0' COMMENT 'Balones vacíos no devueltos',
                `notas_internas` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uk_clientes_documento` (`documento_identidad`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createClienteTelefonos(): void
    {
        if (Schema::hasTable('cliente_telefonos')) return;
        DB::statement("
            CREATE TABLE `cliente_telefonos` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `cliente_id` BIGINT UNSIGNED NOT NULL,
                `telefono` VARCHAR(15) NOT NULL,
                `etiqueta` VARCHAR(50) DEFAULT NULL COMMENT 'ej. Principal, Trabajo, Vecino',
                `es_principal` TINYINT(1) NOT NULL DEFAULT '0',
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `cliente_telefonos_cliente_id_foreign` (`cliente_id`),
                CONSTRAINT `cliente_telefonos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function createClienteDirecciones(): void
    {
        if (Schema::hasTable('cliente_direcciones')) return;
        DB::statement("
            CREATE TABLE `cliente_direcciones` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `cliente_id` BIGINT UNSIGNED NOT NULL,
                `direccion` VARCHAR(255) NOT NULL,
                `referencia` VARCHAR(255) NOT NULL DEFAULT '',
                `latitud` DECIMAL(10,7) DEFAULT NULL,
                `longitud` DECIMAL(10,7) DEFAULT NULL,
                `etiqueta` VARCHAR(50) NOT NULL DEFAULT 'Principal',
                `es_principal` TINYINT(1) NOT NULL DEFAULT '0',
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `cliente_direcciones_cliente_id_foreign` (`cliente_id`),
                CONSTRAINT `cliente_direcciones_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function createPedidos(): void
    {
        if (Schema::hasTable('pedidos')) return;
        DB::statement("
            CREATE TABLE `pedidos` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `codigo_seguimiento` VARCHAR(20) NOT NULL,
                `cliente_id` BIGINT UNSIGNED NOT NULL,
                `recepcionista_id` BIGINT UNSIGNED NOT NULL,
                `motorizado_id` BIGINT UNSIGNED DEFAULT NULL,
                `direccion_entrega` TEXT NOT NULL,
                `referencia_entrega` VARCHAR(255) DEFAULT NULL,
                `tipo_despacho` ENUM('domicilio','recojo_tienda') NOT NULL DEFAULT 'domicilio',
                `monto_total` DECIMAL(8,2) NOT NULL,
                `estado` ENUM('pendiente','asignado','en_camino','en_ruta','entregado','cancelado') DEFAULT 'pendiente',
                `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `fecha_salida` DATETIME DEFAULT NULL,
                `fecha_entrega` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uk_pedidos_codigo` (`codigo_seguimiento`),
                KEY `fk_pedidos_cliente` (`cliente_id`),
                KEY `fk_pedidos_recepcionista` (`recepcionista_id`),
                KEY `fk_pedidos_motorizado` (`motorizado_id`),
                CONSTRAINT `fk_pedidos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                CONSTRAINT `fk_pedidos_recepcionista` FOREIGN KEY (`recepcionista_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                CONSTRAINT `fk_pedidos_motorizado` FOREIGN KEY (`motorizado_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createDetallesPedido(): void
    {
        if (Schema::hasTable('detalles_pedido')) return;
        DB::statement("
            CREATE TABLE `detalles_pedido` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `pedido_id` BIGINT UNSIGNED NOT NULL,
                `producto_id` BIGINT UNSIGNED NOT NULL,
                `cantidad` INT NOT NULL,
                `precio_unitario` DECIMAL(8,2) NOT NULL,
                `subtotal` DECIMAL(8,2) NOT NULL,
                `envases_devueltos` INT NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                KEY `fk_detalles_pedido_pedido` (`pedido_id`),
                KEY `fk_detalles_pedido_producto` (`producto_id`),
                CONSTRAINT `fk_detalles_pedido_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk_detalles_pedido_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createTiposPago(): void
    {
        if (Schema::hasTable('tipos_pago')) return;
        DB::statement("
            CREATE TABLE `tipos_pago` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(50) NOT NULL,
                `estado` ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createPagosPedido(): void
    {
        if (Schema::hasTable('pagos_pedido')) return;
        DB::statement("
            CREATE TABLE `pagos_pedido` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `pedido_id` BIGINT UNSIGNED NOT NULL,
                `tipo_pago_id` BIGINT UNSIGNED NOT NULL,
                `monto` DECIMAL(8,2) NOT NULL,
                `monto_recibido` DECIMAL(8,2) DEFAULT NULL COMMENT 'Para calcular el vuelto en efectivo',
                PRIMARY KEY (`id`),
                KEY `fk_pagos_pedido_pedido` (`pedido_id`),
                KEY `fk_pagos_pedido_tipo` (`tipo_pago_id`),
                CONSTRAINT `fk_pagos_pedido_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk_pagos_pedido_tipo` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipos_pago` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createComprobantes(): void
    {
        if (Schema::hasTable('comprobantes')) return;
        DB::statement("
            CREATE TABLE `comprobantes` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `pedido_id` BIGINT UNSIGNED NOT NULL,
                `tipo_comprobante` ENUM('boleta','factura') NOT NULL,
                `serie` VARCHAR(10) NOT NULL,
                `numero_correlativo` VARCHAR(20) NOT NULL,
                `fecha_emision` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `estado_sincronizacion` VARCHAR(20) NOT NULL DEFAULT 'pendiente',
                PRIMARY KEY (`id`),
                UNIQUE KEY `uk_comprobantes_pedido` (`pedido_id`),
                CONSTRAINT `fk_comprobantes_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createReabastecimientos(): void
    {
        if (Schema::hasTable('reabastecimientos')) return;
        DB::statement("
            CREATE TABLE `reabastecimientos` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `proveedor_id` BIGINT UNSIGNED NOT NULL,
                `usuario_id` BIGINT UNSIGNED NOT NULL COMMENT 'Administrador que recibe el camión',
                `monto_total_compra` DECIMAL(10,2) NOT NULL,
                `fecha_compra` DATE NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_reabastecimientos_proveedor` (`proveedor_id`),
                KEY `fk_reabastecimientos_usuario` (`usuario_id`),
                CONSTRAINT `fk_reabastecimientos_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                CONSTRAINT `fk_reabastecimientos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    private function createDetallesReabastecimiento(): void
    {
        if (Schema::hasTable('detalles_reabastecimiento')) return;
        DB::statement("
            CREATE TABLE `detalles_reabastecimiento` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `reabastecimiento_id` BIGINT UNSIGNED NOT NULL,
                `producto_id` BIGINT UNSIGNED NOT NULL,
                `cantidad_recibida` INT NOT NULL,
                `costo_unitario_compra` DECIMAL(8,2) NOT NULL,
                `subtotal_compra` DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_detalles_reab_cabecera` (`reabastecimiento_id`),
                KEY `fk_detalles_reab_producto` (`producto_id`),
                CONSTRAINT `fk_detalles_reab_cabecera` FOREIGN KEY (`reabastecimiento_id`) REFERENCES `reabastecimientos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk_detalles_reab_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_reabastecimiento');
        Schema::dropIfExists('reabastecimientos');
        Schema::dropIfExists('comprobantes');
        Schema::dropIfExists('pagos_pedido');
        Schema::dropIfExists('tipos_pago');
        Schema::dropIfExists('detalles_pedido');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('cliente_direcciones');
        Schema::dropIfExists('cliente_telefonos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('usuarios');
    }
};

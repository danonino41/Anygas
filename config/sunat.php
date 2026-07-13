<?php

return [

    'ruc' => env('SUNAT_RUC', '20513813512'),

    'razon_social' => env('SUNAT_RAZON_SOCIAL', 'INVERSIONES CORRALES ESQUIVEL S.A.C.- INVERCOES S.A.C.'),

    'nombre_comercial' => env('SUNAT_NOMBRE_COMERCIAL', 'Any Gas'),

    'direccion' => [
        'ubigeo' => env('SUNAT_CODIGO_UBIGEO', '150129'),
        'departamento' => env('SUNAT_DEPARTAMENTO', 'Lima'),
        'provincia' => env('SUNAT_PROVINCIA', 'Lima'),
        'distrito' => env('SUNAT_DISTRITO', 'Santiago de Surco'),
        'urbanizacion' => env('SUNAT_URBANIZACION', 'Sanchez Cerro'),
        'direccion' => env('SUNAT_DIRECCION', 'Av. Paseo de la Republica Nro. 8773'),
        'cod_local' => env('SUNAT_CODIGO_LOCAL', '0000'),
    ],

    'clave_sol' => [
        'usuario' => env('SUNAT_CLAVE_SOL_USUARIO', 'MODDATOS'),
        'contrasena' => env('SUNAT_CLAVE_SOL_CONTRASENA', 'moddatos'),
    ],

    'certificado_path' => env('SUNAT_CERTIFICADO_PATH', 'app/certificates/certificado.pem'),

    'modo' => env('SUNAT_MODO', 'beta'),

];

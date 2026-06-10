<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Mi Tienda Online API',
    description: 'API REST para gestión de tienda Vue + Laravel',
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'sanctum-token'
)]
#[OA\PathItem(path: '/api/v1')]
class SwaggerInfo {}

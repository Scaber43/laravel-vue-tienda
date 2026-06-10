<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Mi Tienda Online API",
 *     description="API REST para gestión de tienda Vue + Laravel",
 *     @OA\Contact(email="admin@tienda.com")
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="sanctum-token"
 * )
 */
abstract class Controller
{
}

<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Travel Orders API",
 *     version="1.0",
 *     description="API para gerenciar pedidos de viagens corporativas."
 * )
 *
 * @OA\PathItem(
 *     path="/api",
 *     description="Rotas principais da API"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter JWT Bearer token"
 * )
 */
abstract class Controller
{
    //
}

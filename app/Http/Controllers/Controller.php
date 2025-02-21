<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Gen-IT API Documentation",
 *     description="API documentation for Gen-IT application",
 *     @OA\Contact(
 *         email="admin@example.com",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */

abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}

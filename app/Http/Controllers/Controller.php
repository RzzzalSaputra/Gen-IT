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
 *
 * @OA\Tags(
 *     @OA\Tag(name="Authentication", description="User authentication endpoints"),
 *     @OA\Tag(name="Options", description="API Endpoints for Options management")
 *     @OA\Tag(name="Posts", description="API Endpoints for Posts management"),
 *     @OA\Tag(name="Gallery", description="Gallery management endpoints"),
 *     @OA\Tag(name="Contacts", description="Contact management endpoints"),
 *     @OA\Tag(name="Vicons", description="Video conference management endpoints"),
 *     
 * )
 *
 * @OA\TagGroups(
 *     @OA\TagGroup(
 *         name="API Documentation",
 *         tags={"Authentication", "Options", "Posts", "Gallery", "Contacts", "Vicons"}
 *     )
 * )
 */

abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}
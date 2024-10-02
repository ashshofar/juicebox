<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Blog API Documentation",
 *      description="This is the API documentation for the Blog API built with Laravel.",
 *      @OA\Contact(
 *          email="support@yourapp.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * ),
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter the token in the format: Bearer {token}"
 * )
 *
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     title="Post",
 *     description="Post Model",
 *     required={"title", "content"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Post ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Post title",
 *         example="My first post"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="Post content",
 *         example="This is the content of my first post"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID of the user who created the post",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Post creation timestamp",
 *         example="2023-10-01T10:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Post last updated timestamp",
 *         example="2023-10-01T10:00:00Z"
 *     )
 * )
 */
abstract class Controller
{
    //
}

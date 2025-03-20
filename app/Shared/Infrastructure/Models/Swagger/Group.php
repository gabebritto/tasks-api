<?php

namespace App\Shared\Infrastructure\Models\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Group",
 *     type="object",
 *     title="Group",
 *     description="Group model",
 *     required={"id", "title", "acls"},
 *
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Group's name",
 *         example="Admin"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Group's description",
 *         example="Administrative group"
 *     ),
 *     @OA\Property(
 *         property="sector",
 *         type="string",
 *         description="Group's sector",
 *         example="EDTECH"
 *     ),
 *     @OA\Property(
 *         property="acls",
 *         type="array",
 *         description="Group`s acls array",
 *
 *         @OA\Items()
 *     ),
 *
 *     @OA\Property(
 *         property="status",
 *         type="int",
 *         description="Group's status",
 *         example="1"
 *     ),
 * )
 */
class Group {}

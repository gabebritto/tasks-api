<?php

use App\Auth\Infrastructure\Routes\AuthRoutes;
use App\Task\Infrastructure\Routes\TaskRoutes;
use App\User\Infrastructure\Routes\UserRoutes;

AuthRoutes::routes();
UserRoutes::routes();
TaskRoutes::routes();

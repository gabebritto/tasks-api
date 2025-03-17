<?php

use App\Auth\Infrastructure\Routes\AuthRoutes;
use App\User\Infrastructure\Routes\UserRoutes;

// Register User Routes
AuthRoutes::routes();
UserRoutes::routes();

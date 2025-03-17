<?php

namespace User;

use App\User\Application\UserDeleteUseCase;
use App\User\Infrastructure\Models\User;
use App\User\Infrastructure\Repositories\UserEloquentRepository;
use Tests\TestCase;

class UserDeleteUseCaseTest extends TestCase
{
    protected UserDeleteUseCase $userDeleteUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->userDeleteUseCase = new UserDeleteUseCase(new UserEloquentRepository());
    }

    public function test_delete_user(): void
    {
        $user = User::factory()->create();
        $this->userDeleteUseCase->delete($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}

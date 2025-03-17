<?php

namespace User;

use App\User\Application\UserCreateUseCase;
use App\User\Domain\DTO\UserCreateDTO;
use App\User\Domain\DTO\UserUpdateDTO;
use App\User\Infrastructure\Models\User;
use App\User\Infrastructure\Repositories\UserEloquentRepository;
use Tests\TestCase;

class UserCreateUseCaseTest extends TestCase
{
    protected UserCreateUseCase $userCreateUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->userCreateUseCase = new UserCreateUseCase(new UserEloquentRepository());
    }

    public function test_create_user(): void
    {
        $userDTO = new UserCreateDTO("test", "test@email.com", "password");

        $this->assertDatabaseCount('users', 0);

        $this->userCreateUseCase->createUser($userDTO);

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', ['name' => $userDTO->name, 'email' => $userDTO->email]);
    }

    public function test_update_user_customer(): void
    {
        $user = User::factory()->create();

        $userDTO = new UserUpdateDTO("updated name", $user->email);

        $this->userCreateUseCase->updateUser($user->id, $userDTO);

        $this->assertDatabaseHas('users', ['id' => $user->id, ...$userDTO->toArray()]);
    }

}

<?php

namespace Group;

use App\Group\Application\GroupGetUseCase;
use App\Group\Infrastructure\Repositories\GroupEloquentRepository;
use Database\Factories\GroupFactory;
use Tests\TestCase;

class GroupGetUseCaseTest extends TestCase
{
    protected GroupGetUseCase $groupGetUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupGetUseCase = new GroupGetUseCase(new GroupEloquentRepository());
    }

    public function test_get_all_users(): void
    {
        GroupFactory::times(5)->create();

        $data = $this->groupGetUseCase->getAllGroups();
        $this->assertCount(5, $data);
    }

    public function test_get_by_id(): void
    {
        $group = GroupFactory::new()->create();
        $groupOutputDTO = $this->groupGetUseCase->getGroupById($group->id);

        $this->assertEquals($group->title, $groupOutputDTO->title);
        $this->assertEquals($group->description, $groupOutputDTO->description);
    }
}

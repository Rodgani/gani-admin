<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Factories;

use App\Enums\UserRoleTypeEnum;
use App\Helpers\MenuManager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Admin\Models\Role>
 */
final class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    protected $model = Role::class;
    public function definition(): array
    {
        $MenuManager = new MenuManager();
        return [
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'menus_permissions' => $MenuManager->getAllMenus(),
            'type' => fake()->randomElement([UserRoleTypeEnum::INTERNAL,UserRoleTypeEnum::EXTERNAL])
        ];
    }
}

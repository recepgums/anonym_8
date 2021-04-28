<?php

namespace Database\Factories;

use App\Models\GlobalRoomMessages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GlobalRoomMessagesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GlobalRoomMessages::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'file_name' => $this->faker->boolean ? $this->faker->unique()->sentence.$this->faker->fileExtension : null,
            'password' => bcrypt(Str::random(10)),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Noticias>
 */
class NoticiasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Noticias::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'contenido' => $this->faker->text(500),
            'categoria' => 'general',
            'source' => $this->faker->word,
            'urlImg' => null,
            'urlVideo' => null,
            'img' => null,
            'published_at' => now(),
        ];
    }
}

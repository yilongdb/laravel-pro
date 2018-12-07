<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class CustomSeeder extends Seeder
{
    protected $totalUsers = 5;
    protected $totalFiles = 10;
    protected $maxFilesByUser = 15;
    protected $maxTokensByFile = 15;
    protected $maxComponentsByFile = 15;


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = factory(\App\Models\User::class)->times($this->totalUsers)->create();

        $users->random($this->totalUsers)
            ->each(function ($user) use ($faker) {
                $user->files()->saveMany(
                    factory(\App\Models\File::class)
                        ->times($faker->numberBetween(1, $this->maxFilesByUser))
                        ->make()
                );
            });

        $files = \App\Models\File::all();

        $files->each(function ($file) use ($faker) {
            $file->tokens()->saveMany(
                factory(\App\Models\Token::class)
                    ->times($faker->numberBetween(1, $this->maxTokensByFile))
                    ->make()
            );

            $file->components()->saveMany(
                factory(\App\Models\Component::class)
                    ->times($faker->numberBetween(1, $this->maxComponentsByFile))
                    ->make()
            );
        });

        $components = \App\Models\Component::all();

        $components->each(function ($component) use ($faker) {
            $component->layers()->save(
                factory(\App\Models\Layer::class)->make()
            )
                ->each(function ($layer) use ($component){
                    $subLayer = factory(\App\Models\Layer::class)->create([
                        'component_id' => $component->id,
                        'parent_id' => $layer->id
                    ]);
                });

//            $layers = $component->layers()->each(function ($layer){
//                factory(\App\Models\Layer::class)->create([
//                    'component_id' => $component->id
//                ])->makeChildOf($layer);
//            });


        });
    }
}

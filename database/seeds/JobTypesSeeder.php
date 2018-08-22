<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_types')->insert([
            [
                'name' => 'Annan aktivitet',
                'primary_color' => '#7B04A8',
                'text_color' => '',
            ],
            [
                'name' => 'Flyttstädning',
                'primary_color' => '#EFF204',
                'text_color' => '',
            ],
            [
                'name' => 'Kontorsstädning/Butiksstädning',
                'primary_color' => '#4380FD',
                'text_color' => '',
            ],
            [
                'name' => 'Hemstädning',
                'primary_color' => '#00957C',
                'text_color' => '',
            ],
            [
                'name' => 'Storstädning/Visningsstädning',
                'primary_color' => '#f49b00',
                'text_color' => '',
            ],
            [
                'name' => 'Flytthjälp',
                'primary_color' => '#f40000',
                'text_color' => '',
            ],
            [
                'name' => 'Möte',
                'primary_color' => '#d9d9d9',
                'text_color' => '',
            ]
        ]);
    }
}

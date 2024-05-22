<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
  
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
               'name'=>'Buffer TI',
               'email'=>'buffer@globalhitss.com.br',
               'type'=>1,
               'setor' => 'Buffer TI',
               'password'=> bcrypt('Acesso@23'),
               'email_verified_at' => now(),
            ],
            [
               'name'=>'Daniel Pinto De Almeida',
               'email'=>'daniel.pinto@globalhitss.com.br',
               'type'=> 2,
               'setor' => 'Agendamento',
               'password'=> bcrypt('Acesso@23'),
               'email_verified_at' => now(),
            ],
            [
               'name'=>'Victor Rossano Couto do Amaral',
               'email'=>'victor.amaral@globalhitss.com.br',
               'type'=>0,
               'setor' => 'Buffer TI',
               'password'=> bcrypt('Acesso@23'),
               'email_verified_at' => now(),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
<?php

namespace App\Console\Commands;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdministrator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-administrator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask("Введіть ім'я адміністратора");
        $email = $this->ask('Введіть email адміністратора');
        $password = $this->secret('Введіть пароль адміністратора');
        $confirmPassword = $this->secret('Підтвердіть пароль адміністратора');

        if ($password !== $confirmPassword) {
            $this->error('Паролі не співпадають. Спробуйте ще раз.');
            return;
        }

        // Перевіряємо чи існує такий email
        if (Administrator::where('email', $email)->exists()) {
            $this->error('Адміністратор з таким email вже існує.');
            return;
        }

        Administrator::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Адміністратор {$name} успішно створений!");
    }
}

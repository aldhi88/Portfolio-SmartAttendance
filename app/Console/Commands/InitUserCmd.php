<?php

namespace App\Console\Commands;

use App\Models\UserLogin;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class InitUserCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:user';

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
        $data = [
            [
                'id' => 100,
                'name' => 'Super User',
                'user_logins' => [
                    [
                        'id' => 101,
                        'user_role_id' => 100,
                        'nickname' => 'Superuser',
                        'username' => 'superuser',
                        'password' => Hash::make('superuser'),
                    ],
                ]
            ],
            [
                'id' => 200,
                'name' => 'Pengawas',
                'user_logins' => []
            ],
            [
                'id' => 300,
                'name' => 'Karyawan',
                'user_logins' => []
            ],
            [
                'id' => 400,
                'name' => 'Pengawas & RDP',
                'user_logins' => []
            ],
            [
                'id' => 500,
                'name' => 'Manajer',
                'user_logins' => []
            ],
        ];


        foreach ($data as $roleData) {
            // Simpan atau update Role
            $role = UserRole::updateOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name']]
            );
            // Simpan User Login jika ada
            if (!empty($roleData['user_logins'])) {
                foreach ($roleData['user_logins'] as $userData) {
                    UserLogin::updateOrCreate(
                        ['id' => $userData['id']],
                        [
                            'user_role_id' => $role->id,
                            'nickname' => $userData['nickname'],
                            'username' => $userData['username'],
                            'password' => $userData['password'],
                        ]
                    );
                }
            }
        }
    }
}

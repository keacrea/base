<?php
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'name' => 'thomas',
                'mail' => 'thomas@keacrea.com',
                'password' => '$2y$10$sYKZ8QYS9lQ5KwrStEyo.OBJ.CcDvQZG727o7tlnuSyu9V5v.sP4S',
                'token' => '3d6fa3584f424c9db4225af80969bc36',
                'created' => '2017-06-01 11:40:16',
                'modified' => '2018-02-23 09:26:24',
                'role' => 'superadmin',
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}

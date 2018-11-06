<?php
use Migrations\AbstractSeed;

/**
 * SettingSites seed.
 */
class SettingSitesSeed extends AbstractSeed
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
                'logo' => '/images/logo/logo.png',
                'email' => 'thomas@keacrea.com',
                'phone' => '',
                'address' => '',
                'zipcode' => '',
                'city' => '',
            ],
        ];

        $table = $this->table('setting_sites');
        $table->insert($data)->save();
    }
}

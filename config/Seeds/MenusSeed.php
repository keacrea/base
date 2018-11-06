<?php
use Migrations\AbstractSeed;

/**
 * Menus seed.
 */
class MenusSeed extends AbstractSeed
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
                'name' => 'Menu principal',
                'created' => '2017-05-11 17:50:03',
                'modified' => '2017-05-11 17:50:05',
            ],
            [
                'id' => '2',
                'name' => 'Menu du bas',
                'created' => '2017-05-11 17:50:44',
                'modified' => '2017-05-11 17:50:46',
            ],
        ];

        $table = $this->table('menus');
        $table->insert($data)->save();
    }
}

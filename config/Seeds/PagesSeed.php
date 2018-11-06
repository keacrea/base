<?php
use Migrations\AbstractSeed;

/**
 * Pages seed.
 */
class PagesSeed extends AbstractSeed
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
                'name' => 'Homepage',
                'type' => 'home',
                'chapo' => 'chapo',
                'content' => '<p>contenu</p>',
                'item' => 'Homepage',
                'slug' => 'homepage',
                'islink' => '0',
                'online' => '1',
                'position' => '1',
                'parent_id' => NULL,
                'menu_id' => '1',
                'lft' => '0',
                'rght' => '0',
                'level' => '0',
                'meta_title' => 'meta title',
                'meta_description' => 'meta description',
                'controller' => 'Pages',
                'action' => 'home',
                'visible' => '0',
                'created' => NULL,
                'modified' => '2018-11-05 16:23:46',
            ],
            [
                'id' => '2',
                'name' => 'page test',
                'type' => 'page',
                'chapo' => 'Chapo de la page',
                'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam, consequatur consequuntur culpa dolorem ducimus, eligendi, eos fugit illo iste iure laudantium nulla placeat quia quos repudiandae vel veritatis vitae voluptas.<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus adipisci assumenda autem consequuntur culpa debitis deleniti dignissimos enim fuga, ipsam libero magni maiores nemo nesciunt, officiis quam quod saepe sapiente.<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque eius ipsam repudiandae. Accusantium, culpa deleniti eveniet hic illo in iure nostrum odio perferendis praesentium, quae reiciendis, repudiandae sapiente unde vitae.</p>',
                'item' => 'page test',
                'slug' => 'page-test',
                'islink' => '0',
                'online' => '1',
                'position' => '2',
                'parent_id' => NULL,
                'menu_id' => '1',
                'lft' => '1',
                'rght' => '6',
                'level' => '0',
                'meta_title' => 'page test',
                'meta_description' => '',
                'controller' => 'Pages',
                'action' => 'view',
                'visible' => '1',
                'created' => '2018-11-05 16:24:26',
                'modified' => '2018-11-06 09:59:14',
            ],
            [
                'id' => '3',
                'name' => 'page enfant',
                'type' => 'page',
                'chapo' => '',
                'content' => '',
                'item' => 'page enfant',
                'slug' => 'page-enfant',
                'islink' => '1',
                'online' => '1',
                'position' => '1',
                'parent_id' => '2',
                'menu_id' => '1',
                'lft' => '2',
                'rght' => '5',
                'level' => '0',
                'meta_title' => 'page enfant',
                'meta_description' => '',
                'controller' => 'Pages',
                'action' => 'view',
                'visible' => '1',
                'created' => '2018-11-05 16:24:35',
                'modified' => '2018-11-06 10:06:20',
            ],
            [
                'id' => '4',
                'name' => 'Contact',
                'type' => 'form',
                'chapo' => '',
                'content' => '',
                'item' => 'Contact',
                'slug' => 'contact',
                'islink' => '0',
                'online' => '1',
                'position' => '1',
                'parent_id' => NULL,
                'menu_id' => '2',
                'lft' => '7',
                'rght' => '8',
                'level' => '0',
                'meta_title' => 'Contact',
                'meta_description' => '',
                'controller' => 'Contacts',
                'action' => 'index',
                'visible' => '1',
                'created' => '2018-11-05 16:59:08',
                'modified' => '2018-11-06 08:58:33',
            ],
            [
                'id' => '5',
                'name' => 'confirmation contact',
                'type' => 'confirm',
                'chapo' => 'Chapo de la page de confirmation',
                'content' => '<p>Contenu de la page de confirmation</p>',
                'item' => 'confirmation contact',
                'slug' => 'confirmation-contact',
                'islink' => '0',
                'online' => '1',
                'position' => '1',
                'parent_id' => '4',
                'menu_id' => '2',
                'lft' => '9',
                'rght' => '10',
                'level' => '0',
                'meta_title' => 'confirmation contact',
                'meta_description' => '',
                'controller' => 'Contacts',
                'action' => 'confirm',
                'visible' => '0',
                'created' => '2018-11-06 08:58:51',
                'modified' => '2018-11-06 09:40:22',
            ],
            [
                'id' => '6',
                'name' => 'test ariane',
                'type' => 'page',
                'chapo' => 'Chapo de la page',
                'content' => '<p>Contenu de la page</p>',
                'item' => 'test ariane',
                'slug' => 'test-ariane',
                'islink' => '0',
                'online' => '1',
                'position' => '1',
                'parent_id' => '3',
                'menu_id' => '1',
                'lft' => '3',
                'rght' => '4',
                'level' => '0',
                'meta_title' => 'test ariane',
                'meta_description' => '',
                'controller' => 'Pages',
                'action' => 'view',
                'visible' => '1',
                'created' => '2018-11-06 10:06:08',
                'modified' => '2018-11-06 10:43:44',
            ],
        ];

        $table = $this->table('pages');
        $table->insert($data)->save();
    }
}

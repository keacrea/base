<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Utility\Text;

/**
 * Page Entity
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $chapo
 * @property string $content
 * @property string $item
 * @property string $slug
 * @property bool $islink
 * @property bool $online
 * @property int $position
 * @property int $parent_id
 * @property int $menu_id
 * @property int $lft
 * @property int $rght
 * @property int $level
 * @property string $meta_title
 * @property string $meta_description
 * @property string $controller
 * @property string $action
 * @property bool $visible
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ParentPage $parent_page
 * @property \App\Model\Entity\Menu $menu
 * @property \App\Model\Entity\ChildPage[] $child_pages
 */
class Page extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'type' => true,
        'chapo' => true,
        'content' => true,
        'item' => true,
        'slug' => true,
        'islink' => true,
        'online' => true,
        'position' => true,
        'parent_id' => true,
        'menu_id' => true,
        'lft' => true,
        'rght' => true,
        'level' => true,
        'meta_title' => true,
        'meta_description' => true,
        'controller' => true,
        'action' => true,
        'visible' => true,
        'created' => true,
        'modified' => true,
        'parent_page' => true,
        'menu' => true,
        'child_pages' => true
    ];

    public function _getUrl()
    {

        switch ($this->type):
            case 'page':
                $url = Router::url(['_name' => 'page' ,'slug' => Text::slug($this->slug , '-')]);
                break;
            default:
                $url = Router::url([
                    'controller' => $this->controller,
                    'action' => $this->action,
                    'prefix'=>false
                ]);
        endswitch;

        return $url;
    }


    protected function _setSlug($slug)
    {
        if(!$slug){
            $slug =  $this->name;
        }
        return Text::slug(mb_strtolower($slug), '-');
    }

    protected function _setItem($item)
    {
        if(!$item){
            $item =  $this->name;
        }
        return $item;
    }

    protected function _setMetaTitle($meta_title)
    {
        if(!$meta_title){
            $meta_title =  $this->name;
        }
        return $meta_title;
    }

}

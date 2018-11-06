<?php
namespace App\Model\Table;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Pages Model
 *
 * @property \App\Model\Table\PagesTable|\Cake\ORM\Association\BelongsTo $ParentPages
 * @property \App\Model\Table\MenusTable|\Cake\ORM\Association\BelongsTo $Menus
 * @property \App\Model\Table\PagesTable|\Cake\ORM\Association\HasMany $ChildPages
 *
 * @method \App\Model\Entity\Page get($primaryKey, $options = [])
 * @method \App\Model\Entity\Page newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Page[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Page|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Page|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Page patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Page[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Page findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class PagesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->addBehavior('Position');
        $this->addBehavior('Cache');

        $this->belongsTo('ParentPages', [
            'className' => 'Pages',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Menus', [
            'foreignKey' => 'menu_id'
        ]);
        $this->hasMany('ChildPages', [
            'className' => 'Pages',
            'foreignKey' => 'parent_id'
        ]);
    }

    public $type = [
        'home' => 'Page d\'accueil',
        'page' => 'Standard',
        'static' => 'Statique',
        'form' => 'Formulaire',
        'confirm' => 'Page confirmation',
    ];

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name', 'Merci de renseigner le titre de la page')
        ;
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentPages'));
        $rules->add($rules->existsIn(['menu_id'], 'Menus'));
        $rules->add($rules->isUnique(['slug'],'Cette url existe déjà'));

        return $rules;
    }

    public function beforeSave($event, $entity, $options)
    {
        if (empty($entity->slug)) {
            $entity->slug = Text::slug(mb_strtolower($entity->name) , '-');
        }
        if (empty($entity->item)) {
            $entity->item = $entity->name;
        }
        if (empty($entity->meta_title)) {
            $entity->meta_title = $entity->name;
        }
    }


    public function afterSave(Event $event, Entity $entity)
    {
        Cache::deleteMany(['pages','pages-resp' ], 'nav');


    }

    public function afterDelete(Event $event, Entity $entity)
    {
        Cache::deleteMany(['pages','pages-resp'], 'nav');
    }

    public function findParent(Query $query, array $options)
    {
        $query->where(['parent_id IS' => $options['parent_id']]);

        return $query;
    }


    public function findPageStatic($controller, $action)
    {
        $page = $this->find()
            ->where([
                'Pages.online' => true,
                'Pages.controller' => $controller,
                'Pages.action' => $action,
            ])
            ->select([
                'id',
                'name',
                'slug',
                'chapo',
                'content',
                'item',
                'meta_title',
                'meta_description',
                'controller',
                'action',
                'modified',
                'online',
            ])
            ->first();

        return $page;
    }


    public function listPageMenu($menu_id, $page_id = null)
    {

        $pages = $this->find('treeList', [
            'keyPath' => 'id',
            'valuePath' => 'item',
            'spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;'])
            ->where([
                'Pages.menu_id IS'  => $menu_id,
                'Pages.type not in'      => ['home', 'form', 'confirm']
            ])
            ->andWhere(function ($exp) use ($page_id) {
                if ($page_id) {
                    $exp->notEq('Pages.id', $page_id);
                }
//                $exp->lt('Pages.level', 1);
                return $exp;
            })
            ->toArray();

        return $pages;
    }

    public function PageParent($controller, $action)
    {
        $page = $this->find()
            ->select(['item', 'controller', 'action', 'modified', 'name'])
            ->where([
                'controller' => $controller,
                'action' => $action,
            ])
            ->first();
        return $page;
    }
}

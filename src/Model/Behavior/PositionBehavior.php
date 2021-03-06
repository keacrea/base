<?php

namespace App\Model\Behavior;


use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class PositionBehavior extends Behavior {

    protected $_defaultConfig = [
        'field' => 'position',
        'type' => 'menu_id'
    ];

    public function initialize(array $config)
    {
        parent::initialize($config); // TODO: Change the autogenerated stub
    }

	public function beforeSave(Event $event,Entity $entity, $options = []){
		if($entity->isNew()){
			$config = $this->getConfig();
			$this->_table->getEventManager()->off('Model.afterSave');
			$table = TableRegistry::getTableLocator()->get($this->_table->getAlias());

			$elements = $this->_table->find()
			  ->select(['id',$config['field']])
			  ->order(['position' => 'asc']);

			if($entity->has($config['type'])){
				$elements->select([$config['type']])
				  ->where([$config['type']=>$entity->get($config['type'])]);
			}

			if($this->_table->behaviors()->has('Tree')){
				if(!is_null($entity->parent_id)){
					$elements->where(function($exp) use ($entity){
						return $exp->eq('parent_id', $entity->parent_id);
					});
				}else{
					$elements->where(function($exp) use ($entity){
						return $exp->isNull('parent_id');
					});
				}
			}

			$result = $elements->count(); // on récupère les enfants du parent_id
			$entity->position = $result +1;
		}


		return true;
	}

    public function afterSave(Event $event, Entity $entity, $options = []){
        if($this->_orderItem($entity)){
            $originalParent = $entity->getOriginal('parent_id'); // on récupère l'ancien parent_id
            if(!is_null($originalParent)){
                $this->_orderOriginalItem($originalParent);
            }
            return true;
        }

    }

    public function afterDelete(Event $event, Entity $entity, $options = []){
        if($this->_orderItem($entity)){
            $originalParent = $entity->getOriginal('parent_id'); // on récupère l'ancien parent_id
            if(!is_null($originalParent)){
                $this->_orderOriginalItem($originalParent);
            }
            return true;
        }
    }

    protected function _orderItem($entity){
        $config = $this->getConfig();
        $this->_table->getEventManager()->off('Model.afterSave');
        $table = TableRegistry::getTableLocator()->get($this->_table->getAlias());

        $elements = $this->_table->find()
            ->select(['id',$config['field']])
		  	->order(['position' => 'asc']);

        if($entity->has($config['type'])){
            $elements->select([$config['type']])
            ->where([$config['type']=>$entity->get($config['type'])]);
        }

        if($this->_table->behaviors()->has('Tree')){
            if(!is_null($entity->parent_id)){
                $elements->where(function($exp) use ($entity){
                    return $exp->eq('parent_id', $entity->parent_id);
                });
            }else{
                $elements->where(function($exp) use ($entity){
                    return $exp->isNull('parent_id');
                });
            }
        }

        $result = $elements->toArray(); // on récupère les enfants du parent_id
        $data = [];
        foreach($result as $k=>$e){
            $data[] = [
                'id'=>$e->id,
                $config['field'] =>$k+1
            ];
        }
        $entities = $this->_table->patchEntities($table,$data,['fields' => ['id',$config['field'],$config['type'] ],'validate'=>false]);

        foreach($entities as $entity){
            $this->_table->save($entity);
        }
        return true;
    }

    /**
     * Permet de réorganiser les positions de l'élément précédent
     *
     * @param $originalParent
     * @return bool
     */
    protected function _orderOriginalItem($originalParent){

        $config = $this->getConfig();
        $this->_table->getEventManager()->off('Model');
        $posts = TableRegistry::getTableLocator()->get($this->_table->getAlias());

        $elements = $this->_table->find()
            ->select(['id',$config['field']])
		  ->order(['position' => 'asc']);

        if(!empty($originalParent->menu_id)){
            $elements->select(['menu_id'])
                ->where(['menu_id'=>$originalParent->menu_id]);
        }

        if($this->_table->behaviors()->has('Tree')){
            if(!is_null($originalParent)){
                $elements->where(function($exp) use ($originalParent){
                    return $exp->eq('parent_id', $originalParent);
                });
            }else{
                $elements->where(function($exp) use ($originalParent){
                    return $exp->isNull('parent_id');
                });
            }
        }
        $result = $elements->toArray(); // on récupère les enfants du parent_id

        $data = [];
        foreach($result as $k=>$e){
            $data[] = [
                'id'=>$e->id,
                $config['field'] =>$k+1,
            ];
        }

        $entities = $this->_table->patchEntities($posts,$data,['validate'=>false]);
        foreach($entities as $entity){
            $this->_table->save($entity);
        }
        return true;
    }

    public function position($data){
		$this->_table->getEventManager()->off('Model.beforeSave');
        $config = $this->getConfig();
        $position = $this->_defaultConfig['field'];
        $tree = false;
        if($this->_table->behaviors()->has('Tree')){
            $tree = true;
        }

        $fields = [$position,'id'];
        if($tree){
            $fields = [$position,'id','parent_id'];
        }

        $page = $this->_table->find()
            ->select($fields)
            ->where([$this->_table->getAlias().'.id'=>$data['id']]);
            if(!empty($data[$config['type']])){
                $page->select([$config['type']])
                    ->where([$config['type']=>$data[$config['type']]]);
            }
            $res = $page->first();

        if(isset($data[$position]) && $data[$position]>0 && $data[$position] != $res->position){
            $this->_table->getEventManager()->off('Model.afterSave');
            $parent = [];
            if($tree){
                $parent = ['parent_id IS'=>$res->parent_id];
            }
            $count = $this->_table->find()
                ->select($fields)
                ->where($parent);
            if(!empty($data[$config['type']])){
                $count->select([$config['type']])
                    ->where([$config['type']=>$data[$config['type']]]);
            }
            $nb_res = $count->count();

            $sisters = $this->_table->find()
                ->select($fields)
                ->where([
                    $parent,
                    $this->_table->getAlias().'.id !='=>$res->id,
                ])
                ->where(function ($exp) use($position, $data, $res) {
                    if($data[$position] > $res->position){
                        $exp->between($position, $res->position, $data[$position]);
                    }else{
                        $exp->between($position, $data[$position], $res->position);
                    }

                    return $exp;
                });
            if(!empty($data[$config['type']])){
                $sisters->select([$config['type']])
                    ->where([$config['type']=>$data[$config['type']]]);
            }
            $res_sister = $sisters->order([$position => 'ASC'])->all();

            if(empty($res_sister)){
                return false;
            }

            if($data[$position]>$nb_res){
                return false;
            }

            $newLine = array(
                $position=>$data[$position],
                'id'=>$data['id']

            );

             if($data[$position] > $res->position){
                foreach($res_sister as $k=>$v){
                    $orders[]= array(
                        $position=>$v[$position]-1,
                        'id'=>$v['id']
                    );
                }
                array_push($orders,$newLine);

            }else{

                foreach($res_sister as $k=>$v){

                    $orders[] = array(
                        $position=>$v[$position]+1,
                        'id'=>$v['id']
                    );
                }

                array_unshift($orders,$newLine);
            }

            $posts = TableRegistry::getTableLocator()->get($this->_table->getAlias());
            $entities = $this->_table->patchEntities($posts,$orders,['validate'=>false]);

            foreach($entities as $entity){
                $this->_table->save($entity);
            }
            if($tree){
                $this->_table->recover();
            }

        }
        return true;
    }

    public function to_up($id = null,$menu_id = null) {
        $config = $this->getConfig();
        $this->_table->getEventManager()->off('Model.afterSave');
        $position = $this->_defaultConfig['field'];
        $tree = false;
        if($this->_table->behaviors()->has('Tree')){
            $tree = true;
        }

        $fields = [$position,$this->_table->getAlias().'.id'];
        if($tree){
            $fields = [$position,$this->_table->getAlias().'.id','parent_id'];
        }


        $pageToUp = $this->_table->find()
            ->select($fields)
            ->where([$this->_table->getAlias().'.id'=>$id]);

        if(!empty($menu_id)){
            $pageToUp->select([$config['type']])
                ->where([$config['type']=>$menu_id]);
        }
        $resToUp = $pageToUp->first();

        $parent = [];
        if($tree){
            $parent = ['parent_id IS'=>$resToUp->parent_id];
        }

        $pageMoved = $this->_table->find()
            ->select($fields)
            ->where($parent)
            ->where(function($exp) use ($resToUp){
                return $exp->notEq($this->_table->getAlias().'.id',$resToUp->id);
            })
            ->where(function($exp) use ($position,$resToUp){
                return $exp->lt($position,$resToUp->$position);
            });
             if(!empty($menu_id)){
                 $pageMoved->select([$config['type']])
                     ->where([$config['type']=>$menu_id]);
             }
        $resMoved = $pageMoved->order([$position =>'DESC'])->first();

        if($resMoved){
            $posts = $this->_table->get($id);
            $data = [
                [
                    'id'=>$resMoved->id,
                    $position=>$resToUp->$position
                ],
                [
                    'id'=>$resToUp->id,
                    $position=>$resMoved->$position
                ]
            ];

            $entities = $this->_table->patchEntities($posts,$data,['validate'=>false]);

            foreach($entities as $entity){
                $this->_table->save($entity);
           }
            if($tree){
                $this->_table->recover();
            }
        }
        return true;

    }

    public function to_down($id = null,$menu_id = null){
        $config = $this->getConfig();
        $this->_table->getEventManager()->off('Model.afterSave');
        $position = $this->_defaultConfig['field'];
        $tree = false;
        if($this->_table->behaviors()->has('Tree')){
            $tree = true;
        }

        $fields = [$position,'id'];
        if($tree){
            $fields = [$position,'id','parent_id'];
        }

        $pageToDown = $this->_table->find()
            ->select($fields)
            ->where([$this->_table->getAlias().'.id'=>$id]);
        if(!empty($menu_id)){
            $pageToDown->select([$config['type']])
                ->where([$config['type']=>$menu_id]);
        }
        $resToDown = $pageToDown->first();

        $parent = [];
        if($tree){
            $parent = ['parent_id IS'=>$resToDown->parent_id];
        }

        $pageMoved = $this->_table->find()
            ->select($fields)
            ->where($parent)
            ->where(function($exp) use ($resToDown){
                return $exp->notEq($this->_table->getAlias().'.id',$resToDown->id);
            })
            ->where(function($exp) use ($position,$resToDown){
                return $exp->gt($position,$resToDown->$position);
            });
             if(!empty($menu_id)){
                 $pageMoved->select([$config['type']])
                     ->where([$config['type']=>$menu_id]);
             }
        $resMoved = $pageMoved->order([$position =>'ASC'])->first();

        if($resMoved){
            $posts = $this->_table->get($id);
            $data = [
                [
                    'id'=>$resMoved->id,
                    $position=>$resToDown->$position
                ],
                [
                   'id'=>$resToDown->id,
                    $position=>$resMoved->$position
                ]
            ];

            $entities = $this->_table->patchEntities($posts,$data,['validate'=>false]);
            foreach($entities as $entity){
                $this->_table->save($entity);
            }
            if($tree){
                $this->_table->recover();
            }
        }
        return true;
    }
}

<?php
namespace App\Model\Table;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SettingSites Model
 *
 * @method \App\Model\Entity\SettingSite get($primaryKey, $options = [])
 * @method \App\Model\Entity\SettingSite newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SettingSite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SettingSite|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SettingSite|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SettingSite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SettingSite[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SettingSite findOrCreate($search, callable $callback = null, $options = [])
 */
class SettingSitesTable extends Table
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

        $this->setTable('setting_sites');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('logo', 'Merci de choisir une image')
            ->notEmpty('email','Merci de renseigner une adresse email')
            ->add('email', 'verif-mail', [
                'rule' => ['email', true],
                'message' => __('Merci de verifier votre adresse email')
            ]);
        ;

        return $validator;
    }

    public function afterSave(Event $event, Entity $entity)
    {
        Cache::delete('setting_site', 'long');
    }


    public function settingsSite()
    {
        $settings = Cache::read('setting_site', 'long');
        if ($settings === false) {
            $settings = $this->find()
                ->where(['SettingSites.id' => 1])
                ->select([
                    'logo',
                    'email',
                    'phone',
                    'address',
                    'zipcode',
                    'city',
                ])
                ->first();
            Cache::write('setting_site', $settings, 'long');
        }

        return $settings;
    }

    public function SettingMail()
    {
        $setting = $this->find()
            ->where(['SettingSites.id' => 1])
            ->select(['email'])
            ->first();

        return $setting;
    }
}

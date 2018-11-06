<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contact Entity
 *
 * @property int $id
 * @property int $civility
 * @property string $name
 * @property string $firstname
 * @property string $phone
 * @property string $email
 * @property string $message
 * @property \Cake\I18n\FrozenTime $created
 * @property bool $readable
 */
class Contact extends Entity
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
        'civility' => true,
        'name' => true,
        'firstname' => true,
        'phone' => true,
        'email' => true,
        'message' => true,
        'created' => true,
        'readable' => true
    ];
}

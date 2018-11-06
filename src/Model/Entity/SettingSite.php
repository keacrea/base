<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SettingSite Entity
 *
 * @property int $id
 * @property string $logo
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $zipcode
 * @property string $city
 */
class SettingSite extends Entity
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
        'logo' => true,
        'email' => true,
        'phone' => true,
        'address' => true,
        'zipcode' => true,
        'city' => true
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SearchStat Entity
 *
 * @property int $id
 * @property int $search_term_id
 * @property int $browser_id
 * @property int $customer_id
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\SearchTerm $search_term
 * @property \App\Model\Entity\Browser $browser
 * @property \App\Model\Entity\Customer $customer
 */
class SearchStat extends Entity
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
        'search_term_id' => true,
        'browser_id' => true,
        'customer_id' => true,
        'created' => true,
        'search_term' => true,
        'browser' => true,
        'customer' => true
    ];
}

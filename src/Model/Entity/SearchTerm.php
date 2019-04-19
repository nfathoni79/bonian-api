<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SearchTerm Entity
 *
 * @property int $id
 * @property string $words
 * @property int $hits
 * @property bool $match
 *
 * @property \App\Model\Entity\SearchStat[] $search_stats
 */
class SearchTerm extends Entity
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
        'words' => true,
        'hits' => true,
        'match' => true,
        'search_stats' => true
    ];
}

<?php

namespace nick97\xfrmwishlist\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null wishlist_id
 * @property int user_id
 * @property int resource_id
 * @property string create_at
 *
 * RELATIONS
 * @property \XFRM\Entity\ResourceItem Resource
 * @property \XF\Entity\User User
 */
class Wishlist extends Entity
{
  
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fc_resource_wishlists';
        $structure->shortName = 'nick97\xfrmwishlist:Wishlist';
        $structure->contentType = 'wishlist';
        $structure->primaryKey = 'wishlist_id';
        $structure->foreignKey = 'user_id';
        $structure->foreignKey = 'resource_id';


        $structure->columns = [
			'wishlist_id' => ['type' => self::UINT, 'autoIncrement' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
            'resource_id' => ['type' => self::UINT, 'required' => true],
            'created_at' => ['type' => self::UINT, 'default' => 0]];

        $structure->relations = [
            'Resource' => [
                'entity' => 'XFRM:ResourceItem',
                'type' => self::TO_ONE,
                'conditions' => 'resource_id',
                'primary' => true
            ],
        ];

        $structure->defaultWith = [
            'Resource'
        ];

        return $structure;
    }
}
<?php

namespace nick97\xfrmwishlist\Extend\Entity;


use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    public static function getStructure(Structure $structure)
	{
		$structure = Parent::getStructure($structure);

        $structure->relations += [
            'UserWishlist' => [
            'entity' => 'nick97\xfrmwishlist:Wishlist',
            'type' => self::TO_MANY,
            'conditions' => 'user_id',
            'primary' => true
        ]];

		return $structure;
	}


    public function _preDelete(){
        Parent::_preDelete();
        foreach($this->UserWishlist as $wishlist){
            $wishlist->delete();
        } 
    }


    public function canViewWishlist(&$error = null)
    {
        return  \XF::visitor()->hasPermission('resource', 'wishlistPermission');
    }
}
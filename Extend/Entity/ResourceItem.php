<?php

namespace nick97\xfrmwishlist\Extend\Entity;


use XF\Mvc\Entity\Structure;

class ResourceItem extends XFCP_ResourceItem
{

    public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

        $structure->relations += [
            'UserWishlist' => [
            'entity' => 'nick97\xfrmwishlist:Wishlist',
            'type' => self::TO_MANY,
            'conditions' => 'resource_id',
            'primary' => true,
        ]];

		return $structure;
	}

    public function _preDelete(){
        Parent::_preDelete();
        foreach($this->UserWishlist as $wishlist){
            $wishlist->delete();
        } 
    }

//    /**@return \nick97\xfrmwishlist\Entity\Wishlist */
//    public function getUserWishlist(){
//        return $this->getWishlistRepo()->getUserWishlist($this->resource_id);
//    }

    public function getUserWishlist(\XF\Entity\User $user = null){
        return $this->getWishlistRepo()->getUserWishlist($this->resource_id , $user);
    }


    /**@return \nick97\xfrmwishlist\Repository\Wishlist */
    protected function getWishlistRepo(){
        return $this->repository('nick97\xfrmwishlist:Wishlist');
    }






}
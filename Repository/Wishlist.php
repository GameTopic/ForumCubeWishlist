<?php

namespace nick97\xfrmwishlist\Repository;


use XF\ConnectedAccount\ProviderData\AbstractProviderData;
use XF\Mvc\Entity\Repository;
use XF\Mvc\Entity\Finder;

class Wishlist extends Repository
{
    public function getUserWishlist($resourceId , \XF\Entity\User $user = null)
    {
        if(empty($user)){
            $user = \XF::visitor();
        }

        if ($resourceId)
        {
            $wishlist = \XF::finder('nick97\xfrmwishlist:Wishlist')
                ->where('user_id', $user->user_id)
                ->where('resource_id', $resourceId)
                ->fetchone();

            return $wishlist;
        }
        return;
    }

    public function getUserWishlistResources(\XF\Entity\User $user = null)
    {
        if (empty($user)) {
            $user = \XF::visitor();
        }
        $finder = $this->finder('nick97\xfrmwishlist:Wishlist');
        $finder->where('user_id',$user->user_id);

        $resourceIds = array_values($finder->pluckFrom('resource_id')->fetch()->toArray());

        $finder = $this->finder('XFRM:ResourceItem');
        $finder->where('resource_id',$resourceIds);

        return $finder;

    }


}

<?php

namespace nick97\xfrmwishlist\Extend\Pub\Controller;

use nick97\xfrmwishlist\Extend\Pub\Controller\XFCP_Member;

use XF\Mvc\Entity\Structure;
use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    public function actionWishlist(ParameterBag $params)
    {

        $user = $this->assertViewableUser($params->user_id, [], true);

        // if (!\XF::visitor()->canViewWishlist()) {
        //     return $this->noPermission();
        // }

        /** @var \nick97\xfrmwishlist\Repository\Wishlist $wishlistRepo */
        $wishlistRepo = $this->repository('nick97\xfrmwishlist:Wishlist');


            /** @var \XFRM\ControllerPlugin\Overview $overviewPlugin */
        
            $overviewPlugin = $this->plugin('XFRM:Overview');

            $categoryParams = $overviewPlugin->getCategoryListData();
            $viewableCategoryIds = $categoryParams['categories']->keys();

            $listParams = $overviewPlugin->getCoreListData($viewableCategoryIds);
        
            $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'resources');
            $this->assertCanonicalUrl($this->buildLink('resource/wishlist', null, ['page' => $listParams['page']]));
  
            $viewParams = $categoryParams + $listParams;


            $viewParams['memberPage'] = true;

            $wishlists = $wishlistRepo->getUserWishlistResources($user)->fetch();
  
            if (empty($wishlists)) {
                return $this->message(\XF::phrase('this_member_has_not_been_add_any_wishlist'));
            }
            
            $viewParams['user'] = $user;
            $viewParams['resources'] = $wishlists;
            
            return $this->view('XF:Member\Warnings', 'fc_wishlist_in_member', $viewParams);
    }
    
}
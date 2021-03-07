<?php

namespace nick97\xfrmwishlist\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Wishlist extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{

		if ($params->resource_id)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}

		/** @var \XFRM\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('XFRM:Overview');

		$categoryParams = $overviewPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();

		$listParams = $overviewPlugin->getCoreListData($viewableCategoryIds);

		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'resources');
		$this->assertCanonicalUrl($this->buildLink('resource/wishlist', null, ['page' => $listParams['page']]));
      
		$viewParams = $categoryParams + $listParams;
        
        
        $viewParams['wishlistPage'] = true;
        /** @var \nick97\xfrmwishlist\Repostiory\Wishlist $wishlistRepo */
        $wishlistRepo = $this->repository('nick97\xfrmwishlist:Wishlist');
        $resources = $wishlistRepo->getUserWishlistResources()->fetch();
        $viewParams['total'] = count($resources->toArray());
        $viewParams['resources'] = $resources;



        return $this->view('nick97\xfrmwishlist:Wishlist', 'fc_wishlist_overview', $viewParams);
	}

    public function actionAdd(ParameterBag $params){

        $resourceId = $this->filter('resourceId', 'int');
        $viewPage = $this->filter('viewPage', 'bool');
        $resource = $this->em()->findOne("XFRM:ResourceItem",['resource_id' => $resourceId]);
        $resourceItem = $this->em()->findOne('XFRM:ResourceItem',['resource_id' => $resourceId]);


        $wishlist = $this->em()->findOne('nick97\xfrmwishlist:Wishlist',
            ['resource_id' => $resourceId , 'user_id'=>\XF::Visitor()->user_id]);

        if(empty($wishlist) && !empty($resourceId)){
            $wishlistCreator = $this->service('nick97\xfrmwishlist:Wishlist\Creator', $resourceItem);
            $wishlistCreator->setValues();
            $wishlistCreator->save();
        }

        if($viewPage){
            $redirect = $this->redirect($this->buildLink('resources/view', $resource));
		
        } else{
            $redirect = $this->redirect($this->buildLink('resources'));
        }
            return $redirect;
       
    }

    public function actionRemove(ParameterBag $params){
        $wishlistId = $params->wishlist_id;
        $resourceId = $this->filter('resourceId', 'int');
        $userId = $this->filter('userId', 'int');
        $viewPage = $this->filter('viewPage', 'bool');

        $wishlistPage = $this->filter('wishlistPage','bool');

//        dump($userId,$viewPage,$wishlistPage,$resourceId,$wishlistId);die;
        $resource = $this->em()->findOne("XFRM:ResourceItem",['resource_id' => $resourceId]);

        $wishlist = $this->em()->findOne('nick97\xfrmwishlist:Wishlist',['wishlist_id' => $wishlistId]);

        if(!empty($wishlist)){
            $wishlist->delete();
        }
        if($viewPage){
            $redirect = $this->redirect($this->buildLink('resources/view', $resource));
        } elseif ($wishlistPage){
            $redirect = $this->redirect($this->buildLink('resource/wishlist'));
        } elseif($userId){
            $user = $this->em()->findOne('XF:User', ['user_id'=>$userId]);
            $redirect = $this->redirect($this->buildLink('members', $user) . '#wishlist');
        }else{
            $redirect = $this->redirect($this->buildLink('resources'));
        }
	
        return $redirect;
    }

}
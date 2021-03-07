<?php

namespace nick97\xfrmwishlist\Service\Wishlist;

use XF\Entity\User;
use XFRM\Entity\ResourceItem;

class Creator extends \XF\Service\AbstractService
{
    use \XF\Service\ValidateAndSavableTrait;

    /**
	 * @var User
	 */
	protected $user;


    /**
	 * @var ResourceItem
	 */
	protected $resourceItem;

        /**
	 * @var Wishlist
	 */
	protected $wishlist;


    
     protected function _validate(){
         return $this->wishlist->getErrors();
     }

    protected function _save(){

        $date = time();

		$wishlist = $this->wishlist;
        $wishlist->created_at = $date;
        $wishlist->save();

        return $wishlist;
    }


    
    public function __construct(\XF\App $app, ResourceItem $resourceItem)
	{
		parent::__construct($app);
        $this->resourceItem = $resourceItem;
        $this->user = \XF::visitor();
		$this->setupDefaults();
	}

    protected function setupDefaults()
	{
        $this->user = \XF::visitor();
        $this->wishlist = $this->em()->create('nick97\xfrmwishlist:Wishlist');

	}

    public function getUser()
	{
		return $this->user;
	}

    public function getResourceItem()
	{
		return $this->resourceItem;
	}

    public function setUser(\XF\Entity\User $user){
        $this->user = $user;
    }

    public function setValues()
	{
        $wishlist = $this->wishlist;
        $wishlist->user_id = $this->user->user_id;
        $wishlist->resource_id = $this->resourceItem->resource_id;

		return $wishlist;
	}


}
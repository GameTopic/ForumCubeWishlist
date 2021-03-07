<?php

namespace nick97\xfrmwishlist;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $this->schemaManager()->createTable('fc_resource_wishlists', function (Create $table) {
            $table->addColumn('wishlist_id', 'int')->autoIncrement();
            $table->addColumn('resource_id', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('created_at', 'int');
            $table->addPrimaryKey('wishlist_id');
        });
    }

    public function uninstallStep1()
    {
        $this->schemaManager()->dropTable('fc_resource_wishlists');
    }
}
<?php

use Phinx\Migration\AbstractMigration;

class User extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(){
    	$users = $this->table('users');
    	$users->addColumn('userid', 'string', array('limit'=>255,'default'=>''))
    				->addColumn('name', 'string', array('limit'=>255,'default'=>''))
    				->addColumn('password', 'string', array('limit'=>255,'default'=>''))
    				->addColumn('cd_user_group', 'integer')
    				->addColumn('last_login', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    				->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
			    	->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
			    	->addIndex(array('userid'))
    				->create();
    }
    
}

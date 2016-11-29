<?php

use Phinx\Migration\AbstractMigration;

class Subscription extends AbstractMigration
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
    public function change()
    {
    	$table = $this->table('subscriptions' );
    	$table->addColumn( 'category_id', 'integer', ['default'=>'0'] )
	    	->addColumn( 'description', 'integer', ['default'=>'0'] )
	    	->addColumn( 'show_id', 'integer', ['default'=>'0'] )
	    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    	->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    	->addIndex('category_id')
	    	->addForeignKey('show_id', 'shows', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
	    	->create();
    	 
    }
}

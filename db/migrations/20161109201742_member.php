<?php

use Phinx\Migration\AbstractMigration;

class Member extends AbstractMigration
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
    	$table = $this->table('members');
    	$table->addColumn( 'fn_no', 'integer', ['default'=>'0', 'null'=>true] )
    	->addColumn( 'name', 'string', ['limit'=>255,'default'=>'', 'null'=>true] )
    	->addColumn( 'prename', 'string', ['limit'=>255,'default'=>'', 'null'=>true] )
    	->addColumn( 'club', 'string', ['limit'=>255,'default'=>'', 'null'=>true] )
    	->addColumn( 'sex', 'string', ['limit'=>255,'default'=>''] )
    	->addColumn( 'birthyear', 'integer', ['default'=>'0'] )
    	->addColumn( 'category', 'string', ['limit'=>255,'default'=>''] )
    	->addColumn( 'armno', 'integer', ['default'=>'0', 'null'=>true] )
    	->addColumn( 'description', 'string', ['limit'=>255,'default'=>''] )
    	->addColumn( 'licence', 'string', ['limit'=>255,'default'=>''] )
	    ->addColumn( 'show_id', 'integer', ['default'=>'0', 'null'=>true] )
    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    ->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addIndex(array('fn_no'))
    	->addIndex(array('name'))
    	->addIndex(array('prename'))
    	->addIndex(array('club'))
    	->addIndex(array('armno'))
	    ->addForeignKey('show_id', 'shows', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
    	->create();
    	 
    }
}

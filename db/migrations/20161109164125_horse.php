<?php

use Phinx\Migration\AbstractMigration;

class Horse extends AbstractMigration
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
		$table = $this->table('horses');
		$table->addColumn( 'name', 'string', ['limit'=>255,'default'=>'', 'null'=>true] )
		->addColumn( 'club', 'string', ['limit'=>255,'default'=>'', 'null'=>true] )
		->addColumn( 'owner', 'string', ['limit'=>255,'default'=>''] )
		->addColumn( 'age', 'integer', ['default'=>'0'] )
		->addColumn( 'sex', 'string', ['limit'=>255,'default'=>''] )
		->addColumn( 'color', 'string', ['limit'=>255,'default'=>''] )
		->addColumn( 'headno', 'integer', ['default'=>'0', 'null'=>true] )
		->addColumn( 'internalno', 'integer', ['default'=>'0', 'null'=>true] )
    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    ->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    ->addIndex(array('internalno'))
    	->addIndex(array('name'))
    	->addIndex(array('club'))
    	->addIndex(array('headno'))
		->create();
    }
}

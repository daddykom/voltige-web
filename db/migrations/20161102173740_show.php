<?php

use Phinx\Migration\AbstractMigration;

class Show extends AbstractMigration
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
    	$shows = $this->table('shows');
    	$shows->addColumn('clubno', 'integer', ['default'=>'0', 'null'=>true])
    	->addColumn('organization', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('organizer', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('address', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('showno', 'string', array('limit'=>255,'default'=>'', 'null'=>true))
    	->addColumn('showcity', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('from_dt', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('to_dt', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addIndex(array('showno'))
    	->addIndex(array('clubno'))
    	->create();
    }
}

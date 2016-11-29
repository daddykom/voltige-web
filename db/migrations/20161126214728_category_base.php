<?php

use Phinx\Migration\AbstractMigration;

class CategoryBase extends AbstractMigration
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
    	$category_bases = $this->table('category_bases');
    	$category_bases
    	->addColumn('short_description', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('mingroup', 'integer')
    	->addColumn('maxgroup', 'integer')
    	->addColumn('minage', 'integer')
    	->addColumn('maxage', 'integer')
    	->addColumn('minref', 'integer')
    	->addColumn('maxref', 'integer')
    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addIndex(array('short_description'))
    	->create();
    	 
    }
}

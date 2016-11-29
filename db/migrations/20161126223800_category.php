<?php

use Phinx\Migration\AbstractMigration;

class Category extends AbstractMigration
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
    	$shows = $this->table('categories');
    	$shows->addColumn('show_id', 'integer', ['default'=>'0'])
    	->addColumn('no', 'integer', ['default'=>'0'])
    	->addColumn('category_base_id', 'integer', ['default'=>'0'])
    	->addColumn('title', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('start_dt', 'date', ['default'=>'0000-00-00'])
    	->addColumn('fee', 'decimal', array('scale'=>2, 'precision'=>12, 'default'=>'0'))
    	->addColumn('type', 'string', array('limit'=>255,'default'=>''))
    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addForeignKey('show_id', 'shows', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
    	->addForeignKey('category_base_id', 'category_bases', 'id', array('delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'))
    	->create();
    	 
    }
}

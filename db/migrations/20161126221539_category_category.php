<?php

use Phinx\Migration\AbstractMigration;

class CategoryCategory extends AbstractMigration
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
    	$category_categories = $this->table('category_categories');
    	$category_categories
    	->addColumn('category_base_id', 'integer' )
    	->addColumn('category_base2_id', 'integer' )
    	->addColumn('cd_restriction_type', 'integer' )
    	->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    	->addForeignKey('category_base_id', 'category_bases', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
    	->addForeignKey('category_base2_id', 'category_bases', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
    	 ->create();
    	 
    }
}

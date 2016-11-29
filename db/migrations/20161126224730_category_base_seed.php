<?php

use Phinx\Migration\AbstractMigration;

class CategoryBaseSeed extends AbstractMigration
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
    public function up(){
    	$ret = $this->execute("INSERT INTO `category_bases` (`id`, `short_description`, `mingroup`, `maxgroup`, `minage`, `maxage`, `minref`, `maxref`, `created_at`, `updated_at`) VALUES(1, 'G-B', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00')");

    }
}

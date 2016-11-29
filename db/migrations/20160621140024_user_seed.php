<?php

use Phinx\Migration\AbstractMigration;

class UserSeed extends AbstractMigration
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
    	// execute()
    	$count = $this->execute('insert into users (userid,name,password,cd_user_group) values("daddykom","Charley Collins","$2y$10$hjXm4kaxE3LMhFUy9.jqU.UTVxUJfaNvWjQkLp8kI7aQCvQO4k64G",1)'); // returns the number of affected rows
    }
}

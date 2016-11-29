<?php

use Phinx\Migration\AbstractMigration;

class Code extends AbstractMigration
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
    	
    	if( !$this->hasTable('codes') ){
	    	$codes = $this->table('codes');
	    	$codes->addColumn('codename', 'string', array('limit'=>255))
	    	->addColumn('codetext', 'string', array('limit'=>255, 'null'=>true, 'default'=>''))
    		->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
			->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    	->addColumn('standard_code_cms_text_id', 'integer', ['default'=>'0'])
    		->addIndex(array('codename'))
	    	->create();
    	}
    	
    	if( !$this->hasTable('code_cmds') ){
	    	$codecmd = $this->table('code_cmds');
	    	$codecmd->addColumn('cmd', 'string', array('limit'=>255))
	    	->addColumn('code_id', 'integer', ['default'=>'0', 'null'=>true] )
	    	->addColumn('description', 'string', array('limit'=>255,'default'=>''))
    		->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
			->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
	    	->addIndex(array('code_id'))
	    	->create();
    	}
    	
    	if( !$this->hasTable('code_cmd_texts') ){
	    	$codecmdtext = $this->table('code_cmd_texts');
	    	$codecmdtext->addColumn('code_cmd_id', 'integer', ['default'=>'0'])
	    	->addColumn('text', 'string', array('limit'=>255))
    		->addColumn('is_deleted', 'boolean')
    		->addColumn('created_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
			->addColumn('updated_at', 'datetime', ['default'=>'0000-00-00 00:00:00'])
    		->create();
    	}
    	
    	
    }
    
    public function up(){
    	$sql = "INSERT INTO `codes` (`id`, `codename`, `codetext`, `created_at`, `updated_at`, `standard_code_cms_text_id`) VALUES(1, 'cdUserGroup', 'Benutzergruppe', '2016-11-21 13:16:37', '2016-11-21 13:16:37', 0);

				INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(1, 'programmer', 1, 'Programmierer', '2016-11-21 13:17:00', '2016-11-21 13:17:00');
				INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(2, 'admin', 1, 'Administrator', '2016-11-21 13:17:14', '2016-11-21 13:17:14');
				INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(3, 'referee', 1, 'Richter', '2016-11-21 13:19:33', '2016-11-21 13:19:33');
				
				INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(1, 1, 'Programmierer', 0, '2016-11-21 13:17:00', '2016-11-21 13:17:00');
				INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(2, 2, 'Administrator', 0, '2016-11-21 13:17:14', '2016-11-21 13:17:14');
				INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(3, 3, 'Richter', 0, '2016-11-21 13:19:33', '2016-11-21 13:19:33');
    			";
    	$this->execute($sql);
    }

}

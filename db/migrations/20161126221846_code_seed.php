<?php

use Phinx\Migration\AbstractMigration;

class CodeSeed extends AbstractMigration
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
    public function up()
    {
		$ret = $this->execute("INSERT INTO `codes` (`id`, `codename`, `codetext`, `created_at`, `updated_at`, `standard_code_cms_text_id`) VALUES(1, 'cdUserGroup', 'Benutzergruppe', '2016-11-21 13:16:37', '2016-11-21 13:16:37', 0)");
		$ret = $this->execute("INSERT INTO `codes` (`id`, `codename`, `codetext`, `created_at`, `updated_at`, `standard_code_cms_text_id`) VALUES(2, 'cdRestrictionType', 'Kategorie-Einschränkungen', '2016-11-21 13:16:37', '2016-11-21 13:16:37', 0)");
		$ret = $this->execute("INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(1, 'programmer', 1, 'Programmierer', '2016-11-21 13:17:00', '2016-11-21 13:17:00')");
		$ret = $this->execute("INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(2, 'admin', 1, 'Administrator', '2016-11-21 13:17:14', '2016-11-21 13:17:14')");
		$ret = $this->execute("INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(3, 'referee', 1, 'Richter', '2016-11-21 13:19:33', '2016-11-21 13:19:33')");
		$ret = $this->execute("INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(4, 'chngCat', 2, 'Kategoriewechsel erlaubt', '2016-11-21 13:19:33', '2016-11-21 13:19:33')");
		$ret = $this->execute("INSERT INTO `code_cmds` (`id`, `cmd`, `code_id`, `description`, `created_at`, `updated_at`) VALUES(5, 'rstrCat', 2, 'Kategorie verboten', '2016-11-21 13:19:33', '2016-11-21 13:19:33')");
		$ret = $this->execute("INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(1, 1, 'Programmierer', 0, '2016-11-21 13:17:00', '2016-11-21 13:17:00')");
		$ret = $this->execute("INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(2, 2, 'Administrator', 0, '2016-11-21 13:17:14', '2016-11-21 13:17:14')");
		$ret = $this->execute("INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(3, 3, 'Richter', 0, '2016-11-21 13:19:33', '2016-11-21 13:19:33')");
		$ret = $this->execute("INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(4, 4, 'Kategoriewechsel erlaubt', 0, '2016-11-21 13:19:33', '2016-11-21 13:19:33')");
		$ret = $this->execute("INSERT INTO `code_cmd_texts` (`id`, `code_cmd_id`, `text`, `is_deleted`, `created_at`, `updated_at`) VALUES(5, 5, 'Kategorie verboten', 0, '2016-11-21 13:19:33', '2016-11-21 13:19:33')");
    }
    
}

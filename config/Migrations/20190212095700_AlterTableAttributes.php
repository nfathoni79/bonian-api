<?php
use Migrations\AbstractMigration;

class AlterTableAttributes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('attributes');
        $table->removeIndex(['product_categories_id']);
        $table->dropForeignKey('product_categories_id');
        $table->save();
        $table->removeColumn('product_categories_id');
        $table->removeColumn('description');
        $table->save();
    }
}

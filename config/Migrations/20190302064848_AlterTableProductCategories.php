<?php
use Migrations\AbstractMigration;

class AlterTableProductCategories extends AbstractMigration
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
        $table = $this->table('product_categories');
        $table->addColumn('counter_view', 'integer', [
            'default' => 0,
            'after' => 'path',
            'null' => true,
            'limit' => 5
        ]);
        $table->update();
    }
}

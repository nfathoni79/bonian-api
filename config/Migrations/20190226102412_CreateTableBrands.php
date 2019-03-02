<?php
use Migrations\AbstractMigration;

class CreateTableBrands extends AbstractMigration
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
        $table = $this->table('brands');

        $table->addColumn('product_category_id', 'integer', [
            'default' => null,
            'after' => 'id',
            'null' => true,
            'limit' => 5
        ]);
        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'after' => 'product_category_id',
            'null' => true,
            'limit' => 5
        ]);
        $table->addColumn('lft', 'integer', [
            'after' => 'parent_id',
            'null' => false,
            'limit' => 5
        ]);
        $table->addColumn('rght', 'integer', [
            'after' => 'lft',
            'null' => false,
            'limit' => 5
        ]);
        $table->addColumn('name', 'string', [
            'after' => 'lft',
            'null' => false,
            'limit' => 100
        ]);
        $table->create();
    }
}

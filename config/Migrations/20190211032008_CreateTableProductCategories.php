<?php
use Migrations\AbstractMigration;

class CreateTableProductCategories extends AbstractMigration
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
        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'limit' => 5,
            'null' => true
        ]);
        $table->addColumn('lft', 'integer', [
            'default' => null,
            'limit' => 6
        ]);
        $table->addColumn('rght', 'integer', [
            'default' => null,
            'limit' => 6
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50
        ]);
        $table->addColumn('slug', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('description', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('path', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->create();
    }
}

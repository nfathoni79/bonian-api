<?php
use Migrations\AbstractMigration;

class CreateTableProductBranches extends AbstractMigration
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
        $table = $this->table('product_branches');
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('branch_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('is_option', 'integer', [
            'default' => null,
            'limit' => 1
        ]);
        $table->addColumn('stock', 'float', [
            'default' => null,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('branch_id');
        $table->addForeignKey('branch_id', 'branches', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}

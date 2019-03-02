<?php
use Migrations\AbstractMigration;

class AlterTableProductOptionValues extends AbstractMigration
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
        $table = $this->table('product_option_values');

        $table->addColumn('branch_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'after' => 'product_id',
            'null' => false
        ]);
        $table->addIndex('branch_id');
//        $table->addForeignKey('branch_id', 'branch_id', 'branches', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->update();
    }
}

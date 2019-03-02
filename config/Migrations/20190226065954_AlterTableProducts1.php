<?php
use Migrations\AbstractMigration;

class AlterTableProducts1 extends AbstractMigration
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
        $table = $this->table('products');

        $table->addColumn('product_warranty_id', 'integer', [
            'default' => 1,
            'after' => 'product_weight_class_id',
            'null' => true
        ]);
        $table->update();
    }
}

<?php
use Migrations\AbstractMigration;

class AlterTableProducts2 extends AbstractMigration
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

        $table->addColumn('brand_id', 'integer', [
            'default' => null,
            'after' => 'product_warranty_id',
            'null' => true
        ]);
        $table->update();
    }
}

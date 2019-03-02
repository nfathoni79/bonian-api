<?php
use Migrations\AbstractMigration;

class AlterProductAttributes extends AbstractMigration
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
        $table = $this->table('product_attributes');

        $table->addColumn('attribute_name_id', 'integer', [
            'default' => null,
            'after' => 'product_id',
            'null' => true,
        ]);

        $table->addIndex('attribute_name_id');

        $table->update();
    }
}

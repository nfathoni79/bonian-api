<?php
use Migrations\AbstractMigration;

class AlterProducts extends AbstractMigration
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


        $table->changeColumn('price', 'float', [
            'default' => 0,
            'null' => true
        ]);

        $table->changeColumn('shipping', 'integer', [
            'default' => 0,
            'null' => true
        ]);

        $table->changeColumn('highlight', 'text', [
            'default' => null,
            'null' => true
        ]);

        $table->changeColumn('condition', 'text', [
            'default' => null,
            'null' => true
        ]);

        $table->changeColumn('profile', 'text', [
            'default' => null,
            'null' => true
        ]);

        //$table->addIndex('slug');

        $table->renameColumn('price_discount', 'price_sale');

        $table->update();
    }
}

<?php
use Migrations\AbstractMigration;

class CreateTableProductOptionValues extends AbstractMigration
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
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('option_value_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);

        $table->addColumn('key_form', 'integer', [
            'default' => null,
            'limit' => 2
        ]);


        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('option_value_id');
        $table->addForeignKey('option_value_id', 'option_values', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}

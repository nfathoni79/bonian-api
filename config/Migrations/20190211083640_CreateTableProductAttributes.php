<?php
use Migrations\AbstractMigration;

class CreateTableProductAttributes extends AbstractMigration
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
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('attribute_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('value', 'text', [
            'default' => null
        ]);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('attribute_id');
        $table->addForeignKey('attribute_id', 'attributes', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}

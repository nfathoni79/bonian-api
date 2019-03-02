<?php
use Migrations\AbstractMigration;

class AlterProductStockMutations extends AbstractMigration
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
        $table = $this->table('product_stock_mutations');

        if ($table->hasForeignKey(['product_id'], 'product_stock_mutations_ibfk_5')) {
            $table->dropForeignKey('product_id', 'product_stock_mutations_ibfk_5')
                ->save();

            $table->addForeignKey('product_option_stock_id', 'product_option_stocks', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        }

        $table->changeColumn('product_stock_mutation_type_id', 'integer', [
           'default' => null,
           'limit' => 3,
           'null' => true
        ]);

        $table->update();
    }
}

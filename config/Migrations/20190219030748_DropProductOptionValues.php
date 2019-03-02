<?php
use Migrations\AbstractMigration;

class DropProductOptionValues extends AbstractMigration
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

        //product_option_stocks

        $table = $this->table('product_stock_mutations');
        if ($table->hasForeignKey(['product_option_value_id'])) {

            $table->dropForeignKey('product_option_value_id')
                ->save();
            $table
                ->removeIndex(['product_option_value_id'])
                ->save();

            $table
                ->removeColumn('product_option_value_id')
                ->save();

            $table
                ->addColumn('product_option_stock_id', 'integer', [
                    'default' => null,
                    'null' => true,
                    'after' => 'branch_id'
                ])
                ->addIndex('product_option_stock_id')
                ->addForeignKey('product_id', 'product_option_stocks', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
                ->save();

        }




        $table = $this->table('product_option_values');

        if ($table->hasForeignKey(['product_id'])) {
            $table->dropForeignKey('product_id')
                ->save();
        }

        if ($table->hasForeignKey(['option_value_id'])) {

            try {
                $table->dropForeignKey('option_value_id')
                    ->save();
            } catch (\Exception $e) {

            }
        }

        if ($table->hasIndex('product_id')) {
            $table->removeIndex(['product_id']);
        }

        if ($table->hasIndex('option_value_id')) {
            $table->removeIndex(['option_value_id']);
        }

        if ($table->hasIndex('branch_id')) {
            $table->removeIndex(['branch_id']);
        }


        $table->drop()
            ->save();
    }
}

<?php
use Migrations\AbstractMigration;

class AlterProductOptionPrices extends AbstractMigration
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
        $table = $this->table('product_option_prices');

        if ($table->hasColumn('weight')) {
            $table->removeColumn('weight');
        }

        if ($table->hasColumn('stock')) {
            $table->removeColumn('stock');
        }

        if ($table->hasColumn('width')) {
            $table->removeColumn('width');
        }

        if ($table->hasColumn('length')) {
            $table->removeColumn('length');
        }

        if ($table->hasColumn('height')) {
            $table->removeColumn('height');
        }

        if ($table->hasIndex('key_form')) {
            $table->removeIndex(['key_form']);
        }

        if ($table->hasColumn('key_form')) {
            $table->removeColumn('key_form');
        }


        $table->update();
    }
}

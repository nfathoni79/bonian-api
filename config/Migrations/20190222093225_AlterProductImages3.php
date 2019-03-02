<?php
use Migrations\AbstractMigration;

class AlterProductImages3 extends AbstractMigration
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
        $table = $this->table('product_images');

        $table->addColumn('idx', 'integer', [
            'default' => null,
            'limit' => 5,
            'after' => 'type',
            'null' => true
        ]);


        $table->update();
    }
}

<?php
use Migrations\AbstractMigration;

class AlterProductImages extends AbstractMigration
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

        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 100,
            'after' => 'product_id',
            'null' => true
        ]);

        $table->update();
    }
}

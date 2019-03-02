<?php
use Migrations\AbstractMigration;

class AlterProductImageSizes extends AbstractMigration
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
        $table = $this->table('product_image_sizes');

        $table->addColumn('size', 'integer', [
            'default' => null,
            'limit' => 8,
            'null' => true
        ]);

        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true
        ]);

        $table->update();
    }
}

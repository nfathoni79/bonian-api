<?php
use Migrations\AbstractMigration;

class CreateTableProductImageSizes extends AbstractMigration
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
        $table->addColumn('product_image_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('dimension', 'string', [
            'default' => null,
            'limit' => 9
        ]);
        $table->addColumn('path', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addIndex('product_image_id');
        $table->addForeignKey('product_image_id', 'product_images', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}

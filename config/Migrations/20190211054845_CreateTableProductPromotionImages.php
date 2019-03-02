<?php
use Migrations\AbstractMigration;

class CreateTableProductPromotionImages extends AbstractMigration
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
        $table = $this->table('product_promotion_images');
        $table->addColumn('product_promotion_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('image', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('dimension', 'string', [
            'default' => null,
            'limit' => 9
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null
        ]);
        $table->addIndex('product_promotion_id');
        $table->addForeignKey('product_promotion_id', 'product_promotions', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}

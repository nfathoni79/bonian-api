<?php
use Migrations\AbstractMigration;

class AlterProducts2 extends AbstractMigration
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
        $table = $this->table('products');
        $table->addColumn('video_url', 'string', [
            'default' => null,
            'null' => true,
            'limit' => 255,
            'after' => 'point'
        ]);

        $table->changeColumn('point', 'integer', [
            'default' => 0,
            'null' => true,
            'limit' => 8
        ]);

        $table->update();
    }
}

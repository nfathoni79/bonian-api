<?php
use Migrations\AbstractMigration;

class AlterTableBanners extends AbstractMigration
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
        $table = $this->table('banners');
        $table->addColumn('url', 'string', [
            'null' => true,
            'limit' => 255,
            'after' => 'position'
        ]);
        $table->update();
    }
}

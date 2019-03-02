<?php
use Migrations\AbstractMigration;

class CreateTableVouchers extends AbstractMigration
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
        $table = $this->table('vouchers');
//        id	code_voucher	date_start	date_end	qty	type 1 :persen atau 2 :discount	value berupa value % (30), atau cashbak 100.0000	status 1 : active, 2 : not active

        $table->addColumn('code_voucher', 'string', [
            'default' => null,
            'limit' => 8
        ]);
        $table->addColumn('date_start', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('date_end', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('qty', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('type', 'integer', [
            'default' => null,
            'limit' => 1,
            'comment' => '1 :persen atau 2 :discount'
        ]);
        $table->addColumn('value', 'float', [
            'default' => null,
            'comment' => 'berupa value % (30), atau cashbak 100.0000'
        ]);
        $table->addColumn('status', 'integer', [
            'default' => null,
            'limit' => 1,
            'comment' => '1 : active, 2 : not active'
        ]);
        $table->create();
    }
}

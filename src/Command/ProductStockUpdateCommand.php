<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * ProductStockUpdate command.
 * @property \App\Model\Table\ProductOptionStocksTable $ProductOptionStocks
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductStockUpdateCommand extends Command
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductOptionStocks');
        $this->loadModel('Products');
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('checking product stock');

        /**
         * @var \App\Model\Entity\ProductOptionStock[] $data
         */
        $data = $this->ProductOptionStocks->find();

        $data = $data
            ->select([
                'product_id',
                'stock' => $data->func()->sum('stock')
            ])
            ->group([
                'product_id'
            ])
            ->having([
                'stock' => 0
            ]);

        if (!$data->isEmpty()) {
            foreach($data as $val) {
                $this->Products->query()
                    ->update()
                    ->set([
                        'product_status_id' => 2,
                        'product_stock_status_id' => 2
                    ])
                    ->where([
                        'id' => $val->product_id
                    ])
                    ->execute();

                $io->out('processing update stock ' . $val->product_id);

            }
        }

        $io->out('finish checking product stock');

    }
}

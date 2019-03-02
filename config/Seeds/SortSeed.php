<?php

use Migrations\AbstractSeed;

class SortSeed extends AbstractSeed
{
    public function run()
    {
         $this->call('AcosSeed');
         $this->call('ArosSeed');
         $this->call('ArosAcosSeed');
         $this->call('ProvincesSeed');
         $this->call('CourriersSeed');
         $this->call('CitiesSeed');
         $this->call('SubdistrictsSeed');
         $this->call('GroupsSeed');
         $this->call('CustomerGroupsSeed');
         $this->call('CustomerStatusesSeed');
         $this->call('CustomerMutationAmountTypesSeed');
         $this->call('CustomerMutationPointTypesSeed');
         $this->call('CustomerPointRatesSeed');
         $this->call('OptionTypesSeed');
         $this->call('OptionsSeed');
         $this->call('OptionValuesSeed');
         $this->call('OrderStatusesSeed');
         $this->call('ProductStatusesSeed');
         $this->call('ProductStockStatusesSeed');
         $this->call('ProductWeightClassesSeed');
         $this->call('UserStatusSeed');
         $this->call('UsersSeed');
         $this->call('ProductCategoriesSeed');

    }
}
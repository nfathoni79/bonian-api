<?php

use Migrations\AbstractSeed;

class SortSeed extends AbstractSeed
{
    public function run()
    {
         $this->call('TagsSeed'); 
         $this->call('ProvincesSeed'); 
         $this->call('CitiesSeed'); 
         $this->call('SubdistrictsSeed'); 
         $this->call('BranchesSeed'); 
         $this->call('ProductCategoriesSeed'); 
         $this->call('BrandsSeed'); 
         $this->call('AttributesSeed'); 
         $this->call('CourriersSeed'); 
         $this->call('ProductStockStatusesSeed'); 
         $this->call('ProductWeightClassesSeed'); 
         $this->call('ProductStatusesSeed'); 
         $this->call('ProductsSeed'); 
         $this->call('ProductToCourriersSeed'); 
         $this->call('ProductToCategoriesSeed'); 
         $this->call('ProductTagsSeed'); 
         $this->call('ProductStockMutationTypesSeed'); 
         $this->call('OptionsSeed'); 
         $this->call('OptionTypesSeed'); 
         $this->call('OptionValuesSeed'); 
         $this->call('ProductOptionPricesSeed'); 
         $this->call('ProductOptionValueListsSeed'); 
         $this->call('ProductOptionStocksSeed'); 
         $this->call('ProductImagesSeed'); 
         $this->call('ProductImageSizesSeed'); 
         $this->call('ProductAttributesSeed'); 
         $this->call('ProductWarrantiesSeed'); 
         $this->call('GameWheelsSeed'); 

    }
}
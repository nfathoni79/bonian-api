<?php

use Migrations\AbstractSeed;

class CustomSeed extends AbstractSeed
{
    public function run()
    {
         $this->call('AttributesSeed'); 
         $this->call('BranchesSeed'); 
         $this->call('TagsSeed'); 
         $this->call('ProductToCourriersSeed'); 
         $this->call('ProductToCategoriesSeed'); 
         $this->call('ProductTagsSeed'); 
         $this->call('ProductStockMutationTypesSeed'); 
         $this->call('ProductStatusesSeed'); 
         $this->call('ProductOptionValueListsSeed'); 
         $this->call('ProductOptionStocksSeed'); 
         $this->call('ProductOptionPricesSeed'); 
         $this->call('ProductImageSizesSeed'); 
         $this->call('ProductImagesSeed'); 
         $this->call('ProductCategoriesSeed'); 
         $this->call('ProductAttributesSeed'); 
         $this->call('ProductsSeed'); 
         $this->call('OptionValuesSeed'); 
         $this->call('BrandsSeed'); 
         $this->call('BranchesSeed'); 
         $this->call('AttributesSeed'); 
         $this->call('ProductWarrantiesSeed'); 
         $this->call('GameWheelsSeed'); 
         $this->call('OptionsSeed'); 
         $this->call('SubdistrictsSeed'); 
         $this->call('ProvincesSeed'); 
         $this->call('ProductStockStatusesSeed'); 
         $this->call('CourriersSeed'); 
         $this->call('CitiesSeed'); 
         $this->call('ProductWeightClassesSeed'); 
         $this->call('OptionTypesSeed'); 

    }
}
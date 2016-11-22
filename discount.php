<?php

class Discount {

    protected $discount = '';
    protected $discountType = '';
    protected $itemFree = '';
    protected $quantityItemFree = '';
    protected $newValue = '';

    public function setOrder($order) {

        $this->discount = 0.0;
        $this->discountType = '';
        $this->itemFree = '';
        $this->quantityItemFree = '';
        $this->newValue = $order->total;

        $this->getDiscountByRevenueOver1000($order);
        $this->get1FreeItenOver5ItensBuyedInCategory2($order);
        $this->get20percentDiscountCategory1Over2Itens($order);
    }

    protected function getDiscountByRevenueOver1000($order) {

        $customers = new Customer();
        $listOfCustumers = $customers->getCustomersDetais();
        $index = $order->{'customer-id'} - 1;

        if (array_key_exists($index, $listOfCustumers) && $listOfCustumers[$index]->revenue >= 1000) {

            $this->discount = 10.0;
            $this->discountType = 'Total Order';
             $this->newValue = round($this->newValue - ($this->newValue * ($this->discount/100)),2);
        } 
        
    }

    protected function get1FreeItenOver5ItensBuyedInCategory2($order) {

        $product = new Product();
        $listOfProducts = $product->getListOfProducts();
        foreach ($order->{'items'} as $key => $orderValue) {

            foreach ($listOfProducts as $productsValue) {

                if ($productsValue->{'id'} == $orderValue->{'product-id'}) {
                    $category = $productsValue->{'category'};
                    break;
                }
            }

            if ($category == 2 && $orderValue->{'quantity'} >= 5) {

                $this->quantityItemFree = floor($orderValue->{'quantity'} / 5);
                $this->itemFree = $orderValue->{'product-id'};
                
                $this->newValue = round($this->newValue - ($orderValue->{'unit-price'}*$this->quantityItemFree),2);

            }
        }
    }

    protected function get20percentDiscountCategory1Over2Itens($order) {

        $product = new Product();
        $listOfProducts = $product->getListOfProducts();
        $lowestPrice = null;
        foreach ($order->{'items'} as $key => $orderValue) {

            foreach ($listOfProducts as $productsValue) {

                if ($productsValue->{'id'} == $orderValue->{'product-id'}) {
                    $category = $productsValue->{'category'};
                    break;
                }
            }

            if ($category == 1 && $orderValue->{'quantity'} >= 2 && (is_null($lowestPrice) || $lowestPrice > $orderValue->{'total'})) {
                $lowestPrice = $orderValue->{'total'};
                $this->discount = 20.0;
                $this->discountType = $orderValue->{'product-id'};
                $this->newValue = round($this->newValue - ($lowestPrice * ($this->discount/100)),2);

            }
        }
    }

    public function getFinalDiscount() {

        return array('discount' => $this->discount,
            'discountType' => $this->discountType,
            'itemFree' => $this->itemFree,
            'quantityItemFree' => $this->quantityItemFree,
            'newValue' => $this->newValue);
    }

}

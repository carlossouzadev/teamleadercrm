<?php

class product {

    public function getListOfProducts(){
            
        return json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/products.json'));
    }
}


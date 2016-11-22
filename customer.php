<?php
class customer {

    public function getCustomersDetais(){
            
        return json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/customers.json'));
    }
    
}

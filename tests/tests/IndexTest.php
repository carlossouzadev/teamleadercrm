<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

use Slim\Environment;

class IndexTest extends PHPUnit_Framework_TestCase {

    public function request($method, $path, $options = array()) {
// Capture STDOUT
        ob_start();

// Prepare a mock environment
        Environment::mock(array_merge(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => 'slim-test.dev',
                        ), $options));

        $app = new \Slim\Slim();
        $this->app = $app;
        $this->request = $app->request();
        $this->response = $app->response();

// Return STDOUT
        return ob_get_clean();
    }

    public function get($path, $options = array()) {
        $this->request('GET', $path, $options);
    }

    public function post($path, $options = array()) {
        $this->request('POST', $path, $options);
    }

    public function delete($path, $options = array()) {
        $this->request('DELETE', $path, $options);
    }

    public function testIndex() {
        $this->get('/');
        $this->assertEquals('200', $this->response->status());
    }

    public function testFreeItem() {
        $result = $this->prepareUrl('discounts', 'id=1');
        $this->assertEquals('{"discount":0,"discountType":"","itemFree":"B102","quantityItemFree":2,"newValue":39.92}', $result);
    }

    public function testDiscount10PercentToalAndFreeItem() {
        $result = $this->prepareUrl('discounts', 'id=2');
        $this->assertEquals('{"discount":10,"discountType":"Total Order","itemFree":"B102","quantityItemFree":1,"newValue":17.47}', $result);
    }

    public function testDiscount20PercentInItem() {
        $result = $this->prepareUrl('discounts', 'id=3');
        $this->assertEquals('{"discount":20,"discountType":"A101","itemFree":"","quantityItemFree":"","newValue":65.1}', $result);
    }

    public function testOrderNotExist() {

        $result = $this->prepareUrl('discounts', 'id=4');
        $this->assertEquals('{"result":"Order not exist"}', $result);
    }

    public function testMissingParametrs() {
        $result = $this->prepareUrl('discounts', '');
        $this->assertEquals('{"result":"Order not exist"}', $result);
    }

    protected function prepareUrl($method, $params) {
        $useragent = "Fake Mozilla 5.0 ";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_URL, 'http://www.carlossouza.com/teamleader/' . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

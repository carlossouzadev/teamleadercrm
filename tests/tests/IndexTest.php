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

    public function testpostsIs200() {
        $this->get('/posts?id=1');
        $this->assertEquals('200', $this->response->status());
    }

    public function testgetpostsIs200() {
        $this->get('/posts');
        $this->assertEquals('200', $this->response->status());
    }

    public function testaddPostIs200() {
        $this->post('/addPost');
        $this->assertEquals('200', $this->response->status());
    }

    public function testdeletePostIs200() {
        $this->delete('/deletePost?id=1');
        $this->assertEquals('200', $this->response->status());
    }

    public function testupdatePostIs200() {
        $this->post('/updatePost?id');
        $this->assertEquals('200', $this->response->status());
    }
    

}

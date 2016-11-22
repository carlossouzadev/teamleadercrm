<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'discount.php';
require 'customer.php';
require 'product.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
// GET route
$app->get(
        '/', function () {
    
}
);



// POST route
$app->post(
        '/post', function () {
    
}
);

// PUT route
$app->put(
        '/put', function () {
    
}
);

// PATCH route
$app->patch('/patch', function () {
    
});

// DELETE route
$app->delete(
        '/delete', function () {
    
}
);


/**
 * Step 4: Configuring the routes
 */
$app->post('/discounts', 'discountByIdOrder');

/**
 * Step 5: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

function logMsg($msg, $level = 'info') {

    $levelStr = '';
    switch ($level) {
        case 'info':
            $levelStr = 'INFO';
            break;

        case 'warning':
            $levelStr = 'WARNING';
            break;

        case 'error':
            $levelStr = 'ERROR';
            break;
    }

    $date = date('Y-m-d H:i:s');

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // formate the message
    // 1o: actutal date
    // 2o: mensagem level (INFO, WARNING ou ERROR)
    // 3o: user IP
    // 4o: the message
    // 5o: /n
    $logMessage = sprintf("[%s] [%s] [%s]: %s%s", $date, $levelStr, $ip, $msg, PHP_EOL);

    $file = 'log/ApiLog_' . date('d_m_Y') . '.log';

    file_put_contents($file, $logMessage, FILE_APPEND);
}

function discountByIdOrder() {
    logMsg("#####################################################");
    logMsg("discountByIdOrder CALLED");

    $app = new \Slim\Slim();
    $discount = new Discount();
    
    if (is_numeric($app->request->params('id'))) {     
        $id = $app->request->params('id');
    }else{
        echo json_encode(array('result' => 'Order not exist'));
        logMsg("RESULT RETURNED: Order not exist");
        exit;
    }

            
    $order = json_decode(@file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/example-orders/order'.$id.'.json'));

  
    if(is_null($order)) {
        
        echo json_encode(array('result' => 'Order not exist'));
        logMsg("RESULT RETURNED: Order not exist");
        exit;
        
    }else{
        $discount->setOrder($order);
        echo json_encode($discount->getFinalDiscount($order));
    }


    logMsg("#####################################################");
}



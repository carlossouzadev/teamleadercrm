<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

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
$app->get('/posts', 'getPosts');
$app->get('/tags', 'getTags');

$app->post('/addPost', 'addPost');
$app->post('/addTag', 'addTag');

$app->get('/post/:id', 'getPostById');
$app->get('/tag/:id', 'getTagById');
$app->get('/tagByPost/:id', 'getTagByIdPost');

$app->post('/updatePost/:id', 'updatePost');
$app->post('/updateTag/:id', 'updateTag');

$app->delete('/deletePost/:id', 'deletePost');
$app->delete('/deleteTag/:id', 'deleteTag');

/**
 * Step 5: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

function getConn() {
    try {
        return new PDO('mysql:host=localhost;dbname=crud_carlos', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function getPosts() {
    try {
        $stmt = getConn()->query("SELECT * FROM posts s");
        $categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo "{posts:" . json_encode($categorias) . "}";
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function getTags() {
    try {
        $stmt = getConn()->query("SELECT * FROM tags WHERE status = 1");
        $categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo "{tags:" . json_encode($categorias) . "}";
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function addPost() {
    try {
        $request = \Slim\Slim::getInstance()->request();

        $sql = "INSERT INTO posts (title,body) values (:title,:body) ";
        $conn = getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("title", $request->post('title'));
        $stmt->bindParam("body", $request->post('body'));
        $stmt->execute();
        echo json_encode(array('id_inserted' => $conn->lastInsertId()));
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function addTag() {
    try {
        $request = \Slim\Slim::getInstance()->request();

        $sql = "INSERT INTO tags (id_post,name) values (:id_post,:name) ";
        $conn = getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id_post", $request->post('id_post'));
        $stmt->bindParam("name", $request->post('name'));
        $stmt->execute();
        echo json_encode(array('id_inserted' => $conn->lastInsertId()));
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function getPostById($id) {
    if (!isset($id)) {
        echo "{'message':'missing id'}";
    }
    try {
        $conn = getConn();
        $sql = "SELECT * FROM posts WHERE id=:id and status = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $produto = $stmt->fetchObject();

        echo json_encode($produto);
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function getTagById($id) {
    if (!isset($id)) {
        echo "{'message':'missing id'}";
    }
    try {
        $conn = getConn();
        $sql = "SELECT * FROM tags WHERE id=:id and status = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $produto = $stmt->fetchObject();

        echo json_encode($produto);
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}


function getTagByIdPost($id) {
    if (!isset($id)) {
        echo "{'message':'missing id'}";
    }
    try {
        $conn = getConn();
        $sql = "SELECT * FROM tags WHERE id_post=:id and status = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $produto = $stmt->fetchObject();

        echo json_encode($produto);
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function updatePost($id) {
    if (!isset($id)) {
        return "{'message':'missing id'}";
        exit;
    }

    try {
        $request = \Slim\Slim::getInstance()->request();

        $sql = "UPDATE posts SET title=:title,body=:body WHERE   id=:id";
        $conn = getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("title", $request->post('title'));
        $stmt->bindParam("body", $request->post('body'));
        $stmt->bindParam("id", $id);
        $stmt->execute();

        echo json_encode(array('Updated'));
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function updateTag($id) {
    if (!isset($id)) {
        return "{'message':'missing id'}";
        exit;
    }

    try {
        $request = \Slim\Slim::getInstance()->request();

        $sql = "UPDATE tags SET id_post=:id_post,name=:name WHERE   id=:id";
        $conn = getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id_post", $request->post('id_post'));
        $stmt->bindParam("name", $request->post('name'));
        $stmt->bindParam("id", $id);
        $stmt->execute();

        echo json_encode(array('Updated'));
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function deletePost($id) {
    if (!isset($id)) {
        echo "{'message':'missing id'}";
    }
    try {
        $sql = "UPDATE posts SET status = 0 WHERE id=:id";
        $conn = getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        echo "{'message':'Post deleted'}";
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

function deleteTag($id) {
    if (!isset($id)) {
        echo "{'message':'missing id'}";
    }
    try {
        $sql = "UPDATE tags SET status = 0 WHERE id=:id";
        $conn = getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        echo "{'message':'Post deleted'}";
    } catch (Exception $exc) {
        echo json_encode(array($exc->getTraceAsString()));
    }
}

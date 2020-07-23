<?php

declare(strict_types=1);

require_once("requires.php");

switch (getRequestParam("command")) {
    case "cardslist":
        $repo = new \cards\DatabaseRepository();
        $response = new \response\RequestResponseJson();
        $controller = new \cards\Controller($repo, $response);
        $controller->cardsList();
        break;
    case "card":
        $repo = new \cards\DatabaseRepository();
        $response = new \response\RequestResponseJson();
        $controller = new \cards\Controller($repo, $response);
        $controller->selectCard();
        break;
    case "cardscreate";
        $repo = new \cards\DatabaseRepository();
        $response = new \response\RequestResponseJson();
        $controller = new \cards\Controller($repo, $response);
        $controller->cardsCreate();
        break;
    case "cardsupdate":
        $repo = new \cards\DatabaseRepository();
        $response = new \response\RequestResponseJson();
        $controller = new \cards\Controller($repo, $response);
        $controller->cardsUpdate();
        break;
    case "carddelete":
        $repo = new \cards\DatabaseRepository();
        $response = new \response\RequestResponseJson();
        $controller = new \cards\Controller($repo, $response);
        $controller->cardDelete();
        break;
    default :
        echo "Nothing to do";
}

function getRequestParam($paramName){
    $paramValue = $_GET[$paramName] ?? null;
    if (!$paramValue) {
        $paramValue = $_POST[$paramName] ?? null;
    }

    return $paramValue;
}
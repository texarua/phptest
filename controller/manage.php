<?php 
include_once('../config/dev.php');
require_once('../model/ToDo.php');
require_once('../model/ToDoDAO.php');


if(!isset($_GET['type'])) {
    all();
    exit;
} else {
    switch ($_GET['type']) {
        case 'add':
            add($_POST);
            break;
        
        default:
            # code...
            break;
    }
}

function all() {
    $todoDAO = new ToDoDAO();
    $listToDo = $todoDAO->getAllToDo();
    require_once('../view/index.php');
}

function add($todo) {
    $todoDAO = new ToDoDAO();
    $result = $todoDAO->addToDo($todo);
    echo $result;
}
?>
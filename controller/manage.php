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
        case 'del':
            del($_POST['id']);
            break;
        case 'show':
            show($_POST['id']);
            break;
        case 'edit':
            edit($_POST['id'], $_POST);
            break;
        case 'allAjax':
            getAllAjax();
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

function getAllAjax() {
    $todoDAO = new ToDoDAO();
    $listToDo = $todoDAO->getAllToDo();
    echo json_encode($listToDo);
}

function del($id) {
    $todoDAO = new ToDoDAO();
    $result = $todoDAO->delToDo($id);
    echo $result;
}

function edit($id, $todo) {
    $todoDAO = new ToDoDAO();
    $result = $todoDAO->editToDo($id, $todo);
    echo $result;
}

function show($id) {
    $todoDAO = new ToDoDAO();
    $result = $todoDAO->getToDoDetail($id);
    echo json_encode($result);
}

function add($todo) {
    $todoDAO = new ToDoDAO();
    $result = $todoDAO->addToDo($todo);
    $todo['id'] = $result;
    echo json_encode($todo);
}
?>
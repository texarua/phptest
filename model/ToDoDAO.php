<?php
include_once "../config/dev.php";
include_once "../model/ToDo.php";
class ToDoDAO
{
    public $conn = null;
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DATABASE, 'root', '');
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getAllToDo()
    {
        return $this->conn->query('select * from todos')->fetchAll(PDO::FETCH_CLASS, 'ToDoModel');
    }

    public function getToDoDetail($id)
    {

    }

    public function addTodo($todo)
    {
        $todo = json_decode($todo);
        unset($todo['id']);
        $sql = "INSERT INTO todos (work_name,starting_date,ending_date) VALUES (?,?,?)";
        return $todo['work_name'];
        return $this->conn->prepare($sql)->execute([$todo['work_name'],$todo['starting_date'], $todo['ending_date']]);
    }

    public function editToDo($id, $todo)
    {

    }

    public function delToDo($id)
    {

    }
}
?>
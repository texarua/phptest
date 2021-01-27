<?php
include_once "../config/dev.php";
include_once "../model/ToDo.php";
class ToDoDAO
{
    public $conn = null;
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DATABASE, USER_NAME, PASSWORD);
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
        return $this->conn->query('select * from todos where id='.$id)->fetchAll(PDO::FETCH_CLASS, 'ToDoModel');
    }

    public function addTodo($todo)
    {
        unset($todo['id']);
        $sql = "INSERT INTO todos (work_name,starting_date,ending_date,status) VALUES (?,?,?,?)";
        $this->conn->prepare($sql)->execute(array_values($todo));
        return $this->conn->lastInsertId();
    }

    public function editToDo($id, $todo)
    {
        unset($todo['id']);
        $sql = "UPDATE todos set work_name = ?,starting_date = ?,ending_date =?, status = ? WHERE id = ".$id;
        return $this->conn->prepare($sql)->execute(array_values($todo));
    }

    public function delToDo($id)
    {
        $sql = "DELETE FROM todos WHERE id=?";
        return $this->conn->prepare($sql)->execute([$id]);
    }
}
?>
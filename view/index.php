<?php 
    const STATUS = [
        'PLANNING' => 1,
        'DOING' => 2,
        'COMPLETE' => 3,
    ];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP-TEST</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

    <style>
        .row {margin-top: 30px;}
    </style>
</head>
<body>
    <div class="row">
        <div class="col-6">
            <table class="table table-dark">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Work Name</th>
                <th scope="col">Starting Date</th>
                <th scope="col">Ending Date</th>
                <th scope="col">Status</th>
                <th scope="col">Options</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($listToDo)): ?>
                    <?php foreach($listToDo as $todo) { ?>
                <tr>
                    <th scope="row"><?php echo $todo->id?></th>
                    <td><?php echo $todo->work_name?></td>
                    <td><?php echo $todo->starting_date?></td>
                    <td><?php echo $todo->ending_date?></td>
                    <td><?php echo array_search($todo->status, STATUS)?></td>
                    <td>
                    <button class="btn-danger edit" data-id="<?php echo $todo->id?>">Show</button>    
                    <button class="btn-danger delete" data-id="<?php echo $todo->id?>">Delete</button>
                    </td>
                </tr>
                <?php }?>
                <?php endif ?>
            </tbody>
            </table>
            <div class="container d-flex justify-content-center">
                <div class=" w-100">
                    <h4>Add / Edit ToDo <button id="add" data-href="" class="btn btn-warning mt-2 px-5">Add New Todo <i class="fa fa-long-arrow-right ml-2 mt-1"></i></button></h4>
                    <div class="row">
                    <div class="col-md-6"> <input type="text" readonly="readonly" name="id" class="form-control" placeholder="Id" /> </div>
                        <div class="col-md-6"> <input type="text" name="work_name" class="form-control" placeholder="Works Name" /> </div>
                        <div class="col-md-6"> <input type="text" name="starting_date" class="form-control date" placeholder="Starting Date" /> </div>
                        <div class="col-md-6"> <input type="text" name="ending_date" class="form-control date" placeholder="Ending Date" /> </div>
                        <div class="col-md-6"> <input type="text" name="status" class="form-control" placeholder="Status" /> </div>
                        <div class="col-md-6"> ( 1: PLANNING, 2: DOING, 3: COMPLETE ) </div>
                    </div>
                    <div class="pull-left"> <button data-href="" class="btn btn-success mt-2 px-5">Submit <i class="fa fa-long-arrow-right ml-2 mt-1"></i></button></div>
                </div>
            </div>
        </div>
        <div class="col-6">
        <h3>Todo Calendar</h3>
            <div id='calendar'></div>          
        </div>
    </div>
</body>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script>
    $(document).find('.date').datepicker({
        autoclose: true,
        dateFormat: "yy-mm-dd"
    });
</script>
<script>
    $("#add").click(function() {
        $('input').val('');
        $('.btn-success').text('Add');
        $('.btn-success').attr('data-href', '/controller/manage.php?type=add')
    })
    $(document).on('click',".edit",function() {
        $('.btn-success').text('Edit');
        $('.btn-success').attr('data-href', '/controller/manage.php?type=edit')
        let id = $(this).attr('data-id');
        $.ajax({
            url: '/controller/manage.php?type=show',
            method: 'POST',
            data: {id},
            success: function (response) {
                let todo = JSON.parse(response)[0];
                $('input[name="id"]').val(todo.id);
                $('input[name="work_name"]').val(todo.work_name);
                $('input[name="starting_date"]').val(todo.starting_date);
                $('input[name="ending_date"]').val(todo.ending_date);
                $('input[name="status"]').val(todo.status);
            }
        })
    })
    $(document).on('click','.btn-success', function () {
            let inputData = $('input').serialize();
            let href = $(this).attr('data-href');
            $.ajax({
                url: href,
                method: 'POST',
                data: inputData,
                success: function (response) {
                    loadAllAjax();
                }
            })
        });

    function getContentToDo(todo) {
        return '<tr>'
                    +'<th scope="row">'+todo.id+'</th>'
                    +'<td scope="row">'+todo.work_name+'</td>'
                    +'<td scope="row">'+todo.starting_date+'</td>'
                    +'<td scope="row">'+todo.ending_date+'</td>'
                    +'<td scope="row">'+todo.status+'</td>'
                    +'<td><button class="btn-danger edit" data-id="'+todo.id+'">Show</button>'
                    +'<button class="btn-danger delete" data-id="'+todo.id+'" id="delete">Delete</button>'
                    +'</td>'
                +'</tr>';
    }

    $(document).on('click','.delete', function () {
       let id = $(this).attr('data-id');
       let element = $(this);
        $.ajax({
            url: '/controller/manage.php?type=del',
            method: 'POST',
            data: {id},
            success: function (response) {
                $(element).closest('tr').remove();
            }
        })
    });

    function getAllAjax() {
        $.ajax({
            url: '/controller/manage.php?type=allAjax',
            method: 'POST',
            success: function (response) {
                let todos = JSON.parse(response);
                let content = '';
                todos.forEach(function (todo) {
                    content += getContentToDo(todo);
                })
                $('.table-dark tbody').html(content);
                    
            }
        })
    }

    function loadAllAjax() {
        $.ajax({
            url: '/controller/manage.php?type=allAjax',
            method: 'POST',
            success: function (response) {
                let todos = JSON.parse(response);
                let content = '';
                todos.forEach(function (todo) {
                    content += getContentToDo(todo);
                })
                $('.table-dark tbody').html(content);
                    
            }
        })
    };
</script>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
            <script>
                $(document).ready(function() {
                    // page is now ready, initialize the calendar...
                    $('#calendar').fullCalendar({
                        // put your options and callbacks here
                        events : [
                            <?php foreach($listToDo as $todo) { ?>
                            {
                                title : '<?php echo $todo->work_name?>',
                                start : '<?php echo $todo->starting_date?>',
                                end  : '<?php echo $todo->ending_date?>',
                                color: '<?php 
                                    if($todo->status == 1) echo 'blue';
                                    else if ($todo->status == 2) echo 'yellow';
                                    else if ($todo->status == 3) echo 'green';
                                ?>'
                            },
                            <?php } ?>
                        ]
                    })
                });
            </script> 
</html>
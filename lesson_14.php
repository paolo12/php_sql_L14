<?php
$pdo = new PDO('mysql:host=localhost:3306;dbname=global', 'root', 'qwerty');
date_default_timezone_set('Europe/Moscow');
$time = date("Y-m-d H:m:s");

if(empty($_GET['action'])){
	
}
else if($_GET['action'] == 'delete'){
	$pdo->query('DELETE FROM tasks WHERE id='.'"'.$_GET['id'].'"');
}
else if($_GET['action'] == 'done'){
	$pdo->query('UPDATE tasks SET is_done="1" WHERE id='.'"'.$_GET['id'].'"');
}
if(empty($POST) or $POST['sort_by'] = 'date_created'){
	$sql_full = 'SELECT id, description, is_done, date_added FROM tasks ORDER BY date_added';
}
else if($POST['sort_by'] = 'is_done'){
	$sql_full = 'SELECT id, description, is_done, date_added FROM tasks ORDER BY is_done';
}
else if($POST['sort_by'] = 'description'){
	$sql_full = 'SELECT id, description, is_done, date_added FROM tasks ORDER BY description';
}

function getTable($row){
	if(empty($row)){
		return '<tr><td></td><td></td><td id="center"></td><td></td></tr>';
	}
	else{
		if($row['is_done'] == 0){
			$task_status = '<span style="color: orange;">В процессе</span>';
		}
		else{
			$task_status = '<span style="color: green;">Выполнено</span>';
		}
		return '<tr>'.'<td>'.$row['description'].'</td>'.'<td>'.$row['date_added'].'</td>'.'<td id="center">'.$task_status.'</td>'.'<td>'.'<a href=?id='.$row['id'].'&action=edit>Изменить</a>'.' '.'<a href=?id='.$row['id'].'&action=done>Выполнить</a>'.' '.'<a href=?id='.$row['id'].'&action=delete>Удалить</a>'.'</td>'.'</tr>';
	}
}

function getDescription(){
}

?>
<html> 
<head>
<style>
    table { 
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    
    table th {
        background: #eee;
    }
</style>
<title>Список дел</title> 
</head> 
<body>
<h2>Список дел</h2>
<div style="float: left">
    <form method="POST">
        <input type="text" name="description" placeholder="Описание задачи" value="<?php
			if(empty($_GET['id']) or $_GET['action'] != 'edit'){
				echo '';
			}
			else{
				foreach ($pdo->query('SELECT * FROM tasks WHERE id='.'"'.$_GET['id'].'"') as $row) {
					echo strip_tags($row['description']);
				}
			}
			?>" />
        <input type="submit" name="save" value="<?php if(empty($_GET['action']) or $_GET['action'] != 'edit'){ echo 'Добавить';} else{echo 'Сохранить';}?>" />
    </form>
</div>

<div style="float: left; margin-left: 20px;">
    <form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
            <option value="date_created">Дате добавления</option>
            <option value="is_done">Статусу</option>
            <option value="description">Описанию</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать" />
    </form>
</div>
<?php
if(!empty($_POST['description'])){
	if($_POST['save'] == 'Добавить'){
		$sql_insert = $pdo->prepare('INSERT INTO `tasks`(`description`, `is_done`, `date_added`) VALUES (:task_description, :is_done, :time_now)');

		$sql_insert->bindParam(':task_description', $task_desctiption);
		$sql_insert->bindParam(':time_now', $time_now);
		$sql_insert->bindParam(':is_done', $done);

		$task_desctiption = strval($_POST['description']);
		$time_now = $time;
		$done = '0';
		$inserted = $sql_insert->execute();
		header('Location: lesson_14.php');
	}
	else if($_POST['save'] == 'Сохранить'){
			
		$sql_insert = $pdo->prepare('UPDATE tasks SET description = :task_description, is_done = :is_done, date_added = :time_now WHERE id = :task_id');
		
		$sql_insert->bindParam(':task_id', $task_id);
		$sql_insert->bindParam(':task_description', $task_desctiption);
		$sql_insert->bindParam(':task_description', $task_desctiption);
		$sql_insert->bindParam(':time_now', $time_now);
		$sql_insert->bindParam(':is_done', $done);
		
		$task_id = $_GET['id'];
		$task_desctiption = strval($_POST['description']);
		$time_now = $time;
		$done = '0';
		$inserted = $sql_insert->execute();
		header('Location: lesson_14.php');
	}
}
?>
<div style="clear: both"></div>
<table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th></th>
    </tr>
<?php
foreach ($pdo->query($sql_full) as $row) {
	echo getTable($row);
}
?>
</table>
</body> 
</html>
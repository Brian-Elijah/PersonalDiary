<!DOCTYPE html>
<html>

<?php
class Database
{
    private $pdo;

    public function __construct($host, $port, $dbname, $username, $password)
    {
        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
//method
    public function executeQuery($query, $params = [])
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
//method
    public function insertDiaryEntry($date, $log)
    {
        $query = "INSERT INTO logs (date, logs) VALUES (:date, :log)";
        $params = [':date' => $date, ':log' => $log];
        $this->executeQuery($query, $params);
    }
// object / function
    public function getDiaryEntries()
    {
        $query = 'SELECT * FROM logs ORDER BY id DESC';
        return $this->executeQuery($query);
    }
}

class Diary
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
 // method
    public function addDiaryEntry($date, $log)
    {
        $date = trim($date);
        $log = trim($log);

        if (empty($date) || empty($log)) {
            return "Date and log are required.";
        }
// inheitance, inherited from database class
        $this->db->insertDiaryEntry($date, $log);
    }
}

// Initialize the Database and Diary objects
$db = new Database('localhost', 3306, 'diary', 'root', '');
$diary = new Diary($db);

$errors = [];
$date = '';
$log = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $log = $_POST['log'];

    $error = $diary->addDiaryEntry($date, $log);
    if ($error) {
        $errors[] = $error;
    }
}

$data = $db->getDiaryEntries();
?>

<head>
<title>welcome to my Diarysystem</title>
<link rel="stylesheet" type ="text/css" href="style.css">

</head>

<style>
        .btnlogout {
            display: inline-block;
            background-color: #d9534f;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btnlogout:hover {
            background-color: #c9302c;
        }
    </style>
    
<body>
            <a class="btnlogout" href="http://localhost/login.system/index.php">Logout</a>
    <div class="container">
        <h1>Personal Diary</h1>
        <form action="" method="POST">
            <label for="name">Date:</label>
            <input type="date" id="date" name="date">

            <label for="message">Log:</label>
            <textarea id="log" name="log" placeholder="Your log" rows="4"></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
    <?php if (!empty($data)) { ?>
        <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }
    </style>
        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Log</th>
            </tr>
            <?php foreach ($data as $i => $entry) { ?>
                <tr>
                    <td><?php echo $entry['id']; ?></td>
                    <td><?php echo $entry['date']; ?></td>
                    <td><?php echo $entry['logs']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</body>
</html>

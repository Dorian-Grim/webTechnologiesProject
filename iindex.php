<?php
// se verifica daca am apasat pe submit
if(isset($_POST['submit'])) 
{
    // definirea variabilelor
    $servername = empty($_POST['hostname']) ? "localhost" : $_POST['hostname'];
    $username = empty($_POST['username']) ? "root" : $_POST['username'];
    $password = empty($_POST['password']) ? "" : $_POST['password'];
    $db = empty($_POST['database']) ? "test" : $_POST['database'];
    // Crearea conexiunii

    $conn = new mysqli($servername, $username, $password, $db);

    // verificarea conexiunii
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    echo "Connected successfully to mysql</br>";
    $sql = "SELECT id, date_id, name, surrname FROM test_table";
    $result = $conn->query($sql);
    
    // afisez tabela din mysql daca exista
    echo "<table><tbody>";
    if ($result)
        if ($result->num_rows > 0) 
            while($row = $result->fetch_assoc()) 
echo <<<tr
<tr><td>{$row["id"]}</td><td>{$row["date_id"]}</td><td>{$row["surrname"]}</td><td>{$row["name"]}</td></tr>
tr;
        else echo "0 results"; // n-am rezultate in tabela
    else
    {
        // creez tabela mysql daca nu exista
        $sql = "CREATE TABLE test_table 
        (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            date_id TIMESTAMP,
            name VARCHAR(30) NOT NULL,
            surrname VARCHAR(30) NOT NULL
        )";

        if ($conn->query($sql) === TRUE) echo "Table test_table created successfully</br>";
        else echo "Error creating table: " . $conn->error;

        // introduc date in tabela
        $sql = "INSERT INTO test_table (id, name, surrname) VALUES ('1','John','Doe')";
        if ($conn->query($sql) === TRUE) echo "New record created successfully. Please refresh page.</br>";
        else echo "Error: " . $sql . "<br>" . $conn->error;
    }
    echo "</tbody></table>";
    $conn->close();
}
else
{
    // preiau curs euro din xml bnr
    $eur;
    $url = "https://www.bnr.ro/nbrfxrates.xml";
    $xml = simplexml_load_file($url);
    foreach($xml->Body->Cube->children() as $rates)
        if ($rates["currency"] == "EUR")
        {
            $eur = $rates[0] . "</br>";
            break;
        }
    // afisez documentul html in pagina
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="index.css">
        <title>Index</title>
    </head>
    <body>
        <h1>Fetched EUR from bnr.ro using simplexml_load_file() = $eur</h1>
        <h1>Please input your mysql database, username, password, hostname to connect to the database</h1>
        <h3>You can omit the form bellow, just submit with blanks, but defaults will be:
            <p><i>database</i>: test</p>
            <p><i>username</i>: root</p>
            <p><i>password</i> will be blank </p>
            <p><i>hostname</i>: localhost</p>
        </h3>
        <form method="post" action="iindex.php">
            <label for="database">Database name:</label><br>
            <input type="text" id="database" name="database" value=""><br>
            
            <label for="userName">User name:</label><br>
            <input type="text" id="userName" name="userName" value=""><br>
            
            <label for="password">Password:</label><br>
            <input type="text" id="password" name="password" value=""><br>
            
            <label for="hostname">hostname:</label><br>
            <input type="text" id="hostname" name="hostname" value=""><br>
            
            <input type="submit" name="submit" value="Submit">
        </form> 
    </body>
    </html>
HTML;
}
?>
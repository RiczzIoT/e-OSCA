<!DOCTYPE html>
<html>
<head>
    <title>Database Connection</title>
</head>
<body>
    <h2>Enter Database Credentials</h2>
    <form method="post" action="connect.php">
        <label for="servername">Server Name:</label><br>
        <input type="text" id="servername" name="servername" value="localhost"><br><br>

        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="root"><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>

        <label for="dbname">Database Name:</label><br>
        <input type="text" id="dbname" name="dbname" value="one"><br><br>

        <input type="submit" value="Connect">
    </form>
</body>
</html>

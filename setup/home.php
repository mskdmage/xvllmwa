<div class="columns is-multiline">
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-info">
                <p class="card-header-title">
                    Configuración de XVLLMWA
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        Para ejecutar este proyecto debes contar con una base de datos MySQL. Asegurate de que MySQL esta corriendo y haber configurado correctamente el archivo <strong>config/config.php</strong>
<pre>/* DATABASE */
$DB_SERVERNAME = "localhost";
$DB_USERNAME = "root";
$DB_PASSWORD = "";</pre>
                        Recuerda que necesitas setear/resetear la base de datos y tablas. Una vez lo hayas conseguido, debes incluir tu API KEY en el campo correspondiente.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="column is-full">
        <div class="columns">
            <div class="column is-half">
                <div class="box">
                    <h2 class="title">Acciones de Base de Datos</h2>
                    <form method="post">
                        <div class="field">
                            <div class="control">
                                <button type="submit" name="drop_reset" value="reset_db" class="button is-danger is-fullwidth">
                                    Drop and Reset Database
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button type="submit" name="reset_users_table" value="reset_users" class="button is-warning is-fullwidth">
                                    Reset Users Table
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button type="submit" name="reset_credentials_table" value="reset_credentials" class="button is-warning is-fullwidth">
                                    Reset Credentials Table
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button type="submit" name="reset_conversations_table" value="reset_conversations" class="button is-info is-fullwidth">
                                    Reset Conversations Table
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="column is-half">
                <div class="box">
                    <h2 class="title">API Keys</h2>
                    <?php
                    
                    try {
                        $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD, 'xvllmwa');

                        if ($conn->connect_error) {
                            throw new Exception("Connection failed: " . $conn->connect_error);
                        }

                        $tableExistsQuery = "SHOW TABLES LIKE 'credentials'";
                        $result = $conn->query($tableExistsQuery);

                        if ($result->num_rows == 0) {
                            echo "<p class='has-text-danger'>The 'credentials' table doesn't exist. Please set up or reset the credentials table first.</p>";
                        } else {
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_key'])) {
                                $service = $_POST['service'];
                                $newKey = $_POST['key'];
                                $updateQuery = "UPDATE credentials SET `key` = '$newKey' WHERE service = '$service'";
                                if ($conn->query($updateQuery) === TRUE) {
                                    echo "<p class='has-text-success'>API Key for '$service' updated successfully!</p>";
                                } else {
                                    echo "<p class='has-text-danger'>Error updating API Key: " . $conn->error . "</p>";
                                }
                            }

                            $selectQuery = "SELECT service, `key` FROM credentials";
                            $result = $conn->query($selectQuery);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $service = $row['service'];
                                    $key = htmlspecialchars($row['key']);
                    ?>
                                    <form method="post" class="mb-4">
                                        <div class="field">
                                            <label class="label"><?php echo ucfirst($service); ?> API Key</label>
                                            <div class="control">
                                                <input class="input" type="text" name="key" value="<?php echo $key; ?>" placeholder="Enter API Key">
                                                <input type="hidden" name="service" value="<?php echo $service; ?>">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="control">
                                                <button type="submit" name="update_key" class="button is-primary is-fullwidth">Update</button>
                                            </div>
                                        </div>
                                    </form>
                    <?php
                                }
                            } else {
                                echo "<p>No credentials found.</p>";
                            }
                        }

                        $conn->close();
                    } catch (mysqli_sql_exception $e) {
                        echo "<p class='has-text-danger'>Error: Unable to connect to the database 'xvllmwa'. Please check if the database exists and is properly configured.</p>";
                        try {
                            $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
                            $createDBQuery = "CREATE DATABASE IF NOT EXISTS xvllmwa";
                            if ($conn->query($createDBQuery) === TRUE) {
                                echo "<p class='has-text-success'>Database 'xvllmwa' created successfully. Please refresh the page to continue.</p>";
                            } else {
                                echo "<p class='has-text-danger'>Error creating database 'xvllmwa': " . $conn->error . "</p>";
                            }
                        } catch (Exception $e) {
                            echo "<p class='has-text-danger'>Fatal Error: Could not create the database. Please contact the system administrator.</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='has-text-danger'>An unexpected error occurred: " . $e->getMessage() . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

function createUsersTable($conn) {
    $createUsersTableQuery = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        age INT,
        role VARCHAR(100) NOT NULL,
        start_date DATE NOT NULL,
        full_name VARCHAR(200) NOT NULL,
        department VARCHAR(100) NOT NULL,
        vacation_days INT NOT NULL
    )";

    if ($conn->query($createUsersTableQuery) === TRUE) {
        echo "Table 'users' created successfully.<br>";
    } else {
        echo "Error creating the 'users' table: " . $conn->error . "<br>";
    }
}

// Función para insertar usuarios
function insertUsers($conn) {
    $users = [
        ['name' => 'Juan', 'email' => 'juan@xcorp.com', 'age' => 25, 'role' => 'Developer', 'start_date' => '2020-01-01', 'full_name' => 'Juan Pérez', 'department' => 'IT', 'password' => '123456'],
        ['name' => 'Maria', 'email' => 'maria@xcorp.com', 'age' => 30, 'role' => 'Manager', 'start_date' => '2018-03-15', 'full_name' => 'Maria López', 'department' => 'HR', 'password' => 'password'],
        ['name' => 'Pedro', 'email' => 'pedro@xcorp.com', 'age' => 28, 'role' => 'Tester', 'start_date' => '2019-05-23', 'full_name' => 'Pedro Sánchez', 'department' => 'QA', 'password' => 'qwerty'],
        ['name' => 'Ana', 'email' => 'ana@xcorp.com', 'age' => 26, 'role' => 'Designer', 'start_date' => '2017-08-14', 'full_name' => 'Ana Gómez', 'department' => 'Design', 'password' => 'abc123'],
        ['name' => 'Luis', 'email' => 'luis@xcorp.com', 'age' => 32, 'role' => 'SysAdmin', 'start_date' => '2016-11-09', 'full_name' => 'Luis Martínez', 'department' => 'IT', 'password' => 'admin'],
        ['name' => 'Elena', 'email' => 'elena@xcorp.com', 'age' => 29, 'role' => 'HR Specialist', 'start_date' => '2015-02-20', 'full_name' => 'Elena Rodríguez', 'department' => 'HR', 'password' => 'letmein'],
        ['name' => 'Carlos', 'email' => 'carlos@xcorp.com', 'age' => 35, 'role' => 'Product Manager', 'start_date' => '2014-07-30', 'full_name' => 'Carlos Fernández', 'department' => 'Product', 'password' => 'welcome'],
        ['name' => 'Sofia', 'email' => 'sofia@xcorp.com', 'age' => 27, 'role' => 'Marketing Lead', 'start_date' => '2021-03-12', 'full_name' => 'Sofia Ruiz', 'department' => 'Marketing', 'password' => '123123'],
        ['name' => 'Miguel', 'email' => 'miguel@xcorp.com', 'age' => 33, 'role' => 'Finance Manager', 'start_date' => '2013-10-15', 'full_name' => 'Miguel Jiménez', 'department' => 'Finance', 'password' => 'password1'],
        ['name' => 'Laura', 'email' => 'laura@xcorp.com', 'age' => 31, 'role' => 'Data Analyst', 'start_date' => '2012-04-01', 'full_name' => 'Laura Castillo', 'department' => 'Data', 'password' => 'password123'],
    ];

    foreach ($users as $user) {
        $username = explode('@', $user['email'])[0];
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        $name = $user['name'];
        $email = $user['email'];
        $age = $user['age'];
        $role = $user['role'];
        $start_date = $user['start_date'];
        $full_name = $user['full_name'];
        $department = $user['department'];
        $vacation_days = rand(5, 20);

        $insertQuery = "INSERT INTO users (username, password, name, email, age, role, start_date, full_name, department, vacation_days)
                        VALUES ('$username', '$password', '$name', '$email', $age, '$role', '$start_date', '$full_name', '$department', $vacation_days)";

        if ($conn->query($insertQuery) === TRUE) {
            echo "User '$name' inserted successfully.<br>";
        } else {
            echo "Error inserting user '$name': " . $conn->error . "<br>";
        }
    }

    // Insertar usuario 'Hal'
    $insertHalQuery = "INSERT INTO users (username, password, name, email, age, role, start_date, full_name, department, vacation_days)
                       VALUES ('hal', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'Hal', 'hal@xcorp.com', 0, 'Especialista de IA', '" . date('Y-m-d') . "', 'HAL 9000', 'Investigacion y Desarrollo', 0)";

    if ($conn->query($insertHalQuery) === TRUE) {
        echo "User 'Hal' inserted successfully.<br>";
    } else {
        echo "Error inserting user 'Hal': " . $conn->error . "<br>";
    }
}

// Función para crear la tabla 'credentials'
function createCredentialsTable($conn) {
    $createCredentialsTableQuery = "CREATE TABLE IF NOT EXISTS credentials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        service VARCHAR(255) NOT NULL,
        `key` VARCHAR(255) NOT NULL
    )";

    if ($conn->query($createCredentialsTableQuery) === TRUE) {
        echo "Table 'credentials' created successfully.<br>";
    } else {
        echo "Error creating the 'credentials' table: " . $conn->error . "<br>";
    }

    $insertCredentialsQuery = "INSERT INTO credentials (service, `key`) VALUES ('openai', '')";
    if ($conn->query($insertCredentialsQuery) === TRUE) {
        echo "Record 'openai' inserted successfully into 'credentials' table.<br>";
    } else {
        echo "Error inserting record into 'credentials': " . $conn->error . "<br>";
    }
}

// Función para crear la tabla 'conversations' sin la clave foránea 'agent_id'
function createConversationsTable($conn) {
    $createConversationsTableQuery = "CREATE TABLE IF NOT EXISTS conversations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        agent VARCHAR(100) NOT NULL,
        role VARCHAR(50) NOT NULL,
        sequence INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($createConversationsTableQuery) === TRUE) {
        echo "Table 'conversations' created successfully.<br>";
    } else {
        echo "Error creating the 'conversations' table: " . $conn->error . "<br>";
    }
}

// Función para resetear la base de datos
function resetDatabase($conn) {
    $sql = "DROP DATABASE IF EXISTS xvllmwa";
    if ($conn->query($sql) === TRUE) {
        echo "Database dropped successfully.<br>";
    } else {
        echo "Error dropping the database: " . $conn->error . "<br>";
    }

    $sql = "CREATE DATABASE xvllmwa";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully.<br>";
    } else {
        echo "Error creating the database: " . $conn->error . "<br>";
    }

    $conn->select_db('xvllmwa');
    createUsersTable($conn);
    insertUsers($conn);
    createCredentialsTable($conn);
    createConversationsTable($conn);
}

function resetUsersTable($conn) {
    $conn->select_db('xvllmwa');
    $dropUsersTableQuery = "DROP TABLE IF EXISTS users";
    if ($conn->query($dropUsersTableQuery) === TRUE) {
        echo "Table 'users' dropped successfully.<br>";
    } else {
        echo "Error dropping the 'users' table: " . $conn->error . "<br>";
    }

    createUsersTable($conn);
    insertUsers($conn);
}

function resetCredentialsTable($conn) {
    $conn->select_db('xvllmwa');
    $dropCredentialsTableQuery = "DROP TABLE IF EXISTS credentials";
    if ($conn->query($dropCredentialsTableQuery) === TRUE) {
        echo "Table 'credentials' dropped successfully.<br>";
    } else {
        echo "Error dropping the 'credentials' table: " . $conn->error . "<br>";
    }

    createCredentialsTable($conn);
}

// Función para resetear la tabla 'conversations'
function resetConversationsTable($conn) {
    $conn->select_db('xvllmwa');
    $dropConversationsTableQuery = "DROP TABLE IF EXISTS conversations";
    if ($conn->query($dropConversationsTableQuery) === TRUE) {
        echo "Table 'conversations' dropped successfully.<br>";
    } else {
        echo "Error dropping the 'conversations' table: " . $conn->error . "<br>";
    }

    createConversationsTable($conn);
}

// Lógica principal
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['drop_reset']) && $_POST['drop_reset'] == 'reset_db') {
        resetDatabase($conn);
    } elseif (isset($_POST['reset_users_table']) && $_POST['reset_users_table'] == 'reset_users') {
        resetUsersTable($conn);
    } elseif (isset($_POST['reset_credentials_table']) && $_POST['reset_credentials_table'] == 'reset_credentials') {
        resetCredentialsTable($conn);
    } elseif (isset($_POST['reset_conversations_table']) && $_POST['reset_conversations_table'] == 'reset_conversations') {
        resetConversationsTable($conn);
    }
}

$conn->close();
?>

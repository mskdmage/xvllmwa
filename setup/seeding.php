<?php

function create_users_table($conn) {
    $create_users_table_query = "CREATE TABLE IF NOT EXISTS users (
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

    if ($conn->query($create_users_table_query) === TRUE) {
        echo "Table 'users' created successfully.<br>";
    } else {
        echo "Error creating the 'users' table: " . $conn->error . "<br>";
    }
}

function insert_users($conn) {
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
        ['name' => 'admin', 'email' => 'admin@xcorp.com', 'age' => 00, 'role' => 'admin', 'start_date' => '2000-01-01', 'full_name' => 'admin', 'department' => 'admin', 'password' => 'S3cur3P455w0rd!'],
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

        $insert_query = "INSERT INTO users (username, password, name, email, age, role, start_date, full_name, department, vacation_days)
                        VALUES ('$username', '$password', '$name', '$email', $age, '$role', '$start_date', '$full_name', '$department', $vacation_days)";

        if ($conn->query($insert_query) === TRUE) {
            echo "User '$name' inserted successfully.<br>";
        } else {
            echo "Error inserting user '$name': " . $conn->error . "<br>";
        }
    }

    $insert_hal_query = "INSERT INTO users (username, password, name, email, age, role, start_date, full_name, department, vacation_days)
                       VALUES ('hal', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'Hal', 'hal@xcorp.com', 0, 'Especialista de IA', '" . date('Y-m-d') . "', 'HAL 9000', 'Investigacion y Desarrollo', 0)";

    if ($conn->query($insert_hal_query) === TRUE) {
        echo "User 'Hal' inserted successfully.<br>";
    } else {
        echo "Error inserting user 'Hal': " . $conn->error . "<br>";
    }
}

function create_credentials_table($conn) {
    $create_credentials_table_query = "CREATE TABLE IF NOT EXISTS credentials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        service VARCHAR(255) NOT NULL,
        `key` VARCHAR(255) NOT NULL
    )";

    if ($conn->query($create_credentials_table_query) === TRUE) {
        echo "Table 'credentials' created successfully.<br>";
    } else {
        echo "Error creating the 'credentials' table: " . $conn->error . "<br>";
    }

    $insert_credentials_query = "INSERT INTO credentials (service, `key`) VALUES ('openai', '')";
    if ($conn->query($insert_credentials_query) === TRUE) {
        echo "Record 'openai' inserted successfully into 'credentials' table.<br>";
    } else {
        echo "Error inserting record into 'credentials': " . $conn->error . "<br>";
    }
}

function create_conversations_table($conn) {
    $create_conversations_table_query = "CREATE TABLE IF NOT EXISTS conversations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        agent VARCHAR(100) NOT NULL,
        role VARCHAR(50) NOT NULL,
        sequence INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($create_conversations_table_query) === TRUE) {
        echo "Table 'conversations' created successfully.<br>";
    } else {
        echo "Error creating the 'conversations' table: " . $conn->error . "<br>";
    }
}

function create_vacation_requests_table($conn) {
    $create_vacation_requests_table_query = "CREATE TABLE IF NOT EXISTS vacation_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        approval BOOLEAN DEFAULT NULL,
        duration_days INT NOT NULL,
        start_date DATE,
        end_date DATE,
        body VARCHAR(255)
    )";

    if ($conn->query($create_vacation_requests_table_query) === TRUE) {
        echo "Table 'vacations' created successfully.<br>";
    } else {
        echo "Error creating the 'vacations' table: " . $conn->error . "<br>";
    }
}

function insert_vacation_requests($conn) {
    $vacation_requests = [
        ['user_id' => 1, 'approval' => true, 'duration_days' => 5, 'start_date' => '2024-01-01', 'end_date' => '2024-01-06', 'body' => 'Approved by HR. <br> <p>Esperamos que te diviertas</p>'],
        ['user_id' => 2, 'approval' => false, 'duration_days' => 10, 'start_date' => '2024-02-10', 'end_date' => '2024-02-20', 'body' => 'Denied by HR. <br> <p>No se puede aprovar.</p>'],
        ['user_id' => 3, 'approval' => true, 'duration_days' => 3, 'start_date' => '2024-03-15', 'end_date' => '2024-03-18', 'body' => 'Approved by HR.'],
        ['user_id' => 4, 'approval' => null, 'duration_days' => 7, 'start_date' => '2024-04-01', 'end_date' => '2024-04-08', 'body' => 'Uppon review by HR.'],
        ['user_id' => 5, 'approval' => true, 'duration_days' => 14, 'start_date' => '2024-05-20', 'end_date' => '2024-06-03', 'body' => 'Approved by HR. \n <p>Esperamos que te diviertas</p>'],
        ['user_id' => 6, 'approval' => false, 'duration_days' => 4, 'start_date' => '2024-06-15', 'end_date' => '2024-06-19', 'body' => 'Denied by HR.'],
        ['user_id' => 7, 'approval' => null, 'duration_days' => 5, 'start_date' => '2024-07-01', 'end_date' => '2024-07-06', 'body' => 'Uppon review by HR.'],
        ['user_id' => 8, 'approval' => true, 'duration_days' => 2, 'start_date' => '2024-08-05', 'end_date' => '2024-08-07', 'body' => 'Approved by HR.'],
        ['user_id' => 9, 'approval' => false, 'duration_days' => 6, 'start_date' => '2024-09-10', 'end_date' => '2024-09-16', 'body' => 'Denied by HR'],
        ['user_id' => 10, 'approval' => null, 'duration_days' => 12, 'start_date' => '2024-10-01', 'end_date' => '2024-10-13', 'body' => 'Uppon review by HR.'],
        ['user_id' => 11, 'approval' => true, 'duration_days' => 20, 'start_date' => '2024-11-01', 'end_date' => '2024-11-21', 'body' => 'Approved by HR.'],
    ];

    foreach ($vacation_requests as $request) {
        $user_id = $request['user_id'];
        $approval = isset($request['approval']) ? ($request['approval'] ? 1 : 0) : 'NULL';
        $duration_days = $request['duration_days'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $body = $request['body'];

        $insert_query = "INSERT INTO vacation_requests (user_id, approval, duration_days, start_date, end_date, body) 
                         VALUES ($user_id, $approval, $duration_days, '$start_date', '$end_date', '$body')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Vacation request for user ID '$user_id' inserted successfully.<br>";
        } else {
            echo "Error inserting vacation request for user ID '$user_id': " . $conn->error . "<br>";
        }
    }
}

function create_policies_table($conn) {
    $create_policies_table_query = "CREATE TABLE IF NOT EXISTS policies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        article_title VARCHAR(255) NOT NULL,
        chunk_sequence INT NOT NULL,
        content LONGTEXT NOT NULL
    )";

    if ($conn->query($create_policies_table_query) === TRUE) {
        echo "Table 'policies' created successfully.<br>";
    } else {
        echo "Error creating the 'policies' table: " . $conn->error . "<br>";
    }
}

function insert_policies($conn) {
    $policies = [
        [
            'article_title' => 'Solicitar Vacaciones',
            'chunk_sequence' => 1,
            'content' => 'Todos los empleados tienen derecho a solicitar vacaciones anuales. Para solicitar vacaciones, el empleado debe completar un formulario de solicitud de vacaciones y enviarlo al departamento de Recursos Humanos al menos 30 días antes de la fecha de inicio deseada. La solicitud debe incluir las fechas específicas de inicio y finalización del periodo de vacaciones.'
        ],
        [
            'article_title' => 'Solicitar Vacaciones',
            'chunk_sequence' => 2,
            'content' => 'El departamento de Recursos Humanos revisará la disponibilidad y confirmará la aprobación o denegación de la solicitud dentro de un plazo de 5 días laborales. En casos excepcionales, como emergencias personales, el plazo de aviso puede reducirse. Sin embargo, estas solicitudes estarán sujetas a la disponibilidad y a la aprobación del supervisor inmediato.'
        ],
        [
            'article_title' => 'Solicitar Vacaciones',
            'chunk_sequence' => 3,
            'content' => 'Las vacaciones deben ser utilizadas durante el año en el que se acumulan y no pueden trasladarse al siguiente año, a menos que haya una aprobación específica de la gerencia. Cualquier empleado que no haya utilizado sus vacaciones para fin de año perderá esos días de descanso. El uso de días de vacaciones está sujeto a las necesidades operativas de la empresa.'
        ],
        [
            'article_title' => 'Información General de Vacaciones',
            'chunk_sequence' => 1,
            'content' => 'La política de vacaciones establece que todos los empleados tienen derecho a un periodo de descanso anual remunerado, acumulado a partir de la fecha de inicio de su empleo. Los empleados a tiempo completo acumulan 1.25 días de vacaciones por cada mes trabajado, lo que resulta en 15 días hábiles al año.'
        ],
        [
            'article_title' => 'Información General de Vacaciones',
            'chunk_sequence' => 2,
            'content' => 'Los empleados a tiempo parcial acumulan vacaciones de manera proporcional al tiempo trabajado. El periodo de vacaciones puede ser utilizado de manera continua o fraccionada, siempre que las fracciones no sean menores a tres días hábiles. El derecho a vacaciones se adquiere después de completar un año de servicio continuo.'
        ],
        [
            'article_title' => 'Información General de Vacaciones',
            'chunk_sequence' => 3,
            'content' => 'La empresa promueve que todos los empleados utilicen sus vacaciones para asegurar un equilibrio saludable entre el trabajo y la vida personal. La acumulación de días de vacaciones se actualiza mensualmente y puede consultarse en el sistema de recursos humanos de la empresa.'
        ],
        [
            'article_title' => 'Política de Acumulación de Vacaciones',
            'chunk_sequence' => 1,
            'content' => 'Los empleados pueden acumular días de vacaciones a lo largo de su periodo de servicio con la empresa. Cada empleado acumula vacaciones a una tasa de 1.25 días por cada mes de trabajo completado. El saldo de vacaciones acumuladas se refleja en el sistema de recursos humanos y se actualiza mensualmente.'
        ],
        [
            'article_title' => 'Política de Acumulación de Vacaciones',
            'chunk_sequence' => 2,
            'content' => 'No es posible acumular más de 30 días de vacaciones. Cualquier exceso sobre este límite se pierde automáticamente al final del año fiscal sin compensación adicional. En situaciones excepcionales, como enfermedad prolongada o licencias por maternidad/paternidad, el empleado puede acumular días de vacaciones más allá del límite, previa aprobación del departamento de Recursos Humanos.'
        ],
        [
            'article_title' => 'Política de Acumulación de Vacaciones',
            'chunk_sequence' => 3,
            'content' => 'Los empleados que dejen la empresa recibirán el pago de las vacaciones no utilizadas como parte de su liquidación final. Sin embargo, los empleados que sean despedidos por mala conducta no tendrán derecho a este beneficio. Los días de vacaciones que no se utilicen dentro del año acumulado serán trasladados automáticamente al año siguiente, siempre que no se superen los 30 días permitidos.'
        ],
    ];

    foreach ($policies as $policy) {
        $article_title = $policy['article_title'];
        $chunk_sequence = $policy['chunk_sequence'];
        $content = $policy['content'];

        $insert_query = "INSERT INTO policies (article_title, chunk_sequence, content)
                         VALUES ('$article_title', $chunk_sequence, '$content')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Policy '$article_title' inserted successfully.<br>";
        } else {
            echo "Error inserting policy '$article_title': " . $conn->error . "<br>";
        }
    }
}

function reset_database($conn) {
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
    create_users_table($conn);
    insert_users($conn);
    create_credentials_table($conn);
    create_conversations_table($conn);
    create_policies_table($conn);
    insert_policies($conn);
    create_vacation_requests_table($conn);
    insert_vacation_requests($conn);
}

function reset_users_table($conn) {
    $conn->select_db('xvllmwa');
    $drop_users_table_query = "DROP TABLE IF EXISTS users";
    if ($conn->query($drop_users_table_query) === TRUE) {
        echo "Table 'users' dropped successfully.<br>";
    } else {
        echo "Error dropping the 'users' table: " . $conn->error . "<br>";
    }

    create_users_table($conn);
    insert_users($conn);
}

function reset_credentials_table($conn) {
    $conn->select_db('xvllmwa');
    $drop_credentials_table_query = "DROP TABLE IF EXISTS credentials";
    if ($conn->query($drop_credentials_table_query) === TRUE) {
        echo "Table 'credentials' dropped successfully.<br>";
    } else {
        echo "Error dropping the 'credentials' table: " . $conn->error . "<br>";
    }

    create_credentials_table($conn);
}

function reset_conversations_table($conn) {
    $conn->select_db('xvllmwa');
    $drop_conversations_table_query = "DROP TABLE IF EXISTS conversations";
    if ($conn->query($drop_conversations_table_query) === TRUE) {
        echo "Table 'conversations' dropped successfully.<br>";
    } else {
        echo "Error dropping the 'conversations' table: " . $conn->error . "<br>";
    }

    create_conversations_table($conn);
}

$conn = connect_to_db();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['drop_reset']) && $_POST['drop_reset'] == 'reset_db') {
        reset_database($conn);
    } elseif (isset($_POST['reset_users_table']) && $_POST['reset_users_table'] == 'reset_users') {
        reset_users_table($conn);
    } elseif (isset($_POST['reset_credentials_table']) && $_POST['reset_credentials_table'] == 'reset_credentials') {
        reset_credentials_table($conn);
    } elseif (isset($_POST['reset_conversations_table']) && $_POST['reset_conversations_table'] == 'reset_conversations') {
        reset_conversations_table($conn);
    }
}

$conn->close();
?>
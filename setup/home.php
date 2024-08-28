<div class="box">
    SOLICITA UN ARCHIVO ```secrets.php``` AL EQUIPO Y COLOCALO EN /config/.
</div>

<div class="box">
    <h2>Setup/Reset Database</h2>
    <form method="post">
        <div class="field">
            <div class="control">
                <button type="submit" name="setup" value="reset" class="button is-danger is-fullwidth">
                    Setup/Reset Database
                </button>
            </div>
        </div>
    </form>
</div>

<?php

include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['setup']) && $_POST['setup'] == 'reset') {
    try {
        // Conexión a la base de datos SQLite usando PDO
        $db = new PDO('sqlite:' . $DOCUMENT_ROOT . '/db/database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Conectado a la base de datos exitosamente.\n";

        // Eliminar la tabla existente si existe
        $db->exec("DROP TABLE IF EXISTS usuarios");
        echo "Tabla 'usuarios' eliminada si existía.\n";

        // Crear la tabla de usuarios con un campo adicional para días de vacaciones
        $query = "CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            nombre TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            edad INTEGER,
            cargo TEXT NOT NULL,
            fecha_desde TEXT NOT NULL,
            nombre_completo TEXT NOT NULL,
            departamento TEXT NOT NULL,
            dias_vacaciones INTEGER NOT NULL
        )";
        $db->exec($query);
        echo "Tabla 'usuarios' creada exitosamente.\n";

        // Preparar la inserción de usuarios
        $stmt = $db->prepare("INSERT INTO usuarios (username, password, nombre, email, edad, cargo, fecha_desde, nombre_completo, departamento, dias_vacaciones) 
                              VALUES (:username, :password, :nombre, :email, :edad, :cargo, :fecha_desde, :nombre_completo, :departamento, :dias_vacaciones)");

        // Lista de usuarios a insertar
        $usuarios = [
            ['nombre' => 'Juan', 'email' => 'juan@xcorp.com', 'edad' => 25, 'cargo' => 'Developer', 'fecha_desde' => '2020-01-01', 'nombre_completo' => 'Juan Pérez', 'departamento' => 'IT'],
            ['nombre' => 'Maria', 'email' => 'maria@xcorp.com', 'edad' => 30, 'cargo' => 'Manager', 'fecha_desde' => '2018-03-15', 'nombre_completo' => 'Maria López', 'departamento' => 'HR'],
            ['nombre' => 'Pedro', 'email' => 'pedro@xcorp.com', 'edad' => 28, 'cargo' => 'Tester', 'fecha_desde' => '2019-05-23', 'nombre_completo' => 'Pedro Sánchez', 'departamento' => 'QA'],
            ['nombre' => 'Ana', 'email' => 'ana@xcorp.com', 'edad' => 26, 'cargo' => 'Designer', 'fecha_desde' => '2017-08-14', 'nombre_completo' => 'Ana Gómez', 'departamento' => 'Design'],
            ['nombre' => 'Luis', 'email' => 'luis@xcorp.com', 'edad' => 32, 'cargo' => 'SysAdmin', 'fecha_desde' => '2016-11-09', 'nombre_completo' => 'Luis Martínez', 'departamento' => 'IT'],
            ['nombre' => 'Elena', 'email' => 'elena@xcorp.com', 'edad' => 29, 'cargo' => 'HR Specialist', 'fecha_desde' => '2015-02-20', 'nombre_completo' => 'Elena Rodríguez', 'departamento' => 'HR'],
            ['nombre' => 'Carlos', 'email' => 'carlos@xcorp.com', 'edad' => 35, 'cargo' => 'Product Manager', 'fecha_desde' => '2014-07-30', 'nombre_completo' => 'Carlos Fernández', 'departamento' => 'Product'],
            ['nombre' => 'Sofia', 'email' => 'sofia@xcorp.com', 'edad' => 27, 'cargo' => 'Marketing Lead', 'fecha_desde' => '2021-03-12', 'nombre_completo' => 'Sofia Ruiz', 'departamento' => 'Marketing'],
            ['nombre' => 'Miguel', 'email' => 'miguel@xcorp.com', 'edad' => 33, 'cargo' => 'Finance Manager', 'fecha_desde' => '2013-10-15', 'nombre_completo' => 'Miguel Jiménez', 'departamento' => 'Finance'],
            ['nombre' => 'Laura', 'email' => 'laura@xcorp.com', 'edad' => 31, 'cargo' => 'Data Analyst', 'fecha_desde' => '2012-04-01', 'nombre_completo' => 'Laura Castillo', 'departamento' => 'Data'],
        ];

        // Inserción de usuarios
        foreach ($usuarios as $usuario) {
            $stmt->execute([
                ':username' => explode('@', $usuario['email'])[0], // Crear username a partir del correo
                ':password' => password_hash('password_' . $usuario['nombre'], PASSWORD_DEFAULT), // Hash de la contraseña
                ':nombre' => $usuario['nombre'],
                ':email' => $usuario['email'],
                ':edad' => $usuario['edad'],
                ':cargo' => $usuario['cargo'],
                ':fecha_desde' => $usuario['fecha_desde'],
                ':nombre_completo' => $usuario['nombre_completo'],
                ':departamento' => $usuario['departamento'],
                ':dias_vacaciones' => rand(5, 20), // Asignar un número aleatorio de días de vacaciones
            ]);
        }

        echo "Usuarios insertados exitosamente.\n";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

    // Cerrando la conexión (opcional, no es realmente necesario)
    $db = null;
}
?>

<div class="columns is-multiline">
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Configuración de XVLLMWA
                </p>
            </header>
            <div class="card-content">
                <div class="notification">
                    <p class="has-text-justified">
                        Para ejecutar este proyecto debes contar con una base de datos MySQL. Asegurate de que MySQL esta corriendo y haber configurado correctamente el archivo <strong>config/config.php</strong>
                        <pre>
                        <?= '<br>/* DATABASE */<br><br><strong>$DB_SERVERNAME</strong>="localhost"<br><strong>$DB_USERNAME</strong>="root"<br><strong>$DB_PASSWORD</strong>=""<br>' ?>
                        </pre>
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
                    <h2 class="subtitle">Acciones de Base de Datos</h2>
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
                   <h2 class="subtitle">API Keys</h2>
                    
                    <?php

                    try {
                        
                        $conn = connect_to_db();
                        $table_exists_query = "SHOW TABLES LIKE 'credentials'";
                        $table_exists_check = $conn->query($table_exists_query);

                        if ($table_exists_check->num_rows == 0) {
                            
                            echo "<p class='has-text-danger'>La tabla 'credentials' no existe. Usa el boton reset credentials.</p>";
                        
                        } else {
                            
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_key'])) {

                                $service = $_POST['service'];
                                $new_key = $_POST['key'];
                                $update_query = "UPDATE credentials SET `key` = '$new_key' WHERE service = '$service'";
                                
                                if ($conn->query($update_query) === TRUE) {
                                    echo "<p class='has-text-success'>La API Key para '$service' fue cargada correctamente!</p>";
                                } else {
                                    echo "<p class='has-text-danger'>Error al cargar la API Key: " . $conn->error . "</p>";
                                }
                            }

                            $select_query = "SELECT service, `key` FROM credentials";
                            $query_results = $conn->query($select_query);

                            if ($query_results->num_rows > 0) {
                                while ($row = $query_results->fetch_assoc()) {
                                    $service = $row['service'];
                                    $key = htmlspecialchars($row['key']);

                                    echo "
                                    <form method='post' class='mb-4'>
                                        <div class='field'>
                                            <label class='label'>$service API Key</label>
                                            <div class='control'>
                                                <input class='input' type='text' name='key' value='$key' placeholder='Ingresa tu API Key'>
                                                <input type='hidden' name='service' value='$service'>
                                            </div>
                                        </div>
                                        <div class='field'>
                                            <div class='control'>
                                                <button type='submit' name='update_key' class='button is-primary is-fullwidth'>Update</button>
                                            </div>
                                        </div>
                                    </form>
                                    ";                               
                                }
                            } else {
                                echo "<p>No se encontraron credenciales.</p>";
                            }
                        }

                        $conn->close();
                    
                    } catch (mysqli_sql_exception $e) {

                        echo "<p class='has-text-danger'>Error: No se pudo establecer conexion a 'xvllmwa'. Verifica que la base de datos exista y que se encuentre configurada correctamente.</p>";

                        try {
                            
                            $conn = connect_to_db();
                            $create_db_query = "CREATE DATABASE IF NOT EXISTS xvllmwa";
                            
                            if ($conn->query($create_db_query) === TRUE) {
                                echo "<p class='has-text-success'>La base de datos 'xvllmwa' fue creada exitosamente. Refresca la pagina para continuar.</p>";
                            } else {
                                echo "<p class='has-text-danger'>Error al crear la base de datos 'xvllmwa': " . $conn->error . "</p>";
                            }
                            $conn->close();

                        } catch (Exception $e) {

                            echo "<p class='has-text-danger'>Se ha presentado un error: " . $e->getMessage() . "</p>";

                        }
                    }

                    include('seeding.php');
                    
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

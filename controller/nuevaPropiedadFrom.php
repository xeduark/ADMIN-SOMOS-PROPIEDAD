<?php
include 'conexion.php';
$formConfig = include 'procesar_formulario.php'; // Asegúrate de que el archivo de configuración esté correcto.

$tableName = "proprieter";
$columnsQuery = "SHOW COLUMNS FROM $tableName";
$columnsResult = $conn->query($columnsQuery);

if (!$columnsResult) {
    die("Error al obtener columnas: " . $conn->error);
}

function generateAttributes($attributes)
{
    $html = '';
    foreach ($attributes as $key => $value) {
        $html .= "$key=\"$value\" ";
    }
    return $html;
}

$includeFields = isset($formConfig['include_fields']) ? $formConfig['include_fields'] : [];
$fieldsPerStep = 10; // Número de campos por paso (5 por cada columna)
?>

<style>
    .step {
        display: none;
    }

    .step.active {
        display: block;
    }

    .progress {
        width: 100%;
        height: 5px;
        background-color: #f3f3f3;
    }

    .progress-bar {
        height: 100%;
        background-color: #ec008c;
        width: 0%;
    }

    .form-navigation {
        margin-top: 20px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
    }


    .form-check-inline .form-check {
        margin-right: 15px;
        /* Espaciado entre opciones */
    }

    .form-label {
        display: block;
        /* Asegura que las etiquetas estén en su propia línea */
        margin-bottom: 5px;
        /* Espaciado inferior entre el label y el input */
        font-weight: bold;
        /* Opcional: hace que las etiquetas sean más visibles */
    }

    .form-check-label {
        margin-left: 5px;
        /* Espaciado entre los radio/checkbox y su texto */
    }
</style>

<div class="progress m-1">
    <div id="progress" class="progress-bar"></div>
</div>
<div class="card p-3">
    <?php
    if (isset($_POST['submit'])) {
        // Variables del formulario
        $codigo = $_POST['codigo'];
        $tipoInmueble = $_POST['tipoInmueble'];
        $nivel_piso = $_POST['nivel_piso'];
        $area = $_POST['area'];
        $estrato = $_POST['estrato'];
        $departamento = $_POST['departamento'];
        $municipios = $_POST['municipios'];
        $terraza = $_POST['terraza'];
        $ascensor = $_POST['ascensor'];
        $patio = $_POST['patio'];
        $parqueadero = $_POST['parqueadero'];
        $cuarto_util = $_POST['cuarto_util'];
        $habitaciones = $_POST['habitaciones'];
        $closet = $_POST['closet'];
        $sala = $_POST['sala'];
        $sala_comedor = $_POST['sala_comedor'];
        $comedor = $_POST['comedor'];
        $cocina = $_POST['cocina'];
        $servicios = $_POST['servicios'];
        $cuartoServicios = $_POST['CuartoServicios'];
        $zonaRopa = $_POST['ZonaRopa'];
        $vista = $_POST['vista'];
        $servicios_publicos = $_POST['servicios_selected']; // Array de servicios seleccionados
        $otras_caracteristicas = $_POST['otras_caracteristicas'];
        $direccion = $_POST['direccion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];
        $telefonoInmueble = $_POST['TelefonoInmueble'];
        $valor_canon = $_POST['valor_canon'];
        $doc_propietario = $_POST['doc_propietario'];
        $nombre_propietario = $_POST['nombre_propietario'];
        $telefono_propietario = $_POST['telefono_propietario'];
        $email_propietario = $_POST['email_propietario'];
        $banco = $_POST['banco'];
        $tipoCuenta = $_POST['tipoCuenta'];
        $numeroCuenta = $_POST['numeroCuenta'];
        $diaPago = $_POST['diaPago'];
        $fecha = $_POST['fecha'];
        $contrato_EPM = $_POST['contrato_EPM'];
        $condicion = $_POST['condicion'];

          // Procesar 'closet'
    $closetSeleccionadoTexto = isset($_POST['closet']) && is_array($_POST['closet']) 
    ? implode(', ', $_POST['closet']) 
    : '';
        // Foto principal
        $ruta1 = '';
        if (isset($_FILES['url_foto_principal']) && $_FILES['url_foto_principal']['error'] == 0) {
            $ruta1 =  basename($_FILES['url_foto_principal']['name']);
            move_uploaded_file($_FILES['url_foto_principal']['tmp_name'], 'fotos/'.$ruta1);
        }

        // Verificar si la propiedad ya existe
        $queryCheck = "SELECT * FROM proprieter WHERE codigo = '$codigo'";
        $result = $conn->query($queryCheck);

        if ($result->num_rows > 0) {
            // La propiedad ya existe
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('error', 'La propiedad ya existe.');
                });
              </script>";
        } else {
            // Insertar la propiedad
            $queryInsert = "INSERT INTO proprieter (
            codigo, tipoInmueble, nivel_piso, area, estrato, departamento, Municipio, terraza, ascensor, patio, parqueadero, cuarto_util, alcobas,
            closet, sala, sala_comedor, comedor, cocina, servicios, CuartoServicios, ZonaRopa, vista, servicios_publicos, otras_caracteristicas, direccion, latitud, longitud,
            TelefonoInmueble, valor_canon, doc_propietario, nombre_propietario, telefono_propietario, email_propietario, banco, tipoCuenta, numeroCuenta, diaPago,
            fecha, contrato_EPM,estadoPropietario, url_foto_principal, condicion, fecha_creacion
        ) VALUES (
            '$codigo', '$tipoInmueble', '$nivel_piso', '$area', '$estrato', '$departamento', '$municipios', '$terraza', '$ascensor', '$patio', '$parqueadero',
            '$cuarto_util', '$habitaciones', '$closetSeleccionadoTexto', '$sala', '$sala_comedor', '$comedor', '$cocina', '$servicios', '$cuartoServicios', '$zonaRopa', '$vista',
            '$servicios_publicos', '$otras_caracteristicas', '$direccion', '$latitud','$longitud', '$telefonoInmueble', '$valor_canon', '$doc_propietario', '$nombre_propietario', 
            '$telefono_propietario', '$email_propietario', '$banco', '$tipoCuenta', '$numeroCuenta', '$diaPago', '$fecha', '$contrato_EPM','NUEVO', '$ruta1', 
            'NUEVO', NOW()
        )";

            if ($conn->query($queryInsert) === TRUE) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showToast('success', 'Registro exitoso.');
                    });
                  </script>";
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showToast('error', 'Error: " . $conn->error . "');
                    });
                  </script>";
            }
        }
    }
    ?>
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>


    <form id="multi-step-form was-validated"  method="POST" enctype="multipart/form-data">

        <?php
        // Dividir los campos por pasos
        $totalFields = count($includeFields);
        $steps = ceil($totalFields / $fieldsPerStep); // Número de pasos necesarios

        // Generar cada paso
        for ($step = 1; $step <= $steps; $step++) {
            echo "<div class='step p-1' id='step-$step'>";
            echo "<h2><i class='bi bi-clipboard-data-fill'></i> Datos de la propiedad $step</h2>";
            echo "<span class='form-label text-indigo-dark'>Tenga en cuenta que todos los campos son obligatorios <i class='bi bi-exclamation-octagon-fill'></i></span>";
            echo "<br>";

            echo "<div class='row'>";

            // Crear las dos columnas para cada paso
            $startIndex = ($step - 1) * $fieldsPerStep;
            $endIndex = min($startIndex + $fieldsPerStep, $totalFields);
            $fieldsInStep = array_slice($includeFields, $startIndex, $fieldsPerStep);

            // Columna 1
            echo "<div class='col col-md-6 col-sm-12'>";
            $counter = 0;
            foreach ($fieldsInStep as $fieldName) {
                if ($counter < 5) { // Primer conjunto de 5 campos
                    generateField($fieldName);
                }
                $counter++;
            }
            echo "</div>";

            // Columna 2
            echo "<div class='col col-md-6 col-sm-12'>";
            $counter = 0;
            foreach ($fieldsInStep as $fieldName) {
                if ($counter >= 5) { // Segundo conjunto de 5 campos
                    generateField($fieldName);
                }
                $counter++;
            }
            echo "</div>";

            echo "</div>"; // Cerrar la fila
            echo "</div>"; // Cerrar paso
        }

        function generateField($fieldName)
        {
            global $formConfig, $conn;

            if ($fieldName == 'tipoInmueble') {
                // Consulta para obtener los tipos de propiedad
                $queryTipo = "SELECT id, nombre_tipo FROM tipos"; // Ajusta esta consulta si es necesario
                $resultTipo = mysqli_query($conn, $queryTipo);

                echo "<div class='form-group'>";
                echo "<label >" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                echo "<select name='$fieldName' id='$fieldName' class='form-control' required>";

                if (mysqli_num_rows($resultTipo) > 0) {
                    while ($row = mysqli_fetch_assoc($resultTipo)) {

                        $tipoNombre = $row['nombre_tipo'];
                        echo "<option value='$tipoNombre'>$tipoNombre</option>";
                    }
                } else {
                    echo "<option value=''>No hay tipos disponibles</option>";
                }
                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'departamento') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'> " . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                echo "<select name='$fieldName' id='lista_departamento' class='form-control' required>";
                echo "<option value=''>Seleccionar</option>";
                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'municipios') {
                echo "<div class='form-group' >";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                echo "<select name='$fieldName' id='municipios' class='form-control' required>";
                echo "<option value=''>Seleccionar</option>";
                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'barrio') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                echo "<select name='$fieldName' id='barrios' class='form-control' required>";
                echo "<option value=''>Seleccionar</option>";
                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'estrato') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                $estratos = [1, 2, 3, 4, 5, 6]; // Lista de opciones para el estrato
                foreach ($estratos as $value) {
                    echo "<div class='form-check-inline'>";
                    echo "<input type='radio' class='form-check-input' name='estrato' id='estrato_$value' value='$value' required>";
                    echo "<label class='form-check-label' for='estrato_$value'> $value</label>";
                    echo "</div>";
                }
                echo "</div>";
            } else if ($fieldName == 'terraza') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='terraza_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label ' for='terraza_switch'> Sí (Si su propiedad no necesita marcarlo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'parqueadero') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                echo "<div class='form-check-inline'>"; // Contenedor para las opciones en línea
                $opcionesParqueadero = ['Sin parqueadero', 'Público', 'Privado']; // Opciones para parqueadero
                foreach ($opcionesParqueadero as $opcion) {
                    $value = $opcion; // Convertir a minúsculas y reemplazar espacios por guiones bajos para el valor
                    echo "<div class='form-check form-check-inline'>";
                    echo "<input type='radio' class='form-check-input' name='$fieldName' id='parqueadero_$value' value='$value' required>";
                    echo "<label class='form-check-label' for='parqueadero_$value'>$opcion</label>";
                    echo "</div>";
                }
                echo "</div>"; // Cerrar contenedor en línea
                echo "</div>";
            } else if ($fieldName == 'ascensor') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='ascensor_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='ascensor_switch'> Sí (Si su propiedad tiene ascensor, márquelo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'patio') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='patio_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='patio_switch'> Sí (Si su propiedad tiene patio, márquelo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'cuarto_util') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Cuarto útil')) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='cuarto_util_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='cuarto_util_switch'> Sí (Si su propiedad cuenta con cuarto útil, márquelo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'habitaciones') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                $habitaciones = [0,1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // Lista de opciones para habitaciones
                foreach ($habitaciones as $value) {
                    echo "<div class='form-check-inline'>";
                    echo "<input type='radio' class='form-check-input' name='$fieldName' id='habitaciones_$value' value='$value' required>";
                    echo "<label class='form-check-label' for='habitaciones_$value'> $value</label>";
                    echo "</div>";
                }
                echo "</div>";
            }else if ($fieldName == 'closet') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
            
                // Opción 'vestier'
                echo "<div class='form-check form-check-inline'>";
                echo "<input type='checkbox' class='form-check-input' name='{$fieldName}[]' id='closet_vestier' value='vestier'>";
                echo "<label class='form-check-label' for='closet_vestier'> Vestier</label>";
                echo "</div>";
            
                // Opciones numéricas
                $closetOpciones = range(0, 10);
                foreach ($closetOpciones as $value) {
                    echo "<div class='form-check form-check-inline'>";
                    echo "<input type='checkbox' class='form-check-input' name='{$fieldName}[]' id='closet_$value' value='$value'>";
                    echo "<label class='form-check-label' for='closet_$value'> $value</label>";
                    echo "</div>";
                }
            
                echo "</div>";
            }
                   
            
            else if ($fieldName == 'sala') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='sala_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='sala_switch'> Sí (Si su propiedad tiene sala, márquelo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'sala_comedor') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='sala_comedor_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='sala_comedor_switch'> Sí (Si su propiedad tiene sala comedor, márquelo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'comedor') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";
                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='comedor_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='comedor_switch'> Sí (Si su propiedad tiene comedor, márquelo aquí)</label>";
                echo "</div>";
                echo "</div>";
            } else if ($fieldName == 'cocina') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";

                // Lista de opciones para la cocina
                $cocina = ['Integral', 'Semi-integral', 'Enchapada', 'Básica', 'o aplica']; // Opciones de cocina
                foreach ($cocina as $value) {
                    echo "<div class='form-check-inline'>";
                    echo "<input type='radio' class='form-check-input' name='$fieldName' id='cocina_$value' value='$value' required>";
                    echo "<label class='form-check-label' for='cocina_$value'> " . ucfirst($value) . "</label>";
                    echo "</div>";
                }

                echo "</div>";
            } else if ($fieldName == 'vista') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";

                // Lista de opciones para vista
                $opcionesVista = ['venta', 'ventanal', 'balcon', 'apartamento interno', 'sotano', 'finca', 'lote', 'puerta garage', 'puerta enrollable','duplex'];

                foreach ($opcionesVista as $value) {
                    echo "<div class='form-check-inline'>";
                    echo "<input type='radio' class='form-check-input' name='$fieldName' id='vista_$value' value='$value' required>";
                    echo "<label class='form-check-label' for='vista_$value'> " . ucfirst($value) . "</label>";
                    echo "</div>";
                }

                echo "</div>";
            } else if ($fieldName == 'servicios') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Baños')) . "</label>";

                // Lista de opciones para servicios (del 0 al 10)
                $opcionesServicios = range(0, 10); // Genera un array del 0 al 10

                foreach ($opcionesServicios as $value) {
                    echo "<div class='form-check-inline'>";
                    echo "<input type='radio' class='form-check-input' name='$fieldName' id='servicios_$value' value='$value' required>";
                    echo "<label class='form-check-label' for='servicios_$value'> $value</label>";
                    echo "</div>";
                }

                echo "</div>";
            } else if ($fieldName == 'CuartoServicios') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";

                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";

                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='cuarto_servicios_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='cuarto_servicios_switch'> Sí (Si su propiedad cuenta con cuarto de servicios, márquelo aquí)</label>";
                echo "</div>";

                echo "</div>";
            } else if ($fieldName == 'ZonaRopa') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Zona de ropa')) . "</label>";

                // Campo oculto para el valor "no"
                echo "<input type='hidden' name='$fieldName' value='no'>";

                // Switch
                echo "<div class='form-switch'>";
                echo "<input type='checkbox' class='form-check-input mr-3' id='zona_ropa_switch' name='$fieldName' value='sí'>";
                echo "<label class='form-check-label' for='zona_ropa_switch'> Sí (Si su propiedad cuenta con zona de ropa, márquelo aquí)</label>";
                echo "</div>";

                echo "</div>";
            } else if ($fieldName == 'servicios_publicos') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Servicios públicos')) . "</label>";

                // Lista de opciones para servicios públicos
                $opcionesServicios = ['Agua', 'Electricidad', 'Gas', 'Internet', 'Television'];

                // Campo de entrada para mostrar los servicios seleccionados
                echo "<input type='text' id='servicios_selected' class='form-control' name='servicios_selected' value='' readonly>";

                // Lista de checkboxes para seleccionar los servicios
                foreach ($opcionesServicios as $value) {
                    echo "<div class='form-check form-check-inline'>";
                    echo "<input type='checkbox' class='form-check-input' name='servicios_publicos' id='servicio_$value' value='$value' onchange='updateServicios()'>";
                    echo "<label class='form-check-label' for='servicio_$value'> " . ucfirst($value) . "</label>";
                    echo "</div>";
                }

                echo "</div>";

                // JavaScript para actualizar el campo de texto
                echo "
    <script>
        function updateServicios() {
            var selectedServices = [];
            var checkboxes = document.querySelectorAll('input[name=\"$fieldName\"]:checked');
            checkboxes.forEach(function(checkbox) {
                selectedServices.push(checkbox.value);
            });
            // Actualizar el campo de texto con los servicios seleccionados
            document.getElementById('servicios_selected').value = selectedServices.join(', ');
        }
    </script>";
            } else if ($fieldName == 'otras_caracteristicas') {
                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";

                // Campo de texto área con límite de 255 caracteres
                echo "<textarea class='form-control' id='$fieldName' name='$fieldName' rows='1' maxlength='255' placeholder='Escriba las características adicionales aquí' oninput='updateCharacterCount()'></textarea>";

                // Contador de caracteres
                echo "<small id='charCount' class='form-text text-muted'>Caracteres restantes: <span id='remainingChars'>255</span></small>";

                echo "</div>";

                // JavaScript para el contador de caracteres
                echo "
    <script>
        function updateCharacterCount() {
            var textArea = document.getElementById('$fieldName');
            var remainingChars = 255 - textArea.value.length;
            document.getElementById('remainingChars').textContent = remainingChars;
        }
    </script>";
            } else if ($fieldName == 'direccion') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Dirección')) . "</label>";
                // Campo para la dirección
                echo "<input type='text' id='direccion' name='$fieldName' class='form-control' placeholder='Escriba la dirección' autocomplete='off'>";
                echo "</div>";
            } else if ($fieldName == 'TelefonoInmueble') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Teléfono inmueble')) . "</label>";
                // Campo para el teléfono
                echo "<input type='tel' id='telefono' name='$fieldName' class='form-control' placeholder='Escriba el número de teléfono' autocomplete='off'>";
                echo "</div>";
            } else if ($fieldName == 'valor_canon') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Valor canon')) . "</label>";
                // Campo para el valor del canon
                echo "<input type='number' id='valor_canon' name='$fieldName' class='form-control' placeholder='Ingrese el valor del canon' autocomplete='off' required>";
                echo "</div>";
            } else if ($fieldName == 'doc_propietario') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Documento del propietario')) . "</label>";
                // Campo para el documento del propietario
                echo "<input type='text' id='doc_propietario' name='$fieldName' class='form-control' placeholder='Ingrese el documento del propietario' autocomplete='off' required>";
                echo "</div>";
            } else if ($fieldName == 'nombre_propietario') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Nombre del propietario')) . "</label>";
                // Campo para el nombre del propietario
                echo "<input type='text' id='nombre_propietario' name='$fieldName' class='form-control' placeholder='Ingrese el nombre del propietario' autocomplete='off' required>";
                echo "</div>";
            } else if ($fieldName == 'telefono_propietario') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Teléfono del propietario')) . "</label>";
                // Campo para el teléfono del propietario
                echo "<input type='tel' id='telefono_propietario' name='$fieldName' class='form-control' placeholder='Ingrese el teléfono del propietario' autocomplete='off' required>";
                echo "</div>";
            } else if ($fieldName == 'email_propietario') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Correo electrónico del propietario')) . "</label>";
                // Campo para el correo electrónico del propietario
                echo "<input type='email' id='email_propietario' name='$fieldName' class='form-control' placeholder='Ingrese el correo electrónico del propietario' autocomplete='off' required>";
                echo "</div>";
            } else if ($fieldName == 'banco') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Banco')) . "</label>";

                // Campo select para elegir el banco
                echo "<select id='banco' name='$fieldName' class='form-control' required>";

                // Opciones de bancos en Colombia
                $bancos = [
                    'Bancolombia',
                    'Davivienda',
                    'BBVA',
                    'Banco de Bogotá',
                    'Banco de Occidente',
                    'Grupo Aval',
                    'Colpatria',
                    'Citibank',
                    'Banco Popular',
                    'Nequi',
                    'Movii',
                    'PSE',
                    'GNB Sudameris'
                ];

                // Mostrar las opciones de bancos
                foreach ($bancos as $banco) {
                    echo "<option value='$banco'>$banco</option>";
                }

                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'tipoCuenta') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Tipo de Cuenta')) . "</label>";

                // Campo select para elegir el tipo de cuenta
                echo "<select id='tipoCuenta' name='$fieldName' class='form-control' required>";

                // Opciones de tipo de cuenta
                $tiposCuenta = [
                    'Ahorro',
                    'Corriente'
                ];

                // Mostrar las opciones de tipo de cuenta
                foreach ($tiposCuenta as $tipo) {
                    echo "<option value='$tipo'>$tipo</option>";
                }

                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'numeroCuenta') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Número de Cuenta')) . "</label>";

                // Campo para el número de cuenta
                echo "<input type='number' id='numeroCuenta' name='$fieldName' class='form-control' placeholder='Ingrese el número de cuenta' required>";

                echo "</div>";
            } else if ($fieldName == 'diaPago') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Día de Pago')) . "</label>";

                // Select para el día de pago
                echo "<select id='diaPago' name='$fieldName' class='form-control' required>";
                echo "<option value=''>Seleccione un día</option>";

                // Generar opciones del 1 al 31
                for ($i = 1; $i <= 31; $i++) {
                    echo "<option value='$i'>$i</option>";
                }

                echo "</select>";
                echo "</div>";
            } else if ($fieldName == 'fecha') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Fecha de ingreso')) . "</label>";

                // Input de fecha
                echo "<input type='date' id='fechaIngreso' name='$fieldName' class='form-control' required>";

                echo "</div>";
            } else if ($fieldName == 'contrato_EPM') {
                echo "<div class='form-group'>";
                echo "<label class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Contrato EPM')) . "</label>";

                // Input para el contrato EPM
                echo "<input type='text' id='contrato_EPM' name='$fieldName' class='form-control' placeholder='Ingrese el número de contrato EPM' required>";

                echo "</div>";
            } else if ($fieldName == 'url_foto_principal') {
                echo "<div class='form-group'>";
                echo "<label for='$fieldName' class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Foto Principal')) . "</label>";

                // Campo para subir la foto
                echo "<input type='file' id='$fieldName' name='$fieldName' class='form-control' accept='image/*' required onchange='previewImage()'>";
                echo "<small class='form-text text-muted'>Seleccione una imagen para la foto principal (solo imágenes JPG, PNG, JPEG).</small>";

                // Espacio para mostrar la imagen en miniatura
                echo "<div id='imagePreview' class='mt-3'></div>";

                echo "</div>";
                // Script para mostrar la imagen seleccionada
                echo "
<script>
    function previewImage() {
        const file = document.getElementById('$fieldName').files[0];
        const reader = new FileReader();
        
        reader.onloadend = function() {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = '<img src=\"' + reader.result + '\" class=\"img-thumbnail\" style=\"width: 150px; height: auto;\" />';
        };
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>";
            } else if ($fieldName == 'condicion') {
                echo "<div class='form-group'>";
                echo "<label for='$fieldName' class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', 'Estado del Propietario')) . "</label>";

                // Select para estado propietario
                echo "<select id='$fieldName' name='$fieldName' class='form-control' required>";
                echo "<option value=''>Seleccione el estado</option>"; // Opción vacía por defecto
                echo "<option value='EN VENTA'>EN VENTA</option>";
                echo "<option value='EN ALQUILER'>EN ALQUILER</option>";
                echo "<option value='EN VENTA O ALQUILER'>EN VENTA O ALQUILER</option>";
                echo "</select>";

                echo "</div>";
            } else {
                // Para campos estándar
                $fieldType = isset($formConfig[$fieldName]['type']) ? $formConfig[$fieldName]['type'] : 'text';
                $attributes = isset($formConfig[$fieldName]['attributes']) ? generateAttributes($formConfig[$fieldName]['attributes']) : '';

                echo "<div class='form-group'>";
                echo "<label  class='form-label text-magenta-dark'>" . ucfirst(str_replace('_', ' ', $fieldName)) . "</label>";
                echo "<input type='$fieldType' name='$fieldName' id='$fieldName' $attributes>";
                echo "</div>";
            }
        }
        ?>
        <!-- CAMPOS OCULTOS PARA CAPTURAR ESTOS CAMPOS -->
        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">

        <!-- Botones de navegación -->
        <div class="form-navigation">
            <button type="button" class="btn bg-indigo-dark text-white" id="prevBtn" style="display:none;"><i class="bi bi-chevron-double-left"></i> Anterior</button>
            <button type="button" class="btn bg-magenta-dark text-white" id="nextBtn">Siguiente <i class="bi bi-chevron-double-right"></i></button>
            <!-- Botón de enviar -->

        </div>
    </form>
</div>

<script>
    // Función para mostrar el paso actual y actualizar la barra de progreso
    let currentStep = 1;
    const steps = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress');

    function showStep() {
        steps.forEach((step, index) => {
            if (index + 1 === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        const progress = ((currentStep - 1) / (steps.length - 1)) * 100;
        progressBar.style.width = progress + '%';

        // Mostrar/ocultar botones de navegación
        if (currentStep == 1) {
            document.getElementById('prevBtn').style.display = 'none';
        } else {
            document.getElementById('prevBtn').style.display = 'inline';
        }
        if (currentStep == steps.length) {
            // Cambiar el botón de "Siguiente" por el de "Enviar"
            document.getElementById('nextBtn').innerHTML = ' <button type="submit" name="submit" class="bg-transparent text-white border-0"><i class="bi bi-floppy-fill"></i> Guardar</button>';
        } else {
            // Mostrar el botón de "Siguiente"
            document.getElementById('nextBtn').innerHTML = 'Siguiente <i class="bi bi-chevron-double-right"></i>';
            document.getElementById('nextBtn').setAttribute('type', 'button'); // Asegurar que sigue siendo un botón
        }
    }

    // Al hacer clic en el botón "Siguiente"
    document.getElementById('nextBtn').addEventListener('click', () => {
        if (currentStep < steps.length) {
            currentStep++;
            showStep();
        } else {
            document.getElementById('multi-step-form').submit(); // Enviar el formulario si llegamos al último paso
        }
    });

    // Al hacer clic en el botón "Anterior"
    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            showStep();
        }
    });

    // Inicializar el primer paso al cargar la página
    showStep();
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA98OpvjlfBwdRXdIVsGCyNM2ak5o-WYYs&libraries=places&callback=initAutocomplete" async defer></script>

<script>
    let autocomplete;

    // Función para inicializar el autocompletado
    function initAutocomplete() {
        // Crear el objeto del campo de texto
        const input = document.getElementById('direccion');

        // Inicializar Autocomplete con la API de Places
        autocomplete = new google.maps.places.Autocomplete(input);

        // Añadir un listener para manejar eventos, como el de selección de una dirección
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                alert("No se encontró información de la ubicación.");
                return;
            }

            // Obtener latitud y longitud
            const latitud = place.geometry.location.lat();
            const longitud = place.geometry.location.lng();

            console.log("Latitud:", latitud);
            console.log("Longitud:", longitud);

            // Asignar valores a campos ocultos (o variables si prefieres)
            document.getElementById('latitud').value = latitud;
            document.getElementById('longitud').value = longitud;

            // Puedes usar estas variables para enviar al servidor o realizar otras acciones
        });
    }
</script>
<script>
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.style.padding = '10px 20px';
        toast.style.marginBottom = '10px';
        toast.style.color = '#fff';
        toast.style.borderRadius = '5px';
        toast.style.fontSize = '16px';
        toast.style.display = 'inline-block';
        toast.style.animation = 'fade-in-out 4s ease forwards';
        toast.style.position = 'relative';

        if (type === 'success') {
            toast.style.backgroundColor = '#66cc00';
        } else if (type === 'error') {
            toast.style.backgroundColor = '#f5a6c2';
        }

        toast.innerText = message;

        document.getElementById('toast-container').appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    }
</script>
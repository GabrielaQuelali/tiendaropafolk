<?php
require("conexionmysqli.php");
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">Lista de Análisis de Costos</h3>
    <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th width="10%">Código</th>
                <th width="20%">Fecha Proceso</th>
                <th width="50%">Glosa</th>
                <th width="20%">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $consulta = "SELECT id, fecha_proceso, glosa, estado FROM analisis_costos_nuevos WHERE estado = 1 ORDER BY id DESC";
        $resp = mysqli_query($enlaceCon, $consulta);

        while ($dat = mysqli_fetch_array($resp)) {
            $idanalisis = $dat['id'];
            $fechainicio = $dat['fecha_proceso'];
            $glosa = $dat['glosa'];
            $estado = $dat['estado'];

            // Badge con estilo Bootstrap
            switch ($estado) {
                case 1:
                    $badge = "<span class='badge bg-success'>Activo</span>";
                    break;
                case 0:
                    $badge = "<span class='badge bg-secondary'>Inactivo</span>";
                    break;
                default:
                    $badge = "<span class='badge bg-danger'>Desconocido</span>";
                    break;
            }

            echo "<tr>
                <td>$idanalisis</td>
                <td>$fechainicio</td>
                <td>$glosa</td>
                <td>
                    <a target='_blank' href='detalleAnalisisCostosPreciosNuevo.php?idanalisis=$idanalisis' class='btn btn-sm btn-info' title='Ver Detalle'>
                        <i class='bi bi-eye'></i>
                    </a>
                    <a href='rptAnalisisCostosPreciosProductosNuevosEditar.php?idanalisis=$idanalisis' class='btn btn-sm btn-warning' title='Editar'>
                        <i class='bi bi-pencil'></i>
                    </a>
                    <button onclick=\"cambiarEstado($idanalisis)\" class='btn btn-sm btn-danger' title='Marcar como Inactivo'>
                        <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    table th, table td {
        padding: 0.4rem 0.6rem !important;
        vertical-align: middle;
    }
    table th {
        font-size: 0.9rem;
        font-weight: 600;
    }
    table td {
        font-size: 0.88rem;
    }
</style>
<script>
function cambiarEstado(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción marcará el análisis como inactivo.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'update_estado_analisis.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        Swal.fire('¡Actualizado!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error de conexión:', error);
                    Swal.fire('Error', 'Ocurrió un error al conectar con el servidor.', 'error');
                }
            });

        }
    });
}
</script>

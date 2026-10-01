import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/Normal/buscador_computadora.js',
                'resources/js/Normal/buscador_materiales.js',
                'resources/js/Normal/creacion_solicitudes_computo.js',
                'resources/js/Normal/creacion_solicitudes.js',
                'resources/js/Normal/laboratorios_normal.js',
                'resources/js/Normal/solicitudes.js',
                'resources/js/Mantenimiento/reportes_computo.js',
                'resources/js/Mantenimiento/reportes_materiales.js',
                'resources/js/Encargado/reportes_materiales.js',
                'resources/js/Encargado/solicitudes_aceptadas_computo.js',
                'resources/js/Encargado/solicitudes_aceptadas.js',
                'resources/js/Encargado/solicitudes_pendientes_computo.js',
                'resources/js/Encargado/solicitudes_pendientes.js',
                'resources/js/Encargado/historial_alumnos.js',
                'resources/js/Admin/alertas.js',
                'resources/js/Admin/analisis_de_datos.js',
                'resources/js/Admin/borrado_masivo.js',
                'resources/js/Admin/boton_modales.js',
                'resources/js/Admin/buscador_grupos.js',
                'resources/js/Admin/buscador_inventario.js',
                'resources/js/Admin/buscador_laboratorios.js',
                'resources/js/Admin/buscador_materiales.js',
                'resources/js/Admin/buscador_usuarios.js',
                'resources/js/Admin/crud_grupos.js',
                'resources/js/Admin/crud_inventario.js',
                'resources/js/Admin/crud_laboratorios.js',
                'resources/js/Admin/crud_materiales.js',
                'resources/js/Admin/crud_usuarios.js',
                'resources/js/Admin/materiales.js',
                'resources/js/Admin/modal.js',
                'resources/js/Admin/reportes.js',
                'resources/js/Admin_Labores/buscador_instituciones.js',
                'resources/js/Admin_Labores/buscador_usuarios.js',
                'resources/js/Admin_Labores/crud_instituciones.js',
                'resources/js/Admin_Labores/crud_usuarios.js',
                'resources/js/Admin_Labores/modales.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

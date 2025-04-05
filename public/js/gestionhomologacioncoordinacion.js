$(document).ready(function () {
    // Inicializar Select2 y tooltips
    $('.select2').select2({
        placeholder: "Seleccionar opción",
        allowClear: true
    });

    $('[data-toggle="tooltip"]').tooltip();

    const filtroForm = document.getElementById("filtroForm");
    const tabla = document.getElementById("solicitudes-table").getElementsByTagName("tbody")[0];
    const filas = tabla.getElementsByTagName("tr");

    // Función para normalizar texto
    function normalizarTexto(texto) {
        return texto
            .trim()
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");
    }

    // Función principal para filtrar
    function filtrarTabla() {
        const estadoFiltro = document.getElementById("estado").value.trim();
        const fechaFiltro = document.getElementById("fecha").value;
        const carreraFiltro = document.getElementById("carrera").value.trim();
        const estudianteFiltro = normalizarTexto(document.getElementById("estudiante").value);

        for (let fila of filas) {
            let estudiante = normalizarTexto(fila.cells[1].innerText);
            let carrera = fila.cells[2].innerText.trim();
            let fecha = fila.cells[4].innerText.trim();
            let estadoTexto = fila.cells[5].querySelector("span").innerText.trim();

            let coincideEstado = estadoFiltro === "" || estadoTexto.toLowerCase() === estadoFiltro.toLowerCase();
            let coincideFecha = fechaFiltro === "" || fecha === fechaFiltro.split("-").reverse().join("/");
            let coincideCarrera = carreraFiltro === "" || normalizarTexto(carrera) === normalizarTexto(carreraFiltro);
            let coincideEstudiante = estudianteFiltro === "" || estudiante.includes(estudianteFiltro);

            fila.style.display = (coincideEstado && coincideFecha && coincideCarrera && coincideEstudiante) ? "" : "none";
        }
    }

    // Escuchar cambios en los filtros
    filtroForm.addEventListener("input", filtrarTabla);

    // Botón limpiar filtros
    document.getElementById("limpiarBtn").addEventListener("click", function () {
        setTimeout(() => {
            document.getElementById("estado").value = "";
            document.getElementById("fecha").value = "";
            document.getElementById("carrera").value = "";
            document.getElementById("estudiante").value = "";
            $(".select2").val("").trigger("change");
            filtrarTabla();
        }, 100);
    });

    // Botón buscar (opcional)
    document.getElementById("buscarBtn").addEventListener("click", filtrarTabla);

    // Abrir detalle de solicitud al hacer clic en botón con clase .ver-detalles
    document.querySelectorAll(".ver-detalles").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            let solicitudID = this.getAttribute("data-id");
            if (solicitudID) {
                let url = `/homologacion/${solicitudID}`;
                window.open(url, "_blank");
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".descargar-pdf").forEach(button => {
        button.addEventListener("click", function () {
            let solicitudID = this.getAttribute("data-id");
            if (solicitudID) {
                let url = `/homologacion/${solicitudID}/descargar`;
                window.open(url, "_blank");
            }
        });
    });
});

function confirmarDatos() {
    const formData = new FormData();

    // Información Personal
    formData.append("tipo_identificacion", document.getElementById("tipo_identificacion").value);
    formData.append("numero_identificacion", document.getElementById("numero_identificacion").value);
    formData.append("primer_nombre", document.getElementById("primer_nombre").value);
    formData.append("segundo_nombre", document.getElementById("segundo_nombre").value);
    formData.append("primer_apellido", document.getElementById("primer_apellido").value);
    formData.append("segundo_apellido", document.getElementById("segundo_apellido").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("telefono", document.getElementById("telefono").value);
    formData.append("direccion", document.getElementById("direccion").value);
    formData.append("pais", document.getElementById("pais").value);
    formData.append("departamento", document.getElementById("departamento").value);
    formData.append("municipio", document.getElementById("municipio").value);

    // Universidad de Origen
    formData.append("departamento_origen", document.getElementById("departamento_origen").value);
    formData.append("municipio_origen", document.getElementById("municipio_origen").value);
    formData.append("institucion", document.getElementById("institucion").value);
    formData.append("tipoFormacion", document.getElementById("tipoFormacion").value);
    formData.append("programa", document.getElementById("programa").value);
    formData.append("finalizo_estudios", document.getElementById("finalizo_estudios").value);
    formData.append("fecha_finalizacion", document.getElementById("fecha_finalizacion").value);
    formData.append("fecha_ultimo_semestre", document.getElementById("fecha_ultimo_semestre").value);

    // Materias (convertidas a JSON string)
    const materiasSeleccionadas = obtenerMaterias(); // función que tú defines
    formData.append("materias", JSON.stringify(materiasSeleccionadas));

    // Archivos PDF
    formData.append("documento_id", document.getElementById("documento_id").files[0]);
    formData.append("certificado_notas", document.getElementById("certificado_notas").files[0]);
    formData.append("contenido_programatico", document.getElementById("contenido_programatico").files[0]);
    formData.append("carta_homologacion", document.getElementById("carta_homologacion").files[0]);

    const certFinal = document.getElementById("certificacion_finalizacion").files[0];
    if (certFinal) formData.append("certificacion_finalizacion", certFinal);

    const visa = document.getElementById("visa_pasaporte").files[0];
    if (visa) formData.append("visa_pasaporte", visa);

    // Envío por fetch
    fetch("/solicitud", {
        method: "POST",
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        alert("Solicitud enviada exitosamente");
        console.log(data);
    })
    .catch(err => {
        console.error(err);
        alert("Error al enviar la solicitud");
    });
}

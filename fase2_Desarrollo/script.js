class Documento {
    constructor() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            //alert("Este navegador soporta el API File");
        } else {
            alert("¡¡¡ Este navegador NO soporta el API File y este programa puede no funcionar correctamente !!!");
        }
        this.nombresTiposTamaños;
        this.j = 0;
    }
    cargarArchivos() {
        this.j = 0;
        var nBytes = 0,
            archivos = document.getElementById("subirArchivos").files,
            nArchivos = archivos.length;
        for (var i = 0; i < nArchivos; i++) {
            nBytes += archivos[i].size;
        }
        this.nombresTiposTamaños = "";
        for (var i = 0; i < nArchivos; i++) {
            var tipoTexto = /text.*/;
            if (archivos[i].type.match(tipoTexto) || archivos[i].type === "application/json") {
                var reader = new FileReader();
                reader.readAsText(archivos[i]);
                reader.onloadend = function () {
                    var datos = $(reader.result);
                    var stringDatos = "";
                    $(datos).find("libro").each(function () {
                        //Extracción de los datos contenidos en el XML
                        var nombre = $(this).attr("nombre");
                        var tipo = $(this).attr("tipo");
                        var dificultad = $(this).attr("dificultad");
                        var publicacion = $('fecha_publicacion', this).text();
                        var paginas = $('paginas', this).text();
                        var editorial = $('editorial', this).text();
                        var descripcion = $('descripcion', this).text();
                        var target = $('target', this).text();
                        var autor = $('autor', this).text();
                        var recomendacion = $('recomendacion', this).text();

                        stringDatos += "<ul>";
                        stringDatos += "<li>Nombre: " + nombre + "</li>";
                        stringDatos += "<li>Tipo: " + tipo + "</li>";
                        stringDatos += "<li>Dificultad: " + dificultad + "</li>";
                        stringDatos += "<li>Publicacion: " + publicacion + "</li>";
                        stringDatos += "<li>Nº paginas: " + paginas + "</li>";
                        stringDatos += "<li>Editorial: " + editorial + "</li>";
                        stringDatos += "<li>Descripcion: " + descripcion + "</li>";
                        stringDatos += "<li>Target: " + target + "</li>";
                        stringDatos += "<li>Autor: " + autor + "</li>";
                        stringDatos += "<li>Recomendacion: " + recomendacion + "</li></ul>";
                    });

                    document.getElementById("nombres").innerHTML = stringDatos;
                };
            }
        }

        document.getElementById("tamaño").innerHTML = nBytes + " bytes";
    }
}
var documento = new Documento();
class Documento {
    constructor() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            //alert("Este navegador soporta el API File");
        } else {
            alert("¡¡¡ Este navegador NO soporta el API File y este programa puede no funcionar correctamente !!!");
        }
        this.nombresTiposTamaños;
        this.j = -1;
    }
    cambiarLibro() {
        if (this.j == -1) {
            var libro = $("#libros").val();
            var x = document.getElementById("div_" + libro);
            x.style.display = "block";
            this.j = libro;
        } else {
            var x = document.getElementById("div_" + this.j);
            x.style.display = "none";
            var libro = $("#libros").val();
            x = document.getElementById("div_" + libro);
            x.style.display = "block";
            this.j = libro;
        }
    }
    cambiarFoco(e) {
        e.requestFullscreen();
    }
}
var documento = new Documento();
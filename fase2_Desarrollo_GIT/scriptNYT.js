class Documento {
    constructor() {

    }
    fetchNYT() {
        var nytimesKey = "ayWCjrB0sCDzXKE2A97dmtp3BGATHAot";
        const url = "https://api.nytimes.com/svc/books/v3/lists.json?list=hardcover-fiction&api-key=" + nytimesKey;
        const options = {
            method: "GET",
            headers: {
                "Accept": "application/json"
            },
        };
        this.settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://api.nytimes.com/svc/books/v3/lists.json?list=hardcover-fiction&api-key=" + nytimesKey,
            "method": "GET",
            "headers": {
                "Accept": "application/json"
            }
        }
        $.ajax(this.settings).done(function (response) {
            response.results.forEach(function (book) {
                var bookInfo = book.book_details[0];
                var lastWeekRank = book.rank_last_week || 'n/a';
                var weeksOnList = book.weeks_on_list || 'New this week!';
                var stringDatos = "";
                stringDatos += "<ul>";
                stringDatos += "<li><a href=" + book.amazon_product_url + " class='enlaceTitulo'>" + bookInfo.title + "</a></li>";
                stringDatos += "<li>Publicacion: " + book.published_date + "</li>";
                stringDatos += "<li>Editorial: " + bookInfo.publisher + "</li>";
                stringDatos += "<li>Descripcion: " + bookInfo.description + "</li>";
                stringDatos += "<li>Target: " + bookInfo.age_group + "</li>";
                stringDatos += "<li>Autor: " + bookInfo.author + "</li>";
                stringDatos += "<li>Last Week: " + lastWeekRank + "</li>";
                stringDatos += "<li>Weeks on list: " + weeksOnList + "</li>";
                stringDatos += "<li>ISBN: " + bookInfo.primary_isbn13 + "</li>";
                stringDatos += "<li>Portada: <img src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/387928/book%20placeholder.png' alt='portada_" + bookInfo.primary_isbn13 + "' onkeypress='documento.cambiarFoco(this)' onclick='documento.cambiarFoco(this)'/></li></ul>"
                $('#libros').append(stringDatos);
                $('#' + book.rank).attr('nyt-rank', book.rank);
            });
            console.log(response);

        });
    }
    cambiarFoco(e) {
        e.requestFullscreen();
    }
}
var documento = new Documento();
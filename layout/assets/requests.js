function shorturl() {

    var url = document.getElementById("urlshort").value;
    var json = { "shorturl": url }

    $.ajax({
        type: "POST",
        crossDomain: !0,
        cache: !1,
        url: "/api/short/url",
        data: json,
        dataType: "json",
        success: function (e) {
            if (e.status == 'success') {
                // Deixar uma DIV com o ID 'shortresponse' para ser alterada com os resultados em HTML
            } else {
                // Exibir Modal com respostas de erro (Type = error || warning[if = null])
            }
        },
        error: function (r) {
            // Exibir modal com respostas de erro (type = warning)
        }
    })
}
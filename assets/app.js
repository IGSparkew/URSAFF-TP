/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

const $ = require("jquery");

let _data = []
$(document).ready(function () {
    $(document).on('click', '#search', function (e) {
        e.preventDefault()
        $.get('https://recherche-entreprises.api.gouv.fr/search?q=' + $('input[name=recherche]').val(), function () { })
            .done(function (e) {
                if (e.results.length > 0) {
                    _data = e.results
                    $('.searchList').html('')
                    $.each(e.results, function (key, data) {
                        $('.searchList').append(`<tr>
                        <td>${data.nom_complet}</td>
                        <td>${data.siren}</td>
                        <td>${data.siege.siret}</td>
                        <td>${data.siege.adresse}</td>
                        <td><button class="choose" data-key="${key}">Choisir</button></td>
                    </tr>`)
                    })
                }
            })
    })


    $(document).on('click', '.choose', function (e) {
        e.preventDefault()
        let data = { 'jsonData': _data[$(this).attr('data-key')] }
        console.log($(this))
        $.post('/save', data, function () { })
            .done(function (e) {
                if (e.status) {
                    $('#text').html(`L'élement choisi est : ${data.jsonData.nom_complet}, <br> avec un SIREN : ${data.jsonData.siren}, <br> et un SIRET : ${data.jsonData.siege.siret}, <br> localisé : ${data.jsonData.siege.adresse} <br><br>
                    <input type="number" name="salaireBrut">
                    <button class="calculate">Calculer</button>`)
                }
            })
    })

    $(document).on('click', '.calculate', function (e) {
        e.preventDefault()
        let value = $('input[name=salaireBrut]').val()
        $('.bodyContent').html('')
        salaireNetCdi(value)
        salaireNetAlternant(value)
        salaireNetCdd(value)
        gratificationMinStage()
    })

    function salaireNetCdi(salaire) {
        let data = {
            "situation": {
                "salarié . contrat . salaire brut": {
                    "valeur": salaire,
                    "unité": "€ / mois"
                },
                "salarié . contrat": "'CDI'"
            },
            "expressions": [
                "salarié . rémunération . net . à payer avant impôt",
                "salarié . coût total employeur",
                "salarié . cotisations"
            ]
        }

        $.ajax({
            url: 'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (e) {

                $('.bodyContent').append(`<h3>Salaire net CDI</h3>
                <p>Rémunération net ${e.evaluate[0].nodeValue}<br>
                Coût employeur : ${e.evaluate[1].nodeValue}<br>
                Cotisation : ${e.evaluate[2].nodeValue}</p>`)
            }
        })
    }

    function gratificationMinStage() {
        let data = {
            "situation": {
                "salarié . contrat": "'Stage'"
            },
            "expressions": [
                "salarié . contrat . stage . gratification minimale"
            ]
        }

        $.ajax({
            url: 'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (e) {
                $('.bodyContent').append(`<h3>Gratification min.</h3>
                <p>Gratification min. : ${e.evaluate[0].nodeValue}</p>`)
            }
        })
    }

    function salaireNetAlternant(salaire) {
        let data = {
            "situation": {
                "salarié . contrat . salaire brut": {
                    "valeur": salaire,
                    "unité": "€ / mois"
                },
                "salarié . contrat": "'Alternance'"
            },
            "expressions": [
                "salarié . rémunération . net . à payer avant impôt",
                "salarié . coût total employeur",
                "salarié . cotisations",
            ]
        }

        $.ajax({
            url: 'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (e) {
                $('.bodyContent').append(`<h3>Salaire net alternant</h3>
                <p>Rémunération net : ${e.evaluate[0].nodeValue}<br>
                Coût employeur : ${e.evaluate[1].nodeValue}<br>
                Cotisations : ${e.evaluate[2].nodeValue}</p>`)
            }
        })
    }

    function salaireNetCdd(salaire) {
        let data = {
            "situation": {
                "salarié . contrat . salaire brut": {
                    "valeur": salaire,
                    "unité": "€ / mois"
                },
                "salarié . contrat": "'CDD'"
            },
            "expressions": [
                "salarié . rémunération . net . à payer avant impôt",
                "salarié . coût total employeur",
                "salarié . cotisations",
                "salarié . rémunération . indemnités CDD"
            ]
        }

        $.ajax({
            url: 'https://mon-entreprise.urssaf.fr/api/v1/evaluate',
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (e) {
                $('.bodyContent').append(`<h3>Salaire net CDD</h3>
                <p>Rémunération net : ${e.evaluate[0].nodeValue}<br>
                Coût employeur : ${e.evaluate[1].nodeValue}<br>
                Cotisations : ${e.evaluate[2].nodeValue}<br>
                Indémnitées : ${e.evaluate[3].nodeValue}</p>`)
            }
        })
    }
})
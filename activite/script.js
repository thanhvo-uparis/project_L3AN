jQuery(document).ready(function ($) {
    function bindCategoryClick() {
        $('.category-item-wrap').unbind('click').bind('click', function () {
            $('.category-item-wrap').removeClass('selected');
            $(this).addClass('selected');
            var mission_id = $('.filter-select-mission').val();
            var category_id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: 'getDatasChart.php',
                data: {
                    mission_id: mission_id,
                    category_id: category_id
                },
                success: function (response) {
                    if (response.status) {
                        if (typeof response.labels !== "undefined" && typeof response.values !== "undefined") {
                            viewCicleChart('chart-item', response.labels, response.values);
                        }

                        if (typeof response.table_html !== "undefined") {
                            $('.chart-table-html').html(response.table_html);
                        }
                    }
                },
                dataType: 'json'
            });
        });
    }

    function viewColumnsChart(chart_id, labels, values) {
        var ctx = document.getElementById(chart_id).getContext('2d');
        var chart = new Chart(ctx, {

            // Le type de graphique que nous voulons créer 
            type: 'bar',

            // The data for our dataset
            data: {
                labels: labels,
                datasets: [{
                    label: '',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                    data: values
                }],


            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                            stepSize: 1
                        }
                    }]
                }
            }

        });
    }

    function viewCicleChart(chart_id, labels, values) {
        var ctx = document.getElementById(chart_id).getContext('2d');
        var chart = new Chart(ctx, {
            // Le type de graphique que nous voulons créer
            type: 'doughnut',

            // The data for our dataset
            data: {
                labels: labels,
                datasets: [{
                    label: 'Statut',
                    backgroundColor: ['rgb(178, 34, 34)', 'rgb(210, 105, 30)', 'rgb(50, 138, 236)', 'rgb(34, 139, 34)'],
                    borderWidth: 1,
                    data: values,
                }],


            },

            // Les options de configuration vont ici 
            options: {
                scales: {
                    ticks: {
                        beginAtZero: true
                    }

                },
                legend: {
                    display: true,

                    labels: {
                        boxWidth: 10
                    },
                },
                animation: {
                    animateScale: false,
                }
            }
        });
    }

    // recherche par controller name
    $('.controller-name-input').on('change', function () {
        var value = $(this).val();
        var missions_id = [];
        $('.filter-select-mission option').each(function () {
            if (parseInt($(this).attr('value')) !== 0) {
                missions_id.push($(this).attr('value'));
            }
        });
        if (missions_id.join() === '' || value === '') {
            return;
        }
        $.ajax({
            type: "POST",
            url: 'getControllerDetails.php',
            data: {
                value: value,
                missions_id: missions_id.join()
            },
            success: function (response) {
                // créer un table
                $('.controller-html').html(response.table_html);
            },
            dataType: 'json'
        });
    });

    // Cliquez sur filtrer par mission ou catégorie
    $('.filter-select').on('change', function () {
        var value = $(this).val();
        if (parseInt(value) === 0) {
            return;
        }
        $('.title-chart').html($(this).find('option[value="'+ value +'"]').html());
        var type = $(this).data('type');
        var data = {};
        if (typeof type !== "undefined") {
            switch (type) {
                case 'mission':
                    $('.category-html, .chart-table-html').show();
                    data.mission_id = value;
                    break;
                case 'category':
                    $('.category-html, .chart-table-html').hide();
                    data.category_id = value;
                    break;
            }

            $.ajax({
                type: "POST",
                url: 'getDatasChart.php',
                data: data,
                success: function (response) {
                    if (response.status) {
                        if (typeof response.labels !== "undefined" && typeof response.values !== "undefined") {
                            // créer un graphique 
                            viewCicleChart('chart-item', response.labels, response.values);
                        }

                        if (typeof response.table_html !== "undefined") {
                            // Créer un bouton pour le nom du contrôle et hover pour afficher la table  (table_html est renvoyé par le serveur dans fichier getDatasChart.php)
                            $('.chart-table-html').html(response.table_html);
                        }

                        if (typeof response.category_html !== "undefined") {
                            // Créer un élément de bouton pour la catégorie (category_html est renvoyé par le serveur dans fichier getDatasChart.php)
                            $('.category-html').html(response.category_html);
                            bindCategoryClick();
                        }
                    }
                },
                dataType: 'json'
            });
        }
    });

    // Cliquez sur un filtre collaborateur
    $('.filter-select-mission-collaborateurs').on('change', function () {
        var mission_id = $(this).val();
        if (parseInt(mission_id) === 0) {
            return;
        }
        $('.title-chart').html($(this).find('option[value="'+ mission_id +'"]').html());
        $.ajax({
            type: "POST",
            url: 'getCollaborateurs.php',
            data: {
                mission_id: mission_id
            },
            success: function (response) {
                viewColumnsChart('bar-chart-item', response.labels, response.values);
                // Créer le tableau de membres et le total de taches  (table_html  est renvoyé par le serveur dans fichier getDatasChart.php)
                $('.user-wrap-wrap').html(response.table_html);
            },
            dataType: 'json'
        });
    });

    $('.left-filter').on('change', function () {
        var element = $(this).data('element');
        $('.result-wrap').hide();
        $('.result-wrap[data-type="' + element + '"]').show();
    });
});
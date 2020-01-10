$(function() {
    $.jsDate.regional['en'] = {
        monthNames: monthNames,
        monthNamesShort: monthNamesShort,
        dayNames: dayNames,
        dayNamesShort: dayNamesShort
    };

    $('#btnAbrirFechar').click(function(event) {
        if ($(this).html() == 'Abrir Grupos') {
            $(this).html('Fechar Grupos');
            $('.childGrupo').show();
            $('.childGrupoValor').show();
        } else {
            $(this).html('Abrir Grupos');
            $('.childGrupo').hide();
            $('.childGrupoValor').hide();
        }
        event.stopPropagation();
    }).click();


    $('[name^="childGrp."]').on('mouseover', function() {
        $('[name="' + $(this).attr("name") + '"]').addClass('selected');
    }).on('mouseout', function() {
        $('[name="' + $(this).attr("name") + '"]').removeClass('selected');
    });

    // grupos por entrada e saida
    var sGES = [[], [], [], [], []];
    // contas por grupo
    var sCG = [[], [], [], [], []];

    // valores de grupos
    var sVGe = [];
    // nomes dos grupos
    var sNGe = [];

    $('[name^="grupo."]').on('click', function() {
        $('[name="child.' + $(this).attr('onclick') + '"]').toggle();
        return false;
    }).on('mouseover', function() {
        $('[name="' + $(this).attr("name") + '"]').addClass('selected');
        $('[name="' + $(this).attr("name") + '"]').next().addClass('selected');
    }).on('mouseout', function() {
        $('[name="' + $(this).attr("name") + '"]').removeClass('selected');
        $('[name="' + $(this).attr("name") + '"]').next().removeClass('selected');
    }).each(function() {

        var chartID = $(this).parents('[name^="chartID."]');
        var date = '';
        if (chartID.length == 1) {
            date = chartID.attr('data-date');
            chartID = chartID.attr('name').toString().replace('chartID.', '');
        } else {
            chartID = NaN;
        }

        var val = parseFloat($(this).html().toString().replace('.', '').replace(',', '.'));
        if (!isNaN(val)) {
            var parciais = $(this).parent().parent().children('[name="parcial"]');
            if (parciais.length == 2) {
                var parcialEnt = parseFloat($(parciais[0]).html().toString().replace('.', '').replace(',', '.'));
                var parcialSai = parseFloat($(parciais[1]).html().toString().replace('.', '').replace(',', '.'));

                parcialEnt = parseInt((val / parcialEnt) * 100, 10);
                parcialSai = parseInt((val / parcialSai) * 100, 10);

                var valParcial = ($(this).parents().filter('.saida').length == 0 ? parcialEnt : parcialSai);

                var title = valParcial + '% do valor parcial';
            }
            $(this).attr('title', title);

            var name = $(this).attr('name').toString();
            var nameGrupo = $('[id="' + name + '"]').html();
            if (!isNaN(chartID)) {
                sGES[chartID].push([nameGrupo, val]);
                if (!(nameGrupo in sVGe)) {
                    sVGe[nameGrupo] = [];
                    sNGe.push(nameGrupo);
                }
                sVGe[nameGrupo].push([date, val]);
            }

            var children = $(this).next().children();
            for (var i = 0; i < children.length; i++) {
                var valChild = parseFloat($(children[i]).html().toString().replace('.', '').replace(',', '.'));
                if (!isNaN(valChild)) {
                    var parcialChild = parseInt((valChild / val) * 100, 10);
                    $(children[i]).attr('title', parcialChild + '% do grupo');

                    var nameChild = $('[id="' + $(children[i]).attr('name') + '"]').html();
                    if (!isNaN(chartID)) {
                        sCG[chartID].push([nameChild, valChild]);
                    }
                }
            }
        }
    });

    var sS = [];
    var sE = [];
    for (var charts = 0; charts < 5; charts++) {
        var parcialEnt = parseFloat($('#parcialE\\.' + charts).html().toString().replace('.', '').replace(',', '.'));
        var parcialSai = parseFloat($('#parcialS\\.' + charts).html().toString().replace('.', '').replace(',', '.'));

        parcialEnt = isNaN(parcialEnt) ? 0 : parcialEnt;
        parcialSai = isNaN(parcialSai) ? 0 : parcialSai;
        // entrada e saida
        var sESParcial = [['Entradas', parcialEnt], ['Sa&iacute;das', parcialSai]];
        sE.push([$('#chart\\.' + (charts)).attr('data-date'), parcialEnt]);
        sS.push([$('#chart\\.' + (charts)).attr('data-date'), parcialSai]);

        if (sCG[charts].length > 0 && sGES[charts].length > 0) {
            var plot = $.jqplot('chart\\.' + (charts), [sCG[charts], sGES[charts], sESParcial], {
                title: $('#chart\\.' + (charts)).attr('name'),
                seriesDefaults: {
                    // make this a donut chart.
                    renderer: $.jqplot.DonutRenderer,
                    rendererOptions: {
                        // Donut's can be cut into slices like pies.
                        sliceMargin: 3,
                        // Pies and donuts can start at any arbitrary angle.
                        startAngle: -90,
                        showDataLabels: true,
                        // By default, data labels show the percentage of the donut/pie.
                        // You can show the data 'value' or data 'label' instead.
                        dataLabels: 'label'
                    }
                },
                highlighter: {
                    show: true,
                    formatString: '%s : %.2f',
                    tooltipLocation: 'sw',
                    useAxesFormatters: false
                }
            });
        }
    }

    var plot = $.jqplot('chartResumo', [sE, sS], {
        title: '<h1>Quadro Resumo</h1>',
        axes: {
            xaxis: {
                renderer: $.jqplot.DateAxisRenderer,
                tickOptions: {
                    formatString: '%b'
                }
            },
            yaxis: {
                tickOptions: {
                    formatString: '%.2f'
                }
            }
        },
        highlighter: {
            show: true,
            tooltipContentEditor: function(str, seriesIndex, pointIndex, plot) {
                return ['Entradas', 'Sa&iacute;das'][seriesIndex] + ': ' + str;
            }
        },
        legend: {
            show: true,
            labels: ['Entradas', 'Sa&iacute;das']
        }
    });

    var aux = [];
    for (var name in sVGe) {
        aux.push(sVGe[name]);
    }

    if (aux.length > 0) {
        var plot = $.jqplot('chartGruposResumo', aux, {
            title: '<h1>Resumo dos Grupos ao longo do tempo</h1>',
            axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    tickOptions: {
                        formatString: '%b'
                    }
                },
                yaxis: {
                    tickOptions: {
                        formatString: '%.2f'
                    }
                }
            },
            highlighter: {
                show: true,
                tooltipContentEditor: function(str, seriesIndex, pointIndex, plot) {
                    return sNGe[seriesIndex] + ': ' + str;
                }
            },
            legend: {
                show: true,
                labels: sNGe,
                placement: "outside",
                location: 's'
            }
        });
    }
});
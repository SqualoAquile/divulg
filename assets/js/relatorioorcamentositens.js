var charts = [];
var labelProdutosGlobal = [];
var dataProdutosGlobal = [];

function floatPadraoInternacional(valor1){
    valor = valor1;

    if(valor != ""){
        valor = valor.replace(".","").replace(".","").replace(".","").replace(".","");
        valor = valor.replace(",",".");
        valor = parseFloat(valor);
        return valor;
    }else{
        valor = '';
        return valor;
    }
}

function addMask (mask, $els) {

    $els.forEach(function (el) {

        if (mask == 'data') {

            $(el)
                .mask('00/00/0000', {maxlength: false})
                .datepicker();

        } else if (mask == 'monetario') {
            
            $(el)
                .mask('#.##0,00', {
                    reverse: true
                });
                
        } else if (mask == 'numero') {

            $(el)
                .mask('0#');
        }
    });
}

function removeMask ($removeMask) {

    $elements = $removeMask ? $removeMask : $('#card-body-filtros input[type=text]');
    
    $elements.each(function () {

        var $this = $(this);

        $this
            .val('')
            .removeClass('is-invalid is-valid')
            .siblings('.invalid-feedback').remove();

        $this[0]
            .setCustomValidity('');
        
        $this
            .datepicker('destroy')
            .unmask();
    });
}

function floatParaPadraoBrasileiro(valor){
    var valortotal = valor;
    valortotal = number_format(valortotal,2,',','.');
    return valortotal;
}

function floatParaPadraoInternacional(valor){
    
    var valortotal = valor;
    valortotal = valortotal.replace(".", "").replace(".", "").replace(".", "").replace(".", "");
    valortotal = valortotal.replace(",", ".");
    valortotal = parseFloat(valortotal).toFixed(2);
    return valortotal;
}

function number_format( numero, decimal, decimal_separador, milhar_separador ){ 
        numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+numero) ? 0 : +numero,
            prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
            sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
            dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
 
        // Fix para IE: parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
}

function exists(arr, search) {
    return arr.some(row => row.includes(search));
}

$(function () {

    var dataTable = $('.dataTableRelatorioOrcamentosItens').DataTable(
        {
            scrollX: true,
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            scrollCollapse: true,
            searchHighlight: true,
            conditionalPaging: true,
            aLengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Mostrar Todos"]
            ],
            order: [0, 'desc'],
            ajax: {
                url: baselink + '/ajax/dataTableAjaxRelatorioOrcamentosItens',
                type: 'POST',
                data: {
                    module: currentModule
                }
            },
            language: {
                'decimal': ',',
                'thousands': '.',
                'sEmptyTable': 'Nenhum registro encontrado',
                'sInfo': 'Mostrando de _START_ até _END_ do total de _TOTAL_ registros',
                'sInfoEmpty': 'Mostrando 0 até 0 do total de 0 registros',
                'sInfoFiltered': '(Filtrados de _MAX_ registros)',
                'sInfoPostFix': '',
                'sInfoThousands': '.',
                'sLengthMenu': '_MENU_ Resultados por página',
                'sLoadingRecords': 'Carregando...',
                'sProcessing': 'Processando...',
                'sZeroRecords': 'Nenhum registro encontrado',
                'oPaginate': {
                    'sNext': 'Próximo',
                    'sPrevious': 'Anterior',
                    'sFirst': 'Primeiro',
                    'sLast': 'Último'
                }
            },
            dom: '<"limit-header-browser"l><t><p><r><i>',
            "rowCallback": function(row, data, index) {
                
                let $tdLargura = $('td', row).eq(10),
                    $tdComprimento = $('td', row).eq(11);

                $tdLargura.text($tdLargura.text().replace('R$ ', ''));
                $tdComprimento.text($tdComprimento.text().replace('R$ ', ''));

            }
        }
    );

    var id = "#chart-div";
    var ctx = document.getElementById(id.substr(1)).getContext('2d');

    var $collapse = $('#collapseFluxocaixaResumo'),
        $cardBodyFiltros = $('#card-body-filtros'),
        indexColumns = {
            acoes: 0,
            tipo: 3,
            material_servico: 4,
            valor: 8,
            quantidade: 9,
            data_aprov: 14
        };

    dataTable.on('xhr.dt', function (e, settings, json, xhr) {
        resumo(json.dataSemPaginacao);
    });

    function resumo (jsonData) {

        let rowData = jsonData,
            quantidadeProdutos = 0,
            quantidadeServicos = 0,
            quantidadeServicosCompl = 0,
            totalServicos = 0,
            totalServicosCompl = 0,
            totalProdutos = 0,
            totalItens = 0,
            nomeProduto = [],
            quantidadeProduto = [],
            listaProdutos =[];

        k = 0;

        if (rowData) {

            rowData.forEach(function (element, index) {

                let valor = element[indexColumns.valor],
                    quantidade = parseInt(element[indexColumns.quantidade]),
                    tipo = element[indexColumns.tipo],
                    produto = element[indexColumns.material_servico];
                
                valor = valor.replace('R$  ', '');
                valor = floatParaPadraoInternacional(valor);
    
                valor = valor * quantidade;

                // Separando os dados para Top 5 Produtos mais vendidos
                if (tipo == "Produtos") {
                    if (exists(nomeProduto,produto) == false) {
                        nomeProduto[k] = produto;
                        quantidadeProduto[k] = quantidade;
    
                        var entrada = [nomeProduto[k],quantidadeProduto[k]];
                        listaProdutos[k]=entrada;
    
                        k++;
    
                    }else {
                        var m = nomeProduto.indexOf(produto);
                        quantidadeProduto[m] += parseInt(quantidade);
                        var entrada = [nomeProduto[m],quantidadeProduto[m]];
                        listaProdutos[m] = entrada;
                    }
                }
                
                // Calculo para os cards do relatorio
                if (tipo=="Produtos") {
                    totalProdutos += parseFloat(valor);
                    quantidadeProdutos += parseInt(quantidade);
                } else if(tipo=="Servicoscomplementares") {
                    totalServicosCompl += parseFloat(valor);
                    quantidadeServicosCompl += parseInt(quantidade);
                } else {
                    totalServicos += parseFloat(valor);
                    quantidadeServicos += parseInt(quantidade);
                }

            });
        }
        
        listaProdutos = listaProdutos.sort(function(a,b) {
            return b[1]-a[1];
        });

        let dataProdutos = [],
            labelProdutos = [];

        for (let i = 0; i < listaProdutos.length; i++) {
            labelProdutos[i] = listaProdutos[i][0];
            dataProdutos[i] = listaProdutos[i][1];
        }

        if (labelProdutos.length>5 || dataProdutos.length>5) {
            labelProdutos = labelProdutos.slice(0,5);
            dataProdutos = dataProdutos.slice(0,5);
        }

        labelProdutosGlobal = labelProdutos;
        dataProdutosGlobal = dataProdutos;

        totalItens = parseFloat(totalProdutos) + parseFloat(totalServicos) + parseFloat(totalServicosCompl);
    
        $('#totalItens').text(floatParaPadraoBrasileiro(totalItens));

        $('#quantidadeServicos').text(parseInt(quantidadeServicos));
        $('#totalServicos').text(floatParaPadraoBrasileiro(totalServicos));

        $('#quantidadeServicosCompl').text(parseInt(quantidadeServicosCompl));
        $('#totalServicosCompl').text(floatParaPadraoBrasileiro(totalServicosCompl));

        $('#quantidadeProdutos').text(parseInt(quantidadeProdutos));
        $('#totalProdutos').text(floatParaPadraoBrasileiro(totalProdutos));

        // console.log('totalItens: ', totalItens);
        // console.log('quantidadeServicos: ', quantidadeServicos);
        // console.log('totalServicos: ', totalServicos);
        // console.log('quantidadeServicosCompl: ', quantidadeServicosCompl);
        // console.log('totalServicosCompl: ', totalServicosCompl);
        // console.log('quantidadeProdutos: ', quantidadeProdutos);
        // console.log('totalProdutos: ', totalProdutos);
        
        drawChart(id);

    };

    $('#relatorioorcamentoitens-section').addClass('d-none');
    $('#graficos').addClass('d-none');

    $('#collapseFluxocaixaResumo').on('show.bs.collapse', function () {
        $('#relatorioorcamentoitens-section').removeClass('d-none');
      });

    $('#collapseFluxocaixaResumo').on('hidden.bs.collapse', function () {
        $('#relatorioorcamentoitens-section').addClass('d-none');
    });

    $('#limpar-filtro').on('click', function () {
        $('#collapseFluxocaixaResumo').collapse('hide');
        $('#relatorioorcamentoitens-section').addClass('d-none');
    });

    $('#graficos').on('click', function () {
        $('#collapseFiltros').collapse('hide');
        $('#collapseFluxocaixaResumo').collapse('hide');
        $('#relatorioorcamentoitens-section').addClass('d-none');
    });

    $('#card-body-filtros').on('change', function () {
        $('#collapseFluxocaixaResumo').collapse('hide');
        $('#relatorioorcamentoitens-section').addClass('d-none');
    });


    // fazer para o campo de input também

    $('#botaoRelatorio').on('click', function(){

        let pesquisar = false;

        $('.filtros').each(function() {
            if (($(this).find('select.input-filtro-faixa').val() && ($(this).find('input.input-filtro-faixa.min').val() || $(this).find('input.input-filtro-faixa.max').val())) || $(this).find('select.input-filtro-texto').val() && $(this).find('input.input-filtro-texto').val()) {
                pesquisar = true;
            }
        });

        if (pesquisar) {
            dataTable.draw();
        } else {
            alert("Aplique um filtro para emitir um relatório!");
            event.stopPropagation();
        }else{
            DataTable.trigger('xhr.dt');
        }

    });
    
    function drawChart(id) {
        
        var titulo = labelProdutosGlobal.length +' Produtos mais vendidos',
        config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: dataProdutosGlobal,
                    backgroundColor: [
                        '#2a4c6b',
                        '#4a85b8',
                        '#adcbe6',
                        '#e7eff7',
                        '#62abea'
                    ]
                }],
                labels: labelProdutosGlobal
            },
            options: {
                animation: false,
                responsive: true,
                maintainAspectRatio: true,
                title: {
                    display: true,
                    text: titulo,
                    position: "top"
                },
                legend: {
                    display: true,
                    position: "right"
                },
            }
        };


        if(typeof charts[id] == "undefined") {   
            charts[id]= new (function(){
            this.ctx=$(id); 
            this.chart=new Chart(this.ctx, config);
            })();     
        } else {
            charts[id].chart.destroy();
            charts[id].chart=new Chart(charts[id].ctx, config); 
        }
    };    
    
    $(this)
        .on('change', '.filtros-faixa .input-filtro-faixa', function () {

            // Filtros Faixa

            $('.filtros-faixa .input-group').each(function () {

                var $this = $(this),
                    $select = $this.find('select'),
                    $selected = $select.find(':selected'),
                    type = $selected.attr('data-tipo'),
                    selectVal = $select.val(),
                    $min = $this.find('.min'),
                    min = $min.val(),
                    $max = $this.find('.max'),
                    max = $max.val(),
                    mascara = $selected.attr('data-mascara'),
                    stringSearch = '',
                    indexAnterior = $select.attr('data-index-anterior');

                addMask(mascara, [$min, $max]);

                if (mascara == 'monetario'){
                    min = floatPadraoInternacional(min);
                    max = floatPadraoInternacional(max);
                }

                if(mascara == 'data'){
                    min = min.split('/').reverse().join('-');
                    max = max.split('/').reverse().join('-');
                } 

                if (indexAnterior && indexAnterior != selectVal) {
                    dataTable
                        .columns(indexAnterior)
                        .search('')
                        .draw();
                }

                $max[0].setCustomValidity('');
                
                if (min && max) {
                    
                    $max.removeClass('is-invalid');
                    $max[0].setCustomValidity('');
                    $max.siblings('.invalid-feedback').remove();

                    if (min > max) {

                        $max.addClass('is-invalid');

                        $max[0].setCustomValidity('invalid');
                        $max.after('<div class="invalid-feedback col-lg-4 m-0">O valor deste campo deve ser maior que o campo anterior.</div>');
                        
                        $max.val('');
                        $min.val('');

                        dataTable.columns().search('').draw();

                        return false;
                    }
                }

                if (selectVal) {

                    if (min || max) {

                        if(mascara == 'data'){
                            min = min.split('-').reverse().join('/');
                            max = max.split('-').reverse().join('/');
                        } 

                        stringSearch = type + ':' + min + '<>' + max;
                    }

                    dataTable
                        .columns(selectVal)
                        .search(stringSearch)
                        .draw();

                    $select.attr('data-index-anterior', selectVal);
                }
            });
        })
        .on('change', '.filtros-texto .input-filtro-texto', function () {

            // Filtros Texto

            $('.filtros-texto .input-group').each(function () {

                var $this = $(this),
                    $select = $this.find('select'),
                    $input = $this.find('.texto'),
                    inputVal = $input.val(),
                    selectVal = $select.val(),
                    value = inputVal ? inputVal : '',
                    indexAnterior = $select.attr('data-index-anterior');

                if (indexAnterior) {
                    dataTable
                        .columns(indexAnterior)
                        .search('')
                        .draw();
                }

                if (selectVal) {
                    
                    dataTable
                        .columns(selectVal)
                        .search(value)
                        .draw();

                    $select.attr('data-index-anterior', selectVal);
                }
            });
        })
        .on('click', '#limpar-filtro', function () {

            // Limpar Filtros

            var $cardBodyFiltros = $('#card-body-filtros'),
            $select = $cardBodyFiltros.find('select'),
            $searchDataTable = $('#searchDataTable');
            
            removeMask();

            $select
                .val('');

            $searchDataTable
                .val('');

            $cardBodyFiltros
                .find('[type=checkbox]')
                .prop('checked', false);

            dataTable
                .columns()
                .search('')
                .draw();

            dataTable.search('').draw();
            
        })
        .on('change', '[name=movimentacao]', function () {

            // Checkbox

            var $this = $(this),
                $fieldset = $this.parents('fieldset'),
                $checkeds = $fieldset.find(':checked'),
                indexColumn = $this.attr('data-index'),
                lenght = $checkeds.length,
                search = lenght == 2 || lenght == 0 ? '' : $checkeds.val();

            dataTable
                .columns(indexColumn)
                .search(search)
                .draw();

        });

});

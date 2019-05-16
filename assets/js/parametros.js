function floatParaPadraoBrasileiro(valor) {
    var valortotal = valor;
    valortotal = number_format(valortotal, 2, ',', '.');
    return valortotal;
}

function floatParaPadraoInternacional(valor) {

    var valortotal = valor;
    valortotal = valortotal.replace(".", "").replace(".", "").replace(".", "").replace(".", "");
    valortotal = valortotal.replace(",", ".");
    valortotal = parseFloat(valortotal).toFixed(2);
    return valortotal;
}

function number_format(numero, decimal, decimal_separador, milhar_separador) {
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

const parametrosSemAcoes = [
    'dinheiro',
    'cartão débito',
    'cartão crédito',
    'boleto',
    'cheque',
    'transferência',
    'TED',
    'DOC',
    'Elo',
    'Visa',
    'MasterCard',
    'BanriCompras',
    'Hiper',
    'Visa Electron',
    'Hipercard',
    'M²',
    'm²',
    'ml',
    'ML',
    'Contato',
    'contato'
];

function Ajax(url, callback, send = {}) {
    $.ajax({
        url: baselink + '/ajax/' + url,
        type: 'POST',
        data: send,
        dataType: 'json',
        success: callback
    });
};

function Popula($wrapper, data, campo) {

    var htmlContentSearch = '';

    data.forEach(element => {

        var htmlAcoes = '';

        if (parametrosSemAcoes.indexOf(element[campo]) == -1) {

            htmlAcoes = `
                <div>
                `;

            if (data_edt == true) {
                htmlAcoes += `
                    <button class="editar btn btn-sm btn-primary" tabindex="-1">
                        <i class="fas fa-edit"></i>
                    </button>
                    `;
            }

            if (data_exc == true) {
                htmlAcoes += `
                    <button class="excluir btn btn-sm btn-danger" tabindex="-1">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    `;
            }

            htmlAcoes += `
                </div>
            `;
        }

        htmlContentSearch += `
            <div id="` + element.id + `" class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text">` + element[campo] + `</span>`
            + htmlAcoes +
            `</div>
            </div>
        `;
    });

    $wrapper.html(htmlContentSearch);
};

function PopulaDoisCampos($wrapper, data, campo1, campo2) {

    var htmlContentSearch = '';

    data.forEach(element => {

        var htmlAcoes = '';

        if (parametrosSemAcoes.indexOf(element[campo1]) == -1) {

            htmlAcoes = '';

            if (data_edt == true) {
                htmlAcoes += `
                    <button class="editar-doiscampos btn btn-sm btn-primary" tabindex="-1">
                        <i class="fas fa-edit"></i>
                    </button>
                `;
            }

            if (data_exc == true) {
                htmlAcoes += `
                    <button class="excluir btn btn-sm btn-danger" tabindex="-1">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;
            }

        }

        htmlContentSearch += `
            <div id="` + element.id + `" class="list-group-item list-group-item-doiscampos">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="m-0 titulo-avisos">` + element[campo1] + `</h6>
                        <small>` + element[campo2] + `</small>
                    </div>
                    <div>`
                        + htmlAcoes +
                    `</div>
                </div>
            </div>
        `;
    });

    $wrapper.html(htmlContentSearch);
};

// Dropdowns
$(document)
    .on('click', '.down-btn', function () {
        $(this).parents('.search-body').find('.search-input').focus();
    })
    .on('click', '.close-btn', function () {

        var $searchBody = $(this).parents('.search-body'),
            $inputSearch = $searchBody.find('.search-input');

        if ($inputSearch.attr('data-id')) {

            $inputSearch[0].setCustomValidity('');

            $inputSearch
                .removeClass('is-invalid is-valid')
                .removeAttr('data-id')
                .val('')
                .focus();

        } else {

            if ($searchBody.find('.elements-add').children().length) {

                $inputSearch
                    .removeClass('is-invalid is-valid')
                    .removeAttr('data-id')
                    .val('')
                    .trigger('input')
                    .focus();
            } else {

                $searchBody.find('.list-group-filtereds-wrapper').hide();
                $searchBody.removeClass('active');
            }

        }
    })
    .on('click touchstart', function (event) {

        var $currentElement = $(event.target);

        if (!$currentElement.parents('.search-body').length) {

            var $searchBodyActive = $('.search-body');

            $searchBodyActive
                .find('.search-input')
                .blur();

            $searchBodyActive
                .removeClass('active')
                .find('.list-group-filtereds-wrapper')
                .hide();

        } else {

            var $notCurrent = $('.search-body.active').not($currentElement.parents('.search-body'))

            $notCurrent
                .find('.list-group-filtereds-wrapper')
                .hide();

            $notCurrent.removeClass('active');
        }
    })
    .on('DOMNodeInserted', '.list-group-item', function (event) {

        var $created = $(event.target),
            $inputSearch = $created.parents('.list-group-filtereds-wrapper').siblings('.search-input');

        if ($created.attr('id') == $inputSearch.attr('data-id')) {

            $created
                .find('.excluir')
                .hide();

            $created
                .find('.editar')
                .removeClass('editar btn-primary')
                .addClass('salvar btn-success')
                .find('.fas')
                .removeClass('fa-edit')
                .addClass('fa-save');

        }
    })
    .on('focus', '.search-input', function () {

        var $this = $(this),
            $searchBody = $this.parents('.search-body'),
            $contentSearchThisWrapper = $searchBody.find('.list-group-filtereds-wrapper'),
            $contentSearchThis = $contentSearchThisWrapper.find('.list-group-filtereds'),
            campo = $searchBody.attr('data-campo'),
            value = $this.val();

        $searchBody.addClass('active');
        $contentSearchThisWrapper.show();

        if ($this.attr('data-id')) {
            value = $this.attr('data-anterior');
        }

        if (($this.val() && $this.attr('data-id')) || (!$this.val() && !$this.attr('data-id'))) {

            Ajax('listarParametros', function (data) {

                Popula($contentSearchThis, data, campo);

                if ($this.attr('data-id')) {
                    $this.trigger('input');
                }

            }, {
                    value: value,
                    tabela: $searchBody.attr('id'),
                    campo: campo
                });
        }

    })
    .on('input', '.search-input', function () {

        var $this = $(this),
            $contentSearchThis = $this.siblings('.list-group-filtereds-wrapper').find('.list-group-filtereds'),
            id = $this.attr('data-id'),
            $searchBody = $this.parents('.search-body'),
            campo = $searchBody.attr('data-campo');
        tabela = $searchBody.attr('id'),
            $elAdd = $contentSearchThis.siblings('.elements-add'),
            $saveParametros = $searchBody.find('.salvar');

        if (id == undefined) {
            // Pesquisando

            Ajax('listarParametros', function (data) {

                Popula($contentSearchThis, data, campo);

                var htmlElAdd = '';

                if (!data.length && data_add == true) {
                    htmlElAdd += `
                        <div class="p-3">
                            <small>Nenhum resultado encontrado</small>
                            <button class="salvar btn btn-success btn-block text-truncate mt-2">Adicionar: ` + $this.val() + `</button>
                        </div>
                    `;
                }

                $elAdd.html(htmlElAdd);

            }, {
                    value: $this.val(),
                    tabela: tabela,
                    campo: campo
                });

        } else {
            // Editando

            var $btnSalvar = $contentSearchThis.find('.salvar');

            $this.removeClass('is-invalid is-valid');

            if ($this.val()) {
                if ($this.val() != $this.attr('data-anterior')) {

                    $this[0].setCustomValidity('');
                    $this.addClass('is-valid');

                    $btnSalvar.removeAttr('disabled');

                } else {

                    $btnSalvar.attr('disabled', 'disabled');

                }
            } else {

                $this[0].setCustomValidity('invalid');
                $this.addClass('is-invalid');

                $btnSalvar.attr('disabled', 'disabled');
            }

            $('.list-group-filtereds #' + id + ' .text').text($this.val());

        }
    })
    .on('keydown', '.search-input', function (event) {

        var $this = $(this),
            $searchBody = $this.parents('.search-body'),
            $inputSearch = $searchBody.find('.search-input'),
            code = event.keyCode || event.which;

        if (code == 27 || code == 9) {
            // Esc || Tab

            $inputSearch[0].setCustomValidity('');

            $inputSearch
                .removeClass('is-valid is-invalid')
                .removeAttr('data-id')
                .val('')
                .trigger('input')
                .blur();

            $searchBody
                .find('.elements-add')
                .html('');

            $searchBody.find('.icons-search-input .close-btn').click();

        }
    })
    .on('click', '.excluir', function () {

        var $this = $(this),
            $parent = $this.closest('.list-group-item'),
            $input = $this.parents('.search-body').find('.search-input')
        tabela = $parent.parents('.search-body').attr('id');

        if (confirm('Tem Certeza?')) {
            Ajax('excluirParametros/' + $parent.attr('id'), function (data) {
                if (data[0] == '00000') {

                    Toast({
                        message: 'Parâmetro excluido com sucesso!',
                        class: 'alert-success'
                    });

                    $input.val('');
                    $parent.remove();
                    $input.focus();
                }
            }, {
                    tabela: tabela
                });
        }
    })
    .on('click', '.salvar', function () {

        var $this = $(this),
            $searchBody = $this.parents('.search-body'),
            $inputSearch = $searchBody.find('.search-input'),
            tabela = $searchBody.attr('id'),
            campo = $searchBody.attr('data-campo');

        if ($inputSearch.attr('data-id') != $inputSearch.val() && $inputSearch.val()) {
            if ($inputSearch.attr('data-id') == undefined) {

                Ajax('adicionarParametros', function (data) {
                    if (data[0] == '00000') {

                        Toast({
                            message: 'Parâmetro incluso com sucesso!',
                            class: 'alert-success'
                        });

                        $inputSearch
                            .removeClass('is-valid is-invalid')
                            .val('')
                            .focus()
                            .trigger('input');

                    }
                }, {
                        value: $inputSearch.val(),
                        campo: campo,
                        tabela: tabela
                    });

            } else {

                Ajax('editarParametros/' + $inputSearch.attr('data-id'), function (data) {
                    if (data[0] == '00000') {

                        Toast({
                            message: 'Parâmetro editado com sucesso!',
                            class: 'alert-success'
                        });

                        $inputSearch
                            .removeClass('is-valid is-invalid')
                            .removeAttr('data-id')
                            .val('')
                            .focus()
                            .trigger('input');
                    }
                }, {
                        value: $inputSearch.val(),
                        tabela: tabela,
                        campo: campo
                    });

            }
        } else {

            $inputSearch[0].setCustomValidity('invalid');

            $inputSearch
                .focus()
                .addClass('is-invalid');
        }

    })
    .on('click', '.editar', function () {

        var $parent = $(this).closest('.list-group-item'),
            $inputSearch = $parent.parents('.list-group-filtereds-wrapper').siblings('.search-input');

        $inputSearch
            .val($parent.find('.text').text())
            .attr('data-id', $parent.attr('id'))
            .attr('data-anterior', $parent.find('.text').text())
            .focus();
    });

// Dropdowns Dois Campos
$(document)
    .ready(function () {
        $(this).trigger('lista-doiscampos');
    })
    .on('lista-doiscampos', function () {
        var $searchBody = $('.search-body-doiscampos'),
            $contentSearchThisWrapper = $searchBody.find('.list-group-filtereds-wrapper'),
            $contentSearchThis = $contentSearchThisWrapper.find('.list-group-filtereds');

        Ajax('listarParametrosDoiscampos', function (data) {

            PopulaDoisCampos($contentSearchThis, data, 'titulo', 'mensagem');

            $contentSearchThisWrapper.hide();

        }, {
                tabela: $searchBody.attr('id')
            });
    })
    .on('focus', '.search-input-doiscampos', function () {

        var $this = $(this),
            $searchBody = $this.parents('.search-body'),
            $contentSearchThisWrapper = $searchBody.find('.list-group-filtereds-wrapper');

        if (!$(this).attr('data-id')) {
            $searchBody.addClass('active');
            $contentSearchThisWrapper.show();
        }

    })
    .on('input', '.search-input-doiscampos', function () {

        var $this = $(this),
            $searchBody = $this.parents('.search-body'),
            $contentSearchThis = $this.siblings('.list-group-filtereds-wrapper').find('.list-group-filtereds'),
            id = $this.attr('data-id'),
            $elAdd = $searchBody.find('.elements-add-doiscampos'),
            $elements = $contentSearchThis.find('.list-group-item'),
            $textarea = $searchBody.find('textarea');

        if (id == undefined) {

            // Pesquisando

            $this.removeClass('is-invalid is-valid');
            $textarea.removeClass('is-invalid is-valid');

            var $filtereds = $elements.filter(function () {
                    return $(this).text().toLowerCase().indexOf($this.val().toLowerCase()) != -1;
                }),
                htmlElAdd = '';

            if (!$filtereds.length) {

                $searchBody
                    .find('.icons-search-input')
                    .removeClass('d-flex')
                    .addClass('d-none');
                
                htmlElAdd += `
                    <small>Nenhum resultado encontrado</small>
                    <div class="row">
                        <div class="col-lg-9">
                            <button class="salvar-doiscampos btn btn-success btn-block text-truncate mt-2">Adicionar</button>
                        </div>
                        <div class="col-lg-3">
                            <button class="cancelar-doiscampos btn btn-light btn-block text-truncate mt-2" title="Cancelar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;

                if ($this.val()) {
                    if ($this.val() != $this.attr('data-anterior')) {
                        $this[0].setCustomValidity('');
                        $this.addClass('is-valid');
                    }
                }

                if ($textarea.val()) {
                    if ($textarea.val() != $textarea.attr('data-anterior')) {
                        $textarea[0].setCustomValidity('');
                        $textarea.addClass('is-valid');
                    }
                }

            } else {
                $searchBody
                    .find('.icons-search-input')
                    .removeClass('d-none')
                    .addClass('d-flex');
            }

            $elAdd.html(htmlElAdd);

            $elements.not($filtereds).hide();
            $filtereds.show();

        } else {
            
            // Editando
            $elements.hide();

            var $btnSalvar = $contentSearchThis.find('.salvar');

            $this.removeClass('is-invalid is-valid');
            $textarea.removeClass('is-invalid is-valid');

            if ($this.val()) {
                if ($this.val() != $this.attr('data-anterior')) {

                    $this[0].setCustomValidity('');
                    $this.addClass('is-valid');

                    $btnSalvar.removeAttr('disabled');

                } else {

                    $btnSalvar.attr('disabled', 'disabled');

                }
            } else {

                $this[0].setCustomValidity('invalid');
                $this.addClass('is-invalid');

                $btnSalvar.attr('disabled', 'disabled');
            }

            if ($textarea.val()) {
                if ($textarea.val() != $textarea.attr('data-anterior')) {
                    $textarea[0].setCustomValidity('');
                    $textarea.addClass('is-valid');
                }
            } else {
                $textarea[0].setCustomValidity('invalid');
                $textarea.addClass('is-invalid');
            }

        }
    })
    .on('click', '.salvar-doiscampos', function () {

        var $this = $(this),
            $searchBody = $this.parents('.search-body'),
            $inputSearch = $searchBody.find('.search-input-doiscampos'),
            tabela = $searchBody.attr('id'),
            $textarea = $searchBody.find('textarea'),
            $iconsSearch = $searchBody.find('.icons-search-input');

        if ($inputSearch.attr('data-id') != $inputSearch.val() && ($inputSearch.val() && $textarea.val())) {

            if ($inputSearch.attr('data-id') == undefined) {

                Ajax('adicionarParametrosDoisCampos', function (data) {

                    if (data[0] == '00000') {

                        $(document).trigger('lista-doiscampos');

                        Toast({
                            message: 'Parâmetro incluso com sucesso!',
                            class: 'alert-success'
                        });

                        $inputSearch
                            .removeClass('is-valid is-invalid')
                            .val('')
                            .trigger('input');

                        $textarea
                            .removeClass('is-valid is-invalid')
                            .val('');

                        $iconsSearch
                            .find('.close-btn')
                            .click();

                    }
                }, {
                        value1: $inputSearch.val(),
                        campo1: 'titulo',
                        value2: $textarea.val(),
                        campo2: 'mensagem',
                        tabela: tabela
                    });

            } else {

                Ajax('editarParametrosDoisCampos/' + $inputSearch.attr('data-id'), function (data) {
                    if (data[0] == '00000') {

                        $(document).trigger('lista-doiscampos');

                        Toast({
                            message: 'Parâmetro editado com sucesso!',
                            class: 'alert-success'
                        });

                        $inputSearch
                            .removeClass('is-valid is-invalid')
                            .removeAttr('data-id')
                            .val('')
                            .trigger('input');

                        $textarea
                            .removeClass('is-valid is-invalid')
                            .val('');

                        $iconsSearch
                            .removeClass('d-none')
                            .addClass('d-flex');

                    }
                }, {
                        value1: $inputSearch.val(),
                        campo1: 'titulo',
                        value2: $textarea.val(),
                        campo2: 'mensagem',
                        tabela: tabela
                    });

            }

        } else {

            if (!$inputSearch.val()) {
                $inputSearch[0].setCustomValidity('invalid');
                $inputSearch.addClass('is-invalid');
            }

            if (!$textarea.val()) {
                $textarea[0].setCustomValidity('invalid');
                $textarea.addClass('is-invalid');
            }

            $searchBody
                .find('.form-control.is-invalid')
                .first()
                .focus();
        }

    })
    .on('click', '.editar-doiscampos', function () {

        var $parent = $(this).closest('.list-group-item'),
            $searchBody = $parent.parents('.search-body'),
            $filteredsWrapper = $parent.parents('.list-group-filtereds-wrapper'),
            $inputSearch = $filteredsWrapper.siblings('.search-input-doiscampos'),
            $elAdd = $searchBody.find('.elements-add-doiscampos'),
            $textarea = $searchBody.find('textarea'),
            $iconsSearch = $inputSearch.siblings('.icons-search-input'),
            htmlElAdd = `
                <div class="row">
                    <div class="col-lg-9">
                        <button class="salvar-doiscampos btn btn-primary btn-block text-truncate mt-2">Salvar</button>
                    </div>
                    <div class="col-lg-3">
                        <button class="cancelar-doiscampos btn btn-light btn-block text-truncate mt-2">Cancelar</button>
                    </div>
                </div>
            `;

        $inputSearch
            .val($parent.find('.titulo-avisos').text())
            .attr('data-id', $parent.attr('id'))
            .attr('data-anterior', $parent.find('.text').text())
            .focus();

        $textarea
            .val($parent.find('.titulo-avisos ~ small').text())
            .attr('data-anterior', $parent.find('.titulo-avisos ~ small').text());

        $elAdd.html(htmlElAdd);

        $iconsSearch
            .removeClass('d-flex')
            .addClass('d-none');

        $searchBody.removeClass('active');
        $filteredsWrapper.hide();
            
    })
    .on('click', '.cancelar-doiscampos', function () {
        
        var $this = $(this),
            $searchBody = $this.parents('.search-body-doiscampos'),
            $inputSearch = $searchBody.find('.search-input-doiscampos');

        $inputSearch
            .removeClass('is-valid is-invalid')
            .removeAttr('data-id data-anterior')
            .val('')
            .trigger('input');

        $inputSearch[0].setCustomValidity('');

        $searchBody
            .find('textarea')
            .removeClass('is-valid is-invalid')
            .removeAttr('data-anterior')
            .val('');

        $('.elements-add-doiscampos').empty();

        $searchBody.find('.icons-search-input')
            .removeClass('d-none')
            .addClass('d-flex');
    })
    .on('click', '.close-btn-doiscampos', function () {

        var $this = $(this),
            $searchBody = $this.parents('.search-body-doiscampos');

        $searchBody
            .find('.form-control')
            .val('');

        $searchBody
            .find('.search-input-doiscampos')
            .trigger('input');
    })
    .on('input', '.textarea-doiscampos', function () {
        $('.search-input-doiscampos').trigger('input');
    });

// Fixos
$(document)
    .on('submit', '.form-params-fixos', function (event) {

        event.preventDefault();

        var $this = $(this),
            $input = $this.find('.input-fixos');
        value = $input.val(),
            campos_alterados = '',
            id = $input.attr('data-id'),
            $label = $this.find('label span');

        $input.blur();

        if (this.checkValidity() == false) {

            $this
                .find('.is-invalid, :invalid')
                .first()
                .focus();

        } else {

            if ($input.val() != $input.attr('data-anterior')) {
                campos_alterados += '{' + $label.text().toUpperCase() + ' de (' + $input.attr('data-anterior') + ') para (' + $input.val() + ')}';
                campos_alterados = $input.attr('data-alteracoes') + '##' + campos_alterados;
            }

            if ($input.attr('data-mascara_validacao') == 'monetario') {
                value = floatParaPadraoInternacional(value);
                value = floatParaPadraoBrasileiro(value);
            }

            if (confirm('Tem Certeza?')) {
                Ajax('editarParametrosFixos/' + id, function (data) {

                    if (data.erro[0] == '00000') {

                        Toast({
                            message: 'Parâmetro editado com sucesso!',
                            class: 'alert-success'
                        });

                        $this
                            .removeClass('was-validated');

                        $input
                            .attr('data-anterior', value)
                            .attr('data-alteracoes', data.result.alteracoes)
                            .removeClass('is-valid is-invalid')
                            .keyup();

                    }

                }, {
                        value: value,
                        alteracoes: campos_alterados
                    });
            }

        }

        $this.addClass('was-validated');

    })
    .on('keyup', '.input-fixos', function () {

        var $this = $(this),
            $submit = $this.parents('form').find('[type=submit]');

        if ($this.val() != $this.attr('data-anterior')) {
            $submit.removeAttr('disabled');
        } else {
            $submit.attr('disabled', 'disabled');
        }
    })
    .on('blur', '.input-fixos', function () {
        if (!$(this).val()) {
            $(this)
                .val($(this).attr('data-anterior'))
                .keyup();
        }
    });
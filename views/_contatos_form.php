<script src="<?php echo BASE_URL;?>/assets/js/contatos_form.js" type="text/javascript"></script>
<form id="contatos-form" autocomplete="off" novalidate>
    <h3 class="mt-5 mb-4">Contatos</h3>
    <div class="table-responsive mb-lg-5 mb-3">
        <table id="contatos" class="table table-striped table-hover table-fixed bg-white">
            <thead>
                <tr role="form" class="d-flex flex-column flex-lg-row">
                    <th class="col-lg-2">
                        <label for="contato_nome" class="font-weight-bold">* Nome</label>
                        <input type="text" class="form-control" id="contato_nome" name="contato_nome" maxlength="40" required>
                    </th>
                    <th class="col-lg-2">
                        <label for="contato_setor" class="font-weight-bold">* Setor</label>
                        <input type="text" class="form-control" id="contato_setor" name="contato_setor" data-mascara_validacao="false" maxlength="20" required>
                    </th>
                    <th class="col-lg-2">
                        <label for="contato_telefone" class="font-weight-normal">Telefone</label>
                        <input type="tel" class="form-control" id="contato_telefone" name="contato_telefone" data-mascara_validacao="telefone" maxlength="13" >
                    </th>
                    <th class="col-lg-1">
                        <label for="contato_ramal" class="font-weight-normal">Ramal</label>
                        <input type="text" class="form-control" id="contato_ramal" name="contato_ramal" data-mascara_validacao="numero" maxlength="5" >
                    </th>
                    <th class="col-lg-2">
                        <label for="contato_celular" class="font-weight-normal">Celular</label>
                        <input type="tel" class="form-control" id="contato_celular" name="contato_celular" data-mascara_validacao="celular" maxlength="14" >
                    </th>
                    <th class="col-lg-2">
                        <label for="contato_email" class="font-weight-normal">Email</label>
                        <input type="email" class="form-control" id="contato_email" name="contato_email" data-mascara_validacao="email" maxlength="50" >
                    </th>
                    <th class="col-lg-1">
                        <label>Ações</label>
                        <br>
                        <button type="submit" class="btn btn-primary">Incluir</a>
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</form>
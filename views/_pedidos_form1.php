<script src="<?php echo BASE_URL;?>/assets/js/pedidos_form.js" type="text/javascript"></script>
<!-- <form id="contatos-form" autocomplete="off" novalidate> -->
    <h3 class="mt-5 mb-4">Pedidos Fixos</h3>
    <div class="table-responsive mb-lg-5 mb-3">
        <table id="pedidos_fixos" class="table table-striped table-hover bg-white" style="max-height: 500px; overflow-y: auto; width: 100%;">
            <thead>
                <tr role="form" class="d-flex flex-column flex-lg-row text-center">
                    <th class="col-lg-2">
                        <label class="text-center" for="dia_semana">Segunda-Feira</label><br>
                        <label for="dia_semana">Rota</label>
                        <input type="text" class="form-control" id="rota" name="rota" data-mascara_validacao="false" maxlength="40" required>

                    </th>
                    <th class="col-lg-2">
                        <label class="text-center" for="dia_semana">Terça-Feira</label><br>
                        <label for="dia_semana">Rota</label>
                        <input type="text" class="form-control" id="rota" name="rota" data-mascara_validacao="false" maxlength="40" required>

                    </th>
                    <th class="col-lg-2">
                        <label class="text-center" for="dia_semana">Quarta-Feira</label><br>
                        <label for="dia_semana">Rota</label>
                        <input type="text" class="form-control" id="rota" name="rota" data-mascara_validacao="false" maxlength="40" required>

                    </th>
                    <th class="col-lg-2">
                        <label class="text-center" for="dia_semana">Quinta-Feira</label><br>
                        <label for="dia_semana">Rota</label>
                        <input type="text" class="form-control" id="rota" name="rota" data-mascara_validacao="false" maxlength="40" required>

                    </th>
                    <th class="col-lg-2">
                        <label class="text-center" for="dia_semana">Sexta-Feira</label><br>
                        <label for="dia_semana">Rota</label>
                        <input type="text" class="form-control" id="rota" name="rota" data-mascara_validacao="false" maxlength="40" required>

                    </th>
                    <th class="col-lg-2">
                        <label class="text-center" for="dia_semana">Sábado</label><br>
                        <label for="dia_semana">Rota</label>
                        <input type="text" class="form-control" id="rota" name="rota" data-mascara_validacao="false" maxlength="40" required>

                    </th>
                    
                </tr>
            </thead>
            <tbody>
                <?php for($j = 0; $j < count($produtos); $j++):?>
                                                    
                    <tr role="form" class="d-flex flex-column flex-lg-row text-center">
                        <th class="col-lg-2 d-flex flex-column flex-lg-row aling-center">
                                <label for="" class="col"> <?php echo $produtos[$j]['codigo']; ?></label>
                                <input type="text" class="form-control col" id="rota" name="rota" data-mascara_validacao="numero" maxlength="2" required>
                        </th>
                        <th class="col-lg-2 d-flex flex-column flex-lg-row aling-center">
                                <label for="" class="col"> <?php echo $produtos[$j]['codigo']; ?></label>
                                <input type="text" class="form-control col" id="rota" name="rota" data-mascara_validacao="numero" maxlength="2" required>
                        </th>
                        <th class="col-lg-2 d-flex flex-column flex-lg-row aling-center">
                                <label for="" class="col"> <?php echo $produtos[$j]['codigo']; ?></label>
                                <input type="text" class="form-control col" id="rota" name="rota" data-mascara_validacao="numero" maxlength="2" required>
                        </th>
                        <th class="col-lg-2 d-flex flex-column flex-lg-row aling-center">
                                <label for="" class="col"> <?php echo $produtos[$j]['codigo']; ?></label>
                                <input type="text" class="form-control col" id="rota" name="rota" data-mascara_validacao="numero" maxlength="2" required>
                        </th>
                        <th class="col-lg-2 d-flex flex-column flex-lg-row aling-center">
                                <label for="" class="col"> <?php echo $produtos[$j]['codigo']; ?></label>
                                <input type="text" class="form-control col" id="rota" name="rota" data-mascara_validacao="numero" maxlength="2" required>
                        </th>
                        <th class="col-lg-2 d-flex flex-column flex-lg-row aling-center">
                                <label for="" class="col"> <?php echo $produtos[$j]['codigo']; ?></label>
                                <input type="text" class="form-control col" id="rota" name="rota" data-mascara_validacao="numero" maxlength="2" required>
                        </th>
                    </tr>
                       
                    
                <?php endfor;?>        
            </tbody>
        </table>
    </div>
<!-- </form> -->
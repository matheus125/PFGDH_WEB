<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="card card-info card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Atualizar Cliente</div>
    </div>

    <!-- Formulário UPDATE -->
    <form class="needs-validation" action="/admin/clientes/update" method="post" novalidate>

        <!-- ID oculto -->
        <input type="hidden" name="id" value="<?php echo htmlspecialchars( $clientes["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">

        <div class="card-body">
            <div class="row g-3">

                <!-- DADOS PESSOAIS -->
                <h5 class="mt-3">Dados Pessoais</h5>
                <div class="col-md-4">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" class="form-control form-control-sm" name="nome_completo"
                        value="<?php echo htmlspecialchars( $clientes["nome_completo"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Nome Social</label>
                    <input type="text" class="form-control form-control-sm" name="nome_social"
                        value="<?php echo htmlspecialchars( $clientes["nome_social"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Cor / Etnia</label>
                    <input type="text" class="form-control form-control-sm" name="cor_cliente"
                        value="<?php echo htmlspecialchars( $clientes["cor_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Nome da Mãe</label>
                    <input type="text" class="form-control form-control-sm" name="nome_mae" value="<?php echo htmlspecialchars( $clientes["nome_mae"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sexo</label>
                    <select class="form-select form-select-sm" name="genero_cliente">
                        <option value="M" {if="$clientes.genero_cliente=='M'" }selected<?php  ?>>Masculino</option>
                        <option value="F" {if="$clientes.genero_cliente=='F'" }selected<?php  ?>>Feminino</option>
                        <option value="Outro" {if="$clientes.genero_cliente=='Outro'" }selected<?php ?>>Outro</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Estado Civil</label>
                    <select class="form-select form-select-sm" name="estado_civil">
                        <option {if="$clientes.estado_civil=='Solteiro(a)'" }selected<?php ?>>Solteiro(a)</option>
                        <option {if="$clientes.estado_civil=='Casado(a)'" }selected<?php ?>>Casado(a)</option>
                        <option {if="$clientes.estado_civil=='Divorciado(a)'" }selected<?php  ?>>Divorciado(a)</option>
                        <option {if="$clientes.estado_civil=='Viúvo(a)'" }selected<?php  ?>>Viúvo(a)</option>
                        <option {if="$clientes.estado_civil=='União Estável'" }selected<?php  ?>>União Estável</option>
                    </select>

                </div>

                <div class="col-md-2">
                    <label class="form-label">Nascimento</label>
                    <input type="date" class="form-control form-control-sm" name="data_nascimento"
                        value="<?php echo htmlspecialchars( $clientes["data_nascimento"] , ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <div class="col-md-1">
                    <label class="form-label">Idade</label>
                    <input type="number" class="form-control form-control-sm" value="<?php echo htmlspecialchars( $clientes["idade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" readonly>
                </div>

                <!-- DOCUMENTOS -->
                <h5 class="mt-4">Documentos</h5>
                <div class="col-md-3">
                    <label class="form-label">RG</label>
                    <input type="text" class="form-control form-control-sm" name="rg" value="<?php echo htmlspecialchars( $clientes["rg"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control form-control-sm" name="cpf" value="<?php echo htmlspecialchars( $clientes["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">NIS</label>
                    <input type="text" class="form-control form-control-sm" name="nis" value="<?php echo htmlspecialchars( $clientes["nis"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control form-control-sm" name="status_cliente"
                        value="<?php echo htmlspecialchars( $clientes["status_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <!-- CONTATO -->
                <h5 class="mt-4">Contato</h5>
                <div class="col-md-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" class="form-control form-control-sm" name="telefone" value="<?php echo htmlspecialchars( $clientes["telefone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        required>
                </div>

                <!-- ENDEREÇO -->
                <h5 class="mt-4">Endereço</h5>
                <div class="col-md-2">
                    <label class="form-label">CEP</label>
                    <input type="text" class="form-control form-control-sm" name="cep" value="<?php echo htmlspecialchars( $clientes["cep"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rua</label>
                    <input type="text" class="form-control form-control-sm" name="rua" value="<?php echo htmlspecialchars( $clientes["rua"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Nº</label>
                    <input type="text" class="form-control form-control-sm" name="numero" value="<?php echo htmlspecialchars( $clientes["numero"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bairro</label>
                    <input type="text" class="form-control form-control-sm" name="bairro" value="<?php echo htmlspecialchars( $clientes["bairro"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Cidade</label>
                    <input type="text" class="form-control form-control-sm" name="cidade" value="<?php echo htmlspecialchars( $clientes["cidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Referência</label>
                    <input type="text" class="form-control form-control-sm" name="referencia"
                        value="<?php echo htmlspecialchars( $clientes["referencia"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tempo de Moradia</label>
                    <input type="text" class="form-control form-control-sm" name="tempo_moradia_cliente"
                        value="<?php echo htmlspecialchars( $clientes["tempo_moradia_anos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Nacionalidade</label>
                    <input type="text" class="form-control form-control-sm" name="nacionalidade"
                        value="<?php echo htmlspecialchars( $clientes["nacionalidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Naturalidade</label>
                    <input type="text" class="form-control form-control-sm" name="naturalidade"
                        value="<?php echo htmlspecialchars( $clientes["naturalidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>

            </div>
        </div>

        <div class="card-footer text-end">
            <button class="btn btn-info btn-sm" type="submit">Atualizar</button>
        </div>
    </form>
</div>
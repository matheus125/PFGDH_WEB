<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Lista de Usuários
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Editar Usuário</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form role="form" action="/admin/funcionarios/<?php echo htmlspecialchars( $funcionarios["id_usuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" method="post">
            <div class="box-body">
              <div class="form-group">
                <label for="desperson">Nome</label>
                <input type="text" class="form-control" id="nome_funcionario" name="nome_funcionario"
                  placeholder="Digite o nome" value="<?php echo htmlspecialchars( $funcionarios["nome_funcionario"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              </div>
              <div class="form-group">
                <label for="cpf">Login</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite o login"
                  value="<?php echo htmlspecialchars( $funcionarios["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              </div>
              <div class="form-group">
                <label for="nrphone">Telefone</label>
                <input type="tel" class="form-control" id="nrphone" name="nrphone" placeholder="Digite o telefone"
                  value="<?php echo htmlspecialchars( $funcionarios["nrphone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              </div>
              <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite o e-mail"
                  value="<?php echo htmlspecialchars( $funcionarios["email"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              </div>

              <div class="form-group">
                <label for="perfil">Perfil de Acesso</label>

                <select name="perfil" id="perfil" class="form-control" required>
                  <option value="">Selecione o perfil</option>

                  <option value="ADMIN" {if $funcionarios.perfil=='ADMIN' }selected<?php  ?>>
                    Administrador
                  </option>

                  <option value="SUPERVISOR" {if $funcionarios.perfil=='SUPERVISOR' }selected<?php  ?>>
                    Supervisor
                  </option>

                  <option value="ASSESSOR" {if $funcionarios.perfil=='ASSESSOR' }selected<?php  ?>>
                    Assessor
                  </option>

                </select>
              </div>



            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
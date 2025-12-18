<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="card mb-4">
  <div class="card-header">
    <h3 class="card-title">USUÁRIOS</h3>
    <div class="card-tools">
      <ul class="pagination pagination-sm float-end">
        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
      </ul>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Login</th>
          <th style="width: 300px">&nbsp; Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php $counter1=-1;  if( isset($funcionarios) && ( is_array($funcionarios) || $funcionarios instanceof Traversable ) && sizeof($funcionarios) ) foreach( $funcionarios as $key1 => $value1 ){ $counter1++; ?>
        <tr>
          <td><?php echo htmlspecialchars( $value1["id_usuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
          <td><?php echo htmlspecialchars( $value1["nome_funcionario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
          <td><?php echo htmlspecialchars( $value1["email"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
          <td><?php echo htmlspecialchars( $value1["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
          <td>
            <a href="/admin/funcionarios/<?php echo htmlspecialchars( $value1["id_usuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i>
              Editar</a>
            <a href="/admin/funcionarios/<?php echo htmlspecialchars( $value1["id_usuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/password" class="btn btn-secondary btn-xs"><i
                class="fa fa-unlock"></i> Alterar Senha</a>
            <a href="/admin/funcionarios/<?php echo htmlspecialchars( $value1["id_usuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete"
              onclick="return confirm('Deseja realmente excluir este registro?')" class="btn btn-danger btn-xs"><i
                class="fa fa-trash"></i> Excluir</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
  <div class="card-footer clearfix">
    <a href="/admin/funcionarios/create" class="btn btn-sm btn-primary float-start">
      NOVO CADASTRO
    </a>

  </div>
</div>
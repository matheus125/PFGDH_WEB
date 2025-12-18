<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- CSS do DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- JS do DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



<div class="card mb-4">
  <div class="card-header d-flex justify-content-center">
    <h3 class="card-title mb-0">CLIENTE</h3>
  </div>

  <!-- /.card-header -->
  <!--begin::App Content-->
  <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Informações para contato</h3>
          </div>
          <!-- /.card-header -->
          <div class="table-responsive">
            <table id="tabela_titulares" class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nome Completo</th>
                  <th>Telefone</th>
                  <th>Estado Civil</th>
                  <th>RG</th>
                  <th>CPF</th>
                  <th>SEXO</th>
                  <th>Status</th>
                  <th>NIS</th>
                  <th>Data Nascimento</th>
                  <th>Idade</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php $counter1=-1;  if( isset($lista_titulares) && ( is_array($lista_titulares) || $lista_titulares instanceof Traversable ) && sizeof($lista_titulares) ) foreach( $lista_titulares as $key1 => $value1 ){ $counter1++; ?>
                <tr>
                  <td><?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["nome_completo"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["telefone"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["estado_civil"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["rg"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["genero_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["status_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["nis"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["data_nascimento"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td><?php echo htmlspecialchars( $value1["idade_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="#" class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#modalRelatorio"
                        data-id="<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-nome="<?php echo htmlspecialchars( $value1["nome_completo"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-social="<?php echo htmlspecialchars( $value1["nome_social"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-mae="<?php echo htmlspecialchars( $value1["nome_mae"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-cor="<?php echo htmlspecialchars( $value1["cor_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-telefone="<?php echo htmlspecialchars( $value1["telefone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-estado="<?php echo htmlspecialchars( $value1["estado_civil"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-rg="<?php echo htmlspecialchars( $value1["rg"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-cpf="<?php echo htmlspecialchars( $value1["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-sexo="<?php echo htmlspecialchars( $value1["genero_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-status="<?php echo htmlspecialchars( $value1["status_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-nis="<?php echo htmlspecialchars( $value1["nis"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-nascimento="<?php echo htmlspecialchars( $value1["data_nascimento"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-idade="<?php echo htmlspecialchars( $value1["idade_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-cep="<?php echo htmlspecialchars( $value1["cep"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-bairro="<?php echo htmlspecialchars( $value1["bairro"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-rua="<?php echo htmlspecialchars( $value1["rua"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-numero="<?php echo htmlspecialchars( $value1["numero"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-referencia="<?php echo htmlspecialchars( $value1["referencia"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-nacionalidade="<?php echo htmlspecialchars( $value1["nacionalidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-naturalidade="<?php echo htmlspecialchars( $value1["naturalidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-tempo="<?php echo htmlspecialchars( $value1["tempo_moradia"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" data-cidade="<?php echo htmlspecialchars( $value1["cidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        <i class="fa fa-eye"></i> Visualizar
                      </a>
                      <a href="/admin/clientes/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-warning btn-xs">
                        <i class="fa fa-edit"></i> Editar
                      </a>
                      <a href="/admin/clientes/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete"
                        onclick="return confirm('Deseja realmente excluir este registro?')"
                        class="btn btn-danger btn-xs">
                        <i class="fa fa-trash"></i> Excluir
                      </a>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>

            <!-- MODAL MODERNO COM ABAS -->
            <!-- MODAL MODERNO AJUSTADO -->
            <!-- MODAL MODERNO ESTILO CRM -->
            <div class="modal fade" id="modalRelatorio" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">

                  <!-- CABEÇALHO -->
                  <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title d-flex align-items-center">
                      <i class="bi bi-person-lines-fill me-2"></i> Detalhes do Titular
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                      aria-label="Fechar"></button>
                  </div>

                  <!-- CORPO COM ABAS -->
                  <div class="modal-body">

                    <!-- NAV DAS ABAS -->
                    <ul class="nav nav-tabs mb-4" id="modalTabs" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-dados-tab" data-bs-toggle="tab"
                          data-bs-target="#tab-dados" type="button" role="tab" aria-controls="tab-dados"
                          aria-selected="true">
                          <i class="bi bi-person me-1"></i> Dados Pessoais
                        </button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-endereco-tab" data-bs-toggle="tab"
                          data-bs-target="#tab-endereco" type="button" role="tab" aria-controls="tab-endereco"
                          aria-selected="false">
                          <i class="bi bi-geo-alt me-1"></i> Endereço
                        </button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-dependentes-tab" data-bs-toggle="tab"
                          data-bs-target="#tab-dependentes" type="button" role="tab" aria-controls="tab-dependentes"
                          aria-selected="false">
                          <i class="bi bi-people me-1"></i> Dependentes
                        </button>
                      </li>
                    </ul>

                    <!-- CONTEÚDO DAS ABAS -->
                    <div class="tab-content" id="modalTabsContent">

                      <!-- ABA DADOS PESSOAIS -->
                      <div class="tab-pane fade show active" id="tab-dados" role="tabpanel"
                        aria-labelledby="tab-dados-tab">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <div class="card shadow-sm mb-3 p-3 border-start border-4 border-primary">
                              <h6 class="text-primary mb-3"><i class="bi bi-info-circle me-1"></i> Informações Pessoais
                              </h6>
                              <p class="mb-2"><b>ID:</b> <span id="m_id"></span></p>
                              <p class="mb-2"><b>Nome Completo:</b> <span id="m_nome"></span></p>
                              <p class="mb-2"><b>Nome Social:</b> <span id="m_social"></span></p>
                              <p class="mb-2"><b>Nome da Mãe:</b> <span id="m_mae"></span></p>
                              <p class="mb-2"><b>Cor/Etnia:</b> <span id="m_cor"></span></p>
                              <p class="mb-2"><b>Telefone:</b> <span id="m_telefone"></span></p>
                              <p class="mb-2"><b>Estado Civil:</b> <span id="m_estado"></span></p>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="card shadow-sm mb-3 p-3 border-start border-4 border-success">
                              <h6 class="text-success mb-3"><i class="bi bi-person-check me-1"></i> Documentos & Sexo
                              </h6>
                              <p class="mb-2"><b>RG:</b> <span id="m_rg"></span></p>
                              <p class="mb-2"><b>CPF:</b> <span id="m_cpf"></span></p>
                              <p class="mb-2"><b>Sexo:</b> <span id="m_sexo"></span></p>
                              <p class="mb-2"><b>Status:</b> <span id="m_status"></span></p>
                              <p class="mb-2"><b>NIS:</b> <span id="m_nis"></span></p>
                              <p class="mb-2"><b>Data de Nascimento:</b> <span id="m_nascimento"></span></p>
                              <p class="mb-2"><b>Idade:</b> <span id="m_idade"></span></p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- ABA ENDEREÇO -->
                      <div class="tab-pane fade" id="tab-endereco" role="tabpanel" aria-labelledby="tab-endereco-tab">
                        <div class="card shadow-sm mb-3 p-3 border-start border-4 border-info">
                          <h6 class="text-info mb-3"><i class="bi bi-geo-alt me-1"></i> Endereço</h6>
                          <div class="row g-3">
                            <div class="col-md-6">
                              <p class="mb-2"><b>CEP:</b> <span id="m_cep"></span></p>
                              <p class="mb-2"><b>Bairro:</b> <span id="m_bairro"></span></p>
                              <p class="mb-2"><b>Rua:</b> <span id="m_rua"></span></p>
                              <p class="mb-2"><b>Número:</b> <span id="m_numero"></span></p>
                              <p class="mb-2"><b>Referência:</b> <span id="m_referencia"></span></p>
                            </div>
                            <div class="col-md-6">
                              <p class="mb-2"><b>Nacionalidade:</b> <span id="m_nacionalidade"></span></p>
                              <p class="mb-2"><b>Naturalidade:</b> <span id="m_naturalidade"></span></p>
                              <p class="mb-2"><b>Tempo de Moradia:</b> <span id="m_tempo"></span></p>
                              <p class="mb-2"><b>Cidade:</b> <span id="m_cidade"></span></p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- ABA DEPENDENTES -->
                      <div class="tab-pane fade" id="tab-dependentes" role="tabpanel"
                        aria-labelledby="tab-dependentes-tab">
                        <div class="card shadow-sm mb-3 p-3 border-start border-4 border-warning">
                          <h6 class="text-warning mb-3"><i class="bi bi-people me-1"></i> Dependentes</h6>
                          <div id="m_dependentes">
                            <p>Nenhum dependente cadastrado.</p>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <!-- RODAPÉ -->
                  <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                      <i class="bi bi-x-circle me-1"></i> Fechar
                    </button>
                  </div>

                </div>
              </div>
            </div>




          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-end">
              <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
          </div>
        </div> <!-- fim do card mb-4 -->

      </div> <!-- fim da row -->

    </div> <!--end::Container-->
  </div> <!--end::App Content-->

  <!-- /.card-body -->
  <div class="card-footer clearfix">
    <a href="/admin/clientes/create" class="btn btn-sm btn-primary float-start">
      NOVO CADASTRO
    </a>
  </div>
</div> <!-- fim do card principal -->


<script>
  $(document).ready(function () {
    $('#tabela_titulares').DataTable({
      paging: true,       // paginação
      searching: true,    // barra de busca
      ordering: true,     // ordenar colunas
      info: true,         // mostrar info de registros
      pageLength: 10,     // registros por página
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' // traduz para português
      }
    });
  });

  <!-- SCRIPT PARA POPULAR O MODAL -->
  document.addEventListener("DOMContentLoaded", function () {
    const modalRelatorio = document.getElementById('modalRelatorio');
    modalRelatorio.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget; // botão que abriu o modal

      document.getElementById('m_id').textContent = button.getAttribute('data-id');
      document.getElementById('m_nome').textContent = button.getAttribute('data-nome');
      document.getElementById('m_social').textContent = button.getAttribute('data-social');
      document.getElementById('m_mae').textContent = button.getAttribute('data-mae');
      document.getElementById('m_cor').textContent = button.getAttribute('data-cor');
      document.getElementById('m_telefone').textContent = button.getAttribute('data-telefone');
      document.getElementById('m_estado').textContent = button.getAttribute('data-estado');
      document.getElementById('m_rg').textContent = button.getAttribute('data-rg');
      document.getElementById('m_cpf').textContent = button.getAttribute('data-cpf');
      document.getElementById('m_sexo').textContent = button.getAttribute('data-sexo');
      document.getElementById('m_status').textContent = button.getAttribute('data-status');
      document.getElementById('m_nis').textContent = button.getAttribute('data-nis');
      document.getElementById('m_nascimento').textContent = button.getAttribute('data-nascimento');
      document.getElementById('m_idade').textContent = button.getAttribute('data-idade');
      document.getElementById('m_cep').textContent = button.getAttribute('data-cep');
      document.getElementById('m_bairro').textContent = button.getAttribute('data-bairro');
      document.getElementById('m_rua').textContent = button.getAttribute('data-rua');
      document.getElementById('m_numero').textContent = button.getAttribute('data-numero');
      document.getElementById('m_referencia').textContent = button.getAttribute('data-referencia');
      document.getElementById('m_nacionalidade').textContent = button.getAttribute('data-nacionalidade');
      document.getElementById('m_naturalidade').textContent = button.getAttribute('data-naturalidade');
      document.getElementById('m_tempo').textContent = button.getAttribute('data-tempo');
      document.getElementById('m_cidade').textContent = button.getAttribute('data-cidade');
    });
  });
</script>
<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- CSS do DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- JS do DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-center">
    <h3 class="card-title mb-0">CLIENTE</h3>
  </div>

  <?php if( $msgSuccess != '' ){ ?>

  <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
    <?php echo htmlspecialchars( $msgSuccess, ENT_COMPAT, 'UTF-8', FALSE ); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php } ?>


  <?php if( $msgError != '' ){ ?>

  <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
    <?php echo htmlspecialchars( $msgError, ENT_COMPAT, 'UTF-8', FALSE ); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php } ?>


  <div class="app-content">
    <div class="container-fluid">
      <div class="row">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Informações para contato</h3>
          </div>

          <div class="table-responsive p-3">
            <table id="tabela_titulares" class="table table-striped table-bordered align-middle w-100">
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
                      <button
                        type="button"
                        class="btn btn-sm btn-primary btn-detalhes"
                        data-bs-toggle="modal"
                        data-bs-target="#modalRelatorio"
                        title="Visualizar detalhes do titular"
                        data-id="<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-nome="<?php echo htmlspecialchars( $value1["nome_completo"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-social="<?php echo htmlspecialchars( $value1["nome_social"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-mae="<?php echo htmlspecialchars( $value1["nome_mae"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-cor="<?php echo htmlspecialchars( $value1["cor_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-telefone="<?php echo htmlspecialchars( $value1["telefone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-estado="<?php echo htmlspecialchars( $value1["estado_civil"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-rg="<?php echo htmlspecialchars( $value1["rg"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-cpf="<?php echo htmlspecialchars( $value1["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-sexo="<?php echo htmlspecialchars( $value1["genero_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-status="<?php echo htmlspecialchars( $value1["status_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-nis="<?php echo htmlspecialchars( $value1["nis"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-nascimento="<?php echo htmlspecialchars( $value1["data_nascimento"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-idade="<?php echo htmlspecialchars( $value1["idade_cliente"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-cep="<?php echo htmlspecialchars( $value1["cep"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-bairro="<?php echo htmlspecialchars( $value1["bairro"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-rua="<?php echo htmlspecialchars( $value1["rua"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-numero="<?php echo htmlspecialchars( $value1["numero"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-referencia="<?php echo htmlspecialchars( $value1["referencia"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-nacionalidade="<?php echo htmlspecialchars( $value1["nacionalidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-naturalidade="<?php echo htmlspecialchars( $value1["naturalidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-tempo="<?php echo htmlspecialchars( $value1["tempo_moradia"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"
                        data-cidade="<?php echo htmlspecialchars( $value1["cidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        <i class="bi bi-eye"></i>
                      </button>

                      <?php if( canAccess('CLIENTES_UPDATE') ){ ?>

                      <a href="/admin/clientes/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-warning btn-sm" title="Editar">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <?php } ?>


                      <?php if( canAccess('CLIENTES_DELETE') ){ ?>

                      <a href="/admin/clientes/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete"
                        onclick="return confirm('Deseja realmente excluir este registro?')"
                        class="btn btn-danger btn-sm"
                        title="Excluir">
                        <i class="bi bi-trash"></i>
                      </a>
                      <?php } ?>

                    </div>
                  </td>
                </tr>
                <?php } ?>

              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="card-footer d-flex gap-2">
    <a href="/admin/clientes/create" class="btn btn-sm btn-primary">
      NOVO TITULAR
    </a>

    <a href="/admin/dependente/create" class="btn btn-sm btn-primary">
      NOVO DEPENDENTE
    </a>
  </div>
</div>

<!-- MODAL RELATÓRIO -->
<div class="modal fade" id="modalRelatorio" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">

      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title d-flex align-items-center">
          <i class="bi bi-person-lines-fill me-2"></i> Detalhes do Titular
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-tabs mb-4" id="modalTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-dados-tab" data-bs-toggle="tab"
              data-bs-target="#tab-dados" type="button" role="tab">
              <i class="bi bi-person me-1"></i> Dados Pessoais
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-endereco-tab" data-bs-toggle="tab"
              data-bs-target="#tab-endereco" type="button" role="tab">
              <i class="bi bi-geo-alt me-1"></i> Endereço
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-dependentes-tab" data-bs-toggle="tab"
              data-bs-target="#tab-dependentes" type="button" role="tab">
              <i class="bi bi-people me-1"></i> Dependentes
            </button>
          </li>
        </ul>

        <div class="tab-content" id="modalTabsContent">
          <div class="tab-pane fade show active" id="tab-dados" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="card shadow-sm mb-3 p-3 border-start border-4 border-primary">
                  <h6 class="text-primary mb-3"><i class="bi bi-info-circle me-1"></i> Informações Pessoais</h6>
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
                  <h6 class="text-success mb-3"><i class="bi bi-person-check me-1"></i> Documentos & Sexo</h6>
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

          <div class="tab-pane fade" id="tab-endereco" role="tabpanel">
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

          <div class="tab-pane fade" id="tab-dependentes" role="tabpanel">
            <div class="card shadow-sm mb-3 p-3 border-start border-4 border-warning">
              <h6 class="text-warning mb-3">
                <i class="bi bi-people me-1"></i> Dependentes
              </h6>
              <div id="m_dependentes">
                <p class="text-muted">Selecione um titular...</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer justify-content-end">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Fechar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- MODAL EDITAR DEPENDENTE -->
<div class="modal fade" id="modalEditarDependente" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar Dependente</h5>
        <button type="button" class="btn-close btn-close-white" aria-label="Fechar" onclick="fecharModalDependente()"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editIdDependente">

        <div class="mb-3">
          <label class="form-label">Nome</label>
          <input type="text" id="editNome" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Parentesco</label>
          <input type="text" id="editParentesco" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Idade</label>
          <input type="number" id="editIdade" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Sexo</label>
          <select id="editGenero" class="form-control">
            <option value="">Selecione</option>
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
            <option value="Outro">Outro</option>
          </select>
        </div>

        <div id="dependenteAlert" class="d-none"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="fecharModalDependente()">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSalvarEdicao">Salvar</button>
      </div>
    </div>
  </div>
</div>

<script>
  let titularAtual = null;
  let modalEditarDependenteInstance = null;

  function exibirMensagemDependente(tipo, mensagem) {
    const box = $('#dependenteAlert');
    box.removeClass('d-none alert-success alert-danger alert-warning')
      .addClass('alert alert-' + tipo)
      .html(mensagem);
  }

  function limparMensagemDependente() {
    $('#dependenteAlert').removeClass('alert alert-success alert-danger alert-warning')
      .addClass('d-none')
      .html('');
  }

  function fecharModalDependente() {
    const modalEl = document.getElementById('modalEditarDependente');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) {
      modal.hide();
    }
    $('body').removeClass('modal-open');
    $('.modal-backdrop').not(':first').remove();
  }

  function preencherDadosTitular(button) {
    if (!button) return;

    $('#m_id').text(button.dataset.id || '');
    $('#m_nome').text(button.dataset.nome || '');
    $('#m_social').text(button.dataset.social || '');
    $('#m_mae').text(button.dataset.mae || '');
    $('#m_cor').text(button.dataset.cor || '');
    $('#m_telefone').text(button.dataset.telefone || '');
    $('#m_estado').text(button.dataset.estado || '');
    $('#m_rg').text(button.dataset.rg || '');
    $('#m_cpf').text(button.dataset.cpf || '');
    $('#m_sexo').text(button.dataset.sexo || '');
    $('#m_status').text(button.dataset.status || '');
    $('#m_nis').text(button.dataset.nis || '');
    $('#m_nascimento').text(button.dataset.nascimento || '');
    $('#m_idade').text(button.dataset.idade || '');
    $('#m_cep').text(button.dataset.cep || '');
    $('#m_bairro').text(button.dataset.bairro || '');
    $('#m_rua').text(button.dataset.rua || '');
    $('#m_numero').text(button.dataset.numero || '');
    $('#m_referencia').text(button.dataset.referencia || '');
    $('#m_nacionalidade').text(button.dataset.nacionalidade || '');
    $('#m_naturalidade').text(button.dataset.naturalidade || '');
    $('#m_tempo').text(button.dataset.tempo || '');
    $('#m_cidade').text(button.dataset.cidade || '');

    titularAtual = button.dataset.id || null;
  }

  function formatarGenero(genero) {
    if (genero === 'M') return 'Masculino';
    if (genero === 'F') return 'Feminino';
    if (genero === 'Outro') return 'Outro';
    return genero ?? '-';
  }

  function renderDependentes(deps) {
    const container = $('#m_dependentes');

    if (!deps || deps.length === 0) {
      container.html(`
        <div class="text-center text-muted py-3">
          <i class="bi bi-people fs-1 mb-2"></i>
          <p class="mb-0">Nenhum dependente cadastrado.</p>
        </div>
      `);
      return;
    }

    let html = '';

    deps.forEach(d => {
      html += `
        <div class="card mb-2 shadow-sm">
          <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
              <p class="mb-1"><b>Nome:</b> ${d.nome ?? '-'}</p>
              <p class="mb-1"><b>Parentesco:</b> ${d.dependencia_cliente ?? '-'}</p>
              <p class="mb-1"><b>Idade:</b> ${d.idade ?? '-'}</p>
              <p class="mb-1"><b>Sexo:</b> ${formatarGenero(d.genero)}</p>
            </div>
            <div class="btn-group-vertical">
              <button class="btn btn-sm btn-primary mb-1" onclick="editarDependente(${d.id})">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-sm btn-danger" onclick="excluirDependente(${d.id})">
                <i class="bi bi-trash"></i> Excluir
              </button>
            </div>
          </div>
        </div>
      `;
    });

    container.html(html);
  }

  function carregarDependentes() {
    const container = $('#m_dependentes');

    if (!titularAtual) {
      container.html('<p class="text-muted">Selecione um titular...</p>');
      return;
    }

    container.html(`
      <div class="d-flex flex-column gap-2">
        <div class="skeleton-card"></div>
        <div class="skeleton-card"></div>
        <div class="skeleton-card"></div>
      </div>
    `);

    $.ajax({
      url: '/admin/dependentes/ajax/' + titularAtual,
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function (resp) {
        if (Array.isArray(resp)) {
          renderDependentes(resp);
          return;
        }

        if (resp && resp.success === true) {
          renderDependentes(resp.data || []);
          return;
        }

        container.html(`
          <div class="alert alert-warning mb-0">
            Nenhum dado retornado.
          </div>
        `);
      },
      error: function (xhr) {
        console.error('Erro ao carregar dependentes:', xhr.status, xhr.responseText);
        container.html(`
          <div class="alert alert-danger mb-0">
            Erro ao carregar dependentes.<br>
            <small>Status: ${xhr.status}</small>
          </div>
        `);
      }
    });
  }

  function editarDependente(idDep) {
    limparMensagemDependente();

    $.ajax({
      url: '/admin/dependentes/get/' + idDep,
      method: 'GET',
      dataType: 'json',
      success: function (resp) {
        if (resp.success === false) {
          alert(resp.message || 'Erro ao carregar dependente.');
          return;
        }

        const dep = resp.data ? resp.data : resp;

        $('#editIdDependente').val(dep.id || '');
        $('#editNome').val(dep.nome || '');
        $('#editParentesco').val(dep.dependencia_cliente || '');
        $('#editIdade').val(dep.idade || '');

        if (dep.genero === 'Masculino') {
          $('#editGenero').val('M');
        } else if (dep.genero === 'Feminino') {
          $('#editGenero').val('F');
        } else {
          $('#editGenero').val(dep.genero || '');
        }

        const modalEl = document.getElementById('modalEditarDependente');

        if (!modalEditarDependenteInstance) {
          modalEditarDependenteInstance = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: false,
            focus: false
          });
        }

        modalEditarDependenteInstance.show();
      },
      error: function (xhr) {
        console.error("Erro ao carregar dependente:", xhr.status, xhr.responseText);
        alert("Erro ao carregar dependente!");
      }
    });
  }

  function excluirDependente(idDep) {
    if (!confirm("Tem certeza que deseja excluir este dependente?")) return;

    $.ajax({
      url: '/admin/dependentes/excluir/' + idDep,
      method: 'DELETE',
      dataType: 'json',
      success: function (resp) {
        alert(resp.message || "Dependente excluído com sucesso!");
        carregarDependentes();
      },
      error: function (xhr) {
        console.error("Erro ao excluir dependente:", xhr.status, xhr.responseText);
        alert("Erro ao excluir dependente!");
      }
    });
  }

  $(document).ready(function () {
    $('#tabela_titulares').DataTable({
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
      },
      paging: true,
      searching: true,
      ordering: true,
      info: true,
      pageLength: 10
    });

    $('#modalRelatorio').on('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      preencherDadosTitular(button);
    });

    $('#modalRelatorio').on('shown.bs.modal', function () {
      carregarDependentes();
    });

    $('#tab-dependentes-tab').on('shown.bs.tab', function () {
      carregarDependentes();
    });

    $('#modalEditarDependente').on('hidden.bs.modal', function () {
      limparMensagemDependente();
      $('body').addClass('modal-open');
      if ($('.modal-backdrop').length === 0 && $('#modalRelatorio').hasClass('show')) {
        $('body').append('<div class="modal-backdrop fade show"></div>');
      }
    });

    $('#btnSalvarEdicao').on('click', function () {
      const idDep = $('#editIdDependente').val();

      const data = {
        nome: $('#editNome').val(),
        dependencia_cliente: $('#editParentesco').val(),
        idade: $('#editIdade').val(),
        genero: $('#editGenero').val()
      };

      $.ajax({
        url: '/admin/dependentes/editar/' + idDep,
        method: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function (resp) {
          if (resp.success === false) {
            exibirMensagemDependente('danger', resp.message || resp.error || 'Erro ao atualizar dependente.');
            return;
          }

          exibirMensagemDependente('success', resp.message || 'Dependente atualizado com sucesso.');

          carregarDependentes();

          setTimeout(function () {
            fecharModalDependente();
          }, 1200);
        },
        error: function (xhr) {
          console.error("Erro ao salvar dependente:", xhr.status, xhr.responseText);
          exibirMensagemDependente('danger', 'Erro ao salvar dependente.');
        }
      });
    });
  });
</script>

<style>
  .skeleton-card {
    height: 100px;
    width: 100%;
    border-radius: 8px;
    background: linear-gradient(90deg, #eee 25%, #ddd 50%, #eee 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    margin-bottom: 12px;
  }

  @keyframes shimmer {
    0% {
      background-position: -200% 0;
    }
    100% {
      background-position: 200% 0;
    }
  }

  .skeleton-line {
    height: 12px;
    width: 80%;
    margin-bottom: 8px;
    border-radius: 4px;
    background: linear-gradient(90deg, #eee 25%, #ddd 50%, #eee 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
  }
</style>
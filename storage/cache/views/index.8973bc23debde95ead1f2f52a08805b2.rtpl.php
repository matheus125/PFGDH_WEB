<?php if(!class_exists('Rain\Tpl')){exit;}?><!--begin::App Main-->
<main class="app-main saas-main">
  <div class="app-content-header saas-content-header">
    <div class="container-fluid">
      <div class="row align-items-center gy-3">
        <div class="col-sm-7">
          <div class="page-eyebrow">Painel administrativo</div>
          <h3 class="mb-0 page-title">Dashboard</h3>
          <p class="page-subtitle mb-0">Visão geral com métricas, acompanhamento operacional e distribuição dos usuários.</p>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-end saas-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
          <div class="saas-stat-card stat-primary interactive-card">
            <div class="stat-topline"><span class="live-dot"></span> Atualizado em tempo real</div>
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-meta">Titulares</div>
            <div class="stat-value" id="totalUsuarios">...</div>
            <div class="stat-note">Base principal cadastrada</div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="saas-stat-card stat-warning interactive-card">
            <div class="stat-topline"><span class="live-dot"></span> Monitoramento ativo</div>
            <div class="stat-icon"><i class="bi bi-person-hearts"></i></div>
            <div class="stat-meta">Dependentes</div>
            <div class="stat-value" id="totalDependentes">...</div>
            <div class="stat-note">Vinculados aos titulares</div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="saas-stat-card stat-danger interactive-card">
            <div class="stat-topline"><span class="live-dot"></span> Panorama consolidado</div>
            <div class="stat-icon"><i class="bi bi-diagram-3-fill"></i></div>
            <div class="stat-meta">Total Geral</div>
            <div class="stat-value" id="totalGeral">...</div>
            <div class="stat-note">Titulares + dependentes</div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="saas-stat-card stat-info interactive-card">
            <div class="stat-topline"><span class="live-dot"></span> Estrutura familiar</div>
            <div class="stat-icon"><i class="bi bi-house-heart-fill"></i></div>
            <div class="stat-meta">Famílias</div>
            <div class="stat-value" id="totalFamilias">...</div>
            <div class="stat-note">Núcleos familiares ativos</div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-xl-9">
          <div class="saas-card h-100 interactive-card card-report">
            <div class="saas-card-header">
              <div>
                <span class="saas-chip"><i class="bi bi-graph-up-arrow"></i> Resumo mensal</span>
                <h5 class="saas-card-title">Relatório de Recapitulação Mensal</h5>
                <p class="saas-card-subtitle">Acompanhe a evolução operacional e comercial do período atual.</p>
              </div>
              <div class="card-tools saas-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                  <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                  <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                </button>
                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>

            <div class="saas-card-body">
              <div class="report-hero">
                <div>
                  <div class="report-value">R$ 35.400</div>
                  <div class="report-label">Receita estimada no ciclo</div>
                </div>
                <div class="report-trend positive">
                  <i class="bi bi-arrow-up-right"></i>
                  <span>+12,8% no mês</span>
                </div>
              </div>

              <div class="stats-grid-4 mt-4">
                <div class="mini-kpi interactive-mini">
                  <span class="mini-kpi-label">Receita</span>
                  <strong>R$ 35.400</strong>
                </div>
                <div class="mini-kpi interactive-mini">
                  <span class="mini-kpi-label">Despesa</span>
                  <strong>R$ 10.000</strong>
                </div>
                <div class="mini-kpi interactive-mini">
                  <span class="mini-kpi-label">Refeições</span>
                  <strong>5.000</strong>
                </div>
                <div class="mini-kpi interactive-mini">
                  <span class="mini-kpi-label">Usuários</span>
                  <strong>10.000</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3">
          <div class="saas-card h-100 backup-card interactive-card">
            <div class="saas-card-header">
              <div>
                <span class="saas-chip"><i class="bi bi-shield-check"></i> Infraestrutura</span>
                <h5 class="saas-card-title">Último Backup</h5>
              </div>
            </div>
            <div class="saas-card-body d-flex flex-column justify-content-center">
              <div class="backup-icon mb-3"><i class="bi bi-cloud-check-fill"></i></div>
              <?php if( $ultimoBackup ){ ?>

              <p class="backup-text mb-2">Último backup em <strong><?php echo date('d/m/Y H:i', strtotime($ultimoBackup)); ?></strong></p>
              <div class="backup-status success"><i class="bi bi-check-circle-fill"></i> Ambiente protegido</div>
              <?php }else{ ?>

              <p class="backup-text mb-2">Nenhum backup executado até o momento.</p>
              <div class="backup-status warning"><i class="bi bi-exclamation-triangle-fill"></i> Aguardando execução</div>
              <?php } ?>

            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-xl-8">
          <div class="saas-card h-100 interactive-card saas-table-card">
            <div class="saas-card-header">
              <div>
                <span class="saas-chip"><i class="bi bi-table"></i> Tabela operacional</span>
                <h5 class="saas-card-title">Usuários</h5>
                <p class="saas-card-subtitle">Listagem resumida com os principais dados cadastrais.</p>
              </div>
              <div class="table-toolbar">
                <div class="table-pill"><i class="bi bi-database"></i> <span id="totalRegistros">0</span> registros</div>
                <div class="card-tools saas-tools">
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="saas-card-body p-0">
              <div class="table-responsive table-shell">
                <table class="table table-modern m-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>NOME</th>
                      <th>E-MAIL / RG</th>
                      <th>CPF</th>
                      <th>NIS</th>
                      <th>IDADE</th>
                      <th>SEXO</th>
                      <th>STATUS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $counter1=-1;  if( isset($index) && ( is_array($index) || $index instanceof Traversable ) && sizeof($index) ) foreach( $index as $key1 => $value1 ){ $counter1++; ?>

                    <tr>
                      <td><span class="cell-id">#<?php echo htmlspecialchars( $value1["id_pessoa"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span></td>
                      <td>
                        <div class="user-cell">
                          <span class="avatar-soft"><i class="bi bi-person"></i></span>
                          <div>
                            <strong><?php echo htmlspecialchars( $value1["nome_funcionario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></strong>
                            <small>Cadastro ativo</small>
                          </div>
                        </div>
                      </td>
                      <td><?php echo htmlspecialchars( $value1["email"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                      <td><span class="pill-soft"><?php echo htmlspecialchars( $value1["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span></td>
                      <td>—</td>
                      <td>—</td>
                      <td>—</td>
                      <td><span class="status-badge status-active">Ativo</span></td>
                    </tr>
                    <?php } ?>

                  </tbody>
                </table>
              </div>
            </div>

            <div class="saas-card-footer table-actions">
              <a href="javascript:void(0)" class="btn btn-primary btn-sm saas-btn-primary">Novo cadastro</a>
              <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm saas-btn-ghost">Ver todos os clientes</a>
            </div>
          </div>
        </div>

        <div class="col-xl-4">
          <div class="saas-card h-100 interactive-card chart-card">
            <div class="saas-card-header">
              <div>
                <span class="saas-chip"><i class="bi bi-pie-chart"></i> Análise</span>
                <h5 class="saas-card-title">Faixa Etária</h5>
                <p class="saas-card-subtitle">Distribuição de usuários por grupo etário.</p>
              </div>
              <div class="card-tools saas-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                  <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                  <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                </button>
                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>

            <div class="saas-card-body">
              <div class="chart-shell">
                <div id="pie-chart" style="width:100%; height:350px;"></div>
              </div>
            </div>

            <div class="saas-card-footer p-0">
              <ul id="pie-legend" class="nav nav-pills flex-column pie-legend-saas"></ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php if(!class_exists('Rain\Tpl')){exit;}?><style>
    :root {
        /* Institucional moderno */
        --bg: #f6f8fb;
        --surface: #ffffff;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e5e7eb;
        --header: #f1f5f9;

        /* Acento discreto (institucional) */
        --accent: #1f3a8a;
        --accent-2: #0ea5e9;
        --shadow: 0 10px 25px rgba(15, 23, 42, .08);
    }

    body {
        background: var(--bg);
        color: var(--text);
    }

    .card {
        border: 1px solid var(--border) !important;
        border-radius: 16px !important;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid var(--border);
    }

    .top-bar {
        background: var(--header);
        border: 1px solid var(--border);
        padding: 16px;
        border-radius: 14px;
        color: var(--text);
    }

    .top-bar label {
        font-weight: 600;
        color: var(--muted);
        font-size: .85rem;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 12px;
        border: 1px solid var(--border);
        background: #fff;
    }

    .form-control:focus {
        border-color: rgba(14, 165, 233, .65);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, .15);
    }

    .btn-primary {
        background: var(--accent) !important;
        border-color: var(--accent) !important;
        border-radius: 12px !important;
        font-weight: 600;
    }

    .btn-light {
        background: #fff !important;
        border: 1px solid var(--border) !important;
        border-radius: 12px !important;
        font-weight: 600;
        color: var(--text) !important;
    }

    .btn-soft {
        background: var(--accent) !important;
        border: 1px solid var(--accent) !important;
        color: #fff !important;
        border-radius: 12px !important;
        font-weight: 700;
    }

    .btn-soft:hover {
        filter: brightness(1.05);
    }

    .btn-outline-soft {
        background: #fff !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
        border-radius: 12px !important;
        font-weight: 700;
    }

    .btn-outline-soft:hover {
        background: #f8fafc !important;
    }

    .box {
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .table-fixed {
        height: 420px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #ffffff;
        z-index: 1;
        border-bottom: 1px solid var(--border);
        font-size: .78rem;
        letter-spacing: .04em;
        color: var(--muted);
        text-transform: uppercase;
    }

    .table tbody td {
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    #tbody-titulares tr {
        cursor: pointer;
        transition: background .12s ease;
    }

    #tbody-titulares tr:hover {
        background: #f8fafc;
    }

    #tbody-titulares tr.active {
        background: rgba(31, 58, 138, .08);
    }

    #tbody-dependentes tr.active-dep {
        background: rgba(14, 165, 233, .12);
    }

    #tbody-dependentes td:first-child,
    #tbody-dependentes th:first-child {
        text-align: center;
        vertical-align: middle;
    }

    .modal-content {
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
    }

    .modal-header {
        background: var(--header);
        border-bottom: 1px solid var(--border);
    }

    .badge-dia-encerrado {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #b91c1c;
        color: #fff;
        font-size: .82rem;
        font-weight: 800;
        letter-spacing: .03em;
        text-transform: uppercase;
        box-shadow: 0 10px 24px rgba(185, 28, 28, .18);
    }

    .badge-dia-encerrado::before {
        content: "";
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .95);
    }

    .acoes-vendas {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .top-bar-status {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .top-bar .row>div {
            margin-bottom: 10px;
        }

        .table-fixed {
            height: 300px;
        }
    }
</style>


<div class="card mb-4">
    <div class="card-header d-flex justify-content-center">
        <h3 class="card-title mb-0">VENDAS</h3>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">

                <!-- TOPO -->
                <div class="top-bar mb-3">
                    <div class="top-bar-status">
                        <div>
                            <div style="font-weight:700; color:#0f172a;">Atendimento</div>
                            <div style="font-size:.85rem; color:#64748b;">Consulta de titulares e dependentes</div>
                        </div>
                        <div id="badgeDiaEncerrado" class="badge-dia-encerrado" style="display:none;">
                            DIA ENCERRADO
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label>Nome Completo</label>
                            <input type="text" id="filtroNome" class="form-control"
                                placeholder="Digite para filtrar...">
                        </div>

                        <div class="col-md-3">
                            <label>Pesquisar</label>
                            <div class="input-group">
                                <input type="text" id="filtroCpf" class="form-control" placeholder="Digite o CPF...">
                                <button class="btn btn-light" type="button"
                                    onclick="filtrarTitulares({renderAll:true})">Pesquisar</button>
                            </div>
                        </div>
                        <small id="statusFiltro" class="text-muted"></small>

                        <div class="col-md-3">
                            <div class="acoes-vendas">
                                <button id="btnGerarSenha" class="btn btn-soft w-100" onclick="abrirModal()">Gerar
                                    Senha</button>
                                <button id="btnSenhaGenerica" class="btn btn-outline-soft w-100" type="button"
                                    onclick="gerarSenhaGenerica()">Senha Genérica</button>
                                <button id="btnEncerrarDia" class="btn btn-light w-100" type="button"
                                    onclick="abrirModalFechamentoLocal('manual')">
                                    Encerrar dia manualmente
                                </button>
                                <div id="statusSenhas" class="small text-muted mt-1"></div>
                                <button id="btnInfoFechamento" class="btn btn-light w-100" type="button"
                                    onclick="abrirModalFechamentoLocal('automatico')" style="display:none;">
                                    Informar Refeições / Ocorrências
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CORPO -->
                <div class="row">

                    <!-- TITULARES -->
                    <div class="col-md-6 mb-3">
                        <div class="box">
                            <div class="table-fixed">
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>TITULAR</th>
                                            <th>CPF</th>
                                            <th>IDADE</th>
                                            <th>GÊNERO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-titulares"></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2 px-1 flex-wrap"
                                style="gap:.5rem;">
                                <div class="small text-muted" id="titularesPaginacaoInfo"></div>
                                <div class="d-flex align-items-center" style="gap:.5rem;">
                                    <span class="small text-muted">Por página</span>
                                    <select id="titularesPageSize" class="form-select form-select-sm"
                                        style="width:auto;">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <button id="titularesPrev" class="btn btn-sm btn-outline-secondary"
                                        type="button">Anterior</button>
                                    <span id="titularesPageIndicator" class="small text-muted"></span>
                                    <button id="titularesNext" class="btn btn-sm btn-outline-secondary"
                                        type="button">Próxima</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DEPENDENTES -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="p-2 border-bottom small d-flex align-items-center justify-content-between"
                                style="gap:10px;">
                                <label class="m-0" style="display:flex;align-items:center;gap:8px;">
                                    <input type="checkbox" id="chk-incluir-titular" class="form-check-input" checked>
                                    <span>Incluir titular na impressão</span>
                                </label>
                                <span id="msgTitularJaComprou" class="text-danger"
                                    style="font-weight:600;display:none;"></span>
                            </div>
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:44px;text-align:center;">
                                            <input id="dep-select-all" type="checkbox" class="form-check-input"
                                                title="Selecionar todos para impressão"
                                                onchange="selecionarTodosDependentes(this.checked)">
                                        </th>
                                        <th>NOME</th>
                                        <th>IDADE</th>
                                        <th>GÊNERO</th>
                                        <th>DEPENDÊNCIA</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-dependentes">
                                    <tr>
                                        <td colspan="5" class="text-center">Selecione um titular</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- MODAL SENHA -->
                <div class="modal fade" id="modalSenha" tabindex="-1">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Senha Gerada</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <input type="text" id="senhaGerada" class="form-control text-center fs-2" readonly>
                                <div class="mt-2" style="font-size:.85rem;color:#64748b;">Use “Imprimir” para gerar o
                                    ticket.</div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary w-100" onclick="imprimirSenha()">Imprimir
                                    (Ticket)</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL FECHAMENTO LOCAL -->
                <div class="modal fade" id="modalFechamentoLocal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Fechamento do limite diário</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="qtdRefeicoesServidas" class="form-label">Quantidade de Refeições
                                        Servidas</label>
                                    <input type="number" min="0" id="qtdRefeicoesServidas" class="form-control"
                                        placeholder="Digite a quantidade">
                                </div>

                                <div class="mb-3">
                                    <label for="cardapioFechamento" class="form-label">Cardápio</label>
                                    <textarea id="cardapioFechamento" class="form-control" rows="3"
                                        placeholder="Descreva o cardápio do dia..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="ocorrenciasFechamento" class="form-label">Ocorrências</label>
                                    <textarea id="ocorrenciasFechamento" class="form-control" rows="4"
                                        placeholder="Descreva as ocorrências..."></textarea>
                                </div>

                                <div class="small text-muted">
                                    Essas informações serão salvas no banco de dados e exibidas no relatório.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" onclick="salvarFechamentoLocal()">Salvar
                                    informações</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- row -->
        </div><!-- container -->
    </div><!-- app-content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

    /* ===== CONFIG DO TICKET ===== */
    const TICKET_CONFIG = {
        papelMM: 80,               // 58 ou 80 conforme impressora
        larguraImprimivelMM: 66,
        margemMM: 1.5,
        feedFinalMM: 3,
        brasaoUrl: "",             // opcional
        orgao: "GOVERNO DO ESTADO DO AMAZONAS",
        programa: "PRATO CHEIO CENTRO"
    };

    const TICKET_TEXT = {
        subtitulo: "ATENDIMENTO",
        labelNome: "NOME",
        labelCPF: "CPF",
        labelSuaSenha: "SUA SENHA",
        rodapeLinha1: "",
        rodapeLinha2: "Obrigado!"
    };


    /* ===== API ROUTES ===== */
    const API = {
        titulares: "/admin/api/titulares",
        dependentes: (id) => `/admin/titulares/${id}/dependentes`,
        senhas: "/admin/api/senhas",
        senhasContagem: "/admin/api/senhas/contagem",
        fechamentoInfo: "/admin/api/relatorio/fechamento-info",
        fechamentoManual: "/admin/api/fechamento/manual",
        fecharRelatorio: "/admin/api/relatorio/fechar"
    };



    async function apiFetch(url, options = {}) {
        const resp = await fetch(url, {
            headers: {
                "Accept": "application/json",
                ...(options.headers || {})
            },
            ...options
        });

        const text = await resp.text();

        let json = null;
        try {
            json = JSON.parse(text);
        } catch (e) {
            throw new Error(`Resposta inválida do servidor (HTTP ${resp.status}).`);
        }

        if (!resp.ok || !json || json.ok !== true) {
            throw new Error(
                (json && (json.message || json.error)) ||
                `Falha na requisição (HTTP ${resp.status}).`
            );
        }

        return json;
    }

    /* ===== SALVAR NO BANCO ===== */

    // Verifica no banco se o titular (CPF) já comprou no dia (retorna Promise<{ok,ja_comprou}>)
    function verificarTitularJaComprouHojeBanco(cpfLimpo) {
        var url = "/admin/api/senhas/ja-comprou?data=" + encodeURIComponent(getHojeKey()) + "&cpf=" + encodeURIComponent(cpfLimpo);

        return fetch(url, { headers: { "Accept": "application/json" } })
            .then(function (resp) {
                return resp.text().then(function (txt) {
                    let json = null;

                    try {
                        json = JSON.parse(txt);
                    } catch (e) {
                        json = null;
                    }

                    if (!resp.ok || !json) {
                        return { ok: false, ja_comprou: false };
                    }

                    return {
                        ok: json.ok === true,
                        ja_comprou: Boolean(json.ja_comprou === true || json.jaComprou === true)
                    };
                });
            })
            .catch(function () {
                return { ok: false, ja_comprou: false };
            });
    }

    async function salvarSenhasNoBanco(payload) {
        const resp = await fetch(API.senhas, {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify(payload)
        });

        const text = await resp.text(); // pega bruto (JSON ou HTML)

        let data = null;
        try { data = JSON.parse(text); } catch (e) { /* veio HTML/aviso */ }

        // Erro HTTP (ex: 409 duplicado)
        if (!resp.ok) {

            let msg = data?.error || `HTTP ${resp.status} - ${text.slice(0, 300)}`;

            if (data?.duplicados && Array.isArray(data.duplicados) && data.duplicados.length) {
                msg += `\n\nClientes já compraram hoje:\n- ${data.duplicados.join("\n- ")}`;
            }

            throw new Error(msg);
        }

        if (!data?.ok) {
            let msg = data?.error || `Resposta inválida: ${text.slice(0, 300)}`;

            if (data?.duplicados && Array.isArray(data.duplicados) && data.duplicados.length) {
                msg += `\n\nClientes já compraram hoje:\n- ${data.duplicados.join("\n- ")}`;
            }

            throw new Error(msg);
        }

        return data;
    }

    /* ===== ESTADO ===== */
    let titulares = [];
    let titularesFiltrados = [];
    let titularesPaginaAtual = 1;
    let titularesPageSize = 25;
    let titularSelecionado = null;

    // Dependentes (para seleção e impressão)
    let dependentesAtuais = [];
    let dependentesSelecionados = new Set(); // guarda chaves (id ou índice) dos dependentes marcados



    /* ===== RENDER TITULARES ===== */
    function renderTitulares(lista) {
        const tbody = document.getElementById("tbody-titulares");
        if (!tbody) return;

        if (!Array.isArray(lista) || lista.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum titular encontrado</td></tr>`;
            return;
        }

        const rows = lista.map(t => `
            <tr data-id="${t.id}" onclick="carregarDependentes(${t.id})">
                <td>${t.nome ?? ""}</td>
                <td>${t.cpf ?? ""}</td>
                <td>${t.idade ?? ""}</td>
                <td>${t.genero ?? ""}</td>
            </tr>`);

        tbody.innerHTML = rows.join("");


        // Mantém destaque do titular selecionado (se estiver nesta página)
        if (titularSelecionado?.id != null) {
            const idSel = String(titularSelecionado.id);
            document.querySelector(`#tbody-titulares tr[data-id="${idSel}"]`)
                ?.classList.add("active");
        }
    }


    /* ===== PAGINAÇÃO TITULARES ===== */
    function totalPaginasTitulares() {
        const total = Array.isArray(titularesFiltrados) ? titularesFiltrados.length : 0;
        return Math.max(1, Math.ceil(total / titularesPageSize));
    }

    function atualizarControlesPaginacaoTitulares() {
        const total = Array.isArray(titularesFiltrados) ? titularesFiltrados.length : 0;
        const totalPaginas = totalPaginasTitulares();

        // Corrige página atual se mudou pageSize/filtro
        if (titularesPaginaAtual > totalPaginas) titularesPaginaAtual = totalPaginas;
        if (titularesPaginaAtual < 1) titularesPaginaAtual = 1;

        const info = document.getElementById("titularesPaginacaoInfo");
        const indicador = document.getElementById("titularesPageIndicator");
        const prevBtn = document.getElementById("titularesPrev");
        const nextBtn = document.getElementById("titularesNext");

        const inicio = total === 0 ? 0 : ((titularesPaginaAtual - 1) * titularesPageSize + 1);
        const fim = Math.min(titularesPaginaAtual * titularesPageSize, total);

        if (info) info.textContent = total === 0 ? "0 resultado(s)" : `Mostrando ${inicio}-${fim} de ${total}`;
        if (indicador) indicador.textContent = `Página ${titularesPaginaAtual} de ${totalPaginas}`;

        if (prevBtn) prevBtn.disabled = titularesPaginaAtual <= 1;
        if (nextBtn) nextBtn.disabled = titularesPaginaAtual >= totalPaginas;
    }

    function renderTitularesPaginados() {
        const total = Array.isArray(titularesFiltrados) ? titularesFiltrados.length : 0;
        const start = (titularesPaginaAtual - 1) * titularesPageSize;
        const pageItems = total ? titularesFiltrados.slice(start, start + titularesPageSize) : [];
        renderTitulares(pageItems);
        atualizarControlesPaginacaoTitulares();
    }

    function irParaPaginaTitulares(novaPagina) {
        titularesPaginaAtual = Number(novaPagina) || 1;
        renderTitularesPaginados();
    }





    /* ===== RENDER DEPENDENTES ===== */
    function renderDependentes(lista) {

        dependentesAtuais = Array.isArray(lista) ? lista : [];
        dependentesSelecionados = new Set();

        // Reset do "selecionar todos"
        const chkAll = document.getElementById("dep-select-all");
        if (chkAll) {
            chkAll.checked = false;
            chkAll.indeterminate = false;
        }

        const tbody = document.getElementById("tbody-dependentes");
        if (!tbody) return;
        tbody.innerHTML = "";

        if (!dependentesAtuais.length) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">Nenhum dependente</td></tr>`;
            return;
        }

        const rows = dependentesAtuais.map((d, idx) => {
            const key = String((d.id !== undefined && d.id !== null) ? d.id : idx);
            const checked = dependentesSelecionados.has(key) ? "checked" : "";
            return `
            <tr data-key="${key}">
                <td style="width:60px; text-align:center;">
                    <input type="checkbox" class="dep-check" data-key="${key}" ${checked}
                        onchange="toggleDependente('${key}', this.checked)">
                </td>
                <td>${d.nome ?? ""}</td>
                <td>${d.idade ?? ""}</td>
                <td>${d.genero ?? ""}</td>
                <td>${d.dependencia_cliente ?? ""}</td>
            </tr>`;
        });

        tbody.innerHTML = rows.join("");
    }

    function toggleDependente(key, checked) {

        const k = String(key);

        if (checked) dependentesSelecionados.add(k);
        else dependentesSelecionados.delete(k);

        // Classe visual na linha
        const tr = document.querySelector(`#tbody-dependentes tr[data-key="${CSS.escape(k)}"]`);
        tr?.classList.toggle("active-dep", !!checked);

        atualizarCheckboxSelecionarTodos();
    }

    function selecionarTodosDependentes(checked) {

        document.querySelectorAll("#tbody-dependentes .dep-check")
            .forEach(chk => {

                chk.checked = !!checked;

                const k = String(chk.dataset.key);

                if (checked) dependentesSelecionados.add(k);
                else dependentesSelecionados.delete(k);

                const tr = chk.closest("tr");
                tr?.classList.toggle("active-dep", !!checked);
            });

        atualizarCheckboxSelecionarTodos();
    }

    function atualizarCheckboxSelecionarTodos() {

        const chkAll = document.getElementById("dep-select-all");
        if (!chkAll) return;

        const checks = Array.from(document.querySelectorAll("#tbody-dependentes .dep-check"));

        if (!checks.length) {
            chkAll.checked = false;
            chkAll.indeterminate = false;
            return;
        }

        const marcados = checks.filter(c => c.checked).length;

        chkAll.checked = marcados === checks.length;
        chkAll.indeterminate = marcados > 0 && marcados < checks.length;
    }


    /* ===== CARREGAR TITULARES ===== */
    async function carregarTitulares() {
        try {
            const resp = await fetch(API.titulares, {
                headers: { "Accept": "application/json" }
            });

            if (!resp.ok) throw new Error("Falha ao buscar titulares");

            titulares = await resp.json();
            // Pré-processa campos para busca rápida (evita toLowerCase/regex a cada tecla)
            titulares.forEach(t => {
                t._nomeKey = String(t.nome || "").toLowerCase();
                t._cpfKey = String(t.cpf || "").replace(/\D/g, "");
            });

            titularesFiltrados = titulares;
            titularesPaginaAtual = 1;
            renderTitularesPaginados();
        } catch (e) {
            console.error(e);

            document.getElementById("tbody-titulares").innerHTML =
                `<tr><td colspan="4" class="text-center text-danger">
                    Erro ao carregar titulares
                 </td></tr>`;
        }
    }


    /* ===== DEPENDENTES ===== */
    async function carregarDependentes(id) {

        titularSelecionado =
            titulares.find(x => Number(x.id) === Number(id)) || null;

        const msgTit = document.getElementById("msgTitularJaComprou");
        if (msgTit) {
            msgTit.style.display = "none";
            msgTit.textContent = "";
        }

        const chkTitular = document.getElementById("chk-incluir-titular");
        if (chkTitular) {
            chkTitular.checked = true;
        }

        document.querySelectorAll("#tbody-titulares tr")
            .forEach(tr => tr.classList.remove("active"));

        document.querySelector(`#tbody-titulares tr[data-id="${id}"]`)
            ?.classList.add("active");

        document.getElementById("tbody-dependentes").innerHTML =
            `<tr><td colspan="5" class="text-center">Carregando...</td></tr>`;

        try {

            const resp = await fetch(API.dependentes(id), {
                headers: { "Accept": "application/json" }
            });

            if (!resp.ok) throw new Error("Falha ao buscar dependentes");

            const lista = await resp.json();
            renderDependentes(lista);

        } catch (e) {
            console.error(e);

            document.getElementById("tbody-dependentes").innerHTML =
                `<tr><td colspan="5" class="text-center text-danger">
                Erro ao carregar dependentes
             </td></tr>`;
        }
    }


    /* ===== FILTRO (NOME + CPF) ===== */
    function formatarCPF(valor) {
        valor = (valor || "").replace(/\D/g, "");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        return valor;
    }

    function mascararCPFPrivado(cpf) {
        const digits = String(cpf || "").replace(/\D/g, "");

        if (!digits) return "—";

        // mantém o padrão final: ***.***.789-00
        if (digits.length === 11) {
            return "***.***." + digits.substring(6, 9) + "-" + digits.substring(9, 11);
        }

        // fallback para casos fora do padrão
        return "***.***.***-**";
    }

    function debounce(fn, wait) {
        let t;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    function setStatusFiltro(msg) {
        const el = document.getElementById("statusFiltro");
        if (!el) return;
        el.textContent = msg || "";
    }

    function filtrarTitulares(opts = {}) {
        const renderAll = Boolean(opts.renderAll);

        const nomeFiltro = (document.getElementById("filtroNome")?.value || "")
            .toLowerCase()
            .trim();

        const cpfFiltro = (document.getElementById("filtroCpf")?.value || "")
            .replace(/\D/g, "")
            .trim();

        // Usa campos pré-processados (_nomeKey/_cpfKey) para performance
        const filtrados = (titulares || []).filter(t => {
            const nome = t._nomeKey ?? String(t.nome || "").toLowerCase();
            const cpf = t._cpfKey ?? String(t.cpf || "").replace(/\D/g, "");

            const matchNome = !nomeFiltro || nome.includes(nomeFiltro);
            const matchCpf = !cpfFiltro || cpf.includes(cpfFiltro);

            return matchNome && matchCpf;
        });

        // Atualiza lista filtrada e paginação
        titularesFiltrados = filtrados;
        titularesPaginaAtual = 1;
        renderTitularesPaginados();

        if (nomeFiltro || cpfFiltro) {
            setStatusFiltro(`${filtrados.length} resultado(s) encontrado(s).`);
        } else {
            setStatusFiltro("");
        }
    }



    // Eventos de digitação (com debounce para não travar)
    const filtrarTitularesDebounced = debounce(() => filtrarTitulares({ renderAll: false }), 220);

    const nomeInput = document.getElementById("filtroNome");
    if (nomeInput) {
        nomeInput.addEventListener("input", filtrarTitularesDebounced);
        nomeInput.addEventListener("keyup", (e) => { if (e.key === "Enter") filtrarTitulares({ renderAll: true }); });
    }

    const cpfInput = document.getElementById("filtroCpf");
    if (cpfInput) {
        cpfInput.addEventListener("input", function () {
            // máscara + filtro leve
            this.value = formatarCPF(this.value);
            filtrarTitularesDebounced();
        });
        cpfInput.addEventListener("keyup", (e) => { if (e.key === "Enter") filtrarTitulares({ renderAll: true }); });
    }

    /* ===== INIT ===== */

    /* ===== PAGINAÇÃO - EVENTOS ===== */
    const pageSizeSelect = document.getElementById("titularesPageSize");
    if (pageSizeSelect) {
        pageSizeSelect.value = String(titularesPageSize);
        pageSizeSelect.addEventListener("change", () => {
            titularesPageSize = Number(pageSizeSelect.value) || 25;
            titularesPaginaAtual = 1;
            renderTitularesPaginados();
        });
    }

    document.getElementById("titularesPrev")?.addEventListener("click", () => {
        irParaPaginaTitulares(titularesPaginaAtual - 1);
    });

    document.getElementById("titularesNext")?.addEventListener("click", () => {
        irParaPaginaTitulares(titularesPaginaAtual + 1);
    });

    carregarTitulares();
    atualizarBloqueioSenhasUI();


    /* ===== MODAL ===== */
    async function abrirModal() {

        if (!titularSelecionado) {
            alert("Selecione um titular antes.");
            return;
        }

        const senhaGerada = await gerarSenha();
        if (!senhaGerada) return;

        new bootstrap.Modal(
            document.getElementById("modalSenha")
        ).show();
    }

    var LIMITE_SENHAS_DIA = 600;
    const TEXTO_OCORRENCIA_PADRAO = "NÃO HOUVE NENHUMA OCORRÊNCIA.";
    let modalFechamentoJaExibido = false;
    let modoFechamentoAtual = 'automatico';

    async function abrirModalFechamentoLocal(modo = 'automatico') {
        modoFechamentoAtual = modo;
        await carregarFechamentoBanco();

        const el = document.getElementById("modalFechamentoLocal");
        if (!el) return;

        const titulo = el.querySelector('.modal-title');
        const btnSalvar = document.querySelector('#modalFechamentoLocal .btn.btn-primary');

        if (titulo) {
            titulo.textContent = modo === 'manual'
                ? 'Encerrar dia manualmente'
                : 'Fechamento do limite diário';
        }

        if (btnSalvar) {
            btnSalvar.textContent = modo === 'manual' ? 'Salvar e encerrar dia' : 'Salvar informações';
        }

        new bootstrap.Modal(el).show();
    }

    let salvandoFechamento = false;

    async function salvarFechamentoLocal() {
        if (salvandoFechamento) return;

        const campoQtd = document.getElementById("qtdRefeicoesServidas");
        const campoCardapio = document.getElementById("cardapioFechamento");
        const campoOcorrencias = document.getElementById("ocorrenciasFechamento");

        let qtd = parseInt(campoQtd?.value || "0", 10);
        if (isNaN(qtd) || qtd < 0) qtd = 0;

        const cardapio = (campoCardapio?.value || "").trim();
        let ocorrencias = (campoOcorrencias?.value || "").trim();

        if (!ocorrencias) {
            ocorrencias = TEXTO_OCORRENCIA_PADRAO;
        }

        salvandoFechamento = true;

        const btnSalvar = document.querySelector('#modalFechamentoLocal .btn.btn-primary');
        if (btnSalvar) {
            btnSalvar.disabled = true;
            btnSalvar.textContent = "Salvando...";
        }

        try {
            const endpoint = modoFechamentoAtual === 'manual' ? API.fechamentoManual : API.fechamentoInfo;

            await apiFetch(endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    data: getHojeKey(),
                    qtd_refeicoes_servidas: qtd,
                    cardapio: cardapio,
                    ocorrencias: ocorrencias
                })
            });

            if (modoFechamentoAtual !== 'manual') {
                await apiFetch(API.fecharRelatorio, {
                    method: "POST"
                });
            }

            alert(modoFechamentoAtual === 'manual'
                ? "Informações salvas e dia encerrado com sucesso."
                : "Informações salvas e dia fechado com sucesso.");

            const el = document.getElementById("modalFechamentoLocal");
            const modal = bootstrap.Modal.getInstance(el);
            if (modal) modal.hide();

            const btnGerar = document.getElementById("btnGerarSenha");
            const btnGenerica = document.getElementById("btnSenhaGenerica");
            const btnInfo = document.getElementById("btnInfoFechamento");
            const btnEncerrar = document.getElementById("btnEncerrarDia");

            if (btnGerar) btnGerar.disabled = true;
            if (btnGenerica) btnGenerica.disabled = true;
            if (btnInfo) btnInfo.style.display = "none";
            if (btnEncerrar) btnEncerrar.disabled = true;
            atualizarBadgeDiaEncerrado(true);

            const status = document.getElementById("statusSenhas");
            if (status) {
                status.textContent = "Fechamento realizado. O dia está encerrado.";
            }

        } catch (e) {
            console.error(e);
            alert(e?.message || "Não foi possível salvar as informações do fechamento.");
        } finally {
            salvandoFechamento = false;

            if (btnSalvar) {
                btnSalvar.disabled = false;
                btnSalvar.textContent = modoFechamentoAtual === "manual" ? "Salvar e encerrar dia" : "Salvar informações";
            }
        }
    }

    async function carregarFechamentoBanco() {
        const campoQtd = document.getElementById("qtdRefeicoesServidas");
        const campoCardapio = document.getElementById("cardapioFechamento");
        const campoOcorrencias = document.getElementById("ocorrenciasFechamento");

        if (campoQtd) campoQtd.value = "";
        if (campoCardapio) campoCardapio.value = "";
        if (campoOcorrencias) campoOcorrencias.value = TEXTO_OCORRENCIA_PADRAO;

        try {
            const json = await apiFetch(API.fechamentoInfo + "?data=" + encodeURIComponent(getHojeKey()));
            const dados = json.data || {};

            if (campoQtd) {
                campoQtd.value = (dados.qtd_refeicoes_servidas !== null && dados.qtd_refeicoes_servidas !== undefined)
                    ? dados.qtd_refeicoes_servidas
                    : "";
            }

            if (campoCardapio) {
                campoCardapio.value = (dados.cardapio || "").trim();
            }

            if (campoOcorrencias) {
                campoOcorrencias.value = (dados.ocorrencias || "").trim() || TEXTO_OCORRENCIA_PADRAO;
            }
        } catch (e) {
            console.warn("Não foi possível carregar fechamento do banco.", e);
        }
    }

    /** Data do dia (YYYY-MM-DD) */
    function getHojeKey() {
        const d = new Date();
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, "0");
        const day = String(d.getDate()).padStart(2, "0");
        return `${y}-${m}-${day}`;
    }

    /** Consulta no BANCO quantas refeições já foram liberadas hoje (linhas em tb_senhas) */
    async function getTotalVendidasHojeBanco() {
        const data = getHojeKey();
        const json = await apiFetch(API.senhasContagem + `?data=${encodeURIComponent(data)}`);
        const dados = json.data || {};

        return {
            total: Number(dados.total || 0),
            limite: Number(dados.limite || LIMITE_SENHAS_DIA),
            fechado: Number(dados.fechado || 0) === 1,
            fechadoEm: dados.fechado_em || null
        };
    }

    /** Atualiza UI e bloqueia/desbloqueia botões baseado no BANCO */
    function atualizarBadgeDiaEncerrado(ativo) {
        const badge = document.getElementById("badgeDiaEncerrado");
        if (badge) {
            badge.style.display = ativo ? "inline-flex" : "none";
        }
    }

    async function atualizarBloqueioSenhasUI() {
        const btnGerar = document.getElementById("btnGerarSenha");
        const btnGen = document.getElementById("btnSenhaGenerica");
        const btnEncerrar = document.getElementById("btnEncerrarDia");
        const status = document.getElementById("statusSenhas");
        const btnInfoFechamento = document.getElementById("btnInfoFechamento");

        try {
            const info = await getTotalVendidasHojeBanco();
            const total = Number(info.total || 0);
            const limite = Number(info.limite || LIMITE_SENHAS_DIA);
            const bloqueadoPorLimite = total >= limite;
            const bloqueado = info.fechado === true || bloqueadoPorLimite;

            if (btnGerar) btnGerar.disabled = bloqueado;
            if (btnGen) btnGen.disabled = bloqueado;
            if (btnEncerrar) btnEncerrar.disabled = bloqueado;
            if (btnInfoFechamento) btnInfoFechamento.style.display = bloqueado ? "block" : "none";
            atualizarBadgeDiaEncerrado(bloqueado);

            if (status) {
                status.textContent = bloqueado
                    ? `Dia encerrado. Total de senhas vendidas hoje: ${total}/${limite}.`
                    : `Senhas vendidas hoje: ${total}/${limite}.`;
            }

            if (!bloqueado) {
                modalFechamentoJaExibido = false;
            }

            if (bloqueadoPorLimite && !modalFechamentoJaExibido) {
                modalFechamentoJaExibido = true;
                setTimeout(function () {
                    abrirModalFechamentoLocal('automatico');
                }, 200);
            }

            return { bloqueado, total, limite, fechado: bloqueado };

        } catch (e) {
            if (btnGerar) btnGerar.disabled = true;
            if (btnGen) btnGen.disabled = true;
            if (btnEncerrar) btnEncerrar.disabled = false;
            if (btnInfoFechamento) btnInfoFechamento.style.display = "none";
            atualizarBadgeDiaEncerrado(false);

            if (status) status.textContent = `Erro ao consultar contagem no banco. Verifique a rota de contagem.`;

            console.error(e);
            return { bloqueado: true, total: null, erro: e };
        }
    }

    /** Gera senha exibida (A1..A5) baseada no total do BANCO + 1 */
    async function gerarSenha() {
        const { bloqueado, total } = await atualizarBloqueioSenhasUI();

        if (bloqueado) {
            alert(`Limite diário atingido. Não é possível vender mais senhas hoje.\nTotal de senhas vendidas hoje: ${total ?? "?"}`);
            return null;
        }

        const senha = "A" + (Number(total) + 1);
        const campo = document.getElementById("senhaGerada");
        if (campo) campo.value = senha;

        return senha;
    }

    async function gerarSenhaGenerica() {
        // Senha genérica: imprime SEM dados de titular/dependentes,
        // mesmo que haja alguém selecionado na tabela.
        const senhaGerada = await gerarSenha();
        if (!senhaGerada) return;

        await imprimirSenhaGenerica(senhaGerada);
    }

    async function imprimirSenhaGenerica(senha) {
        const qtdRefeicoes = 1;

        // === CONTROLE DIÁRIO (BANCO) ===
        let totalHoje = 0;
        try {
            totalHoje = await getTotalVendidasHojeBanco();
        } catch (e) {
            alert("Não foi possível consultar a contagem no banco. Impressão cancelada.\n" + (e?.message || e));
            return;
        }

        if ((totalHoje + qtdRefeicoes) > LIMITE_SENHAS_DIA) {
            const restante = Math.max(0, LIMITE_SENHAS_DIA - totalHoje);
            alert(
                `Limite diário atingido.\n` +
                `Você ainda pode liberar ${restante} refeição${restante === 1 ? "" : "es"} hoje.\n` +
                `Total de senhas vendidas hoje: ${totalHoje}/${LIMITE_SENHAS_DIA}.`
            );
            await atualizarBloqueioSenhasUI();
            return;
        }

        // salva no banco ANTES de imprimir
        try {
            await salvarSenhasNoBanco({
                tipoSenha: "GENERICA",
                data_refeicao: getHojeKey(),
                itens: [
                    {
                        cliente: "SENHA GENÉRICA",
                        cpf: "",
                        idade: "",
                        genero: "",
                        deficiente: "",
                        id_titular: null,
                        id_dependente: null
                    }
                ]
            });
        } catch (e) {
            alert("Não foi possível salvar a senha no banco. Impressão cancelada.\n" + (e?.message || e));
            return;
        }

        await atualizarBloqueioSenhasUI();

        const agora = new Date();
        const data = agora.toLocaleDateString("pt-BR");
        const hora = agora.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" });

        const papel = TICKET_CONFIG.papelMM;
        const largura = TICKET_CONFIG.larguraImprimivelMM;
        const margem = TICKET_CONFIG.margemMM;
        const feed = TICKET_CONFIG.feedFinalMM;

        const nomeImpresso = "SENHA GENÉRICA";
        const cpfImpresso = "—";

        const refeicoesTexto =
            `${qtdRefeicoes} refeição${qtdRefeicoes > 1 ? "es" : ""} liberada${qtdRefeicoes > 1 ? "s" : ""}`;

        const html = `<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8" />
<title>Ticket</title>

<style>
@page { size: ${papel}mm auto; margin: ${margem}mm; }
html, body { margin:0; padding:0; font-family: Arial, Helvetica, sans-serif; font-size:9px; line-height:1.08; }
.ticket { width:${largura}mm; margin:0 auto; text-align:center; }
.brasao { width:12mm; margin:0 auto 2px; display:block; }
.orgao { font-size:8px; font-weight:700; text-transform:uppercase; }
.programa { font-size:10px; font-weight:900; text-transform:uppercase; }
.sep { border-top:1px dashed #000; margin:2px 0; }
.nome { font-size:8px; font-weight:700; text-transform:uppercase; }
.cpf { font-size:8px; }
.senha { font-size:28px; line-height:1; font-weight:900; margin:1px 0; }
.meta { font-size:8px; }
.info { font-size:8px; font-weight:700; margin-top:1px; }
.feed { height:${feed}mm; }
</style>
</head>

<body>
<div class="ticket">
${TICKET_CONFIG.brasaoUrl ? `<img class="brasao" src="${TICKET_CONFIG.brasaoUrl}">` : ""}
<div class="orgao">${TICKET_CONFIG.orgao}</div>
<div class="programa">${TICKET_CONFIG.programa}</div>
<div class="sep"></div>
<div class="nome">${nomeImpresso}</div>
<div class="sep"></div>
<div class="senha">${senha}</div>
<div class="meta">${data} ${hora}</div>
<div class="sep"></div>
<div class="info">${refeicoesTexto}</div>
<div class="feed"></div>
</div>
</body>
</html>`;

        // fecha o modal de senha, se estiver aberto
        const modalEl = document.getElementById("modalSenha");
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        const win = window.open("", "_blank", "width=420,height=640");

        if (!win) {
            alert("Popup bloqueado. Permita popups para imprimir.");
            return;
        }

        win.document.open();
        win.document.write(html);
        win.document.close();

        win.onload = function () {
            setTimeout(function () {
                win.focus();

                try {
                    win.onafterprint = function () {
                        try { win.close(); } catch (e) { }
                    };
                } catch (e) { }

                try {
                    win.print();
                } catch (e) { }

                // fallback
                setTimeout(function () {
                    try { win.close(); } catch (e) { }
                }, 800);

            }, 250);
        };
    }


    /* ===== IMPRESSÃO ===== */



    /* ===== IMPRESSÃO ===== */
    async function imprimirSenha() {

        if (!titularSelecionado) {
            alert("Selecione um titular antes.");
            return;
        }

        const senha = document.getElementById("senhaGerada").value;

        var cpfLimpo = String((titularSelecionado && titularSelecionado.cpf) ? titularSelecionado.cpf : "").replace(/\D/g, "");

        const depsSelecionados = dependentesAtuais.filter((d, idx) => {
            const key = String((d.id !== undefined && d.id !== null) ? d.id : idx);
            return dependentesSelecionados.has(key);
        });

        var chkIncluirTitular = document.getElementById("chk-incluir-titular");
        var incluirTitular = chkIncluirTitular ? chkIncluirTitular.checked : true;

        const msgTit = document.getElementById("msgTitularJaComprou");
        if (msgTit) {
            msgTit.style.display = "none";
            msgTit.textContent = "";
        }

        let titularJaComprou = false;

        if (incluirTitular && cpfLimpo) {
            try {
                const resultadoCompra = await verificarTitularJaComprouHojeBanco(cpfLimpo);
                titularJaComprou = Boolean(resultadoCompra && resultadoCompra.ja_comprou === true);
            } catch (e) {
                alert("Não foi possível verificar se o titular já comprou hoje.\n" + (e?.message || e));
                return;
            }

            if (titularJaComprou) {
                incluirTitular = false;

                const chk = document.getElementById("chk-incluir-titular");
                if (chk) chk.checked = false;

                if (msgTit) {
                    msgTit.textContent = "Titular já comprou hoje — liberando apenas dependentes.";
                    msgTit.style.display = "inline";
                }
            }
        }

        if (!incluirTitular && depsSelecionados.length === 0) {
            alert("Titular já comprou hoje. Selecione pelo menos um dependente para imprimir.");
            return;
        }

        const agora = new Date();
        const data = agora.toLocaleDateString("pt-BR");
        const hora = agora.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" });

        const papel = TICKET_CONFIG.papelMM;
        const largura = TICKET_CONFIG.larguraImprimivelMM;
        const margem = TICKET_CONFIG.margemMM;
        const feed = TICKET_CONFIG.feedFinalMM;

        const nomeImpresso = (titularSelecionado.nome || "").toUpperCase();
        const cpfImpresso = mascararCPFPrivado(titularSelecionado.cpf || "");

        const linhaTitular =
            (!incluirTitular)
                ? `<div style="margin-top:6px; font-size:12px; color:#b91c1c;"><b>AVISO</b><br>TITULAR NÃO INCLUÍDO (já possui senha hoje)</div>`
                : "";

        const depsHtml =
            depsSelecionados.length
                ? `<div style="margin-top:8px; font-size:12px; text-align:left;">
                <b>DEPENDENTES SELECIONADOS</b><br>
                ${depsSelecionados.map(d => `• ${(d.nome || "").toUpperCase()}`).join("<br>")}
           </div>`
                : "";

        const avisoTitularHtml =
            (titularJaComprou && depsSelecionados.length)
                ? `<div style="margin-top:6px; font-size:12px; color:#b91c1c;"><b>OBS:</b> Titular já comprou hoje. Senhas liberadas apenas para dependentes.</div>`
                : "";

        const qtdRefeicoes = (incluirTitular ? 1 : 0) + depsSelecionados.length;

        const refeicoesTexto =
            `${qtdRefeicoes} refeição${qtdRefeicoes > 1 ? "es" : ""} liberada${qtdRefeicoes > 1 ? "s" : ""}`;

        // valida limite e salva no banco antes de imprimir
        {
            let totalHoje = 0;
            try {
                totalHoje = await getTotalVendidasHojeBanco();
            } catch (e) {
                alert("Não foi possível consultar a contagem no banco. Impressão cancelada.\n" + (e?.message || e));
                return;
            }

            const totalApos = totalHoje + qtdRefeicoes;

            if (totalApos > LIMITE_SENHAS_DIA) {
                const restante = Math.max(0, LIMITE_SENHAS_DIA - totalHoje);
                alert(
                    `Limite diário atingido.\n` +
                    `Você ainda pode liberar ${restante} refeição${restante === 1 ? "" : "es"} hoje.\n` +
                    `Total de senhas vendidas hoje: ${totalHoje}/${LIMITE_SENHAS_DIA}.`
                );
                await atualizarBloqueioSenhasUI();
                return;
            }

            const itens = [];

            if (incluirTitular) {
                itens.push({
                    cliente: (titularSelecionado.nome || "").toUpperCase(),
                    cpf: cpfLimpo,
                    idade: (titularSelecionado.idade || ""),
                    genero: (titularSelecionado.genero || ""),
                    deficiente: "",
                    id_titular: (titularSelecionado && titularSelecionado.id != null) ? Number(titularSelecionado.id) : null,
                    id_dependente: null
                });
            }

            depsSelecionados.forEach(d => {
                itens.push({
                    cliente: (d.nome || "").toUpperCase(),
                    cpf: cpfLimpo,
                    idade: (d.idade || ""),
                    genero: (d.genero || ""),
                    deficiente: "",
                    id_titular: (titularSelecionado && titularSelecionado.id != null) ? Number(titularSelecionado.id) : null,
                    id_dependente: (d.id != null) ? Number(d.id) : null
                });
            });

            try {
                await salvarSenhasNoBanco({
                    tipoSenha: "NORMAL",
                    data_refeicao: getHojeKey(),
                    itens
                });
            } catch (e) {
                alert((e?.message || e));
                await atualizarBloqueioSenhasUI();
                return;
            }

            await atualizarBloqueioSenhasUI();
        }

        const html = `<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8" />
<title>Ticket</title>

<style>
@page { size: ${papel}mm auto; margin: ${margem}mm; }
html, body { margin:0; padding:0; font-family: Arial, Helvetica, sans-serif; font-size:9px; line-height:1.08; }
.ticket { width:${largura}mm; margin:0 auto; text-align:center; }
.brasao { width:12mm; margin:0 auto 2px; display:block; }
.orgao { font-size:8px; font-weight:700; text-transform:uppercase; }
.programa { font-size:10px; font-weight:900; text-transform:uppercase; }
.sep { border-top:1px dashed #000; margin:2px 0; }
.nome { font-size:8px; font-weight:700; text-transform:uppercase; }
.cpf { font-size:8px; }
.aviso { font-size:8px; color:#a40000; font-weight:700; }
.dep { text-align:left; font-size:8px; margin-top:1px; line-height:1.05; }
.senha { font-size:28px; line-height:1; font-weight:900; margin:1px 0; }
.meta { font-size:8px; }
.info { font-size:8px; font-weight:700; margin-top:1px; }
.feed { height:${feed}mm; }
</style>
</head>

<body>
<div class="ticket">
${TICKET_CONFIG.brasaoUrl ? `<img class="brasao" src="${TICKET_CONFIG.brasaoUrl}">` : ""}
<div class="orgao">${TICKET_CONFIG.orgao}</div>
<div class="programa">${TICKET_CONFIG.programa}</div>
<div class="sep"></div>
<div class="nome">${nomeImpresso}</div>
<div class="cpf">${cpfImpresso}</div>
${linhaTitular ? `<div class="aviso">TITULAR NÃO INCLUÍDO</div>` : ""}
${depsSelecionados.length ? `<div class="dep">${depsSelecionados.map(d => `• ${(d.nome || "").toUpperCase()}`).join("<br>")}</div>` : ""}
${titularJaComprou && depsSelecionados.length ? `<div class="aviso">APENAS DEPENDENTES</div>` : ""}
<div class="sep"></div>
<div class="senha">${senha}</div>
<div class="meta">${data} ${hora}</div>
<div class="sep"></div>
<div class="info">${refeicoesTexto}</div>
<div class="feed"></div>
</div>
</body>
</html>`;

        // fecha o modal antes de imprimir
        const modalEl = document.getElementById("modalSenha");
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        const win = window.open("", "_blank", "width=420,height=640");

        if (!win) {
            alert("Popup bloqueado. Permita popups para imprimir.");
            return;
        }

        win.document.open();
        win.document.write(html);
        win.document.close();

        win.onload = function () {
            setTimeout(function () {
                win.focus();

                try {
                    win.onafterprint = function () {
                        try { win.close(); } catch (e) { }
                        limparSelecaoDependentes();
                    };
                } catch (e) { }

                try {
                    win.print();
                } catch (e) { }

                // fallback caso o navegador não dispare onafterprint
                setTimeout(function () {
                    try { win.close(); } catch (e) { }
                    limparSelecaoDependentes();
                }, 800);

            }, 250);
        };
    }
    // Limpa seleção de dependentes após imprimir



    // Limpa seleção de dependentes após imprimir
    function limparSelecaoDependentes() {
        try {
            if (dependentesSelecionados && typeof dependentesSelecionados.clear === "function") {
                dependentesSelecionados.clear();
            } else {
                dependentesSelecionados = new Set();
            }
        } catch (e) {
            dependentesSelecionados = new Set();
        }

        // Desmarca checkboxes da tabela de dependentes
        document.querySelectorAll(".dep-check").forEach(cb => cb.checked = false);

        const chkAll = document.getElementById("dep-select-all");
        if (chkAll) chkAll.checked = false;
    }

    /* ===== IFRAME PRINT ===== */
    function printHtmlViaIframe(html) {

        let iframe =
            document.getElementById("print_iframe");

        if (!iframe) {

            iframe = document.createElement("iframe");

            iframe.id = "print_iframe";

            iframe.style.position = "fixed";
            iframe.style.width = "1px";
            iframe.style.height = "1px";
            iframe.style.opacity = "0";

            document.body.appendChild(iframe);
        }


        const doc = iframe.contentWindow.document;

        doc.open();
        doc.write(html);
        doc.close();


        setTimeout(() => {

            // Quando terminar de imprimir, limpa a seleção no documento principal
            try {
                iframe.contentWindow.onafterprint = () => {
                    limparSelecaoDependentes();
                };
            } catch (e) { /* ignore */ }

            iframe.contentWindow.focus();
            iframe.contentWindow.print();

            // Fallback: alguns navegadores não disparam onafterprint de forma confiável
            setTimeout(() => limparSelecaoDependentes(), 600);

        }, 300);
    }



    /* ===== ACESSIBILIDADE (FOCO DO MODAL) ===== */
    (function () {
        const modalSenhaEl = document.getElementById('modalSenha');
        if (modalSenhaEl && typeof bootstrap !== 'undefined') {
            modalSenhaEl.addEventListener('hide.bs.modal', function () {
                document.activeElement?.blur();
            });

            modalSenhaEl.addEventListener('hidden.bs.modal', function () {
                document.body.focus();
            });
        }

        const modalFechamentoEl = document.getElementById('modalFechamentoLocal');
        if (modalFechamentoEl && typeof bootstrap !== 'undefined') {
            modalFechamentoEl.addEventListener('show.bs.modal', function () {
                carregarFechamentoBanco();
            });

            modalFechamentoEl.addEventListener('hide.bs.modal', function () {
                document.activeElement?.blur();
            });

            modalFechamentoEl.addEventListener('hidden.bs.modal', function () {
                document.body.focus();
            });
        }
    })();


    document.addEventListener("DOMContentLoaded", function () {

        document.addEventListener("hidden.bs.modal", function () {
            setTimeout(function () {
                document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
                document.body.classList.remove("modal-open");
                document.body.style.paddingRight = "";
            }, 100);
        });

        document.addEventListener("click", function () {
            const backdrops = document.querySelectorAll(".modal-backdrop");
            if (backdrops.length > 1) {
                backdrops.forEach(el => el.remove());
            }
        });

    });


</script>
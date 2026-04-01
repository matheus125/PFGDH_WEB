<?php if(!class_exists('Rain\Tpl')){exit;}?><style>
    :root {
        --bg: #f6f8fb;
        --surface: #ffffff;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e5e7eb;
        --header: #f1f5f9;
        --accent: #1f3a8a;
        --accent-2: #0ea5e9;
        --shadow: 0 10px 25px rgba(15, 23, 42, .08);
        --success-bg: #dcfce7;
        --success-text: #166534;
        --warning-bg: #fef3c7;
        --warning-text: #92400e;
        --danger-bg: #fee2e2;
        --danger-text: #991b1b;
        --info-bg: #dbeafe;
        --info-text: #1d4ed8;
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

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 1px solid var(--border);
        background: #fff;
        min-height: 42px;
    }

    .form-control:focus,
    .form-select:focus {
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
        max-height: 520px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #ffffff;
        z-index: 2;
        border-bottom: 1px solid var(--border);
        font-size: .78rem;
        letter-spacing: .04em;
        color: var(--muted);
        text-transform: uppercase;
        white-space: nowrap;
    }

    .table tbody td {
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .02em;
    }

    .status-sucesso {
        background: var(--success-bg);
        color: var(--success-text);
    }

    .status-erro {
        background: var(--warning-bg);
        color: var(--warning-text);
    }

    .status-outro {
        background: var(--info-bg);
        color: var(--info-text);
    }

    .arquivo-nome {
        font-weight: 700;
        color: var(--text);
        word-break: break-word;
    }

    .arquivo-meta,
    .responsavel-meta {
        display: block;
        margin-top: 4px;
        color: var(--muted);
        font-size: .84rem;
        word-break: break-word;
    }

    .historico-footer {
        border-top: 1px solid var(--border);
        padding: 14px 16px;
        background: #fff;
    }

    .pagination-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
        align-items: center;
    }

    .pagination-btn {
        min-width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--text);
        font-weight: 600;
        cursor: pointer;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #f8fafc;
    }

    .pagination-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .pagination-btn.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .empty-state {
        padding: 32px 16px;
        text-align: center;
        color: var(--muted);
    }

    .toolbar-title {
        font-weight: 700;
        color: #0f172a;
    }

    .toolbar-subtitle {
        font-size: .85rem;
        color: #64748b;
    }

    .acoes-wrap {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-acao {
        border-radius: 10px !important;
        font-weight: 700 !important;
        padding: 6px 12px !important;
    }

    @media (max-width: 768px) {
        .top-bar .row>div {
            margin-bottom: 10px;
        }

        .table-fixed {
            max-height: 360px;
        }

        .historico-footer {
            display: block !important;
        }

        .pagination-wrap {
            justify-content: flex-start;
            margin-top: 12px;
        }
    }
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-center align-items-center position-relative">
        <h3 class="card-title mb-0">HISTÓRICO DE RELATÓRIOS PDF</h3>
        <a href="/admin/relatorio/senhas" class="btn btn-light btn-sm position-absolute"
            style="right:16px; top:50%; transform:translateY(-50%);">
            Voltar
        </a>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">

                <div class="top-bar mb-3 w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap" style="gap:12px;">
                        <div>
                            <div class="toolbar-title">Relatórios gerados</div>
                            <div class="toolbar-subtitle">Consulta de PDFs enviados, com status, responsável e link
                                público</div>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="filtroData">Data</label>
                            <input type="date" id="filtroData" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label for="filtroStatus">Status</label>
                            <select id="filtroStatus" class="form-control">
                                <option value="">Todos</option>
                                <option value="SUCESSO">SUCESSO</option>
                                <option value="ERRO">ERRO</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filtroPageSize">Itens por página</label>
                            <select id="filtroPageSize" class="form-control">
                                <option value="10">10</option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button id="btnFiltrar" class="btn btn-soft w-100 mb-1" type="button">Filtrar</button>
                            <button id="btnLimpar" class="btn btn-outline-soft w-100" type="button">Limpar</button>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="box">
                        <div class="table-fixed">
                            <table class="table table-striped table-sm mb-0" id="tabelaHistorico">
                                <thead>
                                    <tr>
                                        <th style="width:70px;">ID</th>
                                        <th style="width:130px;">Data Relatório</th>
                                        <th>Arquivo</th>
                                        <th style="width:240px;">Responsável</th>
                                        <th style="width:120px;">Status</th>
                                        <th style="width:150px;">Gerado em</th>
                                        <th style="width:150px;">Upload em</th>
                                        <th style="width:150px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Carregando...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="historico-footer d-flex justify-content-between align-items-center flex-wrap"
                            style="gap:12px;">
                            <div class="small text-muted" id="historicoInfo">-</div>
                            <div class="pagination-wrap" id="historicoPaginacao"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const API_URL = "/admin/api/relatorio/pdf/historico";
        let paginaAtual = 1;

        function escapeHtml(text) {
            if (text === null || text === undefined) return "";
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function escapeJsString(text) {
            if (text === null || text === undefined) return "";
            return String(text)
                .replace(/\\/g, "\\\\")
                .replace(/'/g, "\\'")
                .replace(/\r/g, "\\r")
                .replace(/\n/g, "\\n");
        }

        function formatarData(valor) {
            if (!valor) return "-";
            const partes = String(valor).split("-");
            if (partes.length !== 3) return escapeHtml(valor);
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

        function formatarDataHora(valor) {
            if (!valor) return "-";
            const texto = String(valor).replace(" ", "T");
            const data = new Date(texto);

            if (isNaN(data.getTime())) {
                return escapeHtml(valor);
            }

            const dd = String(data.getDate()).padStart(2, "0");
            const mm = String(data.getMonth() + 1).padStart(2, "0");
            const yyyy = data.getFullYear();
            const hh = String(data.getHours()).padStart(2, "0");
            const mi = String(data.getMinutes()).padStart(2, "0");

            return `${dd}/${mm}/${yyyy} ${hh}:${mi}`;
        }

        function badgeStatus(status) {
            if (status === "SUCESSO") {
                return '<span class="status-badge status-sucesso">SUCESSO</span>';
            }
            if (status === "ERRO") {
                return '<span class="status-badge status-erro">ERRO</span>';
            }
            return `<span class="status-badge status-outro">${escapeHtml(status || "-")}</span>`;
        }

        function montarLinha(item) {
            const abrirBtn = item.url_publica
                ? `<a href="${escapeHtml(item.url_publica)}"
                    target="_blank"
                    class="btn btn-primary btn-xs btn-acao">Abrir</a>`
                : "";

            const erroBtn = item.mensagem_erro
                ? `<button type="button"
                       class="btn btn-warning btn-xs btn-acao"
                       onclick="alert('${escapeJsString(item.mensagem_erro)}')">Erro</button>`
                : "";

            return `
            <tr>
                <td>${item.id}</td>
                <td>${formatarData(item.data_relatorio)}</td>
                <td>
                    <span class="arquivo-nome">${escapeHtml(item.nome_arquivo || "-")}</span>
                    <span class="arquivo-meta">${escapeHtml(item.caminho_remoto || "")}</span>
                </td>
                <td>
                    <strong>${escapeHtml(item.responsavel || "-")}</strong>
                    <span class="responsavel-meta">${escapeHtml(item.cpf_responsavel || "")}</span>
                </td>
                <td>${badgeStatus(item.status_upload)}</td>
                <td>${formatarDataHora(item.data_geracao)}</td>
                <td>${formatarDataHora(item.data_upload)}</td>
                <td>
                    <div class="acoes-wrap">
                        ${abrirBtn}
                        ${erroBtn}
                    </div>
                </td>
            </tr>
        `;
        }

        function montarPaginacao(totalPaginas, paginaAtualLocal) {
            const wrap = document.getElementById("historicoPaginacao");
            wrap.innerHTML = "";

            if (!totalPaginas || totalPaginas <= 1) return;

            function criarBotao(label, pagina, disabled, active) {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.className = "pagination-btn" + (active ? " active" : "");
                btn.textContent = label;
                btn.disabled = !!disabled;

                btn.addEventListener("click", function () {
                    if (!disabled && !active) {
                        carregarHistorico(pagina);
                    }
                });

                wrap.appendChild(btn);
            }

            criarBotao("«", paginaAtualLocal - 1, paginaAtualLocal <= 1, false);

            for (let i = 1; i <= totalPaginas; i++) {
                criarBotao(String(i), i, false, i === paginaAtualLocal);
            }

            criarBotao("»", paginaAtualLocal + 1, paginaAtualLocal >= totalPaginas, false);
        }

        async function carregarHistorico(page = 1) {
            paginaAtual = page;

            const data = document.getElementById("filtroData").value;
            const status = document.getElementById("filtroStatus").value;
            const pageSize = document.getElementById("filtroPageSize").value;

            const params = new URLSearchParams();
            params.set("page", page);
            params.set("pageSize", pageSize);

            if (data) params.set("data", data);
            if (status) params.set("status", status);

            const tbody = document.querySelector("#tabelaHistorico tbody");
            tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted">Carregando...</td>
            </tr>
        `;

            try {
                const response = await fetch(API_URL + "?" + params.toString(), {
                    headers: {
                        "Accept": "application/json"
                    }
                });

                const text = await response.text();

                let json = null;
                try {
                    json = JSON.parse(text);
                } catch (e) {
                    console.error("Resposta inválida da API:", text);
                    throw new Error("A API do histórico retornou uma resposta inválida.");
                }

                if (!response.ok || !json.success) {
                    throw new Error(json.message || "Erro ao carregar histórico.");
                }

                const items = Array.isArray(json.items) ? json.items : [];

                if (!items.length) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="empty-state">Nenhum registro encontrado.</td>
                    </tr>
                `;
                } else {
                    tbody.innerHTML = items.map(montarLinha).join("");
                }

                document.getElementById("historicoInfo").innerHTML =
                    `Total: <strong>${json.total}</strong> registro(s) | Página <strong>${json.page}</strong> de <strong>${json.pages || 1}</strong>`;

                montarPaginacao(json.pages || 1, json.page || 1);

            } catch (err) {
                console.error("Erro ao carregar histórico:", err);
                tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-danger text-center">Erro ao carregar histórico.</td>
                </tr>
            `;
                document.getElementById("historicoInfo").textContent = "Erro ao carregar histórico.";
                document.getElementById("historicoPaginacao").innerHTML = "";
            }
        }

        document.getElementById("btnFiltrar").addEventListener("click", function () {
            carregarHistorico(1);
        });

        document.getElementById("btnLimpar").addEventListener("click", function () {
            document.getElementById("filtroData").value = "";
            document.getElementById("filtroStatus").value = "";
            document.getElementById("filtroPageSize").value = "20";
            carregarHistorico(1);
        });

        document.getElementById("filtroPageSize").addEventListener("change", function () {
            carregarHistorico(1);
        });

        carregarHistorico(1);
    })();
</script>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#000000">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="auth" content="{{ auth()->check() ? '1' : '0' }}">
<script>try{var _t=localStorage.getItem("rl-theme");if(_t==="light"||_t==="dark")document.documentElement.setAttribute("data-theme",_t)}catch(e){}</script>
<title>Desafio Red Hat | Linux & RHCSA</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Courier+Prime:wght@400;700&family=Red+Hat+Display:ital,wght@0,500;0,600;0,700;0,800;1,400;1,500;1,600&display=swap" rel="stylesheet">
@vite(['resources/css/redlab.css', 'resources/js/redlab.js'])
</head>
<body><div class="fxl"></div><div class="fx"></div>
<div class="app">
<aside class="sidebar">
 <div class="logo-b"><img class="hat" src="{{ asset('assets/redhat-logo.png') }}" alt="Red Hat" width="200" height="151"><div><b>REDLAB</b><small>Centro de Treinamento</small></div></div>
 <nav class="nav-list" aria-label="Menu">
  <button class="nav on" data-v="sim" type="button"><svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M7 4.5l13 7.5-13 7.5z" fill="currentColor"/></svg><span>Simulado</span></button>
  <button class="nav" data-v="game" type="button"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 11h4M8 9v4M15 12h.01M18 10h.01M17.32 5H6.68a4 4 0 00-3.980 3.590C2.600 9.420 2 14.460 2 16a3 3 0 003 3c1 0 1.500-.5 2-1l1.410-1.410A2 2 0 019.830 16h4.340a2 2 0 011.410.590L17 18c.5.5 1 1 2 1a3 3 0 003-3c0-1.550-.600-6.580-.690-7.260A4 4 0 0017.320 5z"/></svg><span>Jogo</span></button>
  <button class="nav" data-v="stats" type="button"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7"/></svg><span>Estatísticas</span></button>
  <button class="nav" data-v="set" type="button"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="3.2"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/></svg><span>Configurações</span></button>
 </nav>
 <div class="sstats">
  <div class="sstat"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 114 2c-1 .8-1.5 1.200-1.500 2.500M12 17.200v.1"/></svg><b id="total-q">0</b><span>questões no total</span></div>
  <div class="sstat"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7"/></svg><b id="total-c">0</b><span>categorias</span></div>
  <div class="sstat"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 4h10v5a5 5 0 01-10 0zM7 6H4v1a3 3 0 003 3M17 6h3v1a3 3 0 01-3 3M12 14v4M8 20h8"/></svg><b>100%</b><span>interativo</span></div>
 </div>
</aside>
<div class="main" id="main">
 <header class="topbar">
  <div class="crumb"><b>REDLAB</b><span>›  Centro de Treinamento</span></div><div class="sp"></div>
  <button class="themebtn" id="theme-btn" type="button" aria-label="Alternar modo claro/escuro" title="Alternar modo claro/escuro"><svg class="moon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 14.5A8 8 0 019.5 4a8 8 0 1010.5 10.5z"/></svg><svg class="sun" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2.5M12 19.5V22M2 12h2.5M19.5 12H22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M19.1 4.9l-1.8 1.8M6.7 17.3l-1.8 1.8"/></svg></button>
  <div class="tag"><svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M7 4.5l13 7.5-13 7.5z" fill="currentColor"/></svg>Simulado Interativo</div>
  @auth
  <div class="authlinks">
   <a href="{{ route('dashboard') }}">← Escolher outro quiz</a>
   <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" title="Sair de {{ auth()->user()->email }}">Sair</button></form>
  </div>
  @endauth
 </header>

 <section id="view-sim">
  <div class="hero">
   <div class="eyebrow">Linux Enterprise</div>
   <h1>Domine o<em>Red Hat Linux.</em></h1>
   <p class="sub">Aprenda, pratique e conquiste novos desafios!</p>
  </div>
  <nav class="tabs"><button class="tab on" data-t="mc" type="button">Múltipla escolha</button><button class="tab" data-t="op" type="button">Dissertativa</button><button class="tab" data-t="cmd" type="button">Prática de comandos</button></nav>
  <div class="cat-eyebrow">Categorias</div>
  <section class="panel" aria-live="polite">
   <div id="setup">
    <h2 class="stitle"><em>Escolha</em> os assuntos e a quantidade de questões</h2>
    <div class="cats" id="cats"></div>
    <div class="qrow lvlrow"><span class="qlabel"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="M5 20v-4M12 20V10M19 20V4"/></svg>Nível de dificuldade:</span><div class="counts" id="lvls"></div></div>
    <div class="qrow"><span class="qlabel"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7"/></svg>Quantidade de questões:</span><div class="counts" id="counts"></div><button class="btn primary" id="start" disabled>Começar ›</button></div>
    <span class="hint" id="setup-hint">Selecione ao menos uma categoria para começar.</span>
   </div>
   <div id="quiz">
    <div class="quiz-top"><span class="pill" id="category"></span><span class="qright"><span class="counter" id="counter"></span><button class="btn-cancel" id="cancel" type="button">✕ Cancelar</button><span class="cask" id="cancel-ask" hidden><span class="ask">Voltar às escolhas? O progresso será perdido.</span><button class="btn-yes" id="c-yes" type="button">Sim, cancelar</button><button class="btn-cancel" id="c-no" type="button">Continuar</button></span></span></div>
    <div class="progress"><span id="progress"></span></div>
    <h2 class="question" id="question"></h2>
    <div class="options" id="options"></div>
    <textarea class="ta" id="ta" placeholder="Digite sua resposta aqui..." hidden></textarea>
    <div class="feedback" id="feedback"><strong id="fb-t"></strong><p id="fb-p"></p><div id="fb-x"></div></div>
    <div class="actions"><button class="btn secondary" id="back" disabled>← Voltar</button><span class="hint" id="hint"></span><button class="btn secondary" id="later" type="button">↷ Responder no final</button><button class="btn secondary" id="check" type="button" hidden>Corrigir resposta</button><button class="btn primary" id="next" disabled>Próxima questão →</button></div>
   </div>
   <div class="result" id="result">
    <div class="eyebrow">Missão concluída</div>
    <div class="score-ring"><div><b id="score">0/0</b><small id="score-l">acertos</small></div></div>
    <h2>Resultado</h2><p id="res-p">Aqui entra o resumo do seu desempenho e a correção comentada.</p><div class="rlist" id="res-list"></div>
    <button class="btn primary" id="restart">Novo simulado</button>
   </div>
  </section>
 </section>

 <section id="view-game" hidden>
  <div class="hero" style="padding-bottom:6px"><div class="eyebrow">Jogo</div><h2 class="stitle" style="margin-top:14px"><em>Caça-palavras</em> do Linux</h2></div>
  <div class="ws">
   <div class="wsbox"><div class="wgrid" id="wgrid" aria-label="Grade do caça-palavras"></div></div>
   <aside class="wsbox"><b style="font:600 20px var(--serif)">Palavras</b><ul class="wlist"><li>Palavra 1</li><li>Palavra 2</li><li>Palavra 3</li><li>Palavra 4</li></ul><p class="hint" style="margin:14px 0 0">Grade de exemplo. As palavras e o jogo entram na próxima etapa.</p></aside>
  </div>
 </section>

 <section id="view-stats" hidden>
  <div class="hero" style="padding-bottom:6px"><div class="eyebrow">Seu desempenho</div><h2 class="stitle" style="margin-top:14px"><em>Estatísticas</em> dos seus simulados</h2></div>
  <div class="kpis"><div class="kpi"><b id="k-n">0</b><span>simulados feitos</span></div><div class="kpi"><b id="k-avg">--</b><span>aproveitamento médio</span></div><div class="kpi"><b id="k-best">--</b><span>melhor resultado</span></div><div class="kpi"><b id="k-ok">0</b><span>acertos no total</span></div><div class="kpi"><b id="k-bad">0</b><span>erros no total</span></div></div>
  <h3 class="sh">Por assunto<small>Seu dia mais recente contra a média dos dias anteriores.</small></h3><div class="hist" id="st-cat"></div>
  <h3 class="sh">Evolução dia a dia<small>Aproveitamento médio de cada dia (últimos 14 dias com treino).</small></h3><div class="hist" id="st-day"></div>
  <h3 class="sh">Para revisar<small>Questões que você errou e ainda não acertou na tentativa mais recente.</small></h3><div class="hist" id="st-rev"></div>
 </section>

 <section id="view-set" hidden>
  <div class="hero" style="padding-bottom:6px"><div class="eyebrow">Preferências</div><h2 class="stitle" style="margin-top:14px"><em>Configurações</em></h2></div>
  <div class="setbox"><p>@auth O histórico das estatísticas fica salvo na sua conta. @else O histórico das estatísticas fica salvo apenas neste navegador. Entre ou crie uma conta para guardá-lo no servidor. @endauth</p><div><button class="btn secondary" id="clear-hist" type="button">Limpar histórico de estatísticas</button></div></div>
 </section>
 <div class="footer">REDLAB · Material de estudo · Questões autorais em formato de simulado.</div>
</div>
</div>
</body>
</html>

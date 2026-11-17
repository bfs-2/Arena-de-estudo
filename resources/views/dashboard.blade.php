<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#000000">
<script>try{var t=localStorage.getItem("rl-theme");if(t==="light"||t==="dark")document.documentElement.setAttribute("data-theme",t)}catch(e){}</script>
<title>Arena de Estudo</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Red+Hat+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet">
<style>
:root{--bg:#000;--card:#1B1D21;--line:rgba(255,255,255,.16);--text:#F5F5F5;--muted:#A9ADB3;--red:#EE0000;--red2:#A30000;--sans:Inter,system-ui,sans-serif;--serif:"Red Hat Display",Inter,system-ui,sans-serif}
html[data-theme=light]{--bg:#eef0f3;--card:#fff;--line:rgba(0,0,0,.15);--text:#1c1e22;--muted:#5b616a}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;background:var(--bg) radial-gradient(ellipse 55% 60% at 88% 15%,rgba(120,70,200,.25),transparent 70%) no-repeat;color:var(--text);font:400 16px/1.5 var(--sans)}
header{display:flex;align-items:center;gap:12px;padding:18px 32px;border-bottom:1px solid var(--line)}
.brand{margin-right:auto;font:800 22px var(--serif);color:inherit;text-decoration:none}
.brand span{color:var(--red)}
.hi{color:var(--muted);font-size:14px}
form{margin:0}
.btn{font:600 14px var(--sans);color:var(--text);text-decoration:none;padding:9px 18px;border-radius:999px;border:1px solid var(--line);background:transparent;cursor:pointer}
.btn:hover{border-color:var(--red)}
.btn.primary{background:linear-gradient(135deg,var(--red),var(--red2));border-color:transparent;color:#fff}
main{max-width:1000px;margin:0 auto;padding:72px 24px}
h1{font:800 clamp(34px,6vw,56px)/1.05 var(--serif);margin:0 0 14px}
h1 em{color:var(--red)}
.lead{color:var(--muted);max-width:560px;margin:0 0 44px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:18px}
.card{display:flex;flex-direction:column;gap:10px;padding:24px;border:1px solid var(--line);border-radius:16px;background:var(--card);color:inherit;text-decoration:none}
a.card:hover{border-color:var(--red)}
.card img{width:56px;height:auto}
.card h2{font:700 20px var(--serif);margin:0}
.card p{margin:0;color:var(--muted);font-size:14px}
.go{margin-top:auto;padding-top:8px;color:var(--red);font-weight:600;font-size:14px}
.soon{opacity:.55;border-style:dashed}
</style>
</head>
<body>
<header>
 <a class="brand" href="{{ route('dashboard') }}">Arena de <span>Estudo</span></a>
 @auth
 <span class="hi">Olá, {{ auth()->user()->name }}</span>
 <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn" type="submit">Sair</button></form>
 @else
 <a class="btn" href="{{ route('login') }}">Entrar</a>
 <a class="btn primary" href="{{ route('register') }}">Criar conta</a>
 @endauth
</header>
<main>
 <h1>Estude jogando. <em>Evolua de verdade.</em></h1>
 <p class="lead">Quizzes, simulados e jogos sobre tecnologia para você testar o que sabe e praticar o que falta.</p>
 <div class="grid">
  <a class="card" href="{{ route('simulado') }}">
   <img src="{{ asset('assets/redhat-logo.png') }}" alt="" width="56">
   <h2>Red Hat Linux · Redlab</h2>
   <p>Simulado de RHEL e RHCSA com múltipla escolha, dissertativas e prática de comandos.</p>
   <span class="go">Entrar no quiz →</span>
  </a>
  <div class="card soon"><h2>Mais quizzes em breve</h2><p>Novos temas chegando à plataforma.</p></div>
 </div>
</main>
</body>
</html>

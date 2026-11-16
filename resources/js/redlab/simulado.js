(async()=>{
const $=id=>document.getElementById(id),q=s=>[...document.querySelectorAll(s)];
let CATS=[],COUNTS={},META={total:0};
const sel=new Set();let n=10,mode="mc",i=0,hits=0,total=0,done=false,pool=[];
const wrap=$("view-sim").closest(".main");

const LVN={1:"Básico",2:"Intermediário",3:"Avançado"},MODELBL={mc:"Múltipla escolha",op:"Dissertativa",cmd:"Prática de comandos"};var lv="all";
const AUTH=document.querySelector('meta[name="auth"]')?.content==="1",CSRF=document.querySelector('meta[name="csrf-token"]')?.content||"";
const JSONH={Accept:"application/json"};
function api(method,url,body){return fetch(url,{method,headers:{...JSONH,"Content-Type":"application/json","X-CSRF-TOKEN":CSRF},body:body?JSON.stringify(body):undefined}).catch(()=>{})}
try{const r=await fetch("/api/questoes/contagem",{headers:JSONH});if(!r.ok)throw new Error(r.status);META=await r.json();CATS=META.categorias.map(c=>[c.nome,c.descricao,c.icone]);COUNTS=META.contagem}catch(e){$("setup-hint").textContent="Não foi possível carregar as questões. Recarregue a página."}

// ======================= CORREÇÃO AUTOMÁTICA (dissertativa) =======================
// Corretor offline: compara a resposta escrita com a resposta modelo por cobertura ponderada de conceitos.
const STOP=new Set("a o as os um uma uns umas de do da dos das em no na nos nas por pelo pela pelos pelas para pra com sem sob sobre entre ate e ou mas que se ao aos ja so tambem mais menos muito muita muitos muitas isso isto esse essa esses essas este esta estes estas aquele aquela eles elas ele ela eu voce nos seu sua seus suas meu minha como quando onde qual quais quem ser sao era foi sera esta estao estar tem tem ter temos pode podem poder deve devem dever faz fazer fazem serve servem usado usada usados usadas usa usam usar uso exemplo caso forma tipo coisa vez vezes mesmo mesma depois antes agora ainda apenas somente soh alem cada todo toda todos todas qualquer outro outra outros outras ali aqui nele nela dele dela neste nesta desse dessa disso nisso porque pois entao assim fica ficar fazendo feito sendo sido ha nao sim".split(" "));
// grupos de sinônimos → forma canônica
const SYN=[["apag","apaga","apagar","apagado","remove","remover","removido","exclui","excluir","deleta","deletar","delete","elimina","eliminar"],["pasta","diretorio","diretorios","pastas","folder","dir"],["mostr","mostra","mostrar","exibe","exibir","lista","listar","imprime","visualiza","visualizar","apresenta","retorna","revela"],["arqui","arquivo","arquivos","file","ficheiro"],["usuar","usuario","usuarios","user","conta","contas"],["senha","senhas","password","credencial"],["confi","configuracao","configuracoes","config","configurar","configura","definir","define","ajusta","ajustar"],["cria","criar","gera","gerar","generate","create","constroi"],["permi","permite","permitir","autoriza","autorizar","libera","liberar","habilita","habilitar"],["bloqu","bloqueia","bloquear","impede","impedir","proibe","proibir","nega","negar","barra"],["procu","procura","procurar","busca","buscar","encontra","encontrar","localiza","localizar","pesquisa","pesquisar","acha"],["subpa","subpasta","subpastas","subdiretorio","subdiretorios","recursiv","recursivo","recursivamente","recursive","cascata","dentro"],["inici","inicia","iniciar","boot","bota","inicializa","inicializacao","arranque"],["servi","servico","servicos","servidor","servidores","daemon"],["maqui","maquina","maquinas","host","computador","remota","remoto"],["comp","copia","copiar","copy","duplica","duplicar"],["muda","mudar","altera","alterar","troca","trocar","modifica","modificar","change"],["senh2","protege","proteger","seguranca","seguro","segura","protecao"],["texto","conteudo","linha","linhas","trecho"],["maius","maiuscula","maiusculo","maiusculas","uppercase"],["minus","minuscula","minusculo","minusculas","lowercase"]];
const SYNMAP={};SYN.forEach(g=>g.forEach(w=>SYNMAP[w]=g[0]));
const norm=s=>s.toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g,"");
function stem(w){if(SYNMAP[w])return SYNMAP[w];if(w.length>5&&w.endsWith("s"))w=w.slice(0,-1);if(SYNMAP[w])return SYNMAP[w];if(w.length>5)w=w.slice(0,5);return w}
function words(s){return norm(s).split(/[^a-z0-9]+/).filter(Boolean)}
function lev1(a,b){if(Math.abs(a.length-b.length)>1)return false;let i=0,j=0,d=0;while(i<a.length&&j<b.length){if(a[i]===b[j]){i++;j++;continue}if(++d>1)return false;if(a.length>b.length)i++;else if(a.length<b.length)j++;else{i++;j++}}return d+(a.length-i)+(b.length-j)<=1}
function hasStem(set,st){if(set.has(st))return true;if(st.length<4)return false;for(const u of set){if(u.length>=4&&(u.startsWith(st)||st.startsWith(u)))return true;if(st.length>=5&&u.length>=5&&lev1(u,st))return true}return false}
const FLAG=/(?<![\w-])--?[A-Za-z][A-Za-z0-9-]*/g;
function gradeAnswer(userText,model,question){
 const ut=(userText||"").trim();const uw=words(ut).filter(w=>!STOP.has(w));
 const userStems=new Set(uw.map(stem));
 const qStems=new Set(words(question).filter(w=>!STOP.has(w)).map(stem));
 const items=[];const seen=new Set();
 // 1) opções/flags (sensíveis a maiúsculas): -G, -r, --stdin...
 (model.match(FLAG)||[]).forEach(f=>{if(seen.has("f"+f))return;seen.add("f"+f);const re=new RegExp("(?<![\\w-])"+f.replace(/[-]/g,"\\-")+"(?![\\w-])");items.push({label:f,w:question&&question.includes(f)?.6:1.6,hit:re.test(ut)})});
 // 2) palavras-chave
 words(model.replace(FLAG," ")).forEach(w=>{if(STOP.has(w))return;const st=stem(w);if(seen.has(st))return;seen.add(st);
  let wt=/^\d+$/.test(w)?1.5:(w.length<=3?.6:1);if(qStems.has(st))wt*=.35;
  items.push({label:w,w:wt,hit:hasStem(userStems,st)})});
 const tot=items.reduce((a,x)=>a+x.w,0)||1,got=items.reduce((a,x)=>a+(x.hit?x.w:0),0);
 let cov=got/tot;let score=Math.round(100*Math.pow(cov,.6));
 if(uw.length<3)score=Math.min(score,15);else if(uw.length<6)score=Math.min(score,70);
 score=Math.max(0,Math.min(100,score));
 const key=items.filter(x=>x.w>=.9||x.label[0]==="-");
 return{score,hit:key.filter(x=>x.hit).map(x=>x.label),miss:key.filter(x=>!x.hit).map(x=>x.label),short:uw.length<3};
}
function verdict(s){return s>=85?"Excelente!":s>=70?"Muito bom!":s>=50?"Parcialmente correta.":s>=25?"Incompleta.":"Precisa revisar."}

function normCmd(x){x=x.trim().replace(/^[$#]\s*/,"").replace(/^sudo\s+/,"").replace(/["']/g,"").replace(/\s+/g," ");const o=[];x.split(" ").forEach(w=>{if(/^-[A-Za-z]{2,}$/.test(w))for(const c of w.slice(1))o.push("-"+c);else o.push(w)});const r=[];let run=[];const fl=()=>{run.sort();r.push(...run);run=[]};o.forEach(w=>{if(/^-[A-Za-z]$/.test(w))run.push(w);else{fl();r.push(w)}});fl();return r.join(" ")}
function gradeCmd(t,item){const u=normCmd(t);const ok=item.a.some(a=>normCmd(a)===u);if(ok)return{score:100,hit:["comando correto"],miss:[]};const c0=u.split(" ")[0];const m0=item.a[0].split(" ")[0];return c0===m0?{score:0,hit:["comando: "+m0],miss:["opções ou argumentos"]}:{score:0,hit:[],miss:["comando"]}}
const esc=s=>s.replace(/[&<>]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
let grades=[],answers=[],mcRes=[],picks=[],drafts=[];
// ======================= LÓGICA DO QUIZ =======================
function shuffle(arr){const a=arr.slice();for(let k=a.length-1;k>0;k--){const j=Math.floor(Math.random()*(k+1));[a[k],a[j]]=[a[j],a[k]]}return a}
function catCounts(){CATS.forEach((c,k)=>{const cnt=((COUNTS[mode]||{})[k]||{})[lv]||0;const el=document.querySelector(`.cat[data-k="${k}"] small`);if(el)el.textContent=c[1]+" · "+cnt+" questões"})}
// categorias
$("cats").innerHTML=CATS.map((c,k)=>`<button class="cat" data-k="${k}" type="button"><span class="ctile"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="${c[2]}"/></svg></span><span class="cdiv"></span><span class="ctxt"><b>${c[0]}</b><small>${c[1]} · 0 questões</small></span><span class="check">✓</span></button>`).join("");
$("counts").innerHTML=[5,10,15,20,30,"Todas"].map(v=>`<button class="count${v==10?" on":""}" data-n="${v}" type="button">${v}</button>`).join("");
catCounts();
$("total-q").textContent=META.total;$("total-c").textContent=CATS.length;
$("lvls").innerHTML=[["all","Todos"],[1,"Básico"],[2,"Intermediário"],[3,"Avançado"]].map(v=>`<button class="lvl${v[0]=="all"?" on":""}" data-l="${v[0]}" type="button">${v[1]}</button>`).join("");
$("lvls").onclick=e=>{const b=e.target.closest(".lvl");if(!b)return;q(".lvl").forEach(x=>x.classList.toggle("on",x===b));lv=b.dataset.l;catCounts()};
$("ta").addEventListener("keydown",e=>{if(mode=="cmd"&&e.key=="Enter"&&!e.shiftKey){e.preventDefault();$("check").click()}});
function sync(){$("start").disabled=!sel.size;$("setup-hint").textContent=sel.size?sel.size+" categoria(s) selecionada(s).":"Selecione ao menos uma categoria para começar."}
$("cats").onclick=e=>{const b=e.target.closest(".cat");if(!b)return;b.classList.toggle("on");const k=+b.dataset.k;sel.has(k)?sel.delete(k):sel.add(k);sync()};
$("counts").onclick=e=>{const b=e.target.closest(".count");if(!b)return;q(".count").forEach(x=>x.classList.remove("on"));b.classList.add("on");n=b.dataset.n};
// navegação
q(".nav").forEach(b=>b.onclick=()=>{if(b.dataset.v=="stats")renderStats();q(".nav").forEach(x=>x.classList.toggle("on",x===b));["sim","game","stats","set"].forEach(v=>$("view-"+v).hidden=v!==b.dataset.v)});
q(".tab").forEach(b=>b.onclick=()=>{q(".tab").forEach(x=>x.classList.toggle("on",x===b));mode=b.dataset.t;catCounts()});
function show(id){$("setup").style.display=id=="setup"?"":"none";$("quiz").style.display=id=="quiz"?"flex":"none";$("result").classList.toggle("show",id=="result");wrap.classList.toggle("inquiz",id!="setup")}
function shuffleOpts(it){if(!it.opts)return it;const ix=shuffle(it.opts.map((_,k)=>k));return{...it,opts:ix.map(k=>it.opts[k]),correct:ix.indexOf(it.correct)}}
async function fetchPool(){const p=new URLSearchParams({modo:mode,cats:[...sel].sort((x,y)=>x-y).join(","),nivel:lv,qtd:n});const r=await fetch("/api/questoes?"+p,{headers:JSONH});if(!r.ok)throw new Error(r.status);return (await r.json()).map(shuffleOpts)}
function render(){done=false;const item=pool[i];$("category").textContent=CATS[item.cat][0]+" · "+MODELBL[mode]+" · "+LVN[item.lvl];askOff();$("counter").textContent=`Questão ${i+1} de ${total}`;$("progress").style.width=(i/total*100)+"%";
 $("question").textContent=item.q;
 $("feedback").className="feedback";$("fb-x").innerHTML="";$("ta").readOnly=false;$("check").disabled=false;$("next").disabled=true;$("back").disabled=!i;$("next").textContent=i==total-1?"Finalizar":"Próxima questão →";
 const mc=mode=="mc";$("options").hidden=!mc;$("ta").hidden=mc;$("ta").value=drafts[i]||"";$("check").hidden=mc;
 $("hint").textContent=mc?"Selecione uma alternativa para conferir sua resposta.":(mode=="cmd"?"Digite o comando e pressione Enter ou clique em Corrigir.":"Escreva sua resposta e clique em Corrigir.");$("ta").placeholder=mode=="cmd"?"Digite o comando completo, ex.: ls -la /etc":"Digite sua resposta aqui...";$("ta").classList.toggle("mono",mode=="cmd");
 if(mc){$("options").innerHTML=item.opts.map((t,k)=>`<button class="option" data-k="${k}" type="button"><span class="letter">${"ABCD"[k]}</span>${t}</button>`).join("");if(picks[i]!==undefined)applyPick(picks[i])}
 else if(grades[i]){$("ta").value=answers[i];showGrade(grades[i])}
 $("later").disabled=done||i>=total-1}
function applyPick(k){done=true;const item=pool[i];const ok=k===item.correct;mcRes[i]=ok;
 q(".option").forEach(o=>{o.disabled=true;if(+o.dataset.k===item.correct)o.classList.add("correct");else if(+o.dataset.k===k)o.classList.add("wrong")});
 $("fb-t").textContent=ok?"Correto!":"Quase lá.";$("fb-p").textContent=item.exp;$("feedback").className="feedback show"+(ok?"":" bad");$("next").disabled=false}
$("options").onclick=e=>{const b=e.target.closest(".option");if(!b||done)return;picks[i]=+b.dataset.k;applyPick(picks[i]);$("later").disabled=true};
$("ta").oninput=()=>{drafts[i]=$("ta").value};
$("later").onclick=()=>{if(done||i>=total-1)return;[pool,picks,drafts,answers,grades,mcRes].forEach(a=>a.push(a.splice(i,1)[0]));render();$("hint").textContent="Questão movida para o final do simulado."};
function showGrade(r){const item=pool[i];$("ta").readOnly=true;$("check").disabled=true;$("later").disabled=true;done=true;
 $("fb-t").innerHTML=`<span class="gscore"><b>${r.score}%</b><span>${verdict(r.score)}</span></span><div class="gbar"><span style="width:${r.score}%"></span></div>`;
 $("fb-p").textContent="";
 $("fb-x").innerHTML=(r.hit.length?`<div class="glabel">Conceitos que você acertou</div><div class="chips">${r.hit.map(x=>`<span class="chip ok">${esc(x)}</span>`).join("")}</div>`:"")+(r.miss.length?`<div class="glabel">Conceitos que faltaram</div><div class="chips">${r.miss.map(x=>`<span class="chip no">${esc(x)}</span>`).join("")}</div>`:"")+`<div class="glabel">Resposta modelo</div><p>${esc(item.model)}</p><div class="glabel" style="margin-top:10px">${mode=="cmd"?esc(item.exp||"")+" Outras formas equivalentes também podem ser aceitas.":"Correção automática por palavras-chave: respostas com outras palavras equivalentes podem ser avaliadas com margem de erro."}</div>`;
 $("feedback").className="feedback show"+(r.score<50?" bad":"");$("next").disabled=false}
$("check").onclick=()=>{if(done)return;const t=$("ta").value.trim();if(!t){$("hint").textContent="Escreva uma resposta antes de corrigir.";$("ta").focus();return}
 const item=pool[i];const r=mode=="cmd"?gradeCmd(t,item):gradeAnswer(t,item.model,item.q);grades[i]=r;answers[i]=t;showGrade(r)};
$("start").onclick=async()=>{$("start").disabled=true;try{pool=await fetchPool()}catch(e){$("start").disabled=!sel.size;$("setup-hint").textContent="Não foi possível carregar as questões. Tente de novo.";return}$("start").disabled=!sel.size;if(!pool.length){$("setup-hint").textContent="Não há questões suficientes para essa combinação. Escolha outra(s) categoria(s).";return}total=pool.length;i=0;hits=0;const F=()=>Array.from({length:total});grades=F();answers=F();mcRes=F();picks=F();drafts=F();show("quiz");render()};
$("next").onclick=()=>{if(i<total-1){i++;render()}else{$("progress").style.width="100%";saveSession();if(mode!="mc"){const sc=grades.slice(0,total).map(x=>x?x.score:0);const avg=Math.round(sc.reduce((a,b)=>a+b,0)/total);$("score").textContent=avg+"%";$("score-l").textContent=mode=="cmd"?"comandos corretos":"média de acerto";if(mode=="cmd")$("score").textContent=sc.filter(x=>x==100).length+"/"+total;
  $("res-p").textContent=avg>=85?"Excelente domínio do conteúdo!":avg>=70?"Muito bom! Revise os conceitos que faltaram.":avg>=50?"Bom começo. Releia as respostas modelo e tente de novo.":"Vale revisar o conteúdo e refazer o simulado.";
  $("res-list").innerHTML=pool.map((it,k)=>`<div class="rrow"><span>${k+1}. ${esc(it.q.length>70?it.q.slice(0,70)+"…":it.q)}</span><b>${sc[k]}%</b></div>`).join("")}
 else{$("score").textContent=mcRes.filter(Boolean).length+"/"+total;$("score-l").textContent="acertos";$("res-p").textContent="Aqui entra o resumo do seu desempenho e a correção comentada.";$("res-list").innerHTML=""}
 show("result")}};
$("back").onclick=()=>{if(i>0){i--;render()}};
function askOff(){$("cancel-ask").hidden=true;$("cancel").hidden=false}
$("cancel").onclick=()=>{$("cancel").hidden=true;$("cancel-ask").hidden=false;$("c-no").focus()};
$("c-no").onclick=()=>{askOff();$("cancel").focus()};$("c-yes").onclick=()=>{askOff();show("setup")};
$("restart").onclick=()=>show("setup");
let sd=7;$("wgrid").innerHTML=Array.from({length:100},()=>{sd=(sd*9301+49297)%233280;return`<div class="wc">${"ABCDEFGHIJKLMNOPQRSTUVWXYZ"[Math.floor(sd/233280*26)]}</div>`}).join("");

// ======================= ESTATÍSTICAS =======================
const HK="redlab_hist_v1";let memHist=[];
if(AUTH){try{const r=await fetch("/api/simulados",{headers:JSONH});if(r.ok)memHist=await r.json()}catch(e){}}
function loadHist(){if(AUTH)return memHist;try{const v=JSON.parse(localStorage.getItem(HK)||"null");if(Array.isArray(v))return v}catch(e){}return memHist}
function storeHist(a){memHist=a;if(AUTH)return;try{localStorage.setItem(HK,JSON.stringify(a))}catch(e){}}
const dkey=d=>d.getFullYear()+"-"+String(d.getMonth()+1).padStart(2,"0")+"-"+String(d.getDate()).padStart(2,"0");
function saveSession(){const items=pool.slice(0,total).map((it,k)=>{
  if(mode=="mc"){const ok=!!mcRes[k];return{id:it.id,cat:it.cat,q:it.q,pct:ok?100:0,ok,a:it.opts[it.correct]+" — "+it.exp,m:"mc"}}
  const sc=grades[k]?grades[k].score:0;return{id:it.id,cat:it.cat,q:it.q,pct:sc,ok:sc>=60,a:it.model,m:"op"}});
 const s={t:Date.now(),d:dkey(new Date()),mode,items};storeHist(loadHist().concat([s]).slice(-200));if(AUTH)api("POST","/api/simulados",{modo:mode,dia:s.d,itens:items})}
const avg=a=>a.length?a.reduce((x,y)=>x+y,0)/a.length:0;
function renderStats(){const H=loadHist();const all=H.flatMap(s=>s.items.map(x=>({...x,d:s.d,t:s.t})));
 const sp=H.map(s=>Math.round(avg(s.items.map(x=>x.pct))));
 $("k-n").textContent=H.length;$("k-avg").textContent=H.length?Math.round(avg(sp))+"%":"--";$("k-best").textContent=H.length?Math.max(...sp)+"%":"--";
 const okc=all.filter(x=>x.ok).length;$("k-ok").textContent=okc;$("k-bad").textContent=all.length-okc;
 const E=t=>`<div class="empty">${t}</div>`;
 // por assunto
 const days=[...new Set(all.map(x=>x.d))].sort(),last=days[days.length-1];
 let rows=CATS.map((c,k)=>{const mine=all.filter(x=>x.cat===k);if(!mine.length)return"";
  const td=mine.filter(x=>x.d===last),pv=mine.filter(x=>x.d!==last);
  const cur=td.length?Math.round(avg(td.map(x=>x.pct))):null,prev=pv.length?Math.round(avg(pv.map(x=>x.pct))):null;
  const shown=cur!==null?cur:prev;let dl;
  if(cur===null)dl=`<span class="dl eq">não treinado em ${last.split("-").reverse().slice(0,2).join("/")}</span>`;
  else if(prev===null)dl=`<span class="dl eq">sem dias anteriores</span>`;
  else{const d=cur-prev;dl=`<span class="dl ${d>0?"up":d<0?"dn":"eq"}">${d>0?"▲ +":d<0?"▼ ":"= "}${d} pts · antes ${prev}%</span>`}
  return`<div class="srow"><div class="sn">${c[0]}<small>${(cur!==null?td:mine).length} questões${cur!==null?" no último dia":""}</small></div><div class="gbar"><span style="width:${shown}%"></span></div><b>${shown}%</b>${dl}</div>`}).join("");
 $("st-cat").innerHTML=rows||E("Faça um simulado para ver seu desempenho por assunto.");
 // dia a dia
 const byDay=days.slice(-14).map(d=>{const it=all.filter(x=>x.d===d);return{d,p:Math.round(avg(it.map(x=>x.pct))),n:it.length}});
 $("st-day").innerHTML=byDay.length?byDay.map(r=>`<div class="srow"><div class="sn">${r.d.split("-").reverse().join("/")}<small>${r.n} questões</small></div><div class="gbar"><span style="width:${r.p}%"></span></div><b>${r.p}%</b><span></span></div>`).join(""):E("Sem histórico ainda.");
 // revisar: última tentativa de cada questão errada
 const lastBy=new Map();all.sort((a,b)=>a.t-b.t).forEach(x=>lastBy.set(x.q,x));
 const rev=[...lastBy.values()].filter(x=>!x.ok).sort((a,b)=>b.t-a.t).slice(0,30);
 $("st-rev").innerHTML=rev.length?rev.map(x=>`<div class="rev"><small>${CATS[x.cat]?CATS[x.cat][0]:""} · ${x.m=="op"?"dissertativa, "+x.pct+"%":"múltipla escolha"}</small><b>${esc(x.q)}</b><p>${esc(x.a)}</p></div>`).join(""):E("Nada para revisar por enquanto.")}
$("clear-hist").onclick=()=>{if(confirm("Apagar todo o histórico de estatísticas?")){if(AUTH)api("DELETE","/api/simulados");storeHist([]);renderStats()}};
renderStats();
})();

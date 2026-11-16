// ======================= ZERO RISCADO =======================
(()=>{const SKIP=/^(SCRIPT|STYLE|TEXTAREA|INPUT)$/;let busy=false,tm=0;
function run(){if(busy)return;busy=true;const w=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT,{acceptNode:n=>n.nodeValue.includes("0")&&!SKIP.test(n.parentNode.nodeName)&&!n.parentNode.closest(".z")?1:2});const L=[];while(w.nextNode())L.push(w.currentNode);
 L.forEach(n=>{const f=document.createDocumentFragment();n.nodeValue.split("0").forEach((t,k)=>{if(k){const s=document.createElement("span");s.className="z";s.textContent="0";f.appendChild(s)}if(t)f.appendChild(document.createTextNode(t))});n.parentNode.replaceChild(f,n)});busy=false}
const go=()=>{clearTimeout(tm);tm=setTimeout(run,0)};
new MutationObserver(go).observe(document.body,{childList:true,subtree:true,characterData:true});run()})();

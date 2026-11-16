(function(){const r=document.documentElement,b=document.getElementById("theme-btn"),m=document.querySelector('meta[name="theme-color"]');
function ap(t){r.setAttribute("data-theme",t);if(m)m.content=t=="light"?"#eef0f3":"#000000";b.setAttribute("aria-pressed",t=="light")}
ap(r.getAttribute("data-theme")=="light"?"light":"dark");
b.onclick=()=>{const t=r.getAttribute("data-theme")=="light"?"dark":"light";ap(t);try{localStorage.setItem("rl-theme",t)}catch(e){}};})();

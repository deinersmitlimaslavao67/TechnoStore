<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TechnoStore · Catálogo</title>
<link rel="icon" type="image/png" href="logo/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
<style>
:root{--brand:#1F4E79;--brand-d:#163a5c;--brand-dd:#102f4a;--brand-l:#2f6fa8;--accent:#eaf1f8;--sand:#FFF2CC;--ok:#1e8e5a;--bad:#c0392b;--bg:#f3f5f8;--card:#fff;--line:#e3e8ee;--tx:#1d2733;--mut:#6b7785}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans','Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--tx);line-height:1.55}
button,input,select,textarea{font:inherit}
:focus-visible{outline:3px solid #f0b429;outline-offset:2px}
header{background:var(--brand);color:#fff;position:sticky;top:0;z-index:50}
.bar{max-width:1200px;margin:0 auto;padding:.8rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.logo{font-size:1.35rem;font-weight:700}
nav{display:flex;gap:.3rem;flex-wrap:wrap}
nav button{background:none;border:0;color:#fff;padding:.45rem .8rem;border-radius:6px;cursor:pointer;font-weight:500}
nav button:hover,nav button.on{background:rgba(255,255,255,.16)}
.hero{background:var(--sand);color:var(--brand);padding:2.2rem 1rem;text-align:center}
.hero h1{font-size:clamp(1.6rem,4vw,2.4rem)}
.wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem 3rem}
h2{font-size:1.4rem;margin-bottom:1rem}
.view{display:none}.view.on{display:block}
.btn{border:0;border-radius:6px;padding:.55rem 1rem;background:var(--brand);color:#fff;cursor:pointer;font-weight:500;transition:background .2s}
.btn:hover{background:var(--brand-d)}
.btn.ok{background:var(--ok)}.btn.bad{background:var(--bad)}
.btn.ghost{background:#fff;color:var(--tx);border:1px solid var(--line)}.btn.ghost:hover{background:#eef2f6}
.btn.sm{padding:.3rem .65rem;font-size:.85rem}
.tools{display:flex;gap:.6rem;margin-bottom:1.2rem;flex-wrap:wrap}
.tools input,.tools select{padding:.55rem .8rem;border:1px solid var(--line);border-radius:6px;background:#fff;flex:1;min-width:180px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.2rem}
.card{background:var(--card);border:1px solid var(--line);border-radius:10px;overflow:hidden;display:flex;flex-direction:column;position:relative}
.card img{width:100%;height:170px;object-fit:cover;background:#e9edf2}
.card .b{padding:.9rem 1rem;flex:1}
.card h3{font-size:1.02rem;color:var(--brand)}
.card .m{color:var(--mut);font-size:.85rem}
.card .p{font-size:1.25rem;font-weight:700;margin:.4rem 0 .1rem}
.card .f{padding:.8rem 1rem;border-top:1px solid var(--line)}
.tag{position:absolute;top:10px;left:10px;background:#f0b429;color:#3b2a00;font-size:.75rem;font-weight:700;padding:.15rem .55rem;border-radius:20px}
.empty{grid-column:1/-1;text-align:center;color:var(--mut);padding:2.5rem 1rem;background:#fff;border:1px dashed var(--line);border-radius:10px}
.tabs{display:flex;gap:.4rem;border-bottom:2px solid var(--line);margin-bottom:1.2rem}
.tabs button{background:none;border:0;padding:.6rem 1.1rem;cursor:pointer;font-weight:500;color:var(--mut);border-bottom:3px solid transparent;margin-bottom:-2px}
.tabs button.on{color:var(--brand);border-color:var(--brand)}
.panel{display:none}.panel.on{display:block}
.tw{background:#fff;border:1px solid var(--line);border-radius:10px;overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:640px}
th,td{padding:.7rem .9rem;text-align:left;border-bottom:1px solid var(--line);vertical-align:middle}
th{background:#f7f9fb;font-size:.85rem;color:var(--mut);font-weight:500}
tr:last-child td{border-bottom:0}
tbody tr:hover{background:#fafcfe}
.thumb{width:44px;height:44px;border-radius:6px;object-fit:cover;background:#e9edf2}
.acts{display:flex;gap:.4rem}
.stock{width:78px;padding:.3rem .4rem;border:1px solid var(--line);border-radius:6px}
.pill{display:inline-block;padding:.1rem .6rem;border-radius:20px;font-size:.78rem;font-weight:500;background:#e8edf3}
.pill.y{background:#fdf0c4;color:#6a4b00}.pill.low{background:#fbdcd8;color:#8a2217}
.modal{display:none;position:fixed;inset:0;background:rgba(15,25,35,.55);align-items:center;justify-content:center;z-index:100;padding:1rem}
.modal.on{display:flex}
.mc{background:#fff;border-radius:12px;width:100%;max-width:520px;max-height:92vh;overflow-y:auto;padding:1.5rem}
.mc h3{margin-bottom:1rem}
.fg{margin-bottom:.9rem}
.fg label{display:block;font-size:.88rem;font-weight:500;margin-bottom:.25rem}
.fg input,.fg select,.fg textarea{width:100%;padding:.55rem .7rem;border:1px solid #cdd5de;border-radius:6px}
.fg textarea{min-height:70px;resize:vertical}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:.8rem}
.autocomplete{position:relative}.autocomplete-list{position:absolute;left:0;right:0;top:100%;z-index:300;max-height:210px;overflow-y:auto;background:#fff;border:1px solid #cdd5de;border-radius:0 0 6px 6px;box-shadow:0 8px 18px rgba(15,25,35,.12)}
.autocomplete-list button{display:block;width:100%;border:0;background:#fff;text-align:left;padding:.55rem .7rem;color:var(--tx);cursor:pointer}
.autocomplete-list button:hover,.autocomplete-list button.active{background:#eef4fa;color:var(--brand)}
.fg.chk label{display:flex;gap:.5rem;align-items:center}.fg.chk input{width:auto}
.mf{display:flex;justify-content:flex-end;gap:.5rem;margin-top:1.2rem}
#toasts{position:fixed;right:1rem;bottom:1rem;display:grid;gap:.5rem;z-index:200}
.toast{background:#1d2733;color:#fff;padding:.7rem 1rem;border-radius:8px;border-left:5px solid var(--ok);box-shadow:0 6px 20px rgba(0,0,0,.25)}
.toast.err{border-color:var(--bad)}
footer{text-align:center;padding:1.5rem;color:var(--mut);font-size:.9rem}
@media(max-width:520px){.row2{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){*{transition:none!important}}

/* ===== Inicio profesional ===== */
.hero{background:linear-gradient(135deg,var(--brand-dd),var(--brand) 55%,var(--brand-l));color:#fff;padding:3.2rem 1rem;text-align:left;overflow:hidden}
.hero-in{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1.1fr 1fr;gap:2rem;align-items:center}
.eyebrow{display:inline-block;background:rgba(255,255,255,.14);padding:.25rem .8rem;border-radius:99px;font-size:.85rem;margin-bottom:1rem}
.hero h1{font-size:clamp(2rem,5vw,3.3rem);line-height:1.08;margin-bottom:.8rem}
.hero p{font-size:1.1rem;color:#d8e6f3;max-width:520px;margin-bottom:1.6rem}
.hero-cta{display:flex;gap:.7rem;flex-wrap:wrap}
.btn.gold{background:#f0b429;color:#2b1d00;font-weight:700;padding:.75rem 1.4rem}.btn.gold:hover{background:#ffc94d}
.btn.ghost-w{background:transparent;border:1px solid rgba(255,255,255,.55);padding:.75rem 1.4rem}.btn.ghost-w:hover{background:rgba(255,255,255,.12)}
.hero-art{position:relative;min-height:310px}
.hc{position:absolute;width:210px;background:#fff;color:var(--tx);border-radius:14px;overflow:hidden;box-shadow:0 18px 40px rgba(0,0,0,.3)}
.hc img{width:100%;height:135px;object-fit:cover;display:block}
.hc div{padding:.6rem .8rem;display:grid;gap:.1rem}.hc b{font-size:.9rem;line-height:1.25}.hc span{color:var(--brand);font-weight:700}
.hc0{left:2%;top:40px;transform:rotate(-6deg)}.hc1{left:calc(50% - 105px);top:0;transform:rotate(3deg);z-index:2}.hc2{right:2%;top:70px;transform:rotate(7deg)}
.perks{background:#fff;border-bottom:1px solid var(--line)}
.perks-in{max-width:1200px;margin:0 auto;padding:1rem;display:grid;grid-template-columns:repeat(4,1fr);gap:1rem}
.perks-in div{display:flex;gap:.7rem;align-items:center}.perks-in i{font-style:normal;font-size:1.4rem;background:var(--accent);border-radius:10px;width:42px;height:42px;display:grid;place-items:center}
.perks-in b{display:block;font-size:.92rem}.perks-in span{color:var(--mut);font-size:.8rem}
.sh{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:1rem}.sh h2{margin:0}.sh span{color:var(--mut)}
#sortSel{flex:0 0 auto;min-width:170px}
.chips{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.2rem}
.chip{border:1px solid var(--line);background:#fff;padding:.4rem .95rem;border-radius:99px;cursor:pointer;font-weight:500}
.chip:hover{border-color:var(--brand)}.chip.on{background:var(--brand);color:#fff;border-color:var(--brand)}
.card{border-radius:14px;transition:box-shadow .25s,transform .25s}
.card:hover{box-shadow:0 14px 30px rgba(20,40,60,.14);transform:translateY(-3px)}
.card .ph{position:relative;aspect-ratio:4/3;overflow:hidden}
.card .ph img{height:100%;transition:transform .5s}.card:hover .ph img{transform:scale(1.06)}
.card .b{display:grid;gap:.1rem;align-content:start}.card h3{font-size:1.05rem;color:var(--tx);line-height:1.3}
.card .m:first-child{text-transform:uppercase;letter-spacing:.05em;font-size:.72rem;font-weight:700;color:var(--brand)}
.tag.low{left:auto;right:10px;background:#c0392b;color:#fff}
.full{width:100%}
.pill.ok{background:#d9f3e5;color:#146c43}
.badge{background:#f0b429;color:#3b2a00;border-radius:99px;padding:0 .45rem;font-size:.75rem;font-weight:700;margin-left:.2rem}
.foot{background:var(--brand-dd);color:#b9c8d6;margin-top:2rem;padding:1.6rem 1rem}
.foot-in{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap}.foot b{color:#fff}
/* ===== Carrito ===== */
.cartwrap{display:grid;grid-template-columns:1fr 350px;gap:1.4rem;align-items:start}
.ci{display:grid;grid-template-columns:90px 1fr auto auto auto;gap:1rem;align-items:center;background:#fff;border:1px solid var(--line);border-radius:12px;padding:.8rem;margin-bottom:.8rem}
.ci img{width:90px;height:70px;border-radius:8px;object-fit:cover}
.ci-t{display:grid;font-size:.85rem;color:var(--mut)}.ci-t strong{color:var(--tx);font-size:1rem}
.qty{display:flex;align-items:center;border:1px solid var(--line);border-radius:8px}
.qty button{width:32px;height:32px;border:0;background:none;cursor:pointer;font-size:1.1rem}.qty button:hover:not(:disabled){background:#eef2f6}
.qty button:disabled{opacity:.3;cursor:not-allowed}.qty span{min-width:30px;text-align:center;font-weight:700}
.ci-s{font-weight:700;min-width:100px;text-align:right}
.x{border:0;background:#fbeceb;color:var(--bad);width:32px;height:32px;border-radius:8px;cursor:pointer}.x:hover{background:#f6d4d0}
.ci-foot{display:flex;justify-content:space-between;margin-top:.4rem}
.sum{background:#fff;border:1px solid var(--line);border-radius:14px;padding:1.3rem;position:sticky;top:84px}
.sum h3{margin-bottom:.8rem}.line{display:flex;justify-content:space-between;padding:.3rem 0}
.line.tot{border-top:1px solid var(--line);margin:.5rem 0 1rem;padding-top:.8rem;font-size:1.25rem;font-weight:700}
.logo{display:flex;align-items:center;gap:.6rem}.logo img{height:34px;width:auto;border-radius:6px;background:#fff}
.tabs{overflow-x:auto}.tabs button{white-space:nowrap}
.sub{color:var(--mut);font-size:.82rem}
.oinfo{margin-bottom:1rem;font-size:.92rem;display:grid;gap:.2rem}
.tform{padding:1.4rem;max-width:720px}.tform h3{margin-bottom:1rem}
.fg input[type=color]{height:42px;padding:.2rem;cursor:pointer}
.hint{background:#fdf0c4;color:#6a4b00;border-radius:8px;padding:.5rem .8rem;font-size:.88rem;margin-bottom:.9rem}
.tprev{display:flex;align-items:center;justify-content:space-between;color:#fff;border-radius:10px;padding:.8rem 1rem;font-weight:700;margin:.4rem 0 .2rem}
.tprev i{width:26px;height:26px;border-radius:50%;border:2px solid #fff}
.card .ph,.card h3{cursor:pointer}.card .f{display:grid;gap:.5rem}
.mc.wide{max-width:780px}
.pd{display:grid;grid-template-columns:1fr 1fr;gap:1.4rem}
.pd img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:10px;background:#e9edf2}
.pd h3{font-size:1.35rem;margin:.1rem 0 .3rem}.pd .brand{color:var(--brand);font-weight:700;text-transform:uppercase;letter-spacing:.05em;font-size:.78rem}
.pd .price{font-size:1.7rem;font-weight:700;margin:.2rem 0}.pd .mut{color:var(--mut);font-size:.9rem}
.pd .pdesc{white-space:pre-line;margin:.8rem 0}.pd h4{margin:.8rem 0 .3rem}.pd ul{padding-left:1.1rem;margin-bottom:1rem}.pd li{margin:.15rem 0}
.up{display:flex;gap:.6rem;align-items:center;margin-top:.5rem}.up img{width:44px;height:44px;border-radius:6px;object-fit:cover;background:#e9edf2;flex:0 0 auto}
.up input[type=file]{padding:.35rem;font-size:.85rem}
.pgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(310px,1fr));gap:1.2rem;align-items:start}
.pgrid .tform{max-width:none}
@media(max-width:640px){.pd{grid-template-columns:1fr}}
@media(max-width:860px){.hero-in{grid-template-columns:1fr}.hero-art{display:none}.perks-in{grid-template-columns:1fr 1fr}.cartwrap{grid-template-columns:1fr}.sum{position:static}.ci{grid-template-columns:70px 1fr auto}.ci img{width:70px}.ci-s{text-align:left}}

/* ===== TechnoStore · tema oscuro ===== */
:root{--bg:#0a0e14;--card:#121826;--card2:#1a2234;--line:#243049;--tx:#e6ebf5;--mut:#8b97ad;--acc:#5ab0ff}
body{background:var(--bg);color:var(--tx)}
h1,h2,h3,.logo{font-family:Sora,'DM Sans',system-ui,sans-serif;letter-spacing:-.015em}
:focus-visible{outline-color:var(--acc)}
header{background:rgba(10,14,20,.88);backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}
.bar{gap:.8rem 1rem}
.logo img{background:none}
.hsearch{flex:1;max-width:460px;position:relative}
.hsearch input{width:100%;padding:.55rem 1rem .55rem 2.4rem;background:var(--card);border:1px solid var(--line);border-radius:99px;color:var(--tx)}
.hsearch input::placeholder{color:var(--mut)}
.hsearch svg{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:var(--mut);pointer-events:none}
nav button.on{background:rgba(90,176,255,.14);color:var(--acc)}
.cartbtn{display:inline-flex;align-items:center;gap:.45rem;border:1px solid var(--line)!important}
.badge{background:var(--acc);color:#06111f}
.hero{background:radial-gradient(900px 420px at 85% -10%,color-mix(in srgb,var(--brand) 60%,transparent),transparent),var(--bg);border-bottom:1px solid var(--line);position:relative}
.hero::before{content:"";position:absolute;inset:0;background-image:linear-gradient(var(--line) 1px,transparent 1px),linear-gradient(90deg,var(--line) 1px,transparent 1px);background-size:48px 48px;opacity:.35;mask-image:linear-gradient(180deg,#000,transparent 85%)}
.hero-in{position:relative}
.hero p{color:var(--mut)}
.eyebrow{background:var(--card2);border:1px solid var(--line);color:var(--acc)}
.hc{background:var(--card);color:var(--tx);border:1px solid var(--line)}.hc span{color:var(--acc)}
.perks{background:var(--card);border-color:var(--line)}.perks-in i{background:var(--card2)}.perks-in span{color:var(--mut)}
.btn{background:var(--acc);color:#06111f}.btn:hover{background:#8cc8ff}
.btn.ok{background:#22c58b;color:#04140d}.btn.ok:hover{background:#4ddba6}
.btn.bad{background:#d64545;color:#fff}.btn.bad:hover{background:#e86060}
.btn.ghost{background:transparent;color:var(--tx);border:1px solid var(--line)}.btn.ghost:hover{background:var(--card2)}
.btn.gold{background:var(--acc);color:#06111f}.btn.gold:hover{background:#8cc8ff}
.btn.ghost-w{background:transparent;color:var(--tx);border:1px solid var(--line)}.btn.ghost-w:hover{background:var(--card2)}
.btn:disabled{opacity:.4;cursor:not-allowed}
.card,.tw,.sum,.ci,.mc,.empty,.tprev+*{background:var(--card);border-color:var(--line);color:var(--tx)}
.mc{border:1px solid var(--line)}
.card h3{color:var(--tx)}.card .m{color:var(--mut)}.card .m:first-child{color:var(--acc)}.card .p{color:#fff}
.card:hover{box-shadow:0 0 0 1px var(--acc),0 14px 34px rgba(0,0,0,.5)}
.card .ph img,.pd img,.thumb,.up img{background:var(--card2)}
.tag{background:var(--acc);color:#06111f}.tag.low{background:#d64545;color:#fff}
.tools input,.tools select,.fg input,.fg select,.fg textarea,.stock,.autocomplete-list{background:var(--bg);color:var(--tx);border:1px solid var(--line)}
.autocomplete-list button{background:var(--card);color:var(--tx)}.autocomplete-list button:hover,.autocomplete-list button.active{background:var(--card2);color:var(--acc)}
.chip{background:var(--card);color:var(--tx);border-color:var(--line)}.chip:hover{border-color:var(--acc)}
.chip.on{background:var(--acc);color:#06111f;border-color:var(--acc)}
.tabs{border-color:var(--line)}.tabs button{color:var(--mut)}.tabs button.on{color:var(--acc);border-color:var(--acc)}
th{background:var(--card2);color:var(--mut)}th,td{border-color:var(--line)}tbody tr:hover{background:var(--card2)}
.pill{background:var(--card2);color:var(--tx)}.pill.y{background:#3a2f10;color:#f3c969}.pill.ok{background:#10352a;color:#5fe0b0}.pill.low{background:#3d1a1a;color:#ff8f86}
.qty{border-color:var(--line)}.qty button{color:var(--tx)}.qty button:hover:not(:disabled){background:var(--card2)}
.x{background:#3d1a1a;color:#ff8f86}.x:hover{background:#552222}
.hint{background:#3a2f10;color:#f3c969}
.pd .brand{color:var(--acc)}
.foot{background:#070a10;border-top:1px solid var(--line);color:var(--mut)}
.toast{background:var(--card2);border:1px solid var(--line)}
.modal{background:rgba(3,6,12,.7);z-index:160}
/* Carrito lateral */
.scrim{position:fixed;inset:0;background:rgba(3,6,12,.6);z-index:140}
.drawer{position:fixed;top:0;right:0;height:100%;width:min(400px,100%);transform:translateX(100%);transition:transform .25s;z-index:150;display:flex;flex-direction:column;background:var(--card);border-left:1px solid var(--line)}
.drawer.on{transform:none}
.dh{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.2rem;border-bottom:1px solid var(--line)}
.db{flex:1;overflow-y:auto;padding:1rem 1.2rem;display:grid;gap:.9rem;align-content:start}
.df{padding:1rem 1.2rem;border-top:1px solid var(--line)}.df:empty{display:none}
.di{display:grid;grid-template-columns:64px 1fr auto;gap:.8rem;align-items:center}
.di img{width:64px;height:52px;border-radius:8px;object-fit:cover}
.di>div{display:grid;gap:.25rem;font-size:.85rem;color:var(--mut)}
.di strong{color:var(--tx);font-size:.92rem;line-height:1.25}
.addok{display:flex;gap:1rem;align-items:center}
.addok img{width:84px;height:64px;object-fit:cover;border-radius:8px}
.addok div{display:grid;gap:.2rem;color:var(--mut)}.addok strong{color:var(--tx)}
@media(max-width:860px){.bar{flex-wrap:wrap}.hsearch{order:3;max-width:none;flex-basis:100%}}

/* ===== Dinero digital ===== */
.wallet{display:inline-flex;align-items:center;gap:.4rem;border:1px solid var(--line)!important;color:var(--acc)!important;font-weight:700!important}
.wallet[hidden]{display:none}
.wallet-sec{display:grid;grid-template-columns:1.2fr 1fr;gap:1.2rem;margin-bottom:1.4rem;align-items:start}
.wbal,.wrec{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:1.3rem}
.wbal{background:radial-gradient(520px 220px at 100% 0,color-mix(in srgb,var(--brand) 55%,transparent),transparent),var(--card)}
.wbal h3{margin:.6rem 0 .1rem;font-size:1rem;color:var(--mut);font-weight:500;font-family:'DM Sans',system-ui,sans-serif}
.wbig{font-family:Sora,'DM Sans',sans-serif;font-size:clamp(1.9rem,5vw,2.6rem);font-weight:700;color:var(--acc);line-height:1.1;margin-bottom:.5rem}
.wbig.bump{animation:bump .45s ease}
.wrec label{font-weight:500;display:block;margin-bottom:.5rem}
.wq{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.7rem}
.wrow{display:flex;gap:.5rem}.wrow input{flex:1;min-width:0;padding:.55rem .8rem;background:var(--bg);color:var(--tx);border:1px solid var(--line);border-radius:6px}
.wsec-t{margin:.2rem 0 .7rem;font-size:1.05rem}
.mv{font-weight:700;white-space:nowrap}.mv.in{color:#5fe0b0}.mv.out{color:#ff8f86}
.paybox{border-radius:10px;padding:.7rem .85rem;font-size:.88rem;margin:-.3rem 0 .9rem;border:1px solid var(--line);background:var(--card2);display:grid;gap:.25rem}
.paybox .line{padding:.05rem 0}.paybox.ok{border-color:#1e8e5a}.paybox.low{border-color:#d64545;background:#2a1517}
.paybox .btn{margin-top:.4rem}
.rcpt{font-family:'DM Sans',system-ui,sans-serif}
.rcpt-h{text-align:center;border-bottom:2px dashed var(--line);padding-bottom:.9rem;margin-bottom:.9rem}
.rcpt-h b{display:block;font-family:Sora,sans-serif;font-size:1.15rem;color:var(--acc)}
.rcpt-n{font-size:1.3rem;font-weight:700;letter-spacing:.05em}
.rcpt-i{display:grid;gap:.2rem;font-size:.9rem;margin-bottom:.9rem}.rcpt-i span{color:var(--mut)}
.rcpt-t{display:flex;justify-content:space-between;border-top:2px dashed var(--line);margin-top:.8rem;padding-top:.8rem;font-size:1.2rem;font-weight:700}
.rcpt-w{text-align:center;font-size:.78rem;color:var(--mut);margin-top:.8rem}
@media(max-width:860px){.wallet-sec{grid-template-columns:1fr}}
@media print{body *{visibility:hidden}#compBody,#compBody *{visibility:visible}#compBody{position:absolute;inset:0 auto auto 0;width:100%;padding:1rem;color:#000;background:#fff}#compBody *{color:#000!important;border-color:#999!important}}

/* ===== Animaciones ===== */
@keyframes rise{from{opacity:0;translate:0 22px}to{opacity:1;translate:0 0}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes pop{from{opacity:0;scale:.94;translate:0 10px}to{opacity:1;scale:1;translate:0 0}}
@keyframes floaty{0%,100%{translate:0 0}50%{translate:0 -10px}}
@keyframes toastIn{from{opacity:0;translate:30px 0}to{opacity:1;translate:0 0}}
@keyframes bump{0%{scale:1}35%{scale:1.45}100%{scale:1}}
@keyframes grid{to{background-position:48px 48px}}
@keyframes glow{0%,100%{box-shadow:0 0 0 0 rgba(90,176,255,0)}50%{box-shadow:0 0 0 6px rgba(90,176,255,.18)}}
.hero-in>div:first-child>*{animation:rise .7s cubic-bezier(.2,.7,.2,1) both}
.hero-in>div:first-child>:nth-child(2){animation-delay:.08s}
.hero-in>div:first-child>:nth-child(3){animation-delay:.16s}
.hero-in>div:first-child>:nth-child(4){animation-delay:.26s}
.hc{animation:rise .8s cubic-bezier(.2,.7,.2,1) both,floaty 6s ease-in-out infinite}
.hc0{animation-delay:.3s,1.1s}.hc1{animation-delay:.42s,1.5s}.hc2{animation-delay:.54s,1.9s}
.hero::before{animation:grid 24s linear infinite}
.view.on{animation:fadeIn .35s ease both}
.card{animation:rise .55s cubic-bezier(.2,.7,.2,1) both;animation-delay:calc(var(--i,0) * 45ms)}
.chip,.btn,.qty button,.x,.cartbtn{transition:background .2s,color .2s,border-color .2s,scale .15s}
.btn:active:not(:disabled),.chip:active,.qty button:active:not(:disabled),.cartbtn:active{scale:.95}
.hsearch input{transition:border-color .2s,box-shadow .2s}
.hsearch input:focus{outline:none;border-color:var(--acc);box-shadow:0 0 0 4px rgba(90,176,255,.18)}
.modal.on{animation:fadeIn .2s ease both}
.modal.on .mc{animation:pop .28s cubic-bezier(.2,.8,.2,1) both}
.toast{animation:toastIn .35s cubic-bezier(.2,.8,.2,1) both}
.di{animation:rise .4s cubic-bezier(.2,.7,.2,1) both}
.drawer.on .di:nth-child(2){animation-delay:.05s}.drawer.on .di:nth-child(3){animation-delay:.1s}.drawer.on .di:nth-child(n+4){animation-delay:.15s}
.scrim:not([hidden]){animation:fadeIn .25s ease both}
.badge.bump,.cartbtn.bump{animation:bump .45s ease}
.cartbtn.bump{animation:glow .6s ease}
.tabs button{transition:color .2s,border-color .2s}
tbody tr{transition:background .15s}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}

/* ===== Términos y condiciones + animaciones extra ===== */
.mc.tc{max-width:760px}
.tc-bar{position:sticky;top:0;z-index:3;height:3px;width:0;margin:-1.5rem 0 1.2rem -1.5rem;background:var(--acc);border-radius:0 3px 3px 0}
.tc h3{font-size:1.35rem}.tc .sub{margin:.2rem 0 1rem}
.tc details{border:1px solid var(--line);border-radius:10px;margin-bottom:.5rem;background:var(--card2);transition:border-color .2s}
.tc details[open]{border-color:var(--acc)}
.tc summary{cursor:pointer;padding:.7rem 1rem;font-weight:600;list-style:none;display:flex;justify-content:space-between;gap:1rem}
.tc summary::-webkit-details-marker{display:none}
.tc summary::after{content:"+";color:var(--acc);font-size:1.25rem;line-height:1;transition:rotate .25s}
.tc details[open] summary::after{rotate:45deg}
.tc-b{padding:0 1rem 1rem;color:var(--mut);font-size:.92rem;line-height:1.6}
.tc-b p+p,.tc-b ul{margin-top:.5rem}.tc-b ul{padding-left:1.1rem}
.tc details[open] .tc-b{animation:rise .3s cubic-bezier(.2,.7,.2,1) both}
.tc-top{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem}
.linkbtn{background:none;border:0;padding:0;color:var(--acc);text-decoration:underline;text-underline-offset:3px;cursor:pointer;font:inherit}
.linkbtn:hover{color:#fff}
.terms-row{margin:.2rem 0 .8rem;border-radius:8px}
.terms-row label{align-items:flex-start;font-size:.88rem}
.terms-row input{margin-top:.25rem}
@keyframes shake{20%,60%{translate:-6px 0}40%,80%{translate:6px 0}}
.shake{animation:shake .45s ease;outline:2px solid var(--bad);outline-offset:4px}
@keyframes wiggle{25%{rotate:-12deg}75%{rotate:12deg}}
.perks-in div:hover i{animation:wiggle .5s ease}
.reveal{opacity:0;translate:0 26px;transition:opacity .6s ease,translate .6s cubic-bezier(.2,.7,.2,1)}
.reveal.in{opacity:1;translate:0 0}
.btn.ok{position:relative;overflow:hidden}
.btn.ok::after{content:"";position:absolute;inset:0;background:linear-gradient(100deg,transparent 30%,rgba(255,255,255,.35) 50%,transparent 70%);translate:-120% 0;transition:translate .6s;pointer-events:none}
.btn.ok:hover::after{translate:120% 0}
header{transition:box-shadow .3s}header.stuck{box-shadow:0 10px 28px rgba(0,0,0,.4)}
.foot .linkbtn{color:#b9c8d6}.foot .linkbtn:hover{color:#fff}

/* ===== Overlay de sesión (carga + confirmación) ===== */
.authfx{position:fixed;inset:0;z-index:400;display:grid;place-items:center;background:rgba(3,6,12,.72);backdrop-filter:blur(6px);animation:fadeIn .2s ease both}
.authfx[hidden]{display:none}
.afx-card{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:2rem 2.4rem;text-align:center;min-width:260px;box-shadow:0 20px 50px rgba(0,0,0,.5);animation:pop .3s cubic-bezier(.2,.8,.2,1) both}
.afx-icon{width:72px;height:72px;margin:0 auto 1rem;position:relative}
.afx-spin{position:absolute;inset:0;border-radius:50%;border:5px solid var(--line);border-top-color:var(--acc);animation:afxSpin .8s linear infinite}
.afx-ok{position:absolute;inset:0;display:none}
.afx-ok circle{fill:none;stroke:#22c58b;stroke-width:5;stroke-dasharray:190;stroke-dashoffset:190;animation:afxDraw .5s ease forwards}
.afx-ok path{fill:none;stroke:#22c58b;stroke-width:6;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:50;stroke-dashoffset:50;animation:afxDraw .35s .4s ease forwards}
.authfx.done .afx-spin{display:none}
.authfx.done .afx-ok{display:block}
.afx-txt{font-family:Sora,'DM Sans',sans-serif;font-weight:600;font-size:1.1rem}
.afx-sub{color:var(--mut);font-size:.88rem;margin-top:.2rem}
@keyframes afxSpin{to{rotate:360deg}}
@keyframes afxDraw{to{stroke-dashoffset:0}}
</style>
</head>
<body>
<header><div class="bar">
  <div class="logo"><img id="storeLogo" alt="" hidden><span id="storeTitle">TechnoStore</span></div>
  <form class="hsearch" id="hSearchForm" role="search"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input id="hSearch" type="search" placeholder="Buscar laptops, audífonos, marcas…" aria-label="Buscar productos"></form>
  <nav>
    <button data-v="catalog" class="on">Catálogo</button>
    <button data-v="orders" id="navOrders" hidden>Mis pedidos</button>
    <button data-v="profile" id="navProfile" hidden>Mi perfil</button>
    <button data-v="admin" id="navAdmin" hidden>Administración</button>
    <button type="button" id="navWallet" class="wallet" hidden aria-label="Ver mi saldo de dinero digital" title="Mi dinero digital"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7H5a2 2 0 0 1 0-4h13v4"/><path d="M3 5v14a2 2 0 0 0 2 2h15V7"/><circle cx="16.5" cy="14" r="1.2"/></svg><span id="walletAmt">…</span></button>
    <button type="button" id="navCart" class="cartbtn" aria-label="Abrir carrito"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.3"/><circle cx="18" cy="20" r="1.3"/><path d="M2 3h3l2.7 12.4a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L20 7H6"/></svg>Carrito<span id="cartBadge" class="badge" hidden>0</span></button>
    <button id="navLogin">Iniciar sesión</button>
    <button id="navReg">Registrarse</button>
    <button id="navLogout" hidden>Cerrar sesión</button>
  </nav>
</div></header>

<div id="home">
<section class="hero"><div class="hero-in">
  <div>
    <span class="eyebrow">Tecnología · Envíos a todo el país</span>
    <h1 id="bannerTitle">Cargando tienda…</h1>
    <p id="bannerDesc">Encuentra los mejores productos en tecnología</p>
    <div class="hero-cta"><button class="btn gold" id="goCat">Explorar catálogo</button><button class="btn ghost-w" id="goFeat">Ver destacados</button></div>
  </div>
  <div class="hero-art" id="heroArt"></div>
</div></section>
<section class="perks"><div class="perks-in">
  <div><i>🚚</i><p><b>Envíos nacionales</b><span>Llegamos a todo el país</span></p></div>
  <div><i>💳</i><p><b>Varios medios de pago</b><span>Tarjeta, PSE, Nequi o dinero digital</span></p></div>
  <div><i>📦</i><p><b>Inventario en vivo</b><span>Solo lo que hay disponible</span></p></div>
  <div><i>🛒</i><p><b>Compra fácil</b><span>Carrito y pedido en 2 pasos</span></p></div>
</div></section>
</div>

<main class="wrap">
  <!-- CATÁLOGO -->
  <section id="v-catalog" class="view on">
    <div class="sh"><h2>Catálogo de productos</h2><span id="count"></span></div>
    <div class="tools">
      <input id="searchInput" type="search" placeholder="Buscar por nombre o marca (ej. Sony)">
      <button class="btn" id="bSearch">Buscar</button>
      <select id="sortSel" aria-label="Ordenar"><option value="">Ordenar: relevancia</option><option value="asc">Precio: menor a mayor</option><option value="desc">Precio: mayor a menor</option><option value="name">Nombre A-Z</option></select>
      <button class="btn ghost" id="bAll">Ver todo</button>
      <button class="btn ghost" id="bFeat">Destacados</button>
    </div>
    <div class="chips" id="chips"></div>
    <div class="grid" id="productGrid"></div>
  </section>

  <!-- PEDIDOS -->
  <section id="v-orders" class="view">
    <h2>Mis pedidos</h2>
    <div class="tw"><table><thead><tr><th>Pedido</th><th>Estado</th><th>Fecha</th><th>Pago</th><th>Total</th><th></th></tr></thead><tbody id="ordersBody"></tbody></table></div>
  </section>

  <!-- PERFIL -->
  <section id="v-profile" class="view">
    <h2>Mi perfil</h2>
    <section class="wallet-sec" id="walletSec" aria-labelledby="wTitle">
      <div class="wbal">
        <span class="eyebrow">Dinero digital · simulado</span>
        <h3 id="wTitle">Saldo disponible</h3>
        <div class="wbig" id="wBig" aria-live="polite">—</div>
        <p class="sub">No es dinero real: solo sirve para pagar pedidos dentro de esta tienda.</p>
      </div>
      <form class="wrec" id="fRecarga">
        <label for="wMonto">Recargar saldo</label>
        <div class="wq" id="wQuick">
          <button type="button" class="chip" data-m="50000">+ $50.000</button>
          <button type="button" class="chip" data-m="200000">+ $200.000</button>
          <button type="button" class="chip" data-m="1000000">+ $1.000.000</button>
        </div>
        <div class="wrow"><input id="wMonto" type="number" min="1" max="10000000" step="1" inputmode="numeric" placeholder="Otro monto (COP)" required><button class="btn ok" id="wBtn">Recargar</button></div>
        <p class="sub" style="margin-top:.5rem">Máximo $10.000.000 por recarga.</p>
      </form>
    </section>
    <h3 class="wsec-t">Movimientos de mi saldo</h3>
    <div class="tw" style="margin-bottom:1.4rem"><table><thead><tr><th>Fecha</th><th>Movimiento</th><th>Monto</th><th>Saldo final</th><th>Comprobante</th></tr></thead><tbody id="wMovs"></tbody></table></div>
    <div class="pgrid">
      <form class="tw tform" id="fPerfil">
        <h3>Mis datos</h3>
        <div class="fg"><label for="mpMail">Correo electrónico</label><input id="mpMail" disabled></div>
        <div class="row2">
          <div class="fg"><label for="mpNom">Nombre</label><input id="mpNom" maxlength="80" autocomplete="given-name" required></div>
          <div class="fg"><label for="mpApe">Apellido</label><input id="mpApe" maxlength="80" autocomplete="family-name" required></div>
        </div>
        <div class="fg"><label for="mpTel">Teléfono</label><input id="mpTel" maxlength="20" autocomplete="tel"></div>
        <div class="row2">
          <div class="fg"><label for="mpPais">País</label><input id="mpPais" maxlength="100" autocomplete="country-name"></div>
          <div class="fg"><label for="mpCiudad">Ciudad</label><input id="mpCiudad" maxlength="100" autocomplete="address-level2"></div>
        </div>
        <div class="row2">
          <div class="fg"><label for="mpBarrio">Barrio</label><input id="mpBarrio" maxlength="100" autocomplete="address-level3"></div>
          <div class="fg"><label for="mpDir">Dirección</label><input id="mpDir" maxlength="255" autocomplete="street-address"></div>
        </div>
        <div class="mf"><button class="btn ok">Guardar datos</button></div>
      </form>
      <form class="tw tform" id="fPass">
        <h3>Cambiar contraseña</h3>
        <div class="fg"><label for="mpAct">Contraseña actual</label><input type="password" id="mpAct" autocomplete="current-password" required></div>
        <div class="fg"><label for="mpNew">Nueva contraseña (mín. 6)</label><input type="password" id="mpNew" minlength="6" autocomplete="new-password" required></div>
        <div class="fg"><label for="mpNew2">Repetir nueva contraseña</label><input type="password" id="mpNew2" minlength="6" autocomplete="new-password" required></div>
        <div class="mf"><button class="btn">Cambiar contraseña</button></div>
      </form>
    </div>
  </section>

  <!-- CARRITO -->
  <section id="v-cart" class="view">
    <h2>Mi carrito</h2>
    <div id="cartBox"></div>
  </section>

  <!-- ADMIN -->
  <section id="v-admin" class="view">
    <h2>Administración</h2>
    <div class="tabs">
      <button class="on" data-t="prod">Productos</button>
      <button data-t="cat">Categorías</button>
      <button data-t="ped">Pedidos</button>
      <button data-t="usr">Usuarios</button>
      <button data-t="tie">Tienda</button>
      <button data-t="rep">Reportes</button>
    </div>

    <div class="panel on" id="t-prod">
      <div class="tools">
        <input id="fProd" type="search" placeholder="Filtrar productos por nombre, marca o categoría">
        <select id="fCat"><option value="">Todas las categorías</option></select>
        <button class="btn ok" id="newProd">Nuevo producto</button>
        <button type="button" class="btn ghost" id="togProd">Ver eliminados</button>
      </div>
      <div class="tw"><table>
        <thead><tr><th></th><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody id="prodBody"></tbody>
      </table></div>
    </div>

    <div class="panel" id="t-cat">
      <div class="tools"><button class="btn ok" id="newCat">Nueva categoría</button><button type="button" class="btn ghost" id="togCat" style="flex:0 0 auto">Ver eliminados</button></div>
      <div class="tw"><table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr></thead>
        <tbody id="catBody"></tbody>
      </table></div>
    </div>
    <div class="panel" id="t-ped">
      <div class="chips" id="pedChips"></div>
      <div class="tools"><input id="fPed" type="search" placeholder="Buscar por n.º de pedido, cliente o correo"></div>
      <div class="tw"><table>
        <thead><tr><th>Pedido</th><th>Cliente</th><th>Fecha</th><th>Pago</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody id="pedBody"></tbody>
      </table></div>
    </div>
    <div class="panel" id="t-usr">
      <div class="tools"><button type="button" class="btn ghost" id="togUsr" style="flex:0 0 auto">Ver eliminados</button></div>
      <div class="tw"><table>
        <thead><tr><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Rol</th><th>Acciones</th></tr></thead>
        <tbody id="usrBody"></tbody>
      </table></div>
    </div>

    <div class="panel" id="t-tie">
      <form class="tw tform" id="fTienda">
        <h3>Personalización de la tienda</h3>
        <div class="fg"><label for="tNom">Nombre de la tienda</label><input id="tNom" maxlength="80" required></div>
        <div class="fg"><label for="tDes">Descripción (se muestra en el banner de inicio)</label><textarea id="tDes"></textarea></div>
        <div class="row2">
          <div class="fg"><label for="tLogo">Logo (ruta, URL o subir archivo)</label><input id="tLogo" maxlength="255" placeholder="img/logo.png">
            <div class="up"><img alt=""><input type="file" data-target="tLogo" accept="image/png,image/jpeg,image/webp,image/gif" aria-label="Subir logo"></div></div>
          <div class="fg"><label for="tBan">Banner (ruta, URL o subir archivo)</label><input id="tBan" maxlength="255" placeholder="img/banner.jpg">
            <div class="up"><img alt=""><input type="file" data-target="tBan" accept="image/png,image/jpeg,image/webp,image/gif" aria-label="Subir banner"></div></div>
        </div>
        <div class="row2">
          <div class="fg"><label for="tC1">Color primario</label><input type="color" id="tC1" value="#1f4e79"></div>
          <div class="fg"><label for="tC2">Color secundario</label><input type="color" id="tC2" value="#fff2cc"></div>
        </div>
        <div class="tprev" id="tPrev"><span id="tPrevName"></span><i id="tPrevSw"></i></div>
        <p class="hint" id="tWarn" hidden>El color primario es muy claro: el texto blanco del menú será difícil de leer.</p>
        <div class="row2" style="margin-top:.9rem">
          <div class="fg"><label for="tMail">Correo de contacto</label><input type="email" id="tMail" maxlength="120"></div>
          <div class="fg"><label for="tTel">Teléfono de contacto</label><input id="tTel" maxlength="20"></div>
        </div>
        <div class="mf"><button class="btn ok">Guardar cambios</button></div>
      </form>
    </div>

    <div class="panel" id="t-rep">
      <p class="sub" style="margin-bottom:1rem">Descarga la información de la tienda en archivos de Excel (.xlsx).</p>
      <div class="pgrid">
        <div class="tw tform"><h3>Pedidos entregados</h3><p class="sub" style="margin-bottom:.9rem">Pedidos en estado ENTREGADO, con cliente, fechas, método de pago y total. La segunda hoja trae los productos de cada pedido.</p><div class="mf" style="margin-top:0"><button class="btn ok" data-rep="pedidos_entregados">Descargar Excel</button></div></div>
        <div class="tw tform"><h3>Pedidos pendientes</h3><p class="sub" style="margin-bottom:.9rem">Pedidos en estado PENDIENTE (el cliente ya confirmó la compra y falta confirmar el pago). Incluye la hoja de productos.</p><div class="mf" style="margin-top:0"><button class="btn ok" data-rep="pedidos_pendientes">Descargar Excel</button></div></div>
        <div class="tw tform"><h3>Pedidos enviados</h3><p class="sub" style="margin-bottom:.9rem">Pedidos en estado ENVIADO que van en camino al cliente. Incluye la hoja de productos.</p><div class="mf" style="margin-top:0"><button class="btn ok" data-rep="pedidos_enviados">Descargar Excel</button></div></div>
        <div class="tw tform"><h3>Usuarios con pedidos</h3><p class="sub" style="margin-bottom:.9rem">Usuarios que han hecho al menos un pedido (el carrito no cuenta): cuántos pedidos tienen, cuántos cancelaron y cuánto han comprado.</p><div class="mf" style="margin-top:0"><button class="btn ok" data-rep="usuarios_con_pedidos">Descargar Excel</button></div></div>
        <div class="tw tform"><h3>Usuarios sin pedidos</h3><p class="sub" style="margin-bottom:.9rem">Clientes activos que nunca han hecho un pedido, con una columna que indica si tienen un carrito abierto.</p><div class="mf" style="margin-top:0"><button class="btn ok" data-rep="usuarios_sin_pedidos">Descargar Excel</button></div></div>
        <div class="tw tform"><h3>Productos más y menos comprados</h3><p class="sub" style="margin-bottom:.9rem">Dos hojas: los 10 más comprados y los 10 menos comprados (incluye los que nunca se han vendido). Cuentan los pedidos no cancelados.</p><div class="mf" style="margin-top:0"><button class="btn ok" data-rep="productos_mas_menos_comprados">Descargar Excel</button></div></div>
      </div>
    </div>
  </section>
</main>
<<<<<<< HEAD
<footer class="foot"><div class="foot-in"><b id="footName">TecnoShop</b><span>© 2026 · Tecnología con envío a todo el país</span><span id="footContact"></span></div></footer>
=======
<<<<<<< HEAD
<footer class="foot"><div class="foot-in"><b id="footName">TecnoShop</b><span>© 2026 · Tecnología con envío a todo el país</span><button type="button" class="linkbtn" data-terms>Términos y condiciones</button><span id="footContact"></span></div></footer>
=======
<footer class="foot"><div class="foot-in"><b id="footName">TecnoShop</b><span>© 2026 · Tecnología con envío a todo el país · <a href="#" id="btnFooterTerms" style="color:var(--acc);text-decoration:underline">Términos y Condiciones</a></span><span id="footContact"></span></div></footer>
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc

<!-- MODAL LOGIN / REGISTRO -->
<div class="modal" id="mAuth"><div class="mc">
  <div class="tabs" id="authTabs"><button type="button" class="on" data-a="login">Iniciar sesión</button><button type="button" data-a="reg">Crear cuenta</button></div>
  <form id="fAuth">
    <div class="fg"><label for="lEmail">Correo electrónico</label><input type="email" id="lEmail" autocomplete="email" required></div>
    <div class="fg"><label for="lPass">Contraseña</label><input type="password" id="lPass" autocomplete="current-password" required></div>
    <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn">Ingresar</button></div>
  </form>
  <form id="fReg" hidden>
    <div class="row2">
      <div class="fg"><label for="rNom">Nombre</label><input id="rNom" autocomplete="given-name" required></div>
      <div class="fg"><label for="rApe">Apellido</label><input id="rApe" autocomplete="family-name" required></div>
    </div>
    <div class="fg"><label for="rEmail">Correo electrónico</label><input type="email" id="rEmail" autocomplete="email" required></div>
    <div class="row2">
      <div class="fg"><label for="rPass">Contraseña (mín. 6)</label><input type="password" id="rPass" minlength="6" autocomplete="new-password" required></div>
      <div class="fg"><label for="rPass2">Repetir contraseña</label><input type="password" id="rPass2" minlength="6" autocomplete="new-password" required></div>
    </div>
    <div class="row2">
      <div class="fg"><label for="rTel">Teléfono (opcional)</label><input id="rTel" maxlength="20" autocomplete="tel"></div>
      <div class="fg"><label for="rDir">Dirección (opcional)</label><input id="rDir" maxlength="255" autocomplete="street-address"></div>
    </div>
    <div class="row2">
      <div class="fg"><label for="rPais">País</label><input id="rPais" maxlength="100" autocomplete="country-name"></div>
      <div class="fg"><label for="rCiudad">Ciudad</label><input id="rCiudad" maxlength="100" autocomplete="address-level2"></div>
    </div>
    <div class="fg"><label for="rBarrio">Barrio</label><input id="rBarrio" maxlength="100" autocomplete="address-level3"></div>
<<<<<<< HEAD
    <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Crear cuenta</button></div>
=======
<<<<<<< HEAD
    <div class="fg chk terms-row"><label><input type="checkbox" id="rTerms" required><span>Acepto los <button type="button" class="linkbtn" data-terms>términos y condiciones</button></span></label></div>
    <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Crear cuenta</button></div>
=======
    
    <!-- Términos y Condiciones dentro de Registro -->
    <div class="fg chk" style="margin-top:1rem;background:var(--card2);padding:.8rem;border-radius:8px;border:1px solid var(--line)">
      <label>
        <input type="checkbox" id="chkTerms" required>
        <span style="font-size:.85rem">Acepto los <a href="#" id="btnShowTerms" style="color:var(--acc);text-decoration:underline">Términos y Condiciones</a> y la Política de Tratamiento de Datos.</span>
      </label>
    </div>

    <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok" id="btnSubmitReg" disabled>Crear cuenta</button></div>
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc
  </form>
</div></div>

<!-- MODAL TÉRMINOS Y CONDICIONES -->
<div class="modal" id="mTerms" role="dialog" aria-modal="true" aria-labelledby="termsTitle"><div class="mc terms">
  <h3 id="termsTitle">Términos y Condiciones</h3>
  <div class="terms-box" id="termsBox" tabindex="0">
    <p><b>Última actualización:</b> noviembre de 2026</p>
    <h4>1. Aceptación</h4>
    <p>Al crear una cuenta o usar TechnoStore (la «Tienda») aceptas estos Términos y Condiciones. Si no estás de acuerdo, no uses la Tienda ni crees una cuenta.</p>
    <h4>2. Cuenta de usuario</h4>
    <ul>
      <li>Debes ser mayor de edad (o contar con autorización de tu representante legal) y dar información veraz y actualizada.</li>
      <li>Eres responsable de la confidencialidad de tu contraseña y de toda la actividad realizada con tu cuenta.</li>
      <li>Puedes solicitar la eliminación de tu cuenta; la Tienda puede suspenderla si detecta uso fraudulento o contrario a estos términos.</li>
    </ul>
    <h4>3. Productos, precios y disponibilidad</h4>
    <p>Los productos, precios e inventario se muestran en tiempo real y pueden cambiar sin previo aviso. Las imágenes son de referencia. Si un producto se agota antes de confirmar tu pedido, te lo informaremos.</p>
    <h4>4. Pedidos y pagos</h4>
    <ul>
      <li>Un pedido se considera confirmado cuando recibes el comprobante y el correo de confirmación.</li>
      <li>Medios de pago disponibles: tarjeta, PSE, Nequi y dinero digital.</li>
      <li><b>Dinero digital:</b> es un saldo <b>simulado</b>, sin valor monetario real, que solo sirve para pagar pedidos dentro de la Tienda. No es retirable, transferible ni reembolsable en dinero.</li>
    </ul>
    <h4>5. Envíos</h4>
    <p>Realizamos envíos a todo el país. Los tiempos de entrega son estimados y dependen de la ciudad de destino y de la transportadora. Debes indicar una dirección de entrega correcta y completa.</p>
    <h4>6. Cancelaciones, retracto y garantía</h4>
    <ul>
      <li>Puedes cancelar un pedido desde «Mis pedidos» mientras no haya sido despachado; el stock se devuelve al inventario.</li>
      <li>Conforme a la Ley 1480 de 2011 (Estatuto del Consumidor), en compras a distancia tienes derecho de retracto dentro de los cinco (5) días hábiles siguientes a la entrega, con el producto sin uso y en su empaque original.</li>
      <li>Los productos cuentan con la garantía legal y la del fabricante, según corresponda.</li>
    </ul>
    <h4>7. Protección de datos personales</h4>
    <p>Tratamos tus datos (nombre, correo, teléfono, dirección y datos de compra) para gestionar tu cuenta, procesar pedidos, enviarte notificaciones y mejorar el servicio, de acuerdo con la Ley 1581 de 2012 y sus normas reglamentarias. Tienes derecho a conocer, actualizar, rectificar y suprimir tus datos, y a revocar tu autorización, escribiendo al contacto de la Tienda. No vendemos tus datos a terceros.</p>
    <h4>8. Uso adecuado</h4>
    <p>Está prohibido intentar vulnerar la seguridad de la Tienda, usar datos de otras personas, publicar contenido ilícito o realizar compras fraudulentas.</p>
    <h4>9. Propiedad intelectual</h4>
    <p>Los logotipos, textos, diseños e imágenes de la Tienda son de su titular o de sus licenciantes y no pueden reproducirse sin autorización.</p>
    <h4>10. Limitación de responsabilidad</h4>
    <p>La Tienda no responde por interrupciones del servicio ajenas a su control ni por daños derivados del uso indebido de los productos. Nada de lo aquí dispuesto limita los derechos irrenunciables del consumidor.</p>
    <h4>11. Cambios y ley aplicable</h4>
    <p>Podemos modificar estos términos; la versión vigente será la publicada en la Tienda. Se rigen por las leyes de la República de Colombia.</p>
    <h4>12. Contacto</h4>
    <p>Para dudas, reclamos o ejercer tus derechos sobre tus datos, usa la información de contacto que aparece al pie de la página.</p>
  </div>
  <p class="terms-hint" id="termsHint">Desplázate hasta el final para poder aceptar.</p>
  <div class="mf"><button type="button" class="btn ghost" id="termsClose">Cerrar</button><button type="button" class="btn ok" id="termsAccept" disabled>Aceptar y continuar</button></div>
</div></div>

<!-- MODAL PRODUCTO (crear / editar) -->
<div class="modal" id="mProd"><form class="mc" id="fProdForm">
  <h3 id="mProdTitle">Nuevo producto</h3>
  <input type="hidden" id="pId">
  <div class="row2">
    <div class="fg"><label for="pNom">Nombre</label><input id="pNom" required></div>
    <div class="fg"><label for="pMar">Marca</label><input id="pMar"></div>
  </div>
  <div class="fg"><label for="pCat">Categoría</label><select id="pCat" required></select></div>
  <div class="fg"><label for="pDes">Descripción</label><textarea id="pDes"></textarea></div>
  <div class="fg"><label for="pEsp">Especificaciones</label><textarea id="pEsp"></textarea></div>
  <div class="row2">
    <div class="fg"><label for="pPre">Precio (COP)</label><input type="number" id="pPre" min="0" step="0.01" required></div>
    <div class="fg"><label for="pSto">Stock</label><input type="number" id="pSto" min="0" required></div>
  </div>
  <div class="fg"><label for="pImg">Imagen (ruta, URL o subir archivo)</label><input id="pImg" value="img/default.jpg" maxlength="255">
    <div class="up"><img alt=""><input type="file" data-target="pImg" accept="image/png,image/jpeg,image/webp,image/gif" aria-label="Subir imagen del producto"></div></div>
  <div class="fg chk"><label><input type="checkbox" id="pDest"> Mostrar como destacado</label></div>
  <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok" id="pSave">Guardar producto</button></div>
</form></div>

<!-- MODAL CATEGORÍA (crear / editar) -->
<div class="modal" id="mCat"><form class="mc" id="fCatForm">
  <h3 id="mCatTitle">Nueva categoría</h3>
  <input type="hidden" id="cId">
  <div class="fg"><label for="cNom">Nombre</label><input id="cNom" required></div>
  <div class="fg"><label for="cDes">Descripción</label><input id="cDes"></div>
  <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Guardar categoría</button></div>
</form></div>

<!-- MODAL DETALLE PRODUCTO (público) -->
<div class="modal" id="mProdDet"><div class="mc wide">
  <div id="pdBody"></div>
  <div class="mf"><button class="btn ghost" data-close>Cerrar</button></div>
</div></div>

<!-- MODAL EDITAR USUARIO (administrador) -->
<div class="modal" id="mUsr"><form class="mc" id="fUsr">
  <h3>Editar usuario</h3>
  <input type="hidden" id="uId">
  <div class="row2">
    <div class="fg"><label for="uNom">Nombre</label><input id="uNom" maxlength="80" required></div>
    <div class="fg"><label for="uApe">Apellido</label><input id="uApe" maxlength="80" required></div>
  </div>
  <div class="fg"><label for="uMail">Correo electrónico</label><input type="email" id="uMail" maxlength="120" required></div>
  <div class="row2">
    <div class="fg"><label for="uTel">Teléfono</label><input id="uTel" maxlength="20"></div>
    <div class="fg"><label for="uPass">Nueva contraseña (opcional)</label><input type="password" id="uPass" minlength="6" autocomplete="new-password" placeholder="Dejar vacío para no cambiarla"></div>
  </div>
  <div class="row2">
    <div class="fg"><label for="uPais">País</label><input id="uPais" maxlength="100"></div>
    <div class="fg"><label for="uCiudad">Ciudad</label><input id="uCiudad" maxlength="100"></div>
  </div>
  <div class="row2">
    <div class="fg"><label for="uBarrio">Barrio</label><input id="uBarrio" maxlength="100"></div>
    <div class="fg"><label for="uDir">Dirección</label><input id="uDir" maxlength="255"></div>
  </div>
  <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Guardar usuario</button></div>
</form></div>

<!-- MODAL CONFIRMAR ELIMINAR -->
<div class="modal" id="mDel"><div class="mc">
  <h3>Confirmar acción</h3>
  <p id="delMsg"></p>
  <div class="mf"><button class="btn ghost" data-close>Cancelar</button><button class="btn bad" id="delOk">Eliminar</button></div>
</div></div>

<!-- MODAL DETALLE PEDIDO -->
<div class="modal" id="mOrd"><div class="mc">
  <h3 id="ordTitle">Detalle del pedido699999</h3>
  <div class="oinfo" id="ordInfo"></div>
  <div class="tw"><table style="min-width:0"><thead><tr><th>Producto</th><th>Cant.</th><th>Subtotal</th></tr></thead><tbody id="ordDet"></tbody></table></div>
  <div class="mf"><button class="btn ghost" data-close>Cerrar</button></div>
</div></div>

<!-- MODAL COMPROBANTE (dinero digital) -->
<div class="modal" id="mComp"><div class="mc">
  <div class="rcpt" id="compBody"></div>
  <div class="mf"><button class="btn ghost" id="compPrint">Imprimir</button><button class="btn ghost" data-close>Cerrar</button></div>
</div></div>

<div class="scrim" id="scrim" hidden></div>
<aside class="drawer" id="drawer" aria-label="Carrito de compras" aria-hidden="true">
  <div class="dh"><h3>Tu carrito</h3><button type="button" class="x" id="dClose" aria-label="Cerrar carrito">✕</button></div>
  <div class="db" id="dBody"></div><div class="df" id="dFoot"></div>
</aside>
<div class="modal" id="mInfo"><div class="mc"><h3 id="infTitle"></h3><div id="infBody"></div><div class="mf" id="infBtns"></div></div></div>
<!-- MODAL TÉRMINOS Y CONDICIONES -->
<div class="modal" id="mTerms"><div class="mc tc" role="dialog" aria-labelledby="tcTitle">
<div class="tc-bar" id="tcBar"></div>
<div class="tc-top"><div><h3 id="tcTitle">Términos y condiciones</h3><p class="sub">Última actualización: noviembre de 2026</p></div><button type="button" class="btn ghost sm" id="tcAll">Expandir todo</button></div>
<details><summary>1. Aceptación</summary><div class="tc-b"><p>Al crear una cuenta, navegar o comprar en esta tienda aceptas estos términos. Si no estás de acuerdo, no uses el servicio. Podemos actualizarlos; la versión vigente es la publicada en esta página.</p></div></details>
<details><summary>2. Tu cuenta</summary><div class="tc-b"><ul><li>Debes ser mayor de edad y tener capacidad legal para contratar.</li><li>Los datos que registres deben ser reales y estar actualizados.</li><li>Eres responsable de tu contraseña y de lo que se haga con tu cuenta. Avísanos si detectas un uso no autorizado.</li><li>Podemos suspender cuentas con datos falsos o uso fraudulento.</li></ul></div></details>
<details><summary>3. Productos, precios y disponibilidad</summary><div class="tc-b"><p>Los precios están en pesos colombianos (COP). El inventario se muestra en tiempo real y se descuenta cuando confirmas el pedido. Las imágenes son ilustrativas. Si un producto tiene un error evidente de precio o de stock, podemos cancelar el pedido y devolverte lo pagado.</p></div></details>
<details><summary>4. Pedidos y su estado</summary><div class="tc-b"><p>Al confirmar el pedido se crea en estado <b>Pendiente</b>. Luego pasa por <b>Pagado</b>, <b>Enviado</b> y <b>Entregado</b>, o termina en <b>Cancelado</b>. Te notificamos los cambios al correo registrado y puedes verlos en “Mis pedidos”. Antes de confirmar revisa productos, cantidades, dirección y método de pago.</p></div></details>
<details><summary>5. Métodos de pago</summary><div class="tc-b"><p>Puedes pagar con tarjeta de crédito, PSE, Nequi, contraentrega o dinero digital.</p><p><b>Dinero digital:</b> es un saldo interno de la tienda, simulado, sin valor monetario real. Solo sirve para comprar aquí: no se puede retirar, transferir ni cambiar por dinero. Cada pago genera un comprobante, y si un pedido pagado con este saldo se cancela, el valor se devuelve a tu saldo.</p></div></details>
<details><summary>6. Envíos y entrega</summary><div class="tc-b"><p>Enviamos a todo el país. Debes indicar país, ciudad, barrio y dirección completos y correctos; si el envío falla por datos errados o porque nadie recibe, los costos o demoras adicionales pueden ser tu responsabilidad. Revisa el paquete al recibirlo y repórtanos cualquier daño de inmediato.</p></div></details>
<details><summary>7. Cancelaciones, retracto y devoluciones</summary><div class="tc-b"><ul><li><b>Cancelación:</b> puedes cancelar desde “Mis pedidos” mientras el pedido esté en estado Pendiente. El stock vuelve al inventario.</li><li><b>Derecho de retracto:</b> en compras a distancia puedes retractarte dentro de los 5 días hábiles siguientes a la entrega del producto (artículo 47 de la Ley 1480 de 2011 y normas que lo modifiquen). Escríbenos a <span class="tcContact"></span>.</li><li>El producto debe devolverse en buen estado y con sus accesorios. Los costos de la devolución los asume el consumidor y, si el retracto procede, te devolvemos todo lo pagado sin descuentos ni retenciones.</li><li>Aplican las excepciones que establece la ley.</li></ul></div></details>
<details><summary>8. Garantía</summary><div class="tc-b"><p>Los productos cuentan con la garantía legal del Estatuto del Consumidor (Ley 1480 de 2011) y, cuando exista, con la garantía del fabricante. Si tu producto falla, contáctanos con el número de pedido para gestionar la reclamación.</p></div></details>
<details><summary>9. Tus datos personales</summary><div class="tc-b"><p>Tratamos tus datos conforme a la Ley 1581 de 2012 solo para gestionar tu cuenta, tus compras, los envíos y las notificaciones de tus pedidos. No los vendemos. Tienes derecho a conocer, actualizar, rectificar y solicitar la supresión de tus datos escribiendo a <span class="tcContact"></span>. Puedes editarlos en “Mi perfil”.</p></div></details>
<details><summary>10. Uso adecuado y propiedad intelectual</summary><div class="tc-b"><p>No puedes usar la tienda para actividades ilícitas, intentar vulnerar su seguridad ni copiar su contenido. Logos, textos e imágenes pertenecen a la tienda o a sus titulares.</p></div></details>
<details><summary>11. Responsabilidad, cambios y ley aplicable</summary><div class="tc-b"><p>No respondemos por fallas de internet, de pasarelas de pago o de transportadoras ajenas a nuestro control, salvo lo que la ley disponga. Estos términos se rigen por las leyes de Colombia. Ante cualquier reclamo puedes acudir también a la Superintendencia de Industria y Comercio.</p></div></details>
<div class="mf"><button type="button" class="btn ghost" data-close>Cerrar</button><button type="button" class="btn ok" id="tcAccept">Aceptar y continuar</button></div>
</div></div>

<<<<<<< HEAD
=======
<!-- OVERLAY DE SESIÓN (carga + confirmación) -->
<div class="authfx" id="authFx" role="status" aria-live="polite" hidden>
  <div class="afx-card">
    <div class="afx-icon">
      <div class="afx-spin"></div>
      <svg class="afx-ok" viewBox="0 0 72 72" aria-hidden="true"><circle cx="36" cy="36" r="30"/><path d="M23 37l9 9 17-19"/></svg>
    </div>
    <div class="afx-txt" id="afxTxt"></div>
    <div class="afx-sub" id="afxSub"></div>
  </div>
</div>

>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc
<div id="toasts" aria-live="polite"></div>
<div id="loader" role="status" aria-live="polite" aria-hidden="true"><div class="lbox"><div class="spinner"></div><span id="loaderMsg">Cargando…</span></div></div>

<script>
/* ---------- Utilidades ---------- */
const $ = s => document.querySelector(s);
const $$ = s => [...document.querySelectorAll(s)];
const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const money = n => '$' + Number(n || 0).toLocaleString('es-CO');
const PALETA = [['#1F4E79','#3d8bd1'],['#6a3fb5','#b07cf0'],['#0f8b6d','#4cd1a3'],['#c0562b','#f2a65a'],['#b0306a','#f06fa4'],['#2b3a8c','#5a7bf0'],['#2e7d8c','#58c3d6'],['#7a5c12','#e0b84a']];
/* Imagen de respaldo: ilustración por categoría (se usa si no existe la foto en img/) */
function art(d) {
  const n = d.n || '', c = (d.c || '').toLowerCase();
  let k = 0; for (const ch of n) k = (k * 31 + ch.charCodeAt(0)) >>> 0;
  const [a, b] = PALETA[k % PALETA.length], W = 'fill="#fff" fill-opacity=".93"';
  let ic;
  if (c.includes('celular')) ic = `<rect x="155" y="38" width="90" height="190" rx="16" ${W}/><rect x="164" y="54" width="72" height="150" rx="6" fill="${a}" fill-opacity=".55"/><circle cx="200" cy="216" r="5" fill="${a}"/>`;
  else if (c.includes('comput')) ic = `<rect x="110" y="58" width="180" height="120" rx="9" ${W}/><rect x="120" y="68" width="160" height="100" rx="4" fill="${a}" fill-opacity=".55"/><path d="M80 190h240l-14 24H94z" ${W}/>`;
  else if (c.includes('audio')) ic = `<path d="M120 160a80 80 0 0 1 160 0" fill="none" stroke="#fff" stroke-opacity=".93" stroke-width="14" stroke-linecap="round"/><rect x="104" y="150" width="34" height="68" rx="14" ${W}/><rect x="262" y="150" width="34" height="68" rx="14" ${W}/>`;
  else if (c.includes('gaming')) ic = `<rect x="105" y="85" width="190" height="105" rx="46" ${W}/><path d="M146 120v36M128 138h36" stroke="${a}" stroke-width="9" stroke-linecap="round"/><circle cx="246" cy="128" r="9" fill="${a}"/><circle cx="268" cy="150" r="9" fill="${b}"/>`;
  else if (c.includes('hogar')) ic = `<path d="M110 150l90-72 90 72v78H110z" ${W}/><rect x="176" y="170" width="48" height="58" rx="6" fill="${a}" fill-opacity=".6"/>`;
  else ic = `<path d="M120 100l80-38 80 38v90l-80 38-80-38z" ${W}/><path d="M120 100l80 38 80-38M200 138v90" stroke="${a}" stroke-width="6" fill="none"/>`;
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="${a}"/><stop offset="1" stop-color="${b}"/></linearGradient></defs><rect width="400" height="300" fill="url(#g)"/><circle cx="335" cy="45" r="90" fill="#fff" fill-opacity=".07"/><circle cx="55" cy="285" r="110" fill="#fff" fill-opacity=".06"/>${ic}<text x="200" y="274" text-anchor="middle" font-family="Arial,sans-serif" font-size="22" font-weight="700" fill="#fff" fill-opacity=".92">${esc(d.m || '')}</text></svg>`;
  return 'data:image/svg+xml,' + encodeURIComponent(svg);
}
const dat = p => `data-n="${esc(p.nombre)}" data-c="${esc(p.categoria)}" data-m="${esc(p.marca)}"`;
document.addEventListener('error', e => {
  const i = e.target;
  if (i.tagName === 'IMG' && i.dataset.n !== undefined && !i.dataset.fb) { i.dataset.fb = 1; i.src = art(i.dataset); }
}, true);

let user = null, cats = [], prods = [];
try { user = JSON.parse(localStorage.getItem('currentUser')); } catch (e) {}

function toast(msg, err) {
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' err' : '');
  t.textContent = msg;
  $('#toasts').append(t);
  setTimeout(() => t.remove(), 3500);
}

<<<<<<< HEAD
/* ---------- Cargando: círculo giratorio global ---------- */
const Loader = (() => {
  let n = 0, timer = null, shownAt = 0;
  const box = () => $('#loader');
  const paint = on => { box().classList.toggle('on', on); box().setAttribute('aria-hidden', on ? 'false' : 'true'); };
  return {
    start(msg, delay = 150) {            // delay: evita parpadeos si la carga es rápida
      if (msg) $('#loaderMsg').textContent = msg;
      if (++n === 1) timer = setTimeout(() => { shownAt = Date.now(); paint(true); }, delay);
    },
    stop() {
      if (n > 0) n--;
      if (n > 0) return;
      clearTimeout(timer);
      if (box().classList.contains('on'))   // una vez visible, se queda al menos 300 ms
        setTimeout(() => { if (n === 0) { paint(false); $('#loaderMsg').textContent = 'Cargando…'; } }, Math.max(0, 300 - (Date.now() - shownAt)));
    },
    async track(p, msg, delay = 0) { this.start(msg, delay); try { return await p; } finally { this.stop(); } }
  };
})();
const sleep = ms => new Promise(r => setTimeout(r, ms));
/* Botón con círculo giratorio mientras dura una promesa */
async function busy(btn, p) {
  if (btn) { btn.classList.add('loading'); btn.disabled = true; }
  try { return await p; } finally { if (btn) { btn.classList.remove('loading'); btn.disabled = false; } }
}

/* Cliente API: GET para lecturas, POST (FormData) para escrituras */
const API_SILENT = new Set(['obtener_ciudades', 'sesion']);   // sin spinner (autocompletado / verificación)
=======
<<<<<<< HEAD
/* ---------- Overlay de sesión ---------- */
const sleep = ms => new Promise(r => setTimeout(r, ms));
function fxLoading(txt, sub = 'Un momento por favor…') {
  const f = $('#authFx'); f.classList.remove('done');
  $('#afxTxt').textContent = txt; $('#afxSub').textContent = sub; f.hidden = false;
}
async function fxDone(txt, sub = '') {
  const f = $('#authFx');
  if (f.hidden) f.hidden = false;
  f.classList.remove('done'); void f.offsetWidth;   // reinicia la animación del check
  f.classList.add('done');
  $('#afxTxt').textContent = txt; $('#afxSub').textContent = sub;
  await sleep(1500);
  f.hidden = true; f.classList.remove('done');
}
const fxHide = () => { const f = $('#authFx'); f.hidden = true; f.classList.remove('done'); };

/* Cliente API: GET para lecturas, POST (FormData) para escrituras */
=======
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc
async function api(action, params = {}, post = false) {
  const spin = !API_SILENT.has(action);
  if (spin) Loader.start(null, 250);     // solo aparece si la respuesta tarda más de 250 ms
  try {
    let res;
    if (post) {
      const fd = new FormData();
      fd.append('action', action);
      Object.entries(params).forEach(([k, v]) => v !== undefined && v !== null && fd.append(k, v));
      res = await fetch('api.php', { method: 'POST', body: fd });
    } else {
      res = await fetch('api.php?' + new URLSearchParams({ action, ...params }));
    }
    return await res.json();
  } catch (e) {
    return { success: false, error: 'No se pudo conectar con el servidor.' };
  } finally {
    if (spin) Loader.stop();
  }
}
let countryOptions = null;
const cityOptionsCache = new Map();

async function loadCountries() {
  if (countryOptions) return countryOptions;
  const codes = 'AF AL DZ AD AO AG AR AM AU AT AZ BS BD BB BY BE BZ BJ BT BO BA BW BR BN BG BF BI CV KH CM CA CF TD CL CN CO KM CG CR CI HR CU CY CZ DK DJ DM DO EC EG SV GQ ER EE SZ ET FJ FI FR GA GM GE DE GH GR GD GT GN GW GY HT HN HU IS IN ID IR IQ IE IL IT JM JP JO KZ KE KI KP KR KW KG LA LV LB LS LR LY LI LT LU MG MW MY MV ML MT MH MR MU MX FM MD MC MN ME MA MZ MM NA NR NP NL NZ NI NE NG MK NO OM PK PW PA PG PY PE PH PL PT QA RO RU RW KN LC VC WS SM ST SA SN RS SC SL SG SK SI SB SO ZA SS ES LK SD SR SE CH SY TJ TZ TH TL TG TO TT TN TR TM TV UG UA AE GB US UY UZ VU VA VE VN YE ZM ZW';
  const displayNames = new Intl.DisplayNames(['es'], { type: 'region' });
  countryOptions = codes.split(' ').map(code => {
    try { return displayNames.of(code); } catch (e) { return code; }
  }).filter(Boolean).sort((a, b) => a.localeCompare(b, 'es'));
  return countryOptions;
}

async function loadCities(country, query = '') {
  if (!country || !query.trim()) return [];
  const key = country + '\0' + query.trim().toLowerCase();
  if (cityOptionsCache.has(key)) return cityOptionsCache.get(key);
  const r = await api('obtener_ciudades', { pais: country, q: query.trim() });
  const cities = r.success ? r.data : [];
  cityOptionsCache.set(key, cities);
  return cities;
}

function bindAddressAutocomplete(input, getOptions, countryInput = null, clearOnCountryChange = false) {
  if (!input || input.dataset.autocompleteBound) return;
  input.dataset.autocompleteBound = '1';
  input.parentElement.classList.add('autocomplete');
  const list = document.createElement('div');
  list.className = 'autocomplete-list';
  list.hidden = true;
  input.parentElement.appendChild(list);
  let active = -1;
  let options = [];
  let lastCountry = countryInput ? countryInput.value : '';

  const closeList = () => { list.hidden = true; active = -1; };
  const show = values => {
    options = values.slice(0, 12);
    active = -1;
    list.innerHTML = options.map(value => `<button type="button">${esc(value)}</button>`).join('');
    list.hidden = options.length === 0;
    [...list.children].forEach((button, index) => {
      button.onmousedown = e => e.preventDefault();
      button.onclick = () => selectOption(index);
    });
  };
  const selectOption = index => {
    if (!options[index]) return;
    input.value = options[index];
    closeList();
    input.dispatchEvent(new Event('input', { bubbles: true }));
    input.dispatchEvent(new Event('change', { bubbles: true }));
  };
  const refreshList = async () => {
    const query = input.value.trim().toLowerCase();
    const values = await getOptions();
    show(values.filter(value => !query || value.toLowerCase().includes(query)));
  };
  input.addEventListener('input', refreshList);
  input.addEventListener('focus', refreshList);
  input.onkeydown = e => {
    const buttons = [...list.children];
    if (!buttons.length) return;
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
      e.preventDefault();
      active += e.key === 'ArrowDown' ? 1 : -1;
      if (active < 0) active = buttons.length - 1;
      if (active >= buttons.length) active = 0;
      buttons.forEach((button, index) => button.classList.toggle('active', index === active));
      buttons[active].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'Enter' && active >= 0) {
      e.preventDefault();
      selectOption(active);
    } else if (e.key === 'Escape') closeList();
  };
  input.onblur = () => setTimeout(closeList, 120);

  if (countryInput && clearOnCountryChange) {
    countryInput.addEventListener('input', () => {
      if (lastCountry !== countryInput.value) {
        input.value = '';
        input.dispatchEvent(new Event('input', { bubbles: true }));
        lastCountry = countryInput.value;
      }
    });
  }
}

function setupAddressAutocomplete(prefix) {
  const country = $('#' + prefix + 'Pais');
  const city = $('#' + prefix + 'Ciudad');
  if (!country || !city) return;
  bindAddressAutocomplete(country, loadCountries);
  bindAddressAutocomplete(city, async () => loadCities(country.value.trim(), city.value.trim()), country, true);
}
setupAddressAutocomplete('r');
setupAddressAutocomplete('mp');
setupAddressAutocomplete('u');
const open = id => $('#' + id).classList.add('on');
const close = id => $('#' + id).classList.remove('on');
$$('[data-close]').forEach(b => b.onclick = () => b.closest('.modal').classList.remove('on'));
$$('.modal').forEach(m => m.addEventListener('mousedown', e => { if (e.target === m) m.classList.remove('on'); }));
document.addEventListener('keydown', e => { if (e.key === 'Escape') $$('.modal.on').forEach(m => m.classList.remove('on')); });

/* ---------- Dinero digital (saldo simulado, NO es dinero real) ---------- */
const PAY_DIGITAL = 'Dinero digital';   // debe coincidir con METODO_DIGITAL del backend
const RECARGA_MAX = 10000000;           // mismo tope por recarga que el backend
let saldo = null;                       // null = aún no se ha consultado
const fechaCorta = f => f ? String(f).slice(0, 16).replace('T', ' ') : '';
function paintSaldo() {
  $('#navWallet').hidden = !user;
  const txt = saldo === null ? '…' : money(saldo);
  $('#walletAmt').textContent = txt;
  $('#wBig').textContent = saldo === null ? '—' : txt;
  if (typeof paintPayInfo === 'function') paintPayInfo();
}
async function refreshSaldo() {
  if (!user) { saldo = null; paintSaldo(); return null; }
  const r = await api('saldo_digital');
  if (r.success) { const antes = saldo; saldo = +r.saldo; if (antes !== null && antes !== saldo) { const b = $('#wBig'); b.classList.remove('bump'); void b.offsetWidth; b.classList.add('bump'); } }
  paintSaldo(); return saldo;
}
async function recargar(monto) {
  monto = Math.round(+monto);
  if (!(monto > 0)) { toast('Escribe un monto válido', true); return false; }
  if (monto > RECARGA_MAX) { toast('La recarga máxima por operación es ' + money(RECARGA_MAX), true); return false; }
  const r = await api('recargar_saldo', { monto }, true);
  if (!r.success) { toast(r.error || 'No se pudo recargar', true); return false; }
  saldo = +r.saldo; paintSaldo(); toast(`Recarga de ${money(monto)} realizada`);
  if ($('#v-profile').classList.contains('on')) loadMovs();
  return true;
}
const MOV = { RECARGA: ['Recarga', 'ok', 'in'], REEMBOLSO: ['Reembolso', 'ok', 'in'], PAGO: ['Pago de pedido', 'y', 'out'] };
async function loadMovs() {
  const r = await api('movimientos_saldo');
  const rows = r.success ? r.data : [];
  $('#wMovs').innerHTML = rows.length ? rows.map(m => {
    const [nom, pill, dir] = MOV[m.tipo] || [m.tipo, '', 'in'];
    return `<tr><td>${esc(fechaCorta(m.fecha))}</td>
      <td><span class="pill ${pill}">${esc(nom)}</span>${m.id_pedido ? ` <span class="sub">pedido #${+m.id_pedido}</span>` : ''}</td>
      <td class="mv ${dir}">${dir === 'in' ? '+' : '−'}${money(m.monto)}</td><td>${money(m.saldo_posterior)}</td>
      <td>${m.comprobante ? `<button class="btn ghost sm" onclick="verComprobante(${+m.id_pedido})">${esc(m.comprobante)}</button>` : '—'}</td></tr>`;
  }).join('') : '<tr><td colspan="5" style="text-align:center;color:var(--mut);padding:2rem">Aún no tienes movimientos.</td></tr>';
}
async function verComprobante(idPedido) {
  const r = await api('obtener_comprobante', { id_pedido: idPedido });
  if (!r.success) return toast(r.error || 'No se pudo cargar el comprobante', true);
  const c = r.data;
  $('#compBody').innerHTML = `<div class="rcpt-h"><b>${esc($('#storeTitle').textContent)}</b><span class="sub">Comprobante de pago virtual · dinero digital</span><div class="rcpt-n">${esc(c.numero)}</div></div>
    <div class="rcpt-i"><div><span>Cliente:</span> ${esc(c.cliente)}</div><div><span>Correo:</span> ${esc(c.correo)}</div><div><span>Pedido:</span> #${+c.id_pedido} · <span class="pill ${ESTADO[c.estado] || ''}">${esc(c.estado)}</span></div><div><span>Fecha:</span> ${esc(fechaCorta(c.fecha_emision))}</div><div><span>Método de pago:</span> ${esc(c.metodo_pago)}</div></div>
    <div class="tw"><table style="min-width:0"><thead><tr><th>Producto</th><th>Cant.</th><th>Subtotal</th></tr></thead><tbody>${c.items.map(i => `<tr><td>${esc(i.producto)}<div class="sub">${money(i.precio_unitario)} c/u</div></td><td>${+i.cantidad}</td><td>${money(i.subtotal)}</td></tr>`).join('')}</tbody></table></div>
    <div class="rcpt-t"><span>Total pagado</span><span>${money(c.monto)}</span></div>
    <p class="rcpt-w">Documento simulado con fines académicos. No tiene validez fiscal.</p>`;
  open('mComp');
}
$('#compPrint').onclick = () => window.print();
$('#navWallet').onclick = () => show('profile');
$('#wQuick').onclick = e => { const b = e.target.closest('[data-m]'); if (b) recargar(b.dataset.m); };
$('#fRecarga').onsubmit = async e => {
  e.preventDefault();
  const btn = $('#wBtn'); btn.disabled = true;
  if (await recargar($('#wMonto').value)) $('#fRecarga').reset();
  btn.disabled = false;
};

/* ---------- Navegación ---------- */
function show(v) {
  if (v === 'admin' && !(user && user.rol === 'ADMINISTRADOR')) return toast('Solo los administradores pueden entrar al panel', true);
  if ((v === 'cart' || v === 'orders' || v === 'profile') && !user) return openAuth();
  $('#home').hidden = v !== 'catalog';
  scrollTo(0, 0);
  $$('.view').forEach(s => s.classList.toggle('on', s.id === 'v-' + v));
  $$('nav [data-v]').forEach(b => b.classList.toggle('on', b.dataset.v === v));
  const jobs = [sleep(250)];             // el círculo se ve al menos un instante al cambiar de sección
  if (v === 'orders') jobs.push(loadOrders());
  if (v === 'profile') jobs.push(loadProfile(), refreshSaldo(), loadMovs());
  if (v === 'cart') jobs.push(loadCart());
  if (v === 'admin') jobs.push(loadAdmin());
  Loader.track(Promise.all(jobs), 'Cargando…', 0);
}
$$('nav [data-v]').forEach(b => b.onclick = () => show(b.dataset.v));
$$('.tabs [data-t]').forEach(b => b.onclick = () => {
  $$('#v-admin .tabs button').forEach(x => x.classList.toggle('on', x === b));
  $$('.panel').forEach(p => p.classList.toggle('on', p.id === 't-' + b.dataset.t));
});

/* ---------- Sesión ---------- */
function paintUser() {
  $('#navLogin').hidden = !!user;
  $('#navReg').hidden = !!user;
  $('#navLogout').hidden = !user;
  $('#navOrders').hidden = !user;
  $('#navProfile').hidden = !user;
  $('#navAdmin').hidden = !(user && user.rol === 'ADMINISTRADOR');
  $('#navWallet').hidden = !user;
}
function authTab(t) {
  $$('#authTabs button').forEach(b => b.classList.toggle('on', b.dataset.a === t));
  $('#fAuth').hidden = t !== 'login'; $('#fReg').hidden = t !== 'reg';
}
function openAuth(t = 'login') { authTab(t); open('mAuth'); }
$$('#authTabs button').forEach(b => b.onclick = () => authTab(b.dataset.a));
$('#navLogin').onclick = () => openAuth('login');
$('#navReg').onclick = () => openAuth('reg');
<<<<<<< HEAD
$('#navLogout').onclick = () => { api('logout', {}, true); user = null; cart = []; cartAddress = { pais: '', ciudad: '', barrio: '', direccion: '' }; saldo = null; paintSaldo(); paintBadge(); localStorage.removeItem('currentUser'); paintUser(); show('catalog'); toast('Sesión cerrada'); };

function startSession(u) {
  user = u; localStorage.setItem('currentUser', JSON.stringify(u));
  close('mAuth'); paintUser(); refreshCart(); refreshSaldo(); toast(`¡Bienvenido, ${u.nombre}!`);
=======
<<<<<<< HEAD
$('#navLogout').onclick = async () => {
  fxLoading('Cerrando sesión…');
  await Promise.all([api('logout', {}, true), sleep(900)]);
  user = null; cart = []; cartAddress = { pais: '', ciudad: '', barrio: '', direccion: '' }; saldo = null;
  paintSaldo(); paintBadge(); localStorage.removeItem('currentUser'); paintUser(); show('catalog');
  await fxDone('Sesión cerrada', '¡Hasta pronto!');
=======

$('#navLogout').onclick = () => {
  runActionFlow(
    "Cerrando sesión…",
    "Guardando tu información de forma segura.",
    "Sesión cerrada",
    "¡Esperamos verte pronto!",
    async () => {
      await api('logout', {}, true);
      user = null; cart = []; 
      cartAddress = { pais: '', ciudad: '', barrio: '', direccion: '' }; 
      paintBadge(); 
      localStorage.removeItem('currentUser'); 
      paintUser(); 
      show('catalog'); 
      return true;
    }
  );
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
};

async function startSession(u) {
  user = u; localStorage.setItem('currentUser', JSON.stringify(u));
<<<<<<< HEAD
  close('mAuth'); paintUser(); refreshCart(); refreshSaldo();
  await fxDone('¡Sesión iniciada!', `Bienvenido, ${u.nombre}`);
=======
  close('mAuth'); paintUser(); refreshCart();
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc
}
async function doLogin(correo, password) {
<<<<<<< HEAD
  const r = await Loader.track(api('login', { correo, password }, true), 'Iniciando sesión…');
  if (!r.success) { toast(r.error || 'Correo o contraseña incorrectos', true); return false; }
  startSession(r.user); return true;
=======
<<<<<<< HEAD
  fxLoading('Iniciando sesión…', 'Verificando tus datos');
  const [r] = await Promise.all([api('login', { correo, password }, true), sleep(900)]);
  if (!r.success) { fxHide(); toast(r.error || 'Correo o contraseña incorrectos', true); return false; }
  await startSession(r.user); return true;
=======
  let loginUser = null;
  runActionFlow(
    "Iniciando sesión…",
    "Comprobando credenciales de acceso.",
    "¡Bienvenido de nuevo!",
    "Acceso concedido con éxito.",
    async () => {
      const r = await api('login', { correo, password }, true);
      if (!r.success) { 
        toast(r.error || 'Correo o contraseña incorrectos', true); 
        return false; 
      }
      loginUser = r.user;
      startSession(loginUser);
      return true;
    }
  );
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc
}
$('#fAuth').onsubmit = e => {
  e.preventDefault();
  busy($('#fAuth button:not([data-close])'), doLogin($('#lEmail').value.trim(), $('#lPass').value));
};
$('#fReg').onsubmit = async e => {
  e.preventDefault();
  if ($('#rPass').value !== $('#rPass2').value) return toast('Las contraseñas no coinciden', true);
  if (!$('#rTerms').checked) { toast('Debes leer y aceptar los términos y condiciones', true); return openTerms(true); }
  const correo = $('#rEmail').value.trim(), password = $('#rPass').value;
<<<<<<< HEAD
  const btn = $('#fReg button:not([data-close])');
  const r = await busy(btn, Loader.track(api('registrar', {
    nombre: $('#rNom').value.trim(), apellido: $('#rApe').value.trim(), correo, password,
    telefono: $('#rTel').value.trim(), pais: $('#rPais').value.trim(), ciudad: $('#rCiudad').value.trim(),
    barrio: $('#rBarrio').value.trim(), direccion: $('#rDir').value.trim(), terminos: 1
  }, true), 'Creando tu cuenta…'));
  if (!r.success) return toast(r.error || 'No se pudo crear la cuenta', true);
  $('#fReg').reset();
  toast('Cuenta creada');
  r.user ? startSession(r.user) : doLogin(correo, password);
=======
<<<<<<< HEAD
  const r = await api('registrar', {
    nombre: $('#rNom').value.trim(), apellido: $('#rApe').value.trim(), correo, password,
    telefono: $('#rTel').value.trim(), pais: $('#rPais').value.trim(), ciudad: $('#rCiudad').value.trim(),
    barrio: $('#rBarrio').value.trim(), direccion: $('#rDir').value.trim()
  }, true);
  if (!r.success) return toast(r.error || 'No se pudo crear la cuenta', true);
  $('#fReg').reset();
  r.user ? await startSession(r.user) : await doLogin(correo, password);
=======
  
  runActionFlow(
    "Creando tu cuenta…",
    "Registrando la información en el sistema.",
    "¡Cuenta creada!",
    "Tu perfil ha sido registrado correctamente.",
    async () => {
      const r = await api('registrar', {
        nombre: $('#rNom').value.trim(), apellido: $('#rApe').value.trim(), correo, password,
        telefono: $('#rTel').value.trim(), pais: $('#rPais').value.trim(), ciudad: $('#rCiudad').value.trim(),
        barrio: $('#rBarrio').value.trim(), direccion: $('#rDir').value.trim()
      }, true);
      
      if (!r.success) {
        toast(r.error || 'No se pudo crear la cuenta', true);
        return false;
      }
      $('#fReg').reset();
      chkTerms.checked = false;
      btnSubmitReg.disabled = true;
      if (r.user) startSession(r.user);
      return true;
    }
  );
>>>>>>> f4d5a969e7737d6546d525e8cd7dc68501499691
>>>>>>> 29f6c4f36158eda83b5be6e67a7374130b367fbc
};

/* ---------- Términos y condiciones ---------- */
function termsEnd() {                      // ¿llegó al final del texto?
  const b = $('#termsBox');
  if (b.scrollTop + b.clientHeight >= b.scrollHeight - 8) { $('#termsAccept').disabled = false; $('#termsHint').hidden = true; }
}
function openTerms(forReg = false) {       // forReg: viene del registro → debe leer hasta el final para aceptar
  const b = $('#termsBox'); b.scrollTop = 0;
  $('#termsAccept').hidden = !forReg; $('#termsAccept').disabled = true;
  $('#termsHint').hidden = !forReg;
  $('#termsClose').textContent = forReg ? 'Cancelar' : 'Cerrar';
  open('mTerms'); termsEnd(); b.focus();
}
$('#termsBox').onscroll = termsEnd;
$('#termsClose').onclick = () => close('mTerms');
$('#termsAccept').onclick = () => { $('#rTerms').checked = true; close('mTerms'); toast('Términos y condiciones aceptados'); };
$('#lnkTerms').onclick = () => openTerms(true);
$('#footTerms').onclick = () => openTerms(false);
$('#rTerms').onclick = e => { if (e.target.checked) { e.preventDefault(); openTerms(true); } };   // para marcarla hay que leer

/* ---------- Catálogo público ---------- */
const isHex = h => /^#[0-9a-fA-F]{6}$/.test(h || '');
function shade(hex, f) {   // f < 0 oscurece, f > 0 aclara
  const n = parseInt(hex.slice(1), 16);
  return '#' + [n >> 16, (n >> 8) & 255, n & 255].map(v => Math.round(f < 0 ? v * (1 + f) : v + (255 - v) * f).toString(16).padStart(2, '0')).join('');
}
const lum = hex => { const n = parseInt(hex.slice(1), 16); return (0.299 * (n >> 16) + 0.587 * ((n >> 8) & 255) + 0.114 * (n & 255)) / 255; };
async function loadStore() {
  const r = await api('obtener_tienda');
  if (!(r.success && r.data)) return;
  const d = r.data, root = document.documentElement.style, hero = $('.hero');
  $('#storeTitle').textContent = $('#bannerTitle').textContent = d.nombre_tienda;
  $('#bannerDesc').textContent = d.descripcion_tienda || '';
  $('#footName').textContent = d.nombre_tienda;
  $('#footContact').textContent = [d.correo_contacto, d.telefono_contacto].filter(Boolean).join(' · ');
  document.title = d.nombre_tienda + ' · Catálogo';
  const c1 = isHex(d.color_primario) ? d.color_primario : '#1F4E79', c2 = isHex(d.color_secundario) ? d.color_secundario : '#FFF2CC';
  root.setProperty('--brand', c1); root.setProperty('--brand-d', shade(c1, -.25));
  root.setProperty('--brand-dd', shade(c1, -.45)); root.setProperty('--brand-l', shade(c1, .25));
  root.setProperty('--sand', c2); root.setProperty('--accent', c2); root.setProperty('--acc', lum(c1) > .5 ? c1 : shade(c1, .55));
  const lg = $('#storeLogo');
  lg.onerror = () => { lg.hidden = true; };
  if (d.logo_url) { lg.src = d.logo_url; lg.hidden = false; } else lg.hidden = true;
  const ban = d.banner_url && !/["'()\\\s]/.test(d.banner_url) ? d.banner_url : '';
  hero.style.backgroundImage = ban ? `linear-gradient(135deg, ${shade(c1, -.45)}e6, ${c1}b3), url("${ban}")` : '';
  hero.style.backgroundSize = ban ? 'cover' : ''; hero.style.backgroundPosition = ban ? 'center' : '';
}
async function loadCats() {
  const r = await api('listar_categorias');
  cats = r.success ? r.data : [];
  const opts = cats.map(c => `<option value="${esc(c.id_categoria)}">${esc(c.nombre)}</option>`).join('');
  $('#pCat').innerHTML = opts;
  $('#fCat').innerHTML = '<option value="">Todas las categorías</option>' + opts;
}
let cur = [], curCat = '';
function renderCatalog() {
  const cs = [...new Set(cur.map(p => p.categoria).filter(Boolean))];
  $('#chips').innerHTML = ['', ...cs].map(c => `<button class="chip ${c === curCat ? 'on' : ''}" data-c="${esc(c)}">${c ? esc(c) : 'Todos'}</button>`).join('');
  const s = $('#sortSel').value;
  const list = cur.filter(p => !curCat || p.categoria === curCat).sort(
    s === 'asc' ? (a, b) => a.precio - b.precio : s === 'desc' ? (a, b) => b.precio - a.precio : s === 'name' ? (a, b) => a.nombre.localeCompare(b.nombre) : () => 0);
  $('#count').textContent = list.length + (list.length === 1 ? ' producto' : ' productos');
  $('#productGrid').innerHTML = list.length ? list.map((p, ix) => `
    <article class="card" style="--i:${Math.min(ix, 12)}">
      <div class="ph" onclick="showProduct(${+p.id_producto})">
        <img src="${esc(p.imagen_url || 'img/default.jpg')}" alt="${esc(p.nombre)}" loading="lazy" ${dat(p)}>
        ${+p.destacado ? '<span class="tag">Destacado</span>' : ''}${+p.stock > 0 && +p.stock <= 5 ? '<span class="tag low">Últimas unidades</span>' : ''}
      </div>
      <div class="b">
        <div class="m">${esc(p.marca)}</div>
        <h3 onclick="showProduct(${+p.id_producto})">${esc(p.nombre)}</h3>
        <div class="p">${money(p.precio)}</div>
        <div class="m">${esc(p.categoria)} · ${+p.stock > 0 ? p.stock + ' disponibles' : 'Agotado'}</div>
      </div>
      <div class="f"><button class="btn ghost full" onclick="showProduct(${+p.id_producto})">Ver detalles</button><button class="btn ok full" ${+p.stock > 0 ? '' : 'disabled'} onclick="addCart(${+p.id_producto})">Agregar al carrito</button></div>
    </article>`).join('') : '<div class="empty">No encontramos productos con esos criterios.</div>';
}
async function loadList(action, params) {
  const r = await api(action, params);
  if (!r.success) return toast(r.error || 'Error al cargar productos', true);
  cur = r.data; curCat = ''; renderCatalog();
}
$('#chips').onclick = e => { const b = e.target.closest('.chip'); if (b) { curCat = b.dataset.c; renderCatalog(); } };
$('#sortSel').onchange = renderCatalog;
$('#goCat').onclick = () => $('#v-catalog').scrollIntoView({ behavior: 'smooth' });
$('#goFeat').onclick = () => { $('#bFeat').click(); $('#v-catalog').scrollIntoView({ behavior: 'smooth' }); };
function heroArt(list) {
  $('#heroArt').innerHTML = list.slice(0, 3).map((p, i) => `<div class="hc hc${i}"><img src="${esc(p.imagen_url || 'img/default.jpg')}" alt="" ${dat(p)}><div><b>${esc(p.nombre)}</b><span>${money(p.precio)}</span></div></div>`).join('');
}
$('#bSearch').onclick = () => loadList('buscar_productos', { q: $('#searchInput').value });
$('#searchInput').onkeydown = e => { if (e.key === 'Enter') $('#bSearch').click(); };
$('#bAll').onclick = () => loadList('obtener_vista_catalogo');
$('#bFeat').onclick = () => loadList('obtener_destacados');

/* ---------- Carrito ---------- */
let termsOk = false;   // aceptación de términos (se pide en el registro y antes de confirmar el pedido)
let cart = [], cartAddress = { pais: '', ciudad: '', barrio: '', direccion: '' }, cartPay = 'Tarjeta crédito';
function paintBadge() {
  const n = cart.reduce((s, i) => s + +i.cantidad, 0), b = $('#cartBadge');
  b.textContent = n; b.hidden = !n; paintDrawer();
}
async function refreshCart() {
  if (!user) { cart = []; paintBadge(); return; }
  const r = await api('ver_carrito');
  cart = r.success ? r.data : [];
  if (r.success && !Object.values(cartAddress).some(Boolean)) {
    cartAddress = { pais: r.pais || '', ciudad: r.ciudad || '', barrio: r.barrio || '', direccion: r.direccion || '' };
  }
  paintBadge();
}
async function loadCart() { await Promise.all([refreshCart(), refreshSaldo()]); paintCart(); }
function paintCart() {
  const box = $('#cartBox');
  if (!cart.length) {
    box.innerHTML = '<div class="empty" style="padding:3rem 1rem"><p style="font-size:1.1rem;margin-bottom:1rem">Tu carrito está vacío</p><button class="btn" onclick="show(\'catalog\')">Ir al catálogo</button></div>';
    return;
  }
  const total = cart.reduce((s, i) => s + +i.subtotal, 0), units = cart.reduce((s, i) => s + +i.cantidad, 0);
  box.innerHTML = `<div class="cartwrap"><div>
    ${cart.map(i => `<div class="ci">
      <img src="${esc(i.imagen_url || 'img/default.jpg')}" alt="" ${dat(i)}>
      <div class="ci-t"><strong>${esc(i.nombre)}</strong><span>${esc(i.marca)} · ${esc(i.categoria)}</span><span>${money(i.precio)} c/u</span></div>
      <div class="qty"><button aria-label="Quitar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad - 1})">−</button><span>${+i.cantidad}</span><button aria-label="Agregar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad + 1})" ${+i.cantidad >= +i.stock ? 'disabled' : ''}>+</button></div>
      <div class="ci-s">${money(i.subtotal)}</div>
      <button class="x" aria-label="Quitar ${esc(i.nombre)} del carrito" onclick="setQty(${+i.id_producto},0)">✕</button>
    </div>`).join('')}
    <div class="ci-foot"><button class="btn ghost sm" onclick="show('catalog')">← Seguir comprando</button><button class="btn ghost sm" onclick="askDelete('carrito',0,'')">Vaciar carrito</button></div>
  </div>
  <aside class="sum"><h3>Resumen del pedido</h3>
    <div class="line"><span>Productos (${units})</span><span>${money(total)}</span></div>
    <div class="line tot"><span>Total</span><span>${money(total)}</span></div>
    <div class="row2">
      <div class="fg"><label for="cPais">País</label><input id="cPais" maxlength="100" value="${esc(cartAddress.pais)}" required oninput="cartAddress.pais=this.value"></div>
      <div class="fg"><label for="cCiudad">Ciudad</label><input id="cCiudad" maxlength="100" value="${esc(cartAddress.ciudad)}" required oninput="cartAddress.ciudad=this.value"></div>
    </div>
    <div class="fg"><label for="cBarrio">Barrio</label><input id="cBarrio" maxlength="100" value="${esc(cartAddress.barrio)}" required oninput="cartAddress.barrio=this.value"></div>
    <div class="fg"><label for="cDir">Dirección</label><input id="cDir" maxlength="255" value="${esc(cartAddress.direccion)}" required oninput="cartAddress.direccion=this.value"></div>
    <div class="fg"><label for="cPay">Método de pago</label><select id="cPay" onchange="cartPay=this.value;paintPayInfo()">${['Tarjeta crédito', 'PSE', 'Nequi', 'Contraentrega', PAY_DIGITAL].map(m => `<option ${m === cartPay ? 'selected' : ''}>${m}</option>`).join('')}</select></div>
    <div id="payInfo" aria-live="polite"></div>
    <div class="fg chk terms-row" id="cTermsRow"><label><input type="checkbox" id="cTerms" ${termsOk ? 'checked' : ''} onchange="termsOk=this.checked"><span>He leído y acepto los <button type="button" class="linkbtn" data-terms>términos y condiciones</button></span></label></div>
    <button class="btn ok full" id="bCheckout" onclick="checkout()">Confirmar pedido</button>
  </aside></div>`;
  setupAddressAutocomplete('c');
  paintPayInfo();
}
async function addCart(id) {
  if (!user) { toast('Inicia sesión para usar el carrito', true); return openAuth(); }
  const r = await api('agregar_carrito', { id_producto: id, cantidad: 1 }, true);
  if (!r.success) return toast(r.error, true);
  await refreshCart(); flyToCart(); setTimeout(() => confirmAdd(id), flyDone ? 520 : 0);
}
async function setQty(id, n) {   // n <= 0 quita el producto
  const r = await api('actualizar_carrito', { id_producto: id, cantidad: n }, true);
  if (!r.success) toast(r.error, true); else if (n <= 0) toast('Producto quitado del carrito');
  loadCart();
}
/* Panel informativo cuando se elige "Dinero digital": saldo, total y saldo restante */
function paintPayInfo() {
  const box = $('#payInfo'), btn = $('#bCheckout');
  if (!box || !btn) return;
  const total = cart.reduce((s, i) => s + +i.subtotal, 0);
  if (cartPay !== PAY_DIGITAL) { box.innerHTML = ''; btn.disabled = false; btn.textContent = 'Confirmar pedido'; return; }
  if (saldo === null) { box.innerHTML = '<div class="paybox"><span class="sub">Consultando tu saldo…</span></div>'; btn.disabled = true; return; }
  const falta = total - saldo;
  if (falta > 0) {
    box.innerHTML = `<div class="paybox low"><div class="line"><span>Tu saldo</span><b>${money(saldo)}</b></div><div class="line"><span>Total del pedido</span><b>${money(total)}</b></div><div class="line"><span>Te faltan</span><b style="color:#ff8f86">${money(falta)}</b></div><button type="button" class="btn ghost sm" onclick="recargarFalta(${falta})">Recargar lo que falta</button></div>`;
    btn.disabled = true; btn.textContent = 'Saldo insuficiente';
  } else {
    box.innerHTML = `<div class="paybox ok"><div class="line"><span>Tu saldo</span><b>${money(saldo)}</b></div><div class="line"><span>Total del pedido</span><b>− ${money(total)}</b></div><div class="line"><span>Saldo después de pagar</span><b>${money(saldo - total)}</b></div></div>`;
    btn.disabled = false; btn.textContent = `Pagar ${money(total)} con dinero digital`;
  }
}
async function recargarFalta(falta) {   // redondea hacia arriba a miles y respeta el tope por recarga
  await recargar(Math.min(RECARGA_MAX, Math.ceil(falta / 1000) * 1000));
}
let pagando = false;
async function checkout() {
  if (pagando) return;   // evita doble clic (el backend también lo bloquea)
  if (!termsOk) {   // los términos son obligatorios para confirmar
    const row = $('#cTermsRow');
    if (row) { row.classList.remove('shake'); void row.offsetWidth; row.classList.add('shake'); $('#cTerms').focus(); }
    return toast('Acepta los términos y condiciones para confirmar el pedido', true);
  }
  const btn = $('#bCheckout'), txt = btn ? btn.textContent : '';
  const digital = cartPay === PAY_DIGITAL, tot = cart.reduce((s, i) => s + +i.subtotal, 0);
  pagando = true; if (btn) { btn.disabled = true; btn.textContent = digital ? 'Procesando pago…' : 'Confirmando…'; }
  const r = await api('confirmar_pedido', {
    pais_envio: cartAddress.pais.trim(), ciudad_envio: cartAddress.ciudad.trim(),
    barrio_envio: cartAddress.barrio.trim(), direccion_envio: cartAddress.direccion.trim(),
    metodo_pago: cartPay
  }, true);
  pagando = false;
  if (!r.success) {
    if (btn) { btn.disabled = false; btn.textContent = txt; }
    if (digital && /saldo/i.test(r.error || '')) {   // saldo insuficiente: aviso claro + salida rápida
      await refreshSaldo();
      return notice('Saldo insuficiente', `<p>${esc(r.error)}</p><p class="sub" style="margin-top:.5rem">No se descontó nada ni se reservó stock. Recarga tu saldo o elige otro método de pago.</p>`,
        [['Elegir otro método', 'ghost', () => close('mInfo')], ['Ir a recargar', 'ok', () => { close('mInfo'); show('profile'); }]]);
    }
    return toast(r.error, true);
  }
  cart = []; paintBadge(); loadList('listar_catalogo');
  if (digital && r.comprobante) {
    saldo = +r.saldo; paintSaldo();
    notice('¡Pago realizado!', `<p>Pagaste <b>${money(tot)}</b> con dinero digital. Tu pedido <b>#${r.id_pedido}</b> quedó en estado <b>PAGADO</b>.</p><p style="margin-top:.5rem">Comprobante: <b>${esc(r.comprobante)}</b><br>Saldo restante: <b>${money(r.saldo)}</b></p>`,
      [['Ver mis pedidos', 'ghost', () => { close('mInfo'); show('orders'); }], ['Ver comprobante', 'ok', () => { close('mInfo'); show('orders'); verComprobante(+r.id_pedido); }]]);
  } else {
    notice('¡Pedido confirmado!', `<p>Tu pedido <b>#${r.id_pedido}</b> por <b>${money(tot)}</b> quedó registrado. Puedes seguir su estado en “Mis pedidos”.</p>`);
  }
  show('orders');
}

/* ---------- Pedidos ---------- */
const ESTADO = { PENDIENTE: 'y', PAGADO: 'ok', ENVIADO: 'ok', ENTREGADO: 'ok', CANCELADO: 'low' };
async function loadOrders() {
  if (!user) return;
  const r = await api('pedidos_usuario', { id_usuario: user.id_usuario });
  const rows = (r.success ? r.data : []).filter(o => o.estado !== 'CARRITO');
  $('#ordersBody').innerHTML = rows.length ? rows.map(o => `
    <tr><td>#${esc(o.id_pedido)}</td><td><span class="pill ${ESTADO[o.estado] || ''}">${esc(o.estado)}</span></td><td>${esc(o.fecha_creacion)}</td>
    <td>${esc(o.metodo_pago || 'Pendiente')}</td><td>${money(o.total)}</td>
    <td><div class="acts"><button class="btn ghost sm" onclick="orderDetail(${+o.id_pedido})">Ver detalle</button>${o.metodo_pago === PAY_DIGITAL ? `<button class="btn ghost sm" onclick="verComprobante(${+o.id_pedido})">Comprobante</button>` : ''}${o.estado === 'PENDIENTE' ? `<button class="btn bad sm" onclick="askDelete('pedido',${+o.id_pedido},'')">Cancelar</button>` : ''}</div></td></tr>`).join('')
    : '<tr><td colspan="6" style="text-align:center;color:var(--mut);padding:2rem">Aún no tienes pedidos.</td></tr>';
}
async function orderDetail(id) {
  const r = await api('detalle_pedido', { id_pedido: id });
  if (!r.success) return toast(r.error, true);
  $('#ordTitle').textContent = 'Pedido #' + id; $('#ordInfo').innerHTML = '';
  $('#ordDet').innerHTML = r.data.map(d => `<tr><td>${esc(d.producto)}<div class="m" style="color:var(--mut);font-size:.8rem">${esc(d.marca)} · ${money(d.precio_unitario)} c/u</div></td><td>${esc(d.cantidad)}</td><td>${money(d.subtotal)}</td></tr>`).join('');
  open('mOrd');
}

/* ---------- ADMIN: Productos (CRUD) ---------- */
async function loadAdmin() { await loadCats(); await loadProds(); paintCats(); loadUsers(); loadAdminOrders(); loadTiendaForm(); }

const verElim = { prod: false, cat: false, usr: false };   // ¿el panel muestra activos o eliminados?
async function loadProds() {
  const r = verElim.prod ? await api('listar_eliminados', { tipo: 'producto' }) : await api('admin_productos');
  prods = r.success ? r.data : [];
  paintProds();
}
function paintProds() {
  const q = $('#fProd').value.toLowerCase(), c = $('#fCat').value, del = verElim.prod;
  const list = prods.filter(p => {
    const txt = `${p.nombre} ${p.marca} ${p.categoria}`.toLowerCase();
    const cid = p.id_categoria ?? (cats.find(x => x.nombre === p.categoria) || {}).id_categoria;
    return txt.includes(q) && (!c || String(cid) === c);
  });
  $('#prodBody').innerHTML = list.length ? list.map(p => del ? `
    <tr>
      <td><img class="thumb" src="${esc(p.imagen_url || 'img/default.jpg')}" alt="" ${dat(p)}></td>
      <td><strong>${esc(p.nombre)}</strong><div style="color:var(--mut);font-size:.82rem">${esc(p.marca)}</div></td>
      <td>${esc(p.categoria)}${+p.cat_activa ? '' : ' <span class="pill low">Categoría eliminada</span>'}</td>
      <td>${money(p.precio)}</td><td>${+p.stock}</td><td><span class="pill">Eliminado</span></td>
      <td><button class="btn ok sm" onclick="restaurar('producto',${+p.id_producto})">Restaurar</button></td>
    </tr>` : `
    <tr>
      <td><img class="thumb" src="${esc(p.imagen_url || 'img/default.jpg')}" alt="" ${dat(p)}></td>
      <td><strong>${esc(p.nombre)}</strong><div style="color:var(--mut);font-size:.82rem">${esc(p.marca)}</div></td>
      <td>${esc(p.categoria)}</td>
      <td>${money(p.precio)}</td>
      <td><input class="stock" type="number" min="0" value="${+p.stock}" aria-label="Stock de ${esc(p.nombre)}" onchange="saveStock(this,'${esc(p.nombre).replace(/'/g, '&#39;')}')"></td>
      <td>${+p.destacado ? '<span class="pill y">Destacado</span>' : ''} ${+p.stock <= 5 ? '<span class="pill low">Stock bajo</span>' : ''}</td>
      <td><div class="acts"><button class="btn ghost sm" onclick="editProd(${+p.id_producto})">Editar</button><button class="btn bad sm" onclick="askDelete('producto',${+p.id_producto},'${esc(p.nombre).replace(/'/g, '&#39;')}')">Eliminar</button></div></td>
    </tr>`).join('') : `<tr><td colspan="7" style="text-align:center;color:var(--mut);padding:2rem">${del ? 'No hay productos eliminados.' : 'No hay productos. Crea el primero con “Nuevo producto”.'}</td></tr>`;
}
$('#fProd').oninput = paintProds;
$('#fCat').onchange = paintProds;

async function saveStock(inp, nombre) {
  const r = await api('actualizar_stock', { nombre_producto: nombre, nuevo_stock: inp.value }, true);
  if (r.success) { toast('Stock actualizado'); loadProds(); } else toast(r.error, true);
}

function fillProd(p = {}) {
  $('#pId').value = p.id_producto || '';
  $('#pNom').value = p.nombre || '';
  $('#pMar').value = p.marca || '';
  $('#pCat').value = p.id_categoria ?? (cats.find(x => x.nombre === p.categoria) || cats[0] || {}).id_categoria ?? '';
  $('#pDes').value = p.descripcion || '';
  $('#pEsp').value = p.especificaciones || '';
  $('#pPre').value = p.precio ?? '';
  $('#pSto').value = p.stock ?? '';
  $('#pImg').value = p.imagen_url || 'img/default.jpg';
  $('#pDest').checked = !!+p.destacado;
  $('#pImg').dispatchEvent(new Event('input'));
}
$('#newProd').onclick = () => { $('#mProdTitle').textContent = 'Nuevo producto'; fillProd(); open('mProd'); };
async function editProd(id) {
  let p = prods.find(x => +x.id_producto === id) || {};
  const r = await api('obtener_producto', { id_producto: id });   // trae descripción y especificaciones completas
  if (r.success && r.data) p = { ...p, ...r.data };
  $('#mProdTitle').textContent = 'Editar producto';
  fillProd(p); open('mProd');
}
$('#fProdForm').onsubmit = async e => {
  e.preventDefault();
  const id = $('#pId').value;
  const data = {
    id_categoria: $('#pCat').value, nombre: $('#pNom').value.trim(), marca: $('#pMar').value.trim(),
    descripcion: $('#pDes').value, especificaciones: $('#pEsp').value,
    precio: $('#pPre').value, stock: $('#pSto').value, imagen_url: $('#pImg').value,
    destacado: $('#pDest').checked ? 1 : 0
  };
  if (id) data.id_producto = id;
  const r = await api(id ? 'actualizar_producto' : 'crear_producto', data, true);
  if (!r.success) return toast(r.error || 'No se pudo guardar', true);
  close('mProd'); toast(id ? 'Producto actualizado' : 'Producto creado'); loadProds();
};

/* ---------- ADMIN: Categorías (CRUD) ---------- */
let catsDel = [];
async function loadCatsView() {
  if (verElim.cat) { const r = await api('listar_eliminados', { tipo: 'categoria' }); catsDel = r.success ? r.data : []; }
  paintCats();
}
function paintCats() {
  const del = verElim.cat, list = del ? catsDel : cats;
  $('#catBody').innerHTML = list.length ? list.map(c => `
    <tr><td>${esc(c.id_categoria)}</td><td><strong>${esc(c.nombre)}</strong></td><td>${esc(c.descripcion)}</td>
    <td>${del ? `<button class="btn ok sm" onclick="restaurar('categoria',${+c.id_categoria})">Restaurar</button>` : `<div class="acts"><button class="btn ghost sm" onclick="editCat(${+c.id_categoria})">Editar</button>
    <button class="btn bad sm" onclick="askDelete('categoria',${+c.id_categoria},'${esc(c.nombre).replace(/'/g, '&#39;')}')">Eliminar</button></div>`}</td></tr>`).join('')
    : `<tr><td colspan="4" style="text-align:center;color:var(--mut);padding:2rem">${del ? 'No hay categorías eliminadas.' : 'Sin categorías. Crea una para poder registrar productos.'}</td></tr>`;
}
$('#newCat').onclick = () => { $('#mCatTitle').textContent = 'Nueva categoría'; $('#cId').value = $('#cNom').value = $('#cDes').value = ''; open('mCat'); };
function editCat(id) {
  const c = cats.find(x => +x.id_categoria === id); if (!c) return;
  $('#mCatTitle').textContent = 'Editar categoría';
  $('#cId').value = c.id_categoria; $('#cNom').value = c.nombre; $('#cDes').value = c.descripcion || '';
  open('mCat');
}
$('#fCatForm').onsubmit = async e => {
  e.preventDefault();
  const id = $('#cId').value;
  const data = { nombre: $('#cNom').value.trim(), descripcion: $('#cDes').value };
  if (id) data.id_categoria = id;
  const r = await api(id ? 'actualizar_categoria' : 'crear_categoria', data, true);
  if (!r.success) return toast(r.error || 'No se pudo guardar', true);
  close('mCat'); toast(id ? 'Categoría actualizada' : 'Categoría creada'); await loadCats(); paintCats(); paintProds();
};

/* ---------- ADMIN: Pedidos ---------- */
const FLUJO = { PENDIENTE: ['PAGADO', 'CANCELADO'], PAGADO: ['ENVIADO', 'CANCELADO'], ENVIADO: ['ENTREGADO', 'CANCELADO'] };
let adminOrders = [], pedFiltro = '';
async function loadAdminOrders() {
  const r = await api('admin_pedidos');
  adminOrders = r.success ? r.data : [];
  paintAdminOrders();
}
function paintAdminOrders() {
  const estados = ['PENDIENTE', 'PAGADO', 'ENVIADO', 'ENTREGADO', 'CANCELADO'];
  const n = e => adminOrders.filter(o => !e || o.estado === e).length;
  $('#pedChips').innerHTML = ['', ...estados].map(e => `<button class="chip ${e === pedFiltro ? 'on' : ''}" data-e="${e}">${e ? e[0] + e.slice(1).toLowerCase() : 'Todos'} (${n(e)})</button>`).join('');
  const q = $('#fPed').value.toLowerCase().trim();
  const list = adminOrders.filter(o => (!pedFiltro || o.estado === pedFiltro) && (!q || `${o.id_pedido} ${o.nombre} ${o.apellido} ${o.correo}`.toLowerCase().includes(q)));
  $('#pedBody').innerHTML = list.length ? list.map(o => {
    const sig = FLUJO[o.estado] || [];
    return `<tr>
      <td>#${esc(o.id_pedido)}</td>
      <td><strong>${esc(o.nombre + ' ' + o.apellido)}</strong><div class="sub">${esc(o.correo)}</div></td>
      <td>${esc(o.fecha_creacion)}</td><td>${esc(o.metodo_pago || '—')}</td><td>${money(o.total)}</td>
      <td><span class="pill ${ESTADO[o.estado] || ''}">${esc(o.estado)}</span></td>
      <td><div class="acts"><button class="btn ghost sm" onclick="adminOrderDetail(${+o.id_pedido})">Ver detalle</button>
        ${sig.length ? `<select class="stock" style="width:auto" aria-label="Cambiar estado del pedido ${+o.id_pedido}" onchange="changeOrderState(${+o.id_pedido},this)"><option value="">Cambiar a…</option>${sig.map(x => `<option value="${x}">${x}</option>`).join('')}</select>` : ''}
      </div></td></tr>`;
  }).join('') : '<tr><td colspan="7" style="text-align:center;color:var(--mut);padding:2rem">No hay pedidos con esos criterios.</td></tr>';
}
$('#pedChips').onclick = e => { const b = e.target.closest('.chip'); if (b) { pedFiltro = b.dataset.e; paintAdminOrders(); } };
$('#fPed').oninput = paintAdminOrders;
function changeOrderState(id, sel) {
  const e = sel.value; if (!e) return;
  if (e === 'CANCELADO') { sel.value = ''; return askDelete('pedido_admin', id, ''); }   // cancelar pide confirmación
  setOrderState(id, e);
}
async function setOrderState(id, estado) {
  const r = await api('cambiar_estado_pedido', { id_pedido: id, estado }, true);
  r.success ? toast(`Pedido #${id}: ${estado}`) : toast(r.error, true);
  loadAdminOrders();
}
async function adminOrderDetail(id) {
  const o = adminOrders.find(x => +x.id_pedido === id) || {};
  const r = await api('detalle_pedido', { id_pedido: id });
  if (!r.success) return toast(r.error, true);
  $('#ordTitle').textContent = 'Pedido #' + id;
  const shippingAddress = [o.direccion_envio, o.barrio_envio, o.ciudad_envio, o.pais_envio].filter(Boolean).join(', ');
  $('#ordInfo').innerHTML = `<div><b>${esc(o.nombre)} ${esc(o.apellido)}</b> · ${esc(o.correo)}${o.telefono ? ' · ' + esc(o.telefono) : ''}</div>
    <div>Envío a: ${esc(shippingAddress || '—')}</div><div>Pago: ${esc(o.metodo_pago || '—')} · Total: <b>${money(o.total)}</b></div>`;
  $('#ordDet').innerHTML = r.data.map(d => `<tr><td>${esc(d.producto)}<div class="sub">${esc(d.marca)} · ${money(d.precio_unitario)} c/u</div></td><td>${esc(d.cantidad)}</td><td>${money(d.subtotal)}</td></tr>`).join('');
  open('mOrd');
}

/* ---------- ADMIN: Tienda (personalización) ---------- */
function prevTienda() {
  const c1 = $('#tC1').value, c2 = $('#tC2').value;
  $('#tPrev').style.background = c1; $('#tPrevSw').style.background = c2;
  $('#tPrevName').textContent = $('#tNom').value || 'Tu tienda';
  $('#tWarn').hidden = lum(c1) < .6;
}
['tNom', 'tC1', 'tC2'].forEach(id => $('#' + id).addEventListener('input', prevTienda));
async function loadTiendaForm() {
  const r = await api('tienda_admin'), d = r.success && r.data;
  if (d) {
    $('#tNom').value = d.nombre_tienda || ''; $('#tDes').value = d.descripcion_tienda || '';
    $('#tLogo').value = d.logo_url || ''; $('#tBan').value = d.banner_url || '';
    if (isHex(d.color_primario)) $('#tC1').value = d.color_primario.toLowerCase();
    if (isHex(d.color_secundario)) $('#tC2').value = d.color_secundario.toLowerCase();
    $('#tMail').value = d.correo_contacto || ''; $('#tTel').value = d.telefono_contacto || '';
    $('#tLogo').dispatchEvent(new Event('input')); $('#tBan').dispatchEvent(new Event('input'));
  }
  prevTienda();
}
$('#fTienda').onsubmit = async e => {
  e.preventDefault();
  const r = await api('guardar_tienda', {
    nombre_tienda: $('#tNom').value.trim(), descripcion_tienda: $('#tDes').value, logo_url: $('#tLogo').value.trim(),
    banner_url: $('#tBan').value.trim(), color_primario: $('#tC1').value, color_secundario: $('#tC2').value,
    correo_contacto: $('#tMail').value.trim(), telefono_contacto: $('#tTel').value.trim()
  }, true);
  if (!r.success) return toast(r.error || 'No se pudo guardar', true);
  toast('Tienda actualizada'); loadStore();
};

/* ---------- ADMIN: Usuarios ---------- */
let adminUsers = [];
async function loadUsers() {
  const r = verElim.usr ? await api('listar_eliminados', { tipo: 'usuario' }) : await api('listar_usuarios');
  adminUsers = r.success ? r.data : [];
  const del = verElim.usr;
  $('#usrBody').innerHTML = adminUsers.length ? adminUsers.map(u => {
    const me = user && +u.id_usuario === +user.id_usuario;
    const nom = `<strong>${esc(u.nombre + ' ' + (u.apellido || ''))}</strong>${me ? ' <span class="pill">Tú</span>' : ''}`;
    if (del) return `<tr><td>${nom}</td><td>${esc(u.correo)}</td><td>${esc(u.telefono || '—')}</td><td><span class="pill">${esc(u.rol)}</span></td>
      <td><button class="btn ok sm" onclick="restaurar('usuario',${+u.id_usuario})">Restaurar</button></td></tr>`;
    return `<tr><td>${nom}</td><td>${esc(u.correo)}</td><td>${esc(u.telefono || '—')}</td>
    <td><select class="stock" style="width:auto" ${me ? 'disabled' : ''} onchange="setRole(${+u.id_usuario},this.value)">
      ${['CLIENTE', 'ADMINISTRADOR'].map(x => `<option ${u.rol === x ? 'selected' : ''}>${x}</option>`).join('')}</select></td>
    <td><div class="acts"><button class="btn ghost sm" onclick="editUser(${+u.id_usuario})">Editar</button>${me ? '' : `<button class="btn bad sm" onclick="askDelete('usuario',${+u.id_usuario},'${esc(u.nombre).replace(/'/g, '&#39;')}')">Eliminar</button>`}</div></td></tr>`;
  }).join('') : `<tr><td colspan="5" style="text-align:center;color:var(--mut);padding:2rem">${del ? 'No hay usuarios eliminados.' : 'No hay usuarios registrados.'}</td></tr>`;
}
function editUser(id) {
  const u = adminUsers.find(x => +x.id_usuario === id); if (!u) return;
  $('#uId').value = u.id_usuario; $('#uNom').value = u.nombre || ''; $('#uApe').value = u.apellido || '';
  $('#uMail').value = u.correo || ''; $('#uTel').value = u.telefono || '';
  $('#uPais').value = u.pais || ''; $('#uCiudad').value = u.ciudad || '';
  $('#uBarrio').value = u.barrio || ''; $('#uDir').value = u.direccion || ''; $('#uPass').value = '';
  open('mUsr');
}
$('#fUsr').onsubmit = async e => {
  e.preventDefault();
  const id = $('#uId').value;
  const r = await api('admin_actualizar_usuario', { id_usuario: id, nombre: $('#uNom').value.trim(), apellido: $('#uApe').value.trim(),
    correo: $('#uMail').value.trim(), telefono: $('#uTel').value.trim(), pais: $('#uPais').value.trim(),
    ciudad: $('#uCiudad').value.trim(), barrio: $('#uBarrio').value.trim(), direccion: $('#uDir').value.trim(),
    password: $('#uPass').value }, true);
  if (!r.success) return toast(r.error || 'No se pudo guardar', true);
  if (user && +id === +user.id_usuario) { user.nombre = $('#uNom').value.trim(); user.apellido = $('#uApe').value.trim(); localStorage.setItem('currentUser', JSON.stringify(user)); }
  close('mUsr'); toast('Usuario actualizado'); loadUsers();
};
async function setRole(id, rol) {
  const r = await api('cambiar_rol', { id_usuario: id, rol }, true);
  r.success ? toast('Rol actualizado') : toast(r.error, true);
  loadUsers();
}

/* ---------- ADMIN: alternar activos / eliminados + restaurar ---------- */
$('#togProd').onclick = async function () { verElim.prod = !verElim.prod; this.textContent = verElim.prod ? 'Ver activos' : 'Ver eliminados'; $('#newProd').hidden = verElim.prod; await loadProds(); };
$('#togCat').onclick = async function () { verElim.cat = !verElim.cat; this.textContent = verElim.cat ? 'Ver activas' : 'Ver eliminados'; $('#newCat').hidden = verElim.cat; await loadCatsView(); };
$('#togUsr').onclick = async function () { verElim.usr = !verElim.usr; this.textContent = verElim.usr ? 'Ver activos' : 'Ver eliminados'; await loadUsers(); };
async function restaurar(tipo, id) {
  const r = await api('restaurar', { tipo, id }, true);
  if (!r.success) return toast(r.error || 'No se pudo restaurar', true);
  toast(tipo[0].toUpperCase() + tipo.slice(1) + (tipo === 'categoria' ? ' restaurada' : ' restaurado'));
  if (tipo === 'producto') { loadProds(); loadList('listar_catalogo'); }
  else if (tipo === 'categoria') { await loadCats(); await loadCatsView(); }
  else loadUsers();
}

/* ---------- Mi perfil ---------- */
async function loadProfile() {
  const r = await api('perfil_obtener');
  if (!(r.success && r.data)) return toast(r.error || 'No se pudo cargar tu perfil', true);
  const d = r.data;
  $('#mpMail').value = d.correo || ''; $('#mpNom').value = d.nombre || ''; $('#mpApe').value = d.apellido || '';
  $('#mpTel').value = d.telefono || ''; $('#mpPais').value = d.pais || '';
  $('#mpCiudad').value = d.ciudad || ''; $('#mpBarrio').value = d.barrio || ''; $('#mpDir').value = d.direccion || '';
  $('#mpAct').value = $('#mpNew').value = $('#mpNew2').value = '';
}
$('#fPerfil').onsubmit = async e => {
  e.preventDefault();
  const r = await api('perfil_actualizar', {
    nombre: $('#mpNom').value.trim(), apellido: $('#mpApe').value.trim(), telefono: $('#mpTel').value.trim(),
    pais: $('#mpPais').value.trim(), ciudad: $('#mpCiudad').value.trim(), barrio: $('#mpBarrio').value.trim(),
    direccion: $('#mpDir').value.trim()
  }, true);
  if (!r.success) return toast(r.error || 'No se pudo guardar', true);
  user = r.user; localStorage.setItem('currentUser', JSON.stringify(user));
  cartAddress = { pais: $('#mpPais').value.trim(), ciudad: $('#mpCiudad').value.trim(), barrio: $('#mpBarrio').value.trim(), direccion: $('#mpDir').value.trim() };
  toast('Perfil actualizado');
};
$('#fPass').onsubmit = async e => {
  e.preventDefault();
  if ($('#mpNew').value !== $('#mpNew2').value) return toast('Las contraseñas nuevas no coinciden', true);
  const r = await api('cambiar_password', { password_actual: $('#mpAct').value, password_nueva: $('#mpNew').value }, true);
  if (!r.success) return toast(r.error || 'No se pudo cambiar la contraseña', true);
  $('#mpAct').value = $('#mpNew').value = $('#mpNew2').value = '';
  toast('Contraseña actualizada');
};

/* ---------- Detalle de producto (público) ---------- */
async function showProduct(id) {
  const r = await api('detalle_producto', { id_producto: id });
  if (!(r.success && r.data)) return toast(r.error || 'Este producto ya no está disponible', true);
  const p = r.data;
  const raw = String(p.especificaciones || '');
  const specs = raw.split(raw.includes('\n') ? /\n/ : /,\s*/).map(x => x.trim()).filter(Boolean);
  $('#pdBody').innerHTML = `<div class="pd">
    <img src="${esc(p.imagen_url || 'img/default.jpg')}" alt="${esc(p.nombre)}" ${dat(p)}>
    <div>
      <div class="brand">${esc(p.marca)}</div><h3>${esc(p.nombre)}</h3>
      <div class="price">${money(p.precio)}</div>
      <div class="mut">${esc(p.categoria)} · ${+p.stock > 0 ? p.stock + ' disponibles' : 'Agotado'}</div>
      ${p.descripcion ? `<p class="pdesc">${esc(p.descripcion)}</p>` : ''}
      ${specs.length ? `<h4>Especificaciones</h4><ul>${specs.map(x => `<li>${esc(x)}</li>`).join('')}</ul>` : ''}
      <button class="btn ok full" ${+p.stock > 0 ? '' : 'disabled'} onclick="close('mProdDet');addCart(${+p.id_producto})">Agregar al carrito</button>
    </div></div>`;
  open('mProdDet');
}

/* ---------- Subida de imágenes ---------- */
async function subirImagen(file, inputId) {
  if (file.size > 2 * 1024 * 1024) return toast('La imagen supera el máximo de 2 MB', true);
  const r = await api('subir_imagen', { archivo: file }, true);
  if (!r.success) return toast(r.error || 'No se pudo subir la imagen', true);
  const t = $('#' + inputId); t.value = r.ruta; t.dispatchEvent(new Event('input', { bubbles: true }));
  toast('Imagen subida');
}
$$('input[type=file][data-target]').forEach(f => {
  const t = $('#' + f.dataset.target), im = f.parentElement.querySelector('img');
  const prev = () => { if (t.value) { im.style.visibility = 'visible'; im.src = t.value; } else { im.removeAttribute('src'); im.style.visibility = 'hidden'; } };
  im.onerror = () => { im.style.visibility = 'hidden'; };
  t.addEventListener('input', prev); prev();
  f.onchange = async () => { if (f.files[0]) await subirImagen(f.files[0], f.dataset.target); f.value = ''; };
});

/* ---------- Eliminar (compartido) ---------- */
let pendingDel = null;
function askDelete(tipo, id, nombre) {
  pendingDel = { tipo, id };
  $('#delMsg').textContent = tipo === 'carrito' ? '¿Vaciar todo el carrito?'
    : tipo === 'pedido' || tipo === 'pedido_admin' ? `¿Cancelar el pedido #${id}? Los productos volverán al inventario.`
    : `¿Eliminar ${tipo} “${nombre}”? Podrás restaurarlo después desde “Ver eliminados”.`;
  $('#delOk').textContent = tipo === 'carrito' ? 'Vaciar' : tipo === 'pedido' || tipo === 'pedido_admin' ? 'Cancelar pedido' : 'Eliminar';
  open('mDel');
}
$('#delOk').onclick = async () => {
  const { tipo, id } = pendingDel;
  const custom = { carrito: ['vaciar_carrito', {}], pedido: ['cancelar_pedido', { id_pedido: id }], pedido_admin: ['cambiar_estado_pedido', { id_pedido: id, estado: 'CANCELADO' }] }[tipo];
  const r = custom ? await api(custom[0], custom[1], true) : await api('eliminar_' + tipo, { ['id_' + tipo]: id }, true);
  if (!r.success) return toast(r.error || 'No se pudo completar la acción', true);
  close('mDel');
  if (tipo === 'pedido_admin') { toast(`Pedido #${id} cancelado; stock devuelto`); loadAdminOrders(); loadProds(); return loadList('listar_catalogo'); }
  if (tipo === 'carrito') { toast('Carrito vaciado'); return loadCart(); }
  if (tipo === 'pedido') { toast('Pedido cancelado'); loadOrders(); return loadList('listar_catalogo'); }
  toast(tipo[0].toUpperCase() + tipo.slice(1) + ' eliminado' + (tipo === 'categoria' ? 'a' : '') + '. Puedes restaurarlo en “Ver eliminados”.');
  if (tipo === 'producto') loadProds(); else if (tipo === 'usuario') loadUsers(); else { await loadCats(); paintCats(); }
};

/* ---------- ADMIN: Reportes en Excel ---------- */
$$('[data-rep]').forEach(b => b.onclick = async () => {
  const txt = b.textContent; b.disabled = true; b.textContent = 'Generando…';
  try {
    const res = await fetch('reporte.php?tipo=' + encodeURIComponent(b.dataset.rep));
    if ((res.headers.get('Content-Type') || '').includes('application/json')) {   // el servidor respondió un error
      const r = await res.json(); return toast(r.error || 'No se pudo generar el reporte', true);
    }
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const nombre = ((res.headers.get('Content-Disposition') || '').match(/filename="([^"]+)"/) || [])[1] || 'reporte.xlsx';
    const url = URL.createObjectURL(await res.blob());
    const a = document.createElement('a'); a.href = url; a.download = nombre; document.body.append(a); a.click(); a.remove();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
    toast('Reporte descargado');
  } catch (e) { toast('No se pudo descargar el reporte', true); }
  finally { b.disabled = false; b.textContent = txt; }
});

/* ---------- TechnoStore: carrito lateral, búsqueda del header y modales ---------- */
const openDrawer = () => { $('#drawer').classList.add('on'); $('#drawer').setAttribute('aria-hidden', 'false'); $('#scrim').hidden = false; };
const closeDrawer = () => { $('#drawer').classList.remove('on'); $('#drawer').setAttribute('aria-hidden', 'true'); $('#scrim').hidden = true; };
$('#navCart').onclick = openDrawer;
$('#dClose').onclick = $('#scrim').onclick = closeDrawer;
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

function paintDrawer() {
  const body = $('#dBody'), foot = $('#dFoot');
  if (!user) {
    body.innerHTML = '<div class="empty">Inicia sesión para guardar productos en tu carrito.</div>';
    foot.innerHTML = '<button class="btn full" id="dLogin">Iniciar sesión</button>';
    $('#dLogin').onclick = () => { closeDrawer(); openAuth(); };
    return;
  }
  if (!cart.length) { body.innerHTML = '<div class="empty">Tu carrito está vacío.</div>'; foot.innerHTML = ''; return; }
  const total = cart.reduce((s, i) => s + +i.subtotal, 0), units = cart.reduce((s, i) => s + +i.cantidad, 0);
  body.innerHTML = cart.map(i => `<div class="di">
    <img src="${esc(i.imagen_url || 'img/default.jpg')}" alt="" ${dat(i)}>
    <div><strong>${esc(i.nombre)}</strong><span>${money(i.precio)} c/u</span>
      <div class="qty" style="width:max-content"><button aria-label="Quitar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad - 1})">−</button><span>${+i.cantidad}</span><button aria-label="Agregar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad + 1})" ${+i.cantidad >= +i.stock ? 'disabled' : ''}>+</button></div></div>
    <b>${money(i.subtotal)}</b></div>`).join('');
  foot.innerHTML = `<div class="line tot"><span>Total (${units})</span><span>${money(total)}</span></div><button class="btn ok full" id="dGo">Ir a pagar</button>`;
  $('#dGo').onclick = () => { closeDrawer(); show('cart'); };
}

function notice(title, html, btns = [['Entendido', 'ok', () => close('mInfo')]]) {
  $('#infTitle').textContent = title; $('#infBody').innerHTML = html;
  const box = $('#infBtns'); box.innerHTML = '';
  btns.forEach(([t, c, f]) => { const b = document.createElement('button'); b.className = 'btn ' + c; b.textContent = t; b.onclick = f; box.append(b); });
  open('mInfo');
}
function confirmAdd(id) {
  const it = cart.find(i => +i.id_producto === id); if (!it) return;
  const total = cart.reduce((s, i) => s + +i.subtotal, 0);
  notice('Añadido al carrito', `<div class="addok"><img src="${esc(it.imagen_url || 'img/default.jpg')}" alt="" ${dat(it)}><div><strong>${esc(it.nombre)}</strong><span>${+it.cantidad} en tu carrito · Total ${money(total)}</span></div></div>`,
    [['Seguir comprando', 'ghost', () => close('mInfo')], ['Ver carrito', 'ok', () => { close('mInfo'); openDrawer(); }]]);
}

$('#hSearchForm').onsubmit = e => {
  e.preventDefault();
  const q = $('#hSearch').value.trim();
  show('catalog'); $('#searchInput').value = q;
  loadList(q ? 'buscar_productos' : 'listar_catalogo', q ? { q } : {});
  $('#v-catalog').scrollIntoView({ behavior: 'smooth' });
};

/* ---------- Animaciones ---------- */
const reduceMotion = () => matchMedia('(prefers-reduced-motion: reduce)').matches;
let lastAddBtn = null, flyDone = false, lastUnits = 0;
document.addEventListener('click', e => { lastAddBtn = e.target.closest('button'); }, true);
function flyToCart() {
  flyDone = false;
  const img = lastAddBtn && lastAddBtn.closest('.card') && lastAddBtn.closest('.card').querySelector('.ph img');
  if (!img || reduceMotion()) return;
  const a = img.getBoundingClientRect(), t = $('#navCart').getBoundingClientRect();
  const c = img.cloneNode();
  Object.assign(c.style, { position: 'fixed', left: a.left + 'px', top: a.top + 'px', width: a.width + 'px', height: a.height + 'px', objectFit: 'cover', borderRadius: '14px', zIndex: 300, pointerEvents: 'none', margin: 0 });
  document.body.append(c); flyDone = true;
  const dx = t.left + t.width / 2 - (a.left + a.width / 2), dy = t.top + t.height / 2 - (a.top + a.height / 2);
  c.animate([{ transform: 'none', opacity: 1 }, { transform: `translate(${dx * .6}px,${dy * .5 - 40}px) scale(.45)`, opacity: .95, offset: .55 }, { transform: `translate(${dx}px,${dy}px) scale(.05)`, opacity: .2 }],
    { duration: 600, easing: 'cubic-bezier(.45,0,.75,.4)' }).onfinish = () => c.remove();
}
const _paintDrawer = paintDrawer;
paintDrawer = function () {   // pulso del contador al sumar unidades
  _paintDrawer();
  const n = cart.reduce((s, i) => s + +i.cantidad, 0);
  if (n > lastUnits) ['#cartBadge', '#navCart'].forEach(s => { const el = $(s); el.classList.remove('bump'); void el.offsetWidth; el.classList.add('bump'); });
  lastUnits = n;
};

/* ---------- Términos y condiciones ---------- */
document.addEventListener('click', e => { if (e.target.closest('[data-terms]')) { e.preventDefault(); openTerms(); } });
function openTerms() {
  const contact = $('#footContact').textContent.trim() || 'el correo de contacto de la tienda';
  $$('.tcContact').forEach(el => el.textContent = contact);
  $('#mTerms .mc').scrollTop = 0; $('#tcBar').style.width = '0';
  open('mTerms');
}
$('#mTerms .mc').addEventListener('scroll', e => {
  const m = e.currentTarget;
  $('#tcBar').style.width = (m.scrollTop / Math.max(1, m.scrollHeight - m.clientHeight) * 100) + '%';
});
$('#tcAll').onclick = function () {
  const all = $$('#mTerms details'), openAll = all.some(d => !d.open);
  all.forEach(d => d.open = openAll); this.textContent = openAll ? 'Contraer todo' : 'Expandir todo';
};
$('#tcAccept').onclick = () => {
  termsOk = true; $$('#rTerms,#cTerms').forEach(c => c.checked = true);
  close('mTerms'); toast('Términos y condiciones aceptados');
};

/* ---------- Animaciones al hacer scroll ---------- */
document.addEventListener('scroll', () => $('header').classList.toggle('stuck', scrollY > 8), { passive: true });
if (!reduceMotion() && 'IntersectionObserver' in window) {
  const io = new IntersectionObserver(es => es.forEach(en => {
    if (!en.isIntersecting) return;
    en.target.classList.add('in'); io.unobserve(en.target);
    en.target.addEventListener('transitionend', () => en.target.style.transitionDelay = '', { once: true });
  }), { threshold: .15 });
  $$('.perks-in>div, .sh, .chips, .foot-in').forEach((el, i) => { el.classList.add('reveal'); el.style.transitionDelay = (i % 4) * 80 + 'ms'; io.observe(el); });
}

/* ---------- Inicio ---------- */
async function syncSession() {   // la sesión real vive en el servidor
  const r = await api('sesion');
  user = r.success ? r.user : null;
  user ? localStorage.setItem('currentUser', JSON.stringify(user)) : localStorage.removeItem('currentUser');
  paintUser(); if (user) { refreshCart(); refreshSaldo(); } else { saldo = null; paintSaldo(); }
}
(async () => {
  Loader.start('Cargando tienda…', 0);
  try {
    await syncSession(); loadStore(); await loadCats();
    await loadList('listar_catalogo');
  } finally { Loader.stop(); }
  api('obtener_destacados').then(r => heroArt(r.success ? r.data : []));
})();
</script>
</body>
</html>
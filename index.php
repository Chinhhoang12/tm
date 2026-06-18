<?php
/* shop.php - Web mua mail độc lập
   Không DB, không session hệ thống
   Chỉ cần proxy.php cùng thư mục
*/
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mua Mail TikTok / YouTube</title>
<meta property="og:title" content="Mua Mail TikTok / YouTube">
<meta property="og:description" content="Mail live 2h - Dùng ngay sau khi mua">
<style>
*{ margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }

:root{
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #6366f1;
    --accent: #06b6d4;
    --success: #16a34a;
    --danger: #dc2626;
    --text-dark: #1e1b2e;
    --text-mid: #6b7280;
    --bg-soft: #f4f4f8;
}

body{
    font-family: 'Segoe UI', -apple-system, Roboto, Arial, sans-serif;
    background: linear-gradient(160deg, #eef0ff 0%, #f6f7fb 45%, #eafafe 100%);
    min-height: 100vh;
    padding: 16px;
    color: var(--text-dark);
}

.wrap{
    max-width: 420px;
    margin: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.card{
    background: white;
    border-radius: 20px;
    padding: 18px;
    box-shadow: 0 4px 18px rgba(79,70,229,0.08), 0 1px 3px rgba(0,0,0,0.04);
    border: 1px solid rgba(79,70,229,0.05);
}

/* HEADER */
.header{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.site-title{
    font-size: 19px;
    font-weight: 800;
    letter-spacing: -0.3px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
.share-btn{
    background: var(--bg-soft);
    color: var(--text-mid);
    border: 1px solid #e5e7eb;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.15s;
}
.share-btn:active{ transform: scale(0.96); }

/* PRODUCT BANNER */
.product-banner{
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 55%, var(--accent) 100%);
    border-radius: 18px;
    padding: 20px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(79,70,229,0.28);
}
.product-banner::after{
    content:'';
    position:absolute;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.12);
    border-radius: 50%;
    top: -60px; right: -50px;
}
.product-title{ font-size: 17px; font-weight: 800; margin-bottom: 4px; letter-spacing: -0.2px; }
.product-sub{ font-size: 12.5px; opacity: 0.85; margin-bottom: 16px; }
.product-meta{
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    position: relative;
    z-index: 1;
}
.stock-tag{
    background: rgba(255,255,255,0.22);
    backdrop-filter: blur(4px);
    border-radius: 20px;
    padding: 5px 13px;
    font-size: 12px;
    font-weight: 600;
}
.price-big{ font-size: 28px; font-weight: 800; letter-spacing: -0.5px; }
.price-unit{ font-size: 12px; opacity: 0.85; }

/* LOGIN THUEMAIL */
.tm-label{
    font-size: 12px;
    font-weight: 700;
    color: var(--text-mid);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}
.tm-input{
    width: 100%;
    padding: 13px 14px;
    border: 1.5px solid #e5e7eb;
    background: var(--bg-soft);
    border-radius: 12px;
    font-size: 14px;
    outline: none;
    margin-bottom: 9px;
    transition: 0.15s;
}
.tm-input:focus{ border-color: var(--primary); background: white; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
.tm-btn{
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.15s;
    box-shadow: 0 4px 12px rgba(79,70,229,0.25);
}
.tm-btn:active{ transform: scale(0.98); }
.msg{ font-size: 12px; margin-top: 8px; text-align: center; }

/* SESSION INFO */
.session-bar{
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 1px solid #bbf7d0;
    border-radius: 13px;
    padding: 11px 15px;
}
.session-name{ font-size: 14px; font-weight: 700; color: #15803d; }
.session-label{ font-size: 11px; color: #4ade80; font-weight: 600; letter-spacing: 0.3px; }
.logout-sm{
    background: #fee2e2;
    color: var(--danger);
    border: none;
    padding: 6px 12px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

/* BUY FORM */
.qty-row{
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 12px;
}
.qty-stepper{
    flex: 1;
    display: flex;
    align-items: center;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg-soft);
}
.qty-btn{
    width: 40px;
    height: 46px;
    border: none;
    background: transparent;
    color: var(--primary);
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.15s;
    flex-shrink: 0;
}
.qty-btn:active{ background: rgba(79,70,229,0.1); }
.qty-input{
    flex: 1;
    width: 100%;
    padding: 13px 4px;
    border: none;
    background: transparent;
    font-size: 16px;
    font-weight: 700;
    outline: none;
    text-align: center;
    color: var(--text-dark);
}
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button{ -webkit-appearance: none; margin:0; }
.total-display{
    font-size: 17px;
    font-weight: 800;
    color: var(--primary);
    min-width: 84px;
    text-align: right;
}
.buy-btn{
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 13px;
    background: linear-gradient(135deg, var(--success), #22c55e);
    color: white;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.15s;
    box-shadow: 0 4px 14px rgba(22,163,74,0.3);
}
.buy-btn:active{ transform: scale(0.98); }
.buy-btn:disabled{ background: #9ca3af; cursor: not-allowed; box-shadow: none; }

/* STATUS */
.status{
    padding: 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    display: none;
}
.status.show{ display: block; }
.status.success{ background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; }
.status.error  { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; }
.status.loading{ background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }

/* MAIL RESULT */
.result-box{
    display: none;
    flex-direction: column;
    gap: 8px;
}
.result-box.show{ display: flex; }

.mail-item{
    background: var(--bg-soft);
    border: 1px solid #ececf5;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}
.mail-text{
    font-size: 13px;
    word-break: break-all;
    flex: 1;
    color: var(--text-dark);
}
.copy-btn{
    background: var(--primary);
    color: white;
    border: none;
    padding: 7px 13px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: 0.15s;
}
.copy-btn.copied{
    background: var(--success);
}
.copy-all-btn{
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: var(--text-dark);
    color: white;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}

/* HISTORY */
.history-item{
    padding: 11px 13px;
    background: var(--bg-soft);
    border-radius: 12px;
    margin-bottom: 7px;
}
.history-row{
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-bottom: 3px;
    font-weight: 600;
}
.history-meta{
    font-size: 11px;
    color: #9ca3af;
}
.section-title{
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="wrap">

    <!-- HEADER -->
    <div class="card header">
        <div class="site-title">🛒 Mua Mail</div>
        <button class="share-btn" onclick="shareLink()">📤 SHARE</button>
    </div>

    <!-- PRODUCT BANNER -->
    <div class="product-banner">
        <div class="product-title">MAIL LIVE TIKTOK / YOUTUBE</div>
        <div class="product-sub">⚡ Live 2 giờ kể từ khi mua — Dùng được ngay</div>
        <div class="product-meta">
            <div class="stock-tag" id="stock-tag">📦 Đang tải...</div>
            <div>
                <div class="price-big" id="price-display">59đ</div>
                <div class="price-unit">/ 1 mail</div>
            </div>
        </div>
    </div>

    <!-- LOGIN THUEMAIL (ẩn khi đã login) -->
    <div class="card" id="login-card">
        <div class="tm-label">🔑 Đăng nhập thuemail.net</div>
        <input class="tm-input" type="text"     id="tm-user" placeholder="Username">
        <input class="tm-input" type="password" id="tm-pass" placeholder="Mật khẩu"
               onkeydown="if(event.key==='Enter') tmLogin()">
        <button class="tm-btn" id="tm-login-btn" onclick="tmLogin()">
            Đăng nhập
        </button>
        <div class="msg" id="login-msg" style="color:#ef4444;"></div>
    </div>

    <!-- SESSION BAR (hiện khi đã login) -->
    <div class="card" id="session-card" style="display:none;padding:12px 16px;">
        <div class="session-bar">
            <div>
                <div class="session-label">ĐÃ KẾT NỐI</div>
                <div class="session-name" id="session-name">-</div>
            </div>
            <button class="logout-sm" onclick="tmLogout()">Đăng xuất</button>
        </div>
    </div>

    <!-- MUA -->
    <div class="card" id="buy-card">
        <div class="qty-row">
            <div class="qty-stepper">
                <button type="button" class="qty-btn" onclick="stepQty(-1)">−</button>
                <input type="number" class="qty-input" id="qty"
                       value="1" min="1" max="50"
                       oninput="calcTotal()">
                <button type="button" class="qty-btn" onclick="stepQty(1)">+</button>
            </div>
            <div class="total-display" id="total">0đ</div>
        </div>
        <button class="buy-btn" id="buy-btn" onclick="doBuy()" disabled>
            🔒 Đăng nhập thuemail để mua
        </button>
        <div class="status" id="status-box" style="margin-top:8px;"></div>
    </div>

    <!-- KẾT QUẢ MAIL -->
    <div class="card" id="result-card" style="display:none;">
        <div class="section-title">📩 Mail vừa mua</div>
        <div class="result-box show" id="mail-list"></div>
        <button class="copy-all-btn" style="margin-top:8px;" onclick="copyAll()">
            📋 COPY TẤT CẢ
        </button>
    </div>

    <!-- LỊCH SỬ -->
    <div class="card">
        <div class="section-title">🕐 Lịch sử mua</div>
        <div id="history-list">
            <div style="font-size:12px;color:#aaa;text-align:center;padding:8px;">
                Chưa có lịch sử
            </div>
        </div>
    </div>

</div>

<!-- Link share ẩn -->
<input type="hidden" id="share-url" value="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">

<script>

const PROXY   = 'proxy.php';
const PROD_ID = 29;

let TM_SESSION    = localStorage.getItem('tm_sess') || '';
let TM_USER_NAME  = localStorage.getItem('tm_uname')|| '';
let PRICE         = 59;
let LAST_MAILS    = [];
let HISTORY       = JSON.parse(localStorage.getItem('shop_history')||'[]');

/* ================================
   INIT
================================ */
window.addEventListener('load', ()=>{
    loadStock();
    renderHistory();
    calcTotal();
    if(TM_SESSION) showSession();
});

/* ================================
   STOCK
================================ */
async function loadStock(){
    try{
        let fd = new FormData();
        fd.append('action',    'stock');
        fd.append('phpsessid', TM_SESSION || 'x');
        const res  = await fetch(PROXY, {method:'POST', body:fd});
        const data = await res.json();
        if(data.products){
            let p = data.products.find(x => x.product_id == PROD_ID);
            if(p){
                PRICE = p.price;
                document.getElementById('price-display').innerText =
                    Number(p.price).toLocaleString('vi') + 'đ';
                document.getElementById('stock-tag').innerText =
                    p.available
                    ? '📦 Còn: ' + Number(p.stock).toLocaleString('vi')
                    : '❌ Hết hàng';
                if(!p.available){
                    document.getElementById('buy-btn').disabled  = true;
                    document.getElementById('buy-btn').innerText = '❌ Hết hàng';
                }
                calcTotal();
            }
        }
    }catch(e){}
}

/* ================================
   LOGIN
================================ */
async function tmLogin(){
    let user = document.getElementById('tm-user').value.trim();
    let pass = document.getElementById('tm-pass').value.trim();
    if(!user||!pass){
        setMsg('⚠️ Nhập đầy đủ thông tin','#ef4444');
        return;
    }

    let btn = document.getElementById('tm-login-btn');
    btn.disabled  = true;
    btn.innerText = '⏳ Đang đăng nhập...';
    setMsg('','');

    try{
        let fd = new FormData();
        fd.append('action',   'login');
        fd.append('username', user);
        fd.append('password', pass);
        const res  = await fetch(PROXY, {method:'POST', body:fd});
        const data = await res.json();

        if(data.error){
            setMsg('❌ ' + data.error, '#ef4444');
            return;
        }

        TM_SESSION   = data.phpsessid;
        TM_USER_NAME = user;
        localStorage.setItem('tm_sess',  TM_SESSION);
        localStorage.setItem('tm_uname', TM_USER_NAME);

        showSession();
        loadStock();

    }catch(e){
        setMsg('❌ Lỗi kết nối', '#ef4444');
    }finally{
        btn.disabled  = false;
        btn.innerText = 'Đăng nhập';
    }
}

function showSession(){
    document.getElementById('login-card').style.display   = 'none';
    document.getElementById('session-card').style.display = 'block';
    document.getElementById('session-name').innerText     = TM_USER_NAME;
    document.getElementById('buy-btn').disabled  = false;
    document.getElementById('buy-btn').innerText = '🛒 MUA MAIL';
}

function tmLogout(){
    TM_SESSION = ''; TM_USER_NAME = '';
    localStorage.removeItem('tm_sess');
    localStorage.removeItem('tm_uname');
    document.getElementById('login-card').style.display   = 'block';
    document.getElementById('session-card').style.display = 'none';
    document.getElementById('buy-btn').disabled  = true;
    document.getElementById('buy-btn').innerText = '🔒 Đăng nhập thuemail để mua';
    setMsg('','');
}

function setMsg(txt, color){
    let el = document.getElementById('login-msg');
    el.innerText = txt;
    el.style.color = color;
}

/* ================================
   TÍNH TIỀN
================================ */
function calcTotal(){
    let el = document.getElementById('qty');
    let q  = parseInt(el.value)||0;
    if(q < 1){ q = 1; el.value = 1; }
    document.getElementById('total').innerText =
        Number(q * PRICE).toLocaleString('vi') + 'đ';
}

function stepQty(delta){
    let el = document.getElementById('qty');
    let q  = (parseInt(el.value)||0) + delta;
    if(q < 1) q = 1;
    if(q > 50) q = 50;
    el.value = q;
    calcTotal();
}

/* ================================
   MUA
================================ */
async function doBuy(){
    let qty = parseInt(document.getElementById('qty').value);
    if(!qty||qty<1){
        showStatus('⚠️ Nhập số lượng','error'); return;
    }

    let btn = document.getElementById('buy-btn');
    btn.disabled  = true;
    btn.innerText = '⏳ Đang xử lý...';
    showStatus('⏳ Đang mua mail...','loading');

    /* Ẩn kết quả cũ */
    document.getElementById('result-card').style.display = 'none';
    LAST_MAILS = [];

    try{
        let fd = new FormData();
        fd.append('action',     'buy');
        fd.append('phpsessid',  TM_SESSION);
        fd.append('product_id', PROD_ID);
        fd.append('qty',        qty);
        const res  = await fetch(PROXY, {method:'POST', body:fd});
        const data = await res.json();

        if(data.error || data.status !== 'success'){
            showStatus('❌ ' + (data.error||data.message||'Mua thất bại'), 'error');
            return;
        }

        LAST_MAILS = data.keys || [];
        if(LAST_MAILS.length === 0){
            showStatus('❌ Không nhận được mail', 'error');
            return;
        }

        /* Hiện danh sách mail */
        renderMails(LAST_MAILS);

        /* Lưu lịch sử */
        let h = {
            time:  new Date().toLocaleString('vi-VN'),
            qty:   LAST_MAILS.length,
            code:  data.order_code || '',
            cost:  LAST_MAILS.length * PRICE,
        };
        HISTORY.unshift(h);
        if(HISTORY.length>30) HISTORY=HISTORY.slice(0,30);
        localStorage.setItem('shop_history', JSON.stringify(HISTORY));
        renderHistory();

        document.getElementById('qty').value  = '1';
        calcTotal();
        showStatus('✅ Mua thành công ' + LAST_MAILS.length + ' mail!', 'success');

    }catch(e){
        showStatus('❌ Lỗi: ' + e.message, 'error');
    }finally{
        btn.disabled  = false;
        btn.innerText = '🛒 MUA MAIL';
    }
}

/* ================================
   HIỆN MAIL
================================ */
function renderMails(mails){
    let html = mails.map((m, i) => `
        <div class="mail-item">
            <div class="mail-text">${m}</div>
            <button class="copy-btn" id="cbtn-${i}"
                    onclick="copyMail(${i}, '${m.replace(/'/g,"\\'")}')">
                COPY
            </button>
        </div>
    `).join('');

    document.getElementById('mail-list').innerHTML = html;
    document.getElementById('result-card').style.display = 'block';

    /* Cuộn xuống kết quả */
    document.getElementById('result-card')
        .scrollIntoView({behavior:'smooth', block:'start'});
}

function copyMail(idx, text){
    navigator.clipboard.writeText(text).then(()=>{
        let btn = document.getElementById('cbtn-'+idx);
        btn.innerText = '✓';
        btn.classList.add('copied');
        setTimeout(()=>{
            btn.innerText = 'COPY';
            btn.classList.remove('copied');
        }, 2000);
    }).catch(()=>{
        /* fallback */
        let el = document.createElement('textarea');
        el.value = text;
        el.style.cssText = 'position:fixed;opacity:0;';
        document.body.appendChild(el);
        el.select(); document.execCommand('copy');
        document.body.removeChild(el);
    });
}

function copyAll(){
    let text = LAST_MAILS.join('\n');
    navigator.clipboard.writeText(text).then(()=>{
        let btn = document.querySelector('.copy-all-btn');
        btn.innerText = '✅ ĐÃ COPY TẤT CẢ';
        setTimeout(()=>{ btn.innerText = '📋 COPY TẤT CẢ'; }, 2000);
    });
}

/* ================================
   LỊCH SỬ
================================ */
function renderHistory(){
    let box = document.getElementById('history-list');
    if(!HISTORY.length){
        box.innerHTML='<div style="font-size:12px;color:#aaa;text-align:center;padding:8px;">Chưa có lịch sử</div>';
        return;
    }
    box.innerHTML = HISTORY.slice(0,15).map(h=>`
        <div class="history-item">
            <div class="history-row">
                <span>📦 ${h.qty} mail</span>
                <span style="font-weight:bold;color:#16a34a;">
                    ${Number(h.cost).toLocaleString('vi')}đ
                </span>
            </div>
            <div class="history-meta">
                🕐 ${h.time}${h.code?' &nbsp;·&nbsp; Mã: '+h.code:''}
            </div>
        </div>
    `).join('');
}

/* ================================
   SHARE
================================ */
function shareLink(){
    let url = document.getElementById('share-url').value;
    if(navigator.share){
        navigator.share({ title:'Mua Mail', url }).catch(()=>{});
    }else{
        navigator.clipboard.writeText(url).then(()=>{
            let btn = document.querySelector('.share-btn');
            btn.innerText = '✓ COPIED';
            setTimeout(()=>{ btn.innerText = '📤 SHARE'; }, 2000);
        });
    }
}

/* ================================
   STATUS
================================ */
function showStatus(msg, type){
    let box = document.getElementById('status-box');
    box.className = 'status show ' + type;
    box.innerText  = msg;
}

</script>
</body>
</html>

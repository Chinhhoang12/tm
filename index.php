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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{ margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }

:root{
    --bg:        #0B0E14;
    --panel:     #12161F;
    --panel-alt: #161B26;
    --line:      #232938;
    --text:      #E7EAF0;
    --text-dim:  #7C8699;
    --text-faint:#4B5468;
    --accent:    #43E0A4;
    --accent-dim:#1F4A3B;
    --warn:      #FF6868;
    --warn-dim:  #3A1E22;
    --font-ui:   'Inter', -apple-system, sans-serif;
    --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
}

body{
    font-family: var(--font-ui);
    background: var(--bg);
    background-image:
        radial-gradient(circle at 15% 0%, rgba(67,224,164,0.06), transparent 40%);
    color: var(--text);
    min-height: 100vh;
    padding: 14px;
}

.wrap{
    max-width: 420px;
    margin: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.card{
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 16px;
}

/* HEADER */
.header{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.site-title{
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.2px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.site-title .mark{
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
    flex-shrink: 0;
}
.share-btn{
    background: transparent;
    color: var(--text-dim);
    border: 1px solid var(--line);
    padding: 7px 13px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 500;
    font-family: var(--font-mono);
    letter-spacing: 0.3px;
    cursor: pointer;
    transition: 0.15s;
}
.share-btn:active{ transform: scale(0.96); border-color: var(--text-dim); }

/* PRODUCT TICKER */
.product-banner{
    background: var(--panel-alt);
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 18px;
    position: relative;
    overflow: hidden;
}
.product-banner::before{
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, var(--accent), transparent);
    opacity: 0.6;
}
.product-eyebrow{
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--text-faint);
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 8px;
}
.product-title{ font-size: 16px; font-weight: 600; margin-bottom: 4px; color: var(--text); }
.product-sub{ font-size: 12px; color: var(--text-dim); margin-bottom: 16px; }
.product-meta{
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}
.stock-tag{
    font-family: var(--font-mono);
    font-size: 11px;
    color: var(--text-dim);
    display: flex;
    align-items: center;
    gap: 6px;
}
.stock-tag .dot{
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--accent);
    flex-shrink: 0;
    animation: pulse 1.8s ease-in-out infinite;
}
.stock-tag.out .dot{ background: var(--warn); animation: none; }
.stock-tag.out{ color: var(--warn); }
@keyframes pulse{
    0%, 100%{ opacity: 1; }
    50%{ opacity: 0.35; }
}
.price-big{
    font-family: var(--font-mono);
    font-size: 24px;
    font-weight: 600;
    color: var(--accent);
    letter-spacing: -0.3px;
}
.price-unit{ font-size: 11px; color: var(--text-faint); text-align: right; margin-top: 2px; }

/* LOGIN THUEMAIL */
.tm-label{
    font-size: 11px;
    font-weight: 600;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 10px;
    font-family: var(--font-mono);
}
.tm-input{
    width: 100%;
    padding: 12px 13px;
    border: 1px solid var(--line);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    margin-bottom: 8px;
    background: var(--bg);
    color: var(--text);
    font-family: var(--font-ui);
}
.tm-input::placeholder{ color: var(--text-faint); }
.tm-input:focus{ border-color: var(--accent); }
.tm-btn{
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: var(--accent);
    color: #06140F;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.2px;
    cursor: pointer;
    transition: 0.15s;
}
.tm-btn:active{ transform: scale(0.98); }
.tm-btn:disabled{ background: var(--line); color: var(--text-faint); }
.msg{ font-size: 12px; margin-top: 8px; text-align: center; }

/* SESSION INFO */
.session-bar{
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--panel-alt);
    border: 1px solid var(--accent-dim);
    border-radius: 8px;
    padding: 10px 14px;
}
.session-name{ font-size: 13px; font-weight: 600; color: var(--text); font-family: var(--font-mono); }
.session-label{ font-size: 10px; color: var(--accent); letter-spacing: 0.5px; font-family: var(--font-mono); }
.logout-sm{
    background: transparent;
    color: var(--text-dim);
    border: 1px solid var(--line);
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11px;
    cursor: pointer;
}
.logout-sm:active{ border-color: var(--warn); color: var(--warn); }

/* BUY FORM */
.qty-row{
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 10px;
}
.qty-input{
    flex: 1;
    padding: 13px;
    border: 1px solid var(--line);
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    text-align: center;
    background: var(--bg);
    color: var(--text);
    font-family: var(--font-mono);
}
.qty-input::placeholder{ color: var(--text-faint); font-family: var(--font-ui); }
.qty-input:focus{ border-color: var(--accent); }
.total-display{
    font-family: var(--font-mono);
    font-size: 16px;
    font-weight: 600;
    color: var(--accent);
    min-width: 84px;
    text-align: right;
}
.buy-btn{
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 8px;
    background: var(--accent);
    color: #06140F;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.2px;
    cursor: pointer;
    transition: 0.15s;
}
.buy-btn:active{ transform: scale(0.98); }
.buy-btn:disabled{ background: var(--line); color: var(--text-faint); cursor: not-allowed; }

/* STATUS */
.status{
    padding: 11px;
    border-radius: 8px;
    font-size: 12px;
    text-align: center;
    display: none;
    font-family: var(--font-mono);
    border: 1px solid transparent;
}
.status.show{ display: block; }
.status.success{ background: var(--accent-dim); color: var(--accent); border-color: rgba(67,224,164,0.3); }
.status.error  { background: var(--warn-dim); color: var(--warn); border-color: rgba(255,104,104,0.3); }
.status.loading{ background: var(--panel-alt); color: var(--text-dim); border-color: var(--line); }

/* MAIL RESULT */
.result-box{
    display: none;
    flex-direction: column;
    gap: 8px;
}
.result-box.show{ display: flex; }

.mail-item{
    background: var(--panel-alt);
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 11px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}
.mail-text{
    font-size: 12.5px;
    word-break: break-all;
    flex: 1;
    color: var(--text);
    font-family: var(--font-mono);
}
.copy-btn{
    background: transparent;
    color: var(--accent);
    border: 1px solid var(--accent-dim);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    font-family: var(--font-mono);
    letter-spacing: 0.3px;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
}
.copy-btn.copied{
    background: var(--accent-dim);
}
.copy-all-btn{
    width: 100%;
    padding: 11px;
    border: 1px solid var(--line);
    border-radius: 8px;
    background: transparent;
    color: var(--text-dim);
    font-size: 12px;
    font-weight: 600;
    font-family: var(--font-mono);
    letter-spacing: 0.3px;
    cursor: pointer;
}

/* HISTORY */
.history-item{
    padding: 10px 12px;
    background: var(--panel-alt);
    border: 1px solid var(--line);
    border-radius: 8px;
    margin-bottom: 6px;
}
.history-row{
    display: flex;
    justify-content: space-between;
    font-size: 12.5px;
    margin-bottom: 3px;
    font-family: var(--font-mono);
}
.history-meta{
    font-size: 10.5px;
    color: var(--text-faint);
    font-family: var(--font-mono);
}
.section-title{
    font-size: 11px;
    font-weight: 600;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 10px;
    font-family: var(--font-mono);
}
</style>
</head>
<body>

<div class="wrap">

    <!-- HEADER -->
    <div class="card header">
        <div class="site-title"><span class="mark"></span>Mua Mail <span class="sub">v2.1</span></div>
        <button class="share-btn" onclick="shareLink()">SHARE</button>
    </div>

    <!-- PRODUCT BANNER -->
    <div class="product-banner">
        <div class="product-eyebrow">Sản phẩm #29</div>
        <div class="product-title">Mail live TikTok / YouTube</div>
        <div class="product-sub">Live 2 giờ kể từ khi mua — dùng được ngay</div>
        <div class="product-meta">
            <div class="stock-tag" id="stock-tag"><span class="dot"></span>Đang tải...</div>
            <div>
                <div class="price-big" id="price-display">59đ</div>
                <div class="price-unit">/ 1 mail</div>
            </div>
        </div>
    </div>

    <!-- LOGIN THUEMAIL (ẩn khi đã login) -->
    <div class="card" id="login-card">
        <div class="tm-label">Đăng nhập thuemail.net</div>
        <input class="tm-input" type="text"     id="tm-user" placeholder="Username">
        <input class="tm-input" type="password" id="tm-pass" placeholder="Mật khẩu"
               onkeydown="if(event.key==='Enter') tmLogin()">
        <button class="tm-btn" id="tm-login-btn" onclick="tmLogin()">
            Đăng nhập
        </button>
        <div class="msg" id="login-msg" style="color:var(--warn);"></div>
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
            <input type="number" class="qty-input" id="qty"
                   placeholder="Số lượng" min="1" max="50"
                   oninput="calcTotal()">
            <div class="total-display" id="total">0đ</div>
        </div>
        <button class="buy-btn" id="buy-btn" onclick="doBuy()" disabled>
            Đăng nhập thuemail để mua
        </button>
        <div class="status" id="status-box" style="margin-top:8px;"></div>
    </div>

    <!-- KẾT QUẢ MAIL -->
    <div class="card" id="result-card" style="display:none;">
        <div class="section-title">Mail vừa mua</div>
        <div class="result-box show" id="mail-list"></div>
        <button class="copy-all-btn" style="margin-top:8px;" onclick="copyAll()">
            COPY TẤT CẢ
        </button>
    </div>

    <!-- LỊCH SỬ -->
    <div class="card">
        <div class="section-title">Lịch sử mua</div>
        <div id="history-list">
            <div style="font-size:12px;color:var(--text-faint);text-align:center;padding:8px;font-family:var(--font-mono);">
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
                let tagEl = document.getElementById('stock-tag');
                tagEl.classList.toggle('out', !p.available);
                tagEl.innerHTML = '<span class="dot"></span>' +
                    (p.available
                        ? 'Còn: ' + Number(p.stock).toLocaleString('vi')
                        : 'Hết hàng');
                if(!p.available){
                    document.getElementById('buy-btn').disabled  = true;
                    document.getElementById('buy-btn').innerText = 'Hết hàng';
                }
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
        setMsg('Nhập đầy đủ thông tin','var(--warn)');
        return;
    }

    let btn = document.getElementById('tm-login-btn');
    btn.disabled  = true;
    btn.innerText = 'Đang đăng nhập...';
    setMsg('','');

    try{
        let fd = new FormData();
        fd.append('action',   'login');
        fd.append('username', user);
        fd.append('password', pass);
        const res  = await fetch(PROXY, {method:'POST', body:fd});
        const data = await res.json();

        if(data.error){
            setMsg(data.error, 'var(--warn)');
            return;
        }

        TM_SESSION   = data.phpsessid;
        TM_USER_NAME = user;
        localStorage.setItem('tm_sess',  TM_SESSION);
        localStorage.setItem('tm_uname', TM_USER_NAME);

        showSession();
        loadStock();

    }catch(e){
        setMsg('Lỗi kết nối', 'var(--warn)');
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
    document.getElementById('buy-btn').innerText = 'MUA MAIL';
}

function tmLogout(){
    TM_SESSION = ''; TM_USER_NAME = '';
    localStorage.removeItem('tm_sess');
    localStorage.removeItem('tm_uname');
    document.getElementById('login-card').style.display   = 'block';
    document.getElementById('session-card').style.display = 'none';
    document.getElementById('buy-btn').disabled  = true;
    document.getElementById('buy-btn').innerText = 'Đăng nhập thuemail để mua';
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
    let q = parseInt(document.getElementById('qty').value)||0;
    document.getElementById('total').innerText =
        Number(q * PRICE).toLocaleString('vi') + 'đ';
}

/* ================================
   MUA
================================ */
async function doBuy(){
    let qty = parseInt(document.getElementById('qty').value);
    if(!qty||qty<1){
        showStatus('Nhập số lượng','error'); return;
    }

    let btn = document.getElementById('buy-btn');
    btn.disabled  = true;
    btn.innerText = 'Đang xử lý...';
    showStatus('Đang mua mail...','loading');

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
            showStatus(data.error||data.message||'Mua thất bại', 'error');
            return;
        }

        LAST_MAILS = data.keys || [];
        if(LAST_MAILS.length === 0){
            showStatus('Không nhận được mail', 'error');
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

        document.getElementById('qty').value  = '';
        document.getElementById('total').innerText = '0đ';
        showStatus('Mua thành công ' + LAST_MAILS.length + ' mail', 'success');

    }catch(e){
        showStatus('Lỗi: ' + e.message, 'error');
    }finally{
        btn.disabled  = false;
        btn.innerText = 'MUA MAIL';
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
        btn.innerText = 'ĐÃ COPY TẤT CẢ';
        setTimeout(()=>{ btn.innerText = 'COPY TẤT CẢ'; }, 2000);
    });
}

/* ================================
   LỊCH SỬ
================================ */
function renderHistory(){
    let box = document.getElementById('history-list');
    if(!HISTORY.length){
        box.innerHTML='<div style="font-size:12px;color:var(--text-faint);text-align:center;padding:8px;font-family:var(--font-mono);">Chưa có lịch sử</div>';
        return;
    }
    box.innerHTML = HISTORY.slice(0,15).map(h=>`
        <div class="history-item">
            <div class="history-row">
                <span>${h.qty} mail</span>
                <span style="font-weight:600;color:var(--accent);">
                    ${Number(h.cost).toLocaleString('vi')}đ
                </span>
            </div>
            <div class="history-meta">
                ${h.time}${h.code?' &nbsp;·&nbsp; Mã: '+h.code:''}
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
            btn.innerText = 'COPIED';
            setTimeout(()=>{ btn.innerText = 'SHARE'; }, 2000);
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

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Position Finder</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: system-ui, sans-serif; background: #f3f4f6; color: #111; min-height: 100vh; padding: 24px; }
h1 { font-size: 15px; font-weight: 600; color: #374151; margin-bottom: 16px; letter-spacing: -0.2px; }

#uploadZone {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 10px; width: 100%; max-width: 680px; min-height: 280px;
  border: 2px dashed #d1d5db; border-radius: 10px; background: #fff;
  cursor: pointer; transition: border-color .15s, background .15s; padding: 40px; text-align: center;
}
#uploadZone:hover { border-color: #3b82f6; background: #eff6ff; }
#uploadZone input { display: none; }
#uploadZone p { font-size: 14px; color: #6b7280; }
#uploadZone small { font-size: 12px; color: #9ca3af; }
.up-btn { background: #3b82f6; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; }

#workspace { display: none; flex-direction: column; gap: 16px; align-items: flex-start; }
#layout { display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap; }

#imgContainer {
  position: relative; display: inline-block;
  border-radius: 4px; overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.12); flex-shrink: 0;
  cursor: crosshair;
}
#imgContainer img { display: block; max-width: 100%; height: auto; user-select: none; pointer-events: none; }
#gridOv {
  position: absolute; inset: 0; pointer-events: none;
  background-image: linear-gradient(rgba(59,130,246,.07) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(59,130,246,.07) 1px, transparent 1px);
  background-size: 20px 20px;
}
#crosshair {
  position: absolute; width: 16px; height: 16px;
  pointer-events: none; display: none;
  transform: translate(-50%,-50%); z-index: 5;
}
#crosshair::before, #crosshair::after { content: ''; position: absolute; background: rgba(239,68,68,.8); }
#crosshair::before { width: 1px; height: 100%; left: 50%; top: 0; }
#crosshair::after  { height: 1px; width: 100%; top: 50%; left: 0; }

/* ── BOX ── */
.drag-box {
  position: absolute; top: 0; left: 0;
  border-radius: 3px; border-width: 2px; border-style: solid;
  cursor: move; touch-action: none; user-select: none; z-index: 10;
}
.drag-box.active-box { z-index: 20; outline: 2px solid #fbbf24; outline-offset: 2px; }

/* resize handle */
.resize-handle {
  position: absolute; right: 0; bottom: 0;
  width: 14px; height: 14px; cursor: se-resize;
  background: rgba(255,255,255,0.7);
  border-top: 2px solid currentColor; border-left: 2px solid currentColor;
  border-radius: 2px 0 0 0;
}

/* ── PANEL ── */
#panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px 16px; min-width: 240px; font-size: 13px; }
#panel h2 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #9ca3af; margin-bottom: 10px; }
.row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #f3f4f6; }
.row:last-of-type { border-bottom: none; }
.row span:first-child { color: #6b7280; }
.row .val { font-family: 'Courier New', monospace; font-size: 14px; font-weight: 700; color: #1d4ed8; min-width: 50px; text-align: right; }
.color-row { display: flex; align-items: center; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #f3f4f6; }
.color-row span { color: #6b7280; }
.color-row input[type="color"] { width: 36px; height: 24px; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; padding: 1px; background: none; }

#boxList { margin-top: 12px; display: flex; flex-direction: column; gap: 6px; max-height: 220px; overflow-y: auto; }
.box-item { display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 6px; border: 1px solid #e5e7eb; cursor: pointer; font-size: 12px; background: #f9fafb; transition: background .12s; }
.box-item:hover { background: #f3f4f6; }
.box-item.selected { background: #eff6ff; border-color: #bfdbfe; }
.box-swatch { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }
.box-label { flex: 1; color: #374151; font-family: 'Courier New', monospace; }
.box-del { background: none; border: none; cursor: pointer; color: #9ca3af; font-size: 14px; line-height: 1; padding: 0 2px; }
.box-del:hover { color: #ef4444; }

.btns { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
.btn { flex: 1; padding: 7px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; border: 1px solid #d1d5db; background: #f9fafb; color: #374151; transition: .12s; white-space: nowrap; }
.btn:hover { background: #f3f4f6; border-color: #9ca3af; }
.btn.copy { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
.btn.copy:hover { background: #dbeafe; }
.btn.add  { background: #f0fdf4; border-color: #bbf7d0; color: #16a34a; }
.btn.add:hover  { background: #dcfce7; }
#feedback { font-size: 11px; color: #16a34a; text-align: center; margin-top: 6px; min-height: 14px; opacity: 0; transition: opacity .3s; }
#feedback.show { opacity: 1; }
#clickInfo { font-size: 11px; color: #6b7280; margin-top: 10px; padding-top: 10px; border-top: 1px solid #f3f4f6; min-height: 34px; }
#clickInfo strong { color: #374151; }
.sep { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #9ca3af; margin: 12px 0 6px; }
</style>
</head>
<body>

<h1>QR Position Finder</h1>

<label id="uploadZone" for="fi">
  <input type="file" id="fi" accept="image/*">
  <p>Seret gambar ke sini atau klik untuk memilih</p>
  <small>PNG · JPG · WebP</small>
  <div class="up-btn">Pilih Gambar</div>
</label>

<div id="workspace">
  <div id="layout">
    <div id="imgContainer">
      <img id="img" src="" alt="">
      <div id="gridOv"></div>
      <div id="crosshair"></div>
    </div>

    <div id="panel">
      <h2>Box Aktif</h2>
      <div class="row"><span>x</span><span class="val" id="vx">0</span></div>
      <div class="row"><span>y</span><span class="val" id="vy">0</span></div>
      <div class="row"><span>width</span><span class="val" id="vw">50</span></div>
      <div class="row"><span>height</span><span class="val" id="vh">50</span></div>
      <div class="color-row">
        <span>color</span>
        <input type="color" id="colorPicker" value="#3b82f6">
      </div>
      <div class="sep">Semua Box</div>
      <div id="boxList"></div>
      <div class="btns">
        <button class="btn add" id="addBtn">＋ Tambah Box</button>
        <button class="btn" id="resetBtn">↺ Reset</button>
      </div>
      <div class="btns">
        <button class="btn copy" id="copyBtn">⎘ Copy JSON Array</button>
      </div>
      <div id="feedback">✓ Disalin!</div>
      <div id="clickInfo">Klik gambar untuk pindahkan box aktif.</div>
    </div>
  </div>
</div>

<script>
(function () {
  var PALETTE = ['#3b82f6','#ef4444','#22c55e','#f59e0b','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1','#84cc16'];

  var fi         = document.getElementById('fi');
  var img        = document.getElementById('img');
  var container  = document.getElementById('imgContainer');
  var workspace  = document.getElementById('workspace');
  var uploadZone = document.getElementById('uploadZone');
  var crosshair  = document.getElementById('crosshair');
  var clickInfo  = document.getElementById('clickInfo');
  var feedback   = document.getElementById('feedback');
  var colorPicker= document.getElementById('colorPicker');
  var boxList    = document.getElementById('boxList');
  var vx=document.getElementById('vx'), vy=document.getElementById('vy');
  var vw=document.getElementById('vw'), vh=document.getElementById('vh');

  var natW=0, natH=0, boxes=[], activeId=null, nextId=1, paletteIdx=0;

  function scale() { return natW ? img.getBoundingClientRect().width / natW : 1; }
  function toReal(v) { return Math.round(v / scale()); }
  function getBox(id) { return boxes.find(function(b){ return b.id===id; }); }
  function getActive() { return getBox(activeId); }

  function clamp(val, min, max) { return Math.max(min, Math.min(max, val)); }

  function applyBoxDOM(b) {
    var cW = container.clientWidth, cH = container.clientHeight;
    b.px = clamp(b.px, 0, cW - b.bw);
    b.py = clamp(b.py, 0, cH - b.bh);
    b.el.style.left   = b.px + 'px';
    b.el.style.top    = b.py + 'px';
    b.el.style.width  = b.bw + 'px';
    b.el.style.height = b.bh + 'px';
  }

  function updatePanel() {
    var b = getActive(); if (!b) return;
    vx.textContent = toReal(b.px);
    vy.textContent = toReal(b.py);
    vw.textContent = toReal(b.bw);
    vh.textContent = toReal(b.bh);
    colorPicker.value = b.color;
  }

  function renderList() {
    boxList.innerHTML = '';
    boxes.forEach(function(b) {
      var item = document.createElement('div');
      item.className = 'box-item' + (b.id === activeId ? ' selected' : '');
      var sw = document.createElement('div');
      sw.className = 'box-swatch'; sw.style.background = b.color;
      var lbl = document.createElement('span');
      lbl.className = 'box-label';
      lbl.textContent = 'Box ' + b.id + ' (' + toReal(b.px) + ', ' + toReal(b.py) + ')';
      var del = document.createElement('button');
      del.className = 'box-del'; del.textContent = '×'; del.title = 'Hapus';
      del.addEventListener('click', function(e){ e.stopPropagation(); removeBox(b.id); });
      item.appendChild(sw); item.appendChild(lbl); item.appendChild(del);
      item.addEventListener('click', function(){ setActive(b.id); });
      boxList.appendChild(item);
    });
  }

  function setActive(id) {
    activeId = id;
    boxes.forEach(function(b){ b.el.classList.toggle('active-box', b.id === id); });
    updatePanel(); renderList();
  }

  function applyColor(el, color) {
    el.style.borderColor = color;
    var r=parseInt(color.slice(1,3),16), g=parseInt(color.slice(3,5),16), bl=parseInt(color.slice(5,7),16);
    el.style.background = 'rgba('+r+','+g+','+bl+',0.13)';
  }

  /* ── CREATE BOX ── */
  function createBox(px, py, bw, bh, color) {
    bw = bw||50; bh = bh||50;
    color = color || PALETTE[paletteIdx % PALETTE.length]; paletteIdx++;
    var id = nextId++;

    var el = document.createElement('div');
    el.className = 'drag-box';
    applyColor(el, color);

    /* resize handle inside box */
    var handle = document.createElement('div');
    handle.className = 'resize-handle';
    handle.style.color = color;
    el.appendChild(handle);

    container.appendChild(el);

    var b = { id:id, el:el, px:px||0, py:py||0, bw:bw, bh:bh, color:color };
    boxes.push(b);
    applyBoxDOM(b);
    attachEvents(b, handle);
    setActive(id);
    renderList();
    return b;
  }

  function removeBox(id) {
    var b = getBox(id); if (!b) return;
    container.removeChild(b.el);
    boxes = boxes.filter(function(x){ return x.id !== id; });
    if (activeId === id) activeId = boxes.length ? boxes[boxes.length-1].id : null;
    if (activeId) setActive(activeId);
    else { vx.textContent=vy.textContent=vw.textContent=vh.textContent='—'; }
    renderList();
  }

  /* ── DRAG & RESIZE via native pointer events ── */
  function attachEvents(b, handle) {

    /* — DRAG — */
    var dragActive = false, dragOffX = 0, dragOffY = 0;

    b.el.addEventListener('pointerdown', function(e) {
      if (e.target === handle) return; /* let resize handle its own */
      e.preventDefault();
      setActive(b.id);
      dragActive = true;
      var rect = container.getBoundingClientRect();
      dragOffX = e.clientX - rect.left - b.px;
      dragOffY = e.clientY - rect.top  - b.py;
      b.el.setPointerCapture(e.pointerId);
    });

    b.el.addEventListener('pointermove', function(e) {
      if (!dragActive) return;
      var rect = container.getBoundingClientRect();
      b.px = e.clientX - rect.left - dragOffX;
      b.py = e.clientY - rect.top  - dragOffY;
      applyBoxDOM(b);
      updatePanel();
      /* update label without full DOM rebuild for perf */
      var lbl = boxList.querySelector('[data-id="'+b.id+'"] .box-label');
      if (lbl) lbl.textContent = 'Box '+b.id+' ('+toReal(b.px)+', '+toReal(b.py)+')';
    });

    b.el.addEventListener('pointerup',    function(){ dragActive = false; renderList(); });
    b.el.addEventListener('pointercancel',function(){ dragActive = false; renderList(); });

    /* — RESIZE — */
    var resizeActive = false, resStartX=0, resStartY=0, resStartW=0, resStartH=0;

    handle.addEventListener('pointerdown', function(e) {
      e.preventDefault(); e.stopPropagation();
      setActive(b.id);
      resizeActive = true;
      resStartX = e.clientX; resStartY = e.clientY;
      resStartW = b.bw;      resStartH = b.bh;
      handle.setPointerCapture(e.pointerId);
    });

    handle.addEventListener('pointermove', function(e) {
      if (!resizeActive) return;
      b.bw = Math.max(20, resStartW + (e.clientX - resStartX));
      b.bh = Math.max(20, resStartH + (e.clientY - resStartY));
      applyBoxDOM(b);
      updatePanel();
    });

    handle.addEventListener('pointerup',    function(){ resizeActive = false; renderList(); });
    handle.addEventListener('pointercancel',function(){ resizeActive = false; renderList(); });
  }

  /* ── COLOR PICKER ── */
  colorPicker.addEventListener('input', function() {
    var b = getActive(); if (!b) return;
    b.color = colorPicker.value;
    applyColor(b.el, b.color);
    b.el.querySelector('.resize-handle').style.color = b.color;
    renderList();
  });

  /* ── LOAD IMAGE ── */
  function loadFile(file) {
    if (!file || !file.type.startsWith('image/')) return;
    var url = URL.createObjectURL(file);
    img.onload = function() {
      natW = img.naturalWidth; natH = img.naturalHeight;
      uploadZone.style.display = 'none'; workspace.style.display = 'flex';
      boxes.forEach(function(b){ container.removeChild(b.el); });
      boxes=[]; activeId=null; nextId=1; paletteIdx=0;
      createBox(0, 0, 50, 50, PALETTE[0]);
    };
    img.src = url;
  }

  fi.addEventListener('change', function(e){ loadFile(e.target.files[0]); });
  uploadZone.addEventListener('dragover', function(e){ e.preventDefault(); });
  uploadZone.addEventListener('drop', function(e){ e.preventDefault(); loadFile(e.dataTransfer.files[0]); });
  document.addEventListener('dragover', function(e){ e.preventDefault(); });
  document.addEventListener('drop', function(e){ e.preventDefault(); if(e.dataTransfer.files[0]) loadFile(e.dataTransfer.files[0]); });

  /* ── CLICK IMAGE → place active box ── */
  container.addEventListener('click', function(e) {
    if (e.target.classList.contains('drag-box') || e.target.classList.contains('resize-handle')) return;
    var rect = container.getBoundingClientRect();
    var cx = e.clientX - rect.left, cy = e.clientY - rect.top;
    var b = getActive(); if (!b) return;
    b.px = cx - b.bw/2; b.py = cy - b.bh/2;
    applyBoxDOM(b); updatePanel(); renderList();
    crosshair.style.left = cx+'px'; crosshair.style.top = cy+'px'; crosshair.style.display = 'block';
    clickInfo.innerHTML = '<strong>Klik:</strong> x='+toReal(cx)+', y='+toReal(cy)+
      ' &nbsp;|&nbsp; size: '+toReal(b.bw)+'&times;'+toReal(b.bh)+' px';
  });

  /* ── BUTTONS ── */
  document.getElementById('addBtn').addEventListener('click', function() {
    var off = (boxes.length % 6) * 20; createBox(off, off, 50, 50);
  });
  document.getElementById('resetBtn').addEventListener('click', function() {
    var b = getActive(); if (!b) return;
    b.px=0; b.py=0; b.bw=50; b.bh=50;
    applyBoxDOM(b); updatePanel(); renderList();
    crosshair.style.display = 'none';
    clickInfo.textContent = 'Klik pada gambar untuk memindahkan kotak.';
  });
  document.getElementById('copyBtn').addEventListener('click', function() {
    var arr = boxes.map(function(b){
      return { x:toReal(b.px), y:toReal(b.py), width:toReal(b.bw), height:toReal(b.bh), color:b.color };
    });
    navigator.clipboard.writeText(JSON.stringify(arr, null, 2)).then(function() {
      feedback.classList.add('show');
      setTimeout(function(){ feedback.classList.remove('show'); }, 2000);
    });
  });

  window.addEventListener('resize', function() {
    if (!natW) return;
    boxes.forEach(function(b){ applyBoxDOM(b); }); updatePanel();
  });
})();
</script>
</body>
</html>
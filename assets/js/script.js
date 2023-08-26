const pntSelector = (el, all = false) => {
    el = el.trim()
    if (all) {
        return [...document.querySelectorAll(el)]
    } else {
        return document.querySelector(el)
    }
}
const pntEvent = (type, el, listener, all = false) => {
    let selectEl = pntSelector(el, all)
    if (selectEl) {
        if (all) {
            selectEl.forEach(e => e.addEventListener(type, listener))
        } else {
            selectEl.addEventListener(type, listener)
        }
    }
}
const setModalNotify = (el, op, h, p) => {
    let c = el.className.split(" ");
    for(let i=0; i<c.length; i++){
        if(c[i].indexOf("pnt")==0){
            el.classList.remove(c[i]);
        }
    }
    el.classList.add('pnt-'+op);
    el.querySelector('.modal-notify-text h6').innerHTML = h
    el.querySelector('.modal-notify-text p').innerHTML = p
}

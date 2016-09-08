!
function(a, b) {
    var c,
    d,
    e,
    f,
    g,
    h,
    i,
    j,
    k,
    l,
    m,
    n,
    o,
    p = a.Zepto || a.$,
    q = !1,
    r = !1,
    s = {
        initialize: function(a) {
            function b() {
                d.css({
                    top: window.innerHeight + window.scrollY - 60 + "px",
                    "-webkit-transition": "none"
                }),
                o.css({
                    top: window.scrollY + 50 + "px"
                })
            }
            if (p("body").attr("notshow")) return s.taojiaHide = function() {},
            s.taojiaShow = function() {},
            s.circleHide = function() {},
            s.circleShow = function() {},
            !1;
            var m = this;
            m._checkSysType = function() {
                var a = "m",
                b = location.host;
                return true
            } (),
            m._browser = function() {
                for (var a = navigator.userAgent, b = ["iPhone OS ", "Android "], c = {
                    iphone: !1,
                    adnroid: !1
                },
                d = "", e = [], f = 0; f < b.length; f++) {
                    var g = a.indexOf(b[f]);
                    if (g > -1) {
                        switch (f) {
                        case 0:
                            c.iphone = !0;
                            break;
                        case 1:
                            c.adnroid = !0
                        }
                        var h = b[f].length;
                        d = a.substr(g + h, 6),
                        e = d.split(/_|\./)
                    }
                }
                return {
                    iphone: c.iphone,
                    android: c.adnroid,
                    version: parseFloat(e.join("."))
                }
            } ();
            var n = function() {
                var a,
                b = location.search,
                c = "ttid=",
                d = /ttid=(\w*)(?: |&|$)/;
                d.test(b) && (a = RegExp.$1);
                var e = a && "&" + c + a || "";
                return e
            } ();
            m._opt = a || {},
            c = p("body");
            var q = {
                _generate: function() {
                    var a = {};
                    a.s = "http://" + m._checkSysType + ".taobao.com/channel/act/sale/searchlist.html?pds=search%23h%23taojia" + n,
                    a.cart = web_url+"/?s=cart",
                    a.my = web_url+"/main.php?cg_u_type=1",
                    a.im = web_url+"/main.php?cg_u_type=1",
                    a.logis = web_url+"/main.php?cg_u_type=1",
                    a.more = web_url+"/main.php?cg_u_type=1" + n,
                    d = p(['<div id="J_Shade" class="none"></div>', '<div id="J_Taojia" class="taoplus">', '<div class="circle hide">', '<div class="tpicons">', "<ul>", '<li class="more"><a dataurl="' + a.more + '"></a><span class="bg"></span></li>', '<li class="logis"><a dataurl="' + a.logis + '"></a><span class="bg"></span></li>', '<li class="ww"><a dataurl="' + a.im + '"></a><span class="bg"></span></li>', '<li class="individ"><a dataurl="' + a.my + '"></a><span class="bg"></span></li>', '<li class="car"><a dataurl="' + a.cart + '"></a><span class="bg"></span></li>', '<li class="search"><a dataurl="' + a.s + '"></a><span class="bg"></span></li>', "</ul>", "</div>", '<div class="tplogo">', '<a href="http://m.taobao.com?pds=home%23h%23taojia' + n + '"></a><span class="bg"></span>', "</div>", "</div>", '<div class="tpbtn on">', "<div>", "<ul>", '<li class="icontao p"></li>', "</ul>", "</div>", '<p class="num none">', "</p>", "</div>", "</div>"].join("")),
                    c.append(d)
                }
            };
            q._generate(),
            e = p("#J_Taojia"),
            f = d.find(".tpbtn"),
            g = d.find(".tpicons a"),
            h = d.find(".tplogo a"),
            i = d.find(".circle"),
            j = f.find(".icontao"),
            k = f.find("ul"),
            l = f.find(".num"),
            o = p("#J_Shade"),
            m.events(),
            (m._browser.iphone && m._browser.version < 5 || m._browser.android && m._browser.version <= 2.1) && (o.css({
                position: "absolute"
            }), document.addEventListener("touchend", b, !1)),
            m._browser.android && m._browser.version >= 4.1 && e.css({
                "-webkit-transform": "translate3d(0, 0, 0)"
            })
        },
        events: function() {
            function a() {
                d("click#h#taojia"),
                1 == e.st() ? e.circleShow() : e.circleHide()
            }
            function c() {
                e.circleHide()
            }
            function d(a) {
                var b = a,
                c = (location.host, e._checkSysType);
                p.ajax({
                    url: "http://" + c + ".taobao.com/monitor.htm?callback=?",
                    type: "get",
                    data: {
                        type: "jsonp",
                        pds: b,
                        t: (new Date).getTime()
                    }
                })
            }
            var e = this;
            f.on("click", a),
            p(document).on("touchmove", 
            function(a) {
                q && a.preventDefault()
            }),
            o.on("click", c),
            g.click(function() {
                function a() {
                    f = setTimeout(function() {
                        e.circleHide(),
                        setTimeout(function() {
                            window.location.href = d
                        },
                        300),
                        clearTimeout(f)
                    },
                    500)
                }
                var c = p(this),
                d = c.attr("dataurl"),
                f = null;
                c.parent().hasClass("ww") && b.wangxin ? b.wangxin.waptowx({
                    fromNick: "",
                    toNick: "",
                    itemId: "",
                    wapwwUrl: d,
                    pageId: "h5_taobao_jia"
                }) : a()
            })
        },
        circleShow: function() {
            if (!r) {
                var a = this;
                a._opt.onShow && a._opt.onShow(),
                f.removeClass("on").addClass("off"),
                i.removeClass("hide").addClass("show"),
                o.removeClass("none"),
                (a._browser.iphone && a._browser.version < 5 || a._browser.android && a._browser.version <= 2.1) && o.css({
                    top: window.scrollY - 10 + "px"
                }),
                q = !0
            }
        },
        circleHide: function() {
            if (!r && !i.hasClass("hide")) {
                r = !0;
                var a = this;
                a._opt.onHide && a._opt.onHide();
                var a = this;
                f.removeClass("off").addClass("on"),
                i.removeClass("show").addClass("hide"),
                o.animate({
                    opacity: "0"
                },
                350, "linear", 
                function() {
                    o.addClass("none"),
                    o.attr("style", ""),
                    r = !1,
                    q = !1
                })
            }
        },
        taojiaHide: function() {
            var a = this;
            a.circleHide(),
            e.hide()
        },
        taojiaShow: function() {
            e.show()
        },
        st: function() {
            return f.hasClass("on") ? 1: f.hasClass("off") ? 2: void 0
        },
        getmsg: function() {
            var a = null,
            b = 1,
            c = !1;
            fn = function() {
                var e = k.find("li"),
                f = e.length,
                g = k.height(),
                h = (k.find(".iconact"), k.find(".iconact").length),
                i = (d.find(".iconww"), d.find(".iconlogis"), k[0].offsetLeft / g);
                return k.width(f * g),
                1 == f ? !1: (i >= 0 ? m && (l.html(m), c = !0) || l.html(n) : c && l.html(n), k.animate({
                    left: -(h + 1) * g
                },
                500, "linear", 
                function() {
                    b++,
                    e.eq(b - 1).addClass("iconact")
                }), h + 2 >= f ? (clearTimeout(a), c = !1) : a = setTimeout(fn, 2e3), void 0)
            },
            fn()
        }
    };
    b.taoplus = s
} (window, window.lib || (window.lib = {}));
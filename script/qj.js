//--------------module:jquery-------------

/*! jQuery v1.11.1 | (c) 2005, 2014 jQuery Foundation, Inc. | jquery.org/license */
!function (d, c)
{
    "object" == typeof module && "object" == typeof module.exports ? module.exports = d.document ? c(d, !0) : function (b)
    {
        if (!b.document)
        {
            throw new Error("jQuery requires a window with a document")
        }
        return c(b)
    } : c(d)
}("undefined" != typeof window ? window : this, function (a, b)
{
    var c = [], d = c.slice, e = c.concat, f = c.push, g = c.indexOf, h = {}, i = h.toString, j = h.hasOwnProperty, k = {}, l = "1.11.1", m = function (a, b)
    {
        return new m.fn.init(a, b)
    }, n = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, o = /^-ms-/, p = /-([\da-z])/gi, q = function (a, b)
    {
        return b.toUpperCase()
    };
    m.fn = m.prototype = {
        jquery: l, constructor: m, selector: "", length: 0, toArray: function ()
        {
            return d.call(this)
        }, get: function (a)
        {
            return null != a ? 0 > a ? this[a + this.length] : this[a] : d.call(this)
        }, pushStack: function (a)
        {
            var b = m.merge(this.constructor(), a);
            return b.prevObject = this, b.context = this.context, b
        }, each: function (a, b)
        {
            return m.each(this, a, b)
        }, map: function (a)
        {
            return this.pushStack(m.map(this, function (b, c)
            {
                return a.call(b, c, b)
            }))
        }, slice: function ()
        {
            return this.pushStack(d.apply(this, arguments))
        }, first: function ()
        {
            return this.eq(0)
        }, last: function ()
        {
            return this.eq(-1)
        }, eq: function (a)
        {
            var b = this.length, c = +a + (0 > a ? b : 0);
            return this.pushStack(c >= 0 && b > c ? [this[c]] : [])
        }, end: function ()
        {
            return this.prevObject || this.constructor(null)
        }, push: f, sort: c.sort, splice: c.splice
    }, m.extend = m.fn.extend = function ()
    {
        var a, b, c, d, e, f, g = arguments[0] || {}, h = 1, i = arguments.length, j = !1;
        for ("boolean" == typeof g && (j = g, g = arguments[h] || {}, h++), "object" == typeof g || m.isFunction(g) || (g = {}), h === i && (g = this, h--); i > h; h++)
        {
            if (null != (e = arguments[h]))
            {
                for (d in e)
                {
                    a = g[d], c = e[d], g !== c && (j && c && (m.isPlainObject(c) || (b = m.isArray(c))) ? (b ? (b = !1, f = a && m.isArray(a) ? a : []) : f = a && m.isPlainObject(a) ? a : {}, g[d] = m.extend(j, f, c)) : void 0 !== c && (g[d] = c))
                }
            }
        }
        return g
    }, m.extend({
        expando: "jQuery" + (l + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (a)
        {
            throw new Error(a)
        }, noop: function ()
        {
        }, isFunction: function (a)
        {
            return "function" === m.type(a)
        }, isArray: Array.isArray || function (a)
        {
            return "array" === m.type(a)
        }, isWindow: function (a)
        {
            return null != a && a == a.window
        }, isNumeric: function (a)
        {
            return !m.isArray(a) && a - parseFloat(a) >= 0
        }, isEmptyObject: function (a)
        {
            var b;
            for (b in a)
            {
                return !1
            }
            return !0
        }, isPlainObject: function (a)
        {
            var b;
            if (!a || "object" !== m.type(a) || a.nodeType || m.isWindow(a))
            {
                return !1
            }
            try
            {
                if (a.constructor && !j.call(a, "constructor") && !j.call(a.constructor.prototype, "isPrototypeOf"))
                {
                    return !1
                }
            } catch (c)
            {
                return !1
            }
            if (k.ownLast)
            {
                for (b in a)
                {
                    return j.call(a, b)
                }
            }
            for (b in a)
            {
            }
            return void 0 === b || j.call(a, b)
        }, type: function (a)
        {
            return null == a ? a + "" : "object" == typeof a || "function" == typeof a ? h[i.call(a)] || "object" : typeof a
        }, globalEval: function (b)
        {
            b && m.trim(b) && (a.execScript || function (b)
            {
                a.eval.call(a, b)
            })(b)
        }, camelCase: function (a)
        {
            return a.replace(o, "ms-").replace(p, q)
        }, nodeName: function (a, b)
        {
            return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase()
        }, each: function (a, b, c)
        {
            var d, e = 0, f = a.length, g = r(a);
            if (c)
            {
                if (g)
                {
                    for (; f > e; e++)
                    {
                        if (d = b.apply(a[e], c), d === !1)
                        {
                            break
                        }
                    }
                }
                else
                {
                    for (e in a)
                    {
                        if (d = b.apply(a[e], c), d === !1)
                        {
                            break
                        }
                    }
                }
            }
            else
            {
                if (g)
                {
                    for (; f > e; e++)
                    {
                        if (d = b.call(a[e], e, a[e]), d === !1)
                        {
                            break
                        }
                    }
                }
                else
                {
                    for (e in a)
                    {
                        if (d = b.call(a[e], e, a[e]), d === !1)
                        {
                            break
                        }
                    }
                }
            }
            return a
        }, trim: function (a)
        {
            return null == a ? "" : (a + "").replace(n, "")
        }, makeArray: function (a, b)
        {
            var c = b || [];
            return null != a && (r(Object(a)) ? m.merge(c, "string" == typeof a ? [a] : a) : f.call(c, a)), c
        }, inArray: function (a, b, c)
        {
            var d;
            if (b)
            {
                if (g)
                {
                    return g.call(b, a, c)
                }
                for (d = b.length, c = c ? 0 > c ? Math.max(0, d + c) : c : 0; d > c; c++)
                {
                    if (c in b && b[c] === a)
                    {
                        return c
                    }
                }
            }
            return -1
        }, merge: function (a, b)
        {
            var c = +b.length, d = 0, e = a.length;
            while (c > d)
            {
                a[e++] = b[d++]
            }
            if (c !== c)
            {
                while (void 0 !== b[d])
                {
                    a[e++] = b[d++]
                }
            }
            return a.length = e, a
        }, grep: function (a, b, c)
        {
            for (var d, e = [], f = 0, g = a.length, h = !c; g > f; f++)
            {
                d = !b(a[f], f), d !== h && e.push(a[f])
            }
            return e
        }, map: function (a, b, c)
        {
            var d, f = 0, g = a.length, h = r(a), i = [];
            if (h)
            {
                for (; g > f; f++)
                {
                    d = b(a[f], f, c), null != d && i.push(d)
                }
            }
            else
            {
                for (f in a)
                {
                    d = b(a[f], f, c), null != d && i.push(d)
                }
            }
            return e.apply([], i)
        }, guid: 1, proxy: function (a, b)
        {
            var c, e, f;
            return "string" == typeof b && (f = a[b], b = a, a = f), m.isFunction(a) ? (c = d.call(arguments, 2), e = function ()
            {
                return a.apply(b || this, c.concat(d.call(arguments)))
            }, e.guid = a.guid = a.guid || m.guid++, e) : void 0
        }, now: function ()
        {
            return +new Date
        }, support: k
    }), m.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (a, b)
    {
        h["[object " + b + "]"] = b.toLowerCase()
    });
    function r(a)
    {
        var b = a.length, c = m.type(a);
        return "function" === c || m.isWindow(a) ? !1 : 1 === a.nodeType && b ? !0 : "array" === c || 0 === b || "number" == typeof b && b > 0 && b - 1 in a
    }

    var s = function (a)
    {
        var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u = "sizzle" + -new Date, v = a.document, w = 0, x = 0, y = gb(), z = gb(), A = gb(), B = function (a, b)
        {
            return a === b && (l = !0), 0
        }, C = "undefined", D = 1 << 31, E = {}.hasOwnProperty, F = [], G = F.pop, H = F.push, I = F.push, J = F.slice, K = F.indexOf || function (a)
            {
                for (var b = 0, c = this.length; c > b; b++)
                {
                    if (this[b] === a)
                    {
                        return b
                    }
                }
                return -1
            }, L = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", M = "[\\x20\\t\\r\\n\\f]", N = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", O = N.replace("w", "w#"), P = "\\[" + M + "*(" + N + ")(?:" + M + "*([*^$|!~]?=)" + M + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + O + "))|)" + M + "*\\]", Q = ":(" + N + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + P + ")*)|.*)\\)|)", R = new RegExp("^" + M + "+|((?:^|[^\\\\])(?:\\\\.)*)" + M + "+$", "g"), S = new RegExp("^" + M + "*," + M + "*"), T = new RegExp("^" + M + "*([>+~]|" + M + ")" + M + "*"), U = new RegExp("=" + M + "*([^\\]'\"]*?)" + M + "*\\]", "g"), V = new RegExp(Q), W = new RegExp("^" + O + "$"), X = {
            ID: new RegExp("^#(" + N + ")"),
            CLASS: new RegExp("^\\.(" + N + ")"),
            TAG: new RegExp("^(" + N.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + P),
            PSEUDO: new RegExp("^" + Q),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + M + "*(even|odd|(([+-]|)(\\d*)n|)" + M + "*(?:([+-]|)" + M + "*(\\d+)|))" + M + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + L + ")$", "i"),
            needsContext: new RegExp("^" + M + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + M + "*((?:-\\d)?\\d*)" + M + "*\\)|)(?=[^-]|$)", "i")
        }, Y = /^(?:input|select|textarea|button)$/i, Z = /^h\d$/i, $ = /^[^{]+\{\s*\[native \w/, _ = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, ab = /[+~]/, bb = /'|\\/g, cb = new RegExp("\\\\([\\da-f]{1,6}" + M + "?|(" + M + ")|.)", "ig"), db = function (a, b, c)
        {
            var d = "0x" + b - 65536;
            return d !== d || c ? b : 0 > d ? String.fromCharCode(d + 65536) : String.fromCharCode(d >> 10 | 55296, 1023 & d | 56320)
        };
        try
        {
            I.apply(F = J.call(v.childNodes), v.childNodes), F[v.childNodes.length].nodeType
        } catch (eb)
        {
            I = {
                apply: F.length ? function (a, b)
                {
                    H.apply(a, J.call(b))
                } : function (a, b)
                {
                    var c = a.length, d = 0;
                    while (a[c++] = b[d++])
                    {
                    }
                    a.length = c - 1
                }
            }
        }
        function fb(a, b, d, e)
        {
            var f, h, j, k, l, o, r, s, w, x;
            if ((b ? b.ownerDocument || b : v) !== n && m(b), b = b || n, d = d || [], !a || "string" != typeof a)
            {
                return d
            }
            if (1 !== (k = b.nodeType) && 9 !== k)
            {
                return []
            }
            if (p && !e)
            {
                if (f = _.exec(a))
                {
                    if (j = f[1])
                    {
                        if (9 === k)
                        {
                            if (h = b.getElementById(j), !h || !h.parentNode)
                            {
                                return d
                            }
                            if (h.id === j)
                            {
                                return d.push(h), d
                            }
                        }
                        else
                        {
                            if (b.ownerDocument && (h = b.ownerDocument.getElementById(j)) && t(b, h) && h.id === j)
                            {
                                return d.push(h), d
                            }
                        }
                    }
                    else
                    {
                        if (f[2])
                        {
                            return I.apply(d, b.getElementsByTagName(a)), d
                        }
                        if ((j = f[3]) && c.getElementsByClassName && b.getElementsByClassName)
                        {
                            return I.apply(d, b.getElementsByClassName(j)), d
                        }
                    }
                }
                if (c.qsa && (!q || !q.test(a)))
                {
                    if (s = r = u, w = b, x = 9 === k && a, 1 === k && "object" !== b.nodeName.toLowerCase())
                    {
                        o = g(a), (r = b.getAttribute("id")) ? s = r.replace(bb, "\\$&") : b.setAttribute("id", s), s = "[id='" + s + "'] ", l = o.length;
                        while (l--)
                        {
                            o[l] = s + qb(o[l])
                        }
                        w = ab.test(a) && ob(b.parentNode) || b, x = o.join(",")
                    }
                    if (x)
                    {
                        try
                        {
                            return I.apply(d, w.querySelectorAll(x)), d
                        } catch (y)
                        {
                        } finally
                        {
                            r || b.removeAttribute("id")
                        }
                    }
                }
            }
            return i(a.replace(R, "$1"), b, d, e)
        }

        function gb()
        {
            var a = [];

            function b(c, e)
            {
                return a.push(c + " ") > d.cacheLength && delete b[a.shift()], b[c + " "] = e
            }

            return b
        }

        function hb(a)
        {
            return a[u] = !0, a
        }

        function ib(a)
        {
            var b = n.createElement("div");
            try
            {
                return !!a(b)
            } catch (c)
            {
                return !1
            } finally
            {
                b.parentNode && b.parentNode.removeChild(b), b = null
            }
        }

        function jb(a, b)
        {
            var c = a.split("|"), e = a.length;
            while (e--)
            {
                d.attrHandle[c[e]] = b
            }
        }

        function kb(a, b)
        {
            var c = b && a, d = c && 1 === a.nodeType && 1 === b.nodeType && (~b.sourceIndex || D) - (~a.sourceIndex || D);
            if (d)
            {
                return d
            }
            if (c)
            {
                while (c = c.nextSibling)
                {
                    if (c === b)
                    {
                        return -1
                    }
                }
            }
            return a ? 1 : -1
        }

        function lb(a)
        {
            return function (b)
            {
                var c = b.nodeName.toLowerCase();
                return "input" === c && b.type === a
            }
        }

        function mb(a)
        {
            return function (b)
            {
                var c = b.nodeName.toLowerCase();
                return ("input" === c || "button" === c) && b.type === a
            }
        }

        function nb(a)
        {
            return hb(function (b)
            {
                return b = +b, hb(function (c, d)
                {
                    var e, f = a([], c.length, b), g = f.length;
                    while (g--)
                    {
                        c[e = f[g]] && (c[e] = !(d[e] = c[e]))
                    }
                })
            })
        }

        function ob(a)
        {
            return a && typeof a.getElementsByTagName !== C && a
        }

        c = fb.support = {}, f = fb.isXML = function (a)
        {
            var b = a && (a.ownerDocument || a).documentElement;
            return b ? "HTML" !== b.nodeName : !1
        }, m = fb.setDocument = function (a)
        {
            var b, e = a ? a.ownerDocument || a : v, g = e.defaultView;
            return e !== n && 9 === e.nodeType && e.documentElement ? (n = e, o = e.documentElement, p = !f(e), g && g !== g.top && (g.addEventListener ? g.addEventListener("unload", function ()
            {
                m()
            }, !1) : g.attachEvent && g.attachEvent("onunload", function ()
            {
                m()
            })), c.attributes = ib(function (a)
            {
                return a.className = "i", !a.getAttribute("className")
            }), c.getElementsByTagName = ib(function (a)
            {
                return a.appendChild(e.createComment("")), !a.getElementsByTagName("*").length
            }), c.getElementsByClassName = $.test(e.getElementsByClassName) && ib(function (a)
            {
                return a.innerHTML = "<div class='a'></div><div class='a i'></div>", a.firstChild.className = "i", 2 === a.getElementsByClassName("i").length
            }), c.getById = ib(function (a)
            {
                return o.appendChild(a).id = u, !e.getElementsByName || !e.getElementsByName(u).length
            }), c.getById ? (d.find.ID = function (a, b)
            {
                if (typeof b.getElementById !== C && p)
                {
                    var c = b.getElementById(a);
                    return c && c.parentNode ? [c] : []
                }
            }, d.filter.ID = function (a)
            {
                var b = a.replace(cb, db);
                return function (a)
                {
                    return a.getAttribute("id") === b
                }
            }) : (delete d.find.ID, d.filter.ID = function (a)
            {
                var b = a.replace(cb, db);
                return function (a)
                {
                    var c = typeof a.getAttributeNode !== C && a.getAttributeNode("id");
                    return c && c.value === b
                }
            }), d.find.TAG = c.getElementsByTagName ? function (a, b)
            {
                return typeof b.getElementsByTagName !== C ? b.getElementsByTagName(a) : void 0
            } : function (a, b)
            {
                var c, d = [], e = 0, f = b.getElementsByTagName(a);
                if ("*" === a)
                {
                    while (c = f[e++])
                    {
                        1 === c.nodeType && d.push(c)
                    }
                    return d
                }
                return f
            }, d.find.CLASS = c.getElementsByClassName && function (a, b)
            {
                return typeof b.getElementsByClassName !== C && p ? b.getElementsByClassName(a) : void 0
            }, r = [], q = [], (c.qsa = $.test(e.querySelectorAll)) && (ib(function (a)
            {
                a.innerHTML = "<select msallowclip=''><option selected=''></option></select>", a.querySelectorAll("[msallowclip^='']").length && q.push("[*^$]=" + M + "*(?:''|\"\")"), a.querySelectorAll("[selected]").length || q.push("\\[" + M + "*(?:value|" + L + ")"), a.querySelectorAll(":checked").length || q.push(":checked")
            }), ib(function (a)
            {
                var b = e.createElement("input");
                b.setAttribute("type", "hidden"), a.appendChild(b).setAttribute("name", "D"), a.querySelectorAll("[name=d]").length && q.push("name" + M + "*[*^$|!~]?="), a.querySelectorAll(":enabled").length || q.push(":enabled", ":disabled"), a.querySelectorAll("*,:x"), q.push(",.*:")
            })), (c.matchesSelector = $.test(s = o.matches || o.webkitMatchesSelector || o.mozMatchesSelector || o.oMatchesSelector || o.msMatchesSelector)) && ib(function (a)
            {
                c.disconnectedMatch = s.call(a, "div"), s.call(a, "[s!='']:x"), r.push("!=", Q)
            }), q = q.length && new RegExp(q.join("|")), r = r.length && new RegExp(r.join("|")), b = $.test(o.compareDocumentPosition), t = b || $.test(o.contains) ? function (a, b)
            {
                var c = 9 === a.nodeType ? a.documentElement : a, d = b && b.parentNode;
                return a === d || !(!d || 1 !== d.nodeType || !(c.contains ? c.contains(d) : a.compareDocumentPosition && 16 & a.compareDocumentPosition(d)))
            } : function (a, b)
            {
                if (b)
                {
                    while (b = b.parentNode)
                    {
                        if (b === a)
                        {
                            return !0
                        }
                    }
                }
                return !1
            }, B = b ? function (a, b)
            {
                if (a === b)
                {
                    return l = !0, 0
                }
                var d = !a.compareDocumentPosition - !b.compareDocumentPosition;
                return d ? d : (d = (a.ownerDocument || a) === (b.ownerDocument || b) ? a.compareDocumentPosition(b) : 1, 1 & d || !c.sortDetached && b.compareDocumentPosition(a) === d ? a === e || a.ownerDocument === v && t(v, a) ? -1 : b === e || b.ownerDocument === v && t(v, b) ? 1 : k ? K.call(k, a) - K.call(k, b) : 0 : 4 & d ? -1 : 1)
            } : function (a, b)
            {
                if (a === b)
                {
                    return l = !0, 0
                }
                var c, d = 0, f = a.parentNode, g = b.parentNode, h = [a], i = [b];
                if (!f || !g)
                {
                    return a === e ? -1 : b === e ? 1 : f ? -1 : g ? 1 : k ? K.call(k, a) - K.call(k, b) : 0
                }
                if (f === g)
                {
                    return kb(a, b)
                }
                c = a;
                while (c = c.parentNode)
                {
                    h.unshift(c)
                }
                c = b;
                while (c = c.parentNode)
                {
                    i.unshift(c)
                }
                while (h[d] === i[d])
                {
                    d++
                }
                return d ? kb(h[d], i[d]) : h[d] === v ? -1 : i[d] === v ? 1 : 0
            }, e) : n
        }, fb.matches = function (a, b)
        {
            return fb(a, null, null, b)
        }, fb.matchesSelector = function (a, b)
        {
            if ((a.ownerDocument || a) !== n && m(a), b = b.replace(U, "='$1']"), !(!c.matchesSelector || !p || r && r.test(b) || q && q.test(b)))
            {
                try
                {
                    var d = s.call(a, b);
                    if (d || c.disconnectedMatch || a.document && 11 !== a.document.nodeType)
                    {
                        return d
                    }
                } catch (e)
                {
                }
            }
            return fb(b, n, null, [a]).length > 0
        }, fb.contains = function (a, b)
        {
            return (a.ownerDocument || a) !== n && m(a), t(a, b)
        }, fb.attr = function (a, b)
        {
            (a.ownerDocument || a) !== n && m(a);
            var e = d.attrHandle[b.toLowerCase()], f = e && E.call(d.attrHandle, b.toLowerCase()) ? e(a, b, !p) : void 0;
            return void 0 !== f ? f : c.attributes || !p ? a.getAttribute(b) : (f = a.getAttributeNode(b)) && f.specified ? f.value : null
        }, fb.error = function (a)
        {
            throw new Error("Syntax error, unrecognized expression: " + a)
        }, fb.uniqueSort = function (a)
        {
            var b, d = [], e = 0, f = 0;
            if (l = !c.detectDuplicates, k = !c.sortStable && a.slice(0), a.sort(B), l)
            {
                while (b = a[f++])
                {
                    b === a[f] && (e = d.push(f))
                }
                while (e--)
                {
                    a.splice(d[e], 1)
                }
            }
            return k = null, a
        }, e = fb.getText = function (a)
        {
            var b, c = "", d = 0, f = a.nodeType;
            if (f)
            {
                if (1 === f || 9 === f || 11 === f)
                {
                    if ("string" == typeof a.textContent)
                    {
                        return a.textContent
                    }
                    for (a = a.firstChild; a; a = a.nextSibling)
                    {
                        c += e(a)
                    }
                }
                else
                {
                    if (3 === f || 4 === f)
                    {
                        return a.nodeValue
                    }
                }
            }
            else
            {
                while (b = a[d++])
                {
                    c += e(b)
                }
            }
            return c
        }, d = fb.selectors = {
            cacheLength: 50,
            createPseudo: hb,
            match: X,
            attrHandle: {},
            find: {},
            relative: {">": {dir: "parentNode", first: !0}, " ": {dir: "parentNode"}, "+": {dir: "previousSibling", first: !0}, "~": {dir: "previousSibling"}},
            preFilter: {
                ATTR: function (a)
                {
                    return a[1] = a[1].replace(cb, db), a[3] = (a[3] || a[4] || a[5] || "").replace(cb, db), "~=" === a[2] && (a[3] = " " + a[3] + " "), a.slice(0, 4)
                }, CHILD: function (a)
                {
                    return a[1] = a[1].toLowerCase(), "nth" === a[1].slice(0, 3) ? (a[3] || fb.error(a[0]), a[4] = +(a[4] ? a[5] + (a[6] || 1) : 2 * ("even" === a[3] || "odd" === a[3])), a[5] = +(a[7] + a[8] || "odd" === a[3])) : a[3] && fb.error(a[0]), a
                }, PSEUDO: function (a)
                {
                    var b, c = !a[6] && a[2];
                    return X.CHILD.test(a[0]) ? null : (a[3] ? a[2] = a[4] || a[5] || "" : c && V.test(c) && (b = g(c, !0)) && (b = c.indexOf(")", c.length - b) - c.length) && (a[0] = a[0].slice(0, b), a[2] = c.slice(0, b)), a.slice(0, 3))
                }
            },
            filter: {
                TAG: function (a)
                {
                    var b = a.replace(cb, db).toLowerCase();
                    return "*" === a ? function ()
                    {
                        return !0
                    } : function (a)
                    {
                        return a.nodeName && a.nodeName.toLowerCase() === b
                    }
                }, CLASS: function (a)
                {
                    var b = y[a + " "];
                    return b || (b = new RegExp("(^|" + M + ")" + a + "(" + M + "|$)")) && y(a, function (a)
                        {
                            return b.test("string" == typeof a.className && a.className || typeof a.getAttribute !== C && a.getAttribute("class") || "")
                        })
                }, ATTR: function (a, b, c)
                {
                    return function (d)
                    {
                        var e = fb.attr(d, a);
                        return null == e ? "!=" === b : b ? (e += "", "=" === b ? e === c : "!=" === b ? e !== c : "^=" === b ? c && 0 === e.indexOf(c) : "*=" === b ? c && e.indexOf(c) > -1 : "$=" === b ? c && e.slice(-c.length) === c : "~=" === b ? (" " + e + " ").indexOf(c) > -1 : "|=" === b ? e === c || e.slice(0, c.length + 1) === c + "-" : !1) : !0
                    }
                }, CHILD: function (a, b, c, d, e)
                {
                    var f = "nth" !== a.slice(0, 3), g = "last" !== a.slice(-4), h = "of-type" === b;
                    return 1 === d && 0 === e ? function (a)
                    {
                        return !!a.parentNode
                    } : function (b, c, i)
                    {
                        var j, k, l, m, n, o, p = f !== g ? "nextSibling" : "previousSibling", q = b.parentNode, r = h && b.nodeName.toLowerCase(), s = !i && !h;
                        if (q)
                        {
                            if (f)
                            {
                                while (p)
                                {
                                    l = b;
                                    while (l = l[p])
                                    {
                                        if (h ? l.nodeName.toLowerCase() === r : 1 === l.nodeType)
                                        {
                                            return !1
                                        }
                                    }
                                    o = p = "only" === a && !o && "nextSibling"
                                }
                                return !0
                            }
                            if (o = [g ? q.firstChild : q.lastChild], g && s)
                            {
                                k = q[u] || (q[u] = {}), j = k[a] || [], n = j[0] === w && j[1], m = j[0] === w && j[2], l = n && q.childNodes[n];
                                while (l = ++n && l && l[p] || (m = n = 0) || o.pop())
                                {
                                    if (1 === l.nodeType && ++m && l === b)
                                    {
                                        k[a] = [w, n, m];
                                        break
                                    }
                                }
                            }
                            else
                            {
                                if (s && (j = (b[u] || (b[u] = {}))[a]) && j[0] === w)
                                {
                                    m = j[1]
                                }
                                else
                                {
                                    while (l = ++n && l && l[p] || (m = n = 0) || o.pop())
                                    {
                                        if ((h ? l.nodeName.toLowerCase() === r : 1 === l.nodeType) && ++m && (s && ((l[u] || (l[u] = {}))[a] = [w, m]), l === b))
                                        {
                                            break
                                        }
                                    }
                                }
                            }
                            return m -= e, m === d || m % d === 0 && m / d >= 0
                        }
                    }
                }, PSEUDO: function (a, b)
                {
                    var c, e = d.pseudos[a] || d.setFilters[a.toLowerCase()] || fb.error("unsupported pseudo: " + a);
                    return e[u] ? e(b) : e.length > 1 ? (c = [a, a, "", b], d.setFilters.hasOwnProperty(a.toLowerCase()) ? hb(function (a, c)
                    {
                        var d, f = e(a, b), g = f.length;
                        while (g--)
                        {
                            d = K.call(a, f[g]), a[d] = !(c[d] = f[g])
                        }
                    }) : function (a)
                    {
                        return e(a, 0, c)
                    }) : e
                }
            },
            pseudos: {
                not: hb(function (a)
                {
                    var b = [], c = [], d = h(a.replace(R, "$1"));
                    return d[u] ? hb(function (a, b, c, e)
                    {
                        var f, g = d(a, null, e, []), h = a.length;
                        while (h--)
                        {
                            (f = g[h]) && (a[h] = !(b[h] = f))
                        }
                    }) : function (a, e, f)
                    {
                        return b[0] = a, d(b, null, f, c), !c.pop()
                    }
                }), has: hb(function (a)
                {
                    return function (b)
                    {
                        return fb(a, b).length > 0
                    }
                }), contains: hb(function (a)
                {
                    return function (b)
                    {
                        return (b.textContent || b.innerText || e(b)).indexOf(a) > -1
                    }
                }), lang: hb(function (a)
                {
                    return W.test(a || "") || fb.error("unsupported lang: " + a), a = a.replace(cb, db).toLowerCase(), function (b)
                    {
                        var c;
                        do {
                            if (c = p ? b.lang : b.getAttribute("xml:lang") || b.getAttribute("lang"))
                            {
                                return c = c.toLowerCase(), c === a || 0 === c.indexOf(a + "-")
                            }
                        } while ((b = b.parentNode) && 1 === b.nodeType);
                        return !1
                    }
                }), target: function (b)
                {
                    var c = a.location && a.location.hash;
                    return c && c.slice(1) === b.id
                }, root: function (a)
                {
                    return a === o
                }, focus: function (a)
                {
                    return a === n.activeElement && (!n.hasFocus || n.hasFocus()) && !!(a.type || a.href || ~a.tabIndex)
                }, enabled: function (a)
                {
                    return a.disabled === !1
                }, disabled: function (a)
                {
                    return a.disabled === !0
                }, checked: function (a)
                {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && !!a.checked || "option" === b && !!a.selected
                }, selected: function (a)
                {
                    return a.parentNode && a.parentNode.selectedIndex, a.selected === !0
                }, empty: function (a)
                {
                    for (a = a.firstChild; a; a = a.nextSibling)
                    {
                        if (a.nodeType < 6)
                        {
                            return !1
                        }
                    }
                    return !0
                }, parent: function (a)
                {
                    return !d.pseudos.empty(a)
                }, header: function (a)
                {
                    return Z.test(a.nodeName)
                }, input: function (a)
                {
                    return Y.test(a.nodeName)
                }, button: function (a)
                {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && "button" === a.type || "button" === b
                }, text: function (a)
                {
                    var b;
                    return "input" === a.nodeName.toLowerCase() && "text" === a.type && (null == (b = a.getAttribute("type")) || "text" === b.toLowerCase())
                }, first: nb(function ()
                {
                    return [0]
                }), last: nb(function (a, b)
                {
                    return [b - 1]
                }), eq: nb(function (a, b, c)
                {
                    return [0 > c ? c + b : c]
                }), even: nb(function (a, b)
                {
                    for (var c = 0; b > c; c += 2)
                    {
                        a.push(c)
                    }
                    return a
                }), odd: nb(function (a, b)
                {
                    for (var c = 1; b > c; c += 2)
                    {
                        a.push(c)
                    }
                    return a
                }), lt: nb(function (a, b, c)
                {
                    for (var d = 0 > c ? c + b : c; --d >= 0;)
                    {
                        a.push(d)
                    }
                    return a
                }), gt: nb(function (a, b, c)
                {
                    for (var d = 0 > c ? c + b : c; ++d < b;)
                    {
                        a.push(d)
                    }
                    return a
                })
            }
        }, d.pseudos.nth = d.pseudos.eq;
        for (b in {radio: !0, checkbox: !0, file: !0, password: !0, image: !0})
        {
            d.pseudos[b] = lb(b)
        }
        for (b in {submit: !0, reset: !0})
        {
            d.pseudos[b] = mb(b)
        }
        function pb()
        {
        }

        pb.prototype = d.filters = d.pseudos, d.setFilters = new pb, g = fb.tokenize = function (a, b)
        {
            var c, e, f, g, h, i, j, k = z[a + " "];
            if (k)
            {
                return b ? 0 : k.slice(0)
            }
            h = a, i = [], j = d.preFilter;
            while (h)
            {
                (!c || (e = S.exec(h))) && (e && (h = h.slice(e[0].length) || h), i.push(f = [])), c = !1, (e = T.exec(h)) && (c = e.shift(), f.push({
                    value: c,
                    type: e[0].replace(R, " ")
                }), h = h.slice(c.length));
                for (g in d.filter)
                {
                    !(e = X[g].exec(h)) || j[g] && !(e = j[g](e)) || (c = e.shift(), f.push({value: c, type: g, matches: e}), h = h.slice(c.length))
                }
                if (!c)
                {
                    break
                }
            }
            return b ? h.length : h ? fb.error(a) : z(a, i).slice(0)
        };
        function qb(a)
        {
            for (var b = 0, c = a.length, d = ""; c > b; b++)
            {
                d += a[b].value
            }
            return d
        }

        function rb(a, b, c)
        {
            var d = b.dir, e = c && "parentNode" === d, f = x++;
            return b.first ? function (b, c, f)
            {
                while (b = b[d])
                {
                    if (1 === b.nodeType || e)
                    {
                        return a(b, c, f)
                    }
                }
            } : function (b, c, g)
            {
                var h, i, j = [w, f];
                if (g)
                {
                    while (b = b[d])
                    {
                        if ((1 === b.nodeType || e) && a(b, c, g))
                        {
                            return !0
                        }
                    }
                }
                else
                {
                    while (b = b[d])
                    {
                        if (1 === b.nodeType || e)
                        {
                            if (i = b[u] || (b[u] = {}), (h = i[d]) && h[0] === w && h[1] === f)
                            {
                                return j[2] = h[2]
                            }
                            if (i[d] = j, j[2] = a(b, c, g))
                            {
                                return !0
                            }
                        }
                    }
                }
            }
        }

        function sb(a)
        {
            return a.length > 1 ? function (b, c, d)
            {
                var e = a.length;
                while (e--)
                {
                    if (!a[e](b, c, d))
                    {
                        return !1
                    }
                }
                return !0
            } : a[0]
        }

        function tb(a, b, c)
        {
            for (var d = 0, e = b.length; e > d; d++)
            {
                fb(a, b[d], c)
            }
            return c
        }

        function ub(a, b, c, d, e)
        {
            for (var f, g = [], h = 0, i = a.length, j = null != b; i > h; h++)
            {
                (f = a[h]) && (!c || c(f, d, e)) && (g.push(f), j && b.push(h))
            }
            return g
        }

        function vb(a, b, c, d, e, f)
        {
            return d && !d[u] && (d = vb(d)), e && !e[u] && (e = vb(e, f)), hb(function (f, g, h, i)
            {
                var j, k, l, m = [], n = [], o = g.length, p = f || tb(b || "*", h.nodeType ? [h] : h, []), q = !a || !f && b ? p : ub(p, m, a, h, i), r = c ? e || (f ? a : o || d) ? [] : g : q;
                if (c && c(q, r, h, i), d)
                {
                    j = ub(r, n), d(j, [], h, i), k = j.length;
                    while (k--)
                    {
                        (l = j[k]) && (r[n[k]] = !(q[n[k]] = l))
                    }
                }
                if (f)
                {
                    if (e || a)
                    {
                        if (e)
                        {
                            j = [], k = r.length;
                            while (k--)
                            {
                                (l = r[k]) && j.push(q[k] = l)
                            }
                            e(null, r = [], j, i)
                        }
                        k = r.length;
                        while (k--)
                        {
                            (l = r[k]) && (j = e ? K.call(f, l) : m[k]) > -1 && (f[j] = !(g[j] = l))
                        }
                    }
                }
                else
                {
                    r = ub(r === g ? r.splice(o, r.length) : r), e ? e(null, g, r, i) : I.apply(g, r)
                }
            })
        }

        function wb(a)
        {
            for (var b, c, e, f = a.length, g = d.relative[a[0].type], h = g || d.relative[" "], i = g ? 1 : 0, k = rb(function (a)
            {
                return a === b
            }, h, !0), l = rb(function (a)
            {
                return K.call(b, a) > -1
            }, h, !0), m = [function (a, c, d)
            {
                return !g && (d || c !== j) || ((b = c).nodeType ? k(a, c, d) : l(a, c, d))
            }]; f > i; i++)
            {
                if (c = d.relative[a[i].type])
                {
                    m = [rb(sb(m), c)]
                }
                else
                {
                    if (c = d.filter[a[i].type].apply(null, a[i].matches), c[u])
                    {
                        for (e = ++i; f > e; e++)
                        {
                            if (d.relative[a[e].type])
                            {
                                break
                            }
                        }
                        return vb(i > 1 && sb(m), i > 1 && qb(a.slice(0, i - 1).concat({value: " " === a[i - 2].type ? "*" : ""})).replace(R, "$1"), c, e > i && wb(a.slice(i, e)), f > e && wb(a = a.slice(e)), f > e && qb(a))
                    }
                    m.push(c)
                }
            }
            return sb(m)
        }

        function xb(a, b)
        {
            var c = b.length > 0, e = a.length > 0, f = function (f, g, h, i, k)
            {
                var l, m, o, p = 0, q = "0", r = f && [], s = [], t = j, u = f || e && d.find.TAG("*", k), v = w += null == t ? 1 : Math.random() || 0.1, x = u.length;
                for (k && (j = g !== n && g); q !== x && null != (l = u[q]); q++)
                {
                    if (e && l)
                    {
                        m = 0;
                        while (o = a[m++])
                        {
                            if (o(l, g, h))
                            {
                                i.push(l);
                                break
                            }
                        }
                        k && (w = v)
                    }
                    c && ((l = !o && l) && p--, f && r.push(l))
                }
                if (p += q, c && q !== p)
                {
                    m = 0;
                    while (o = b[m++])
                    {
                        o(r, s, g, h)
                    }
                    if (f)
                    {
                        if (p > 0)
                        {
                            while (q--)
                            {
                                r[q] || s[q] || (s[q] = G.call(i))
                            }
                        }
                        s = ub(s)
                    }
                    I.apply(i, s), k && !f && s.length > 0 && p + b.length > 1 && fb.uniqueSort(i)
                }
                return k && (w = v, j = t), r
            };
            return c ? hb(f) : f
        }

        return h = fb.compile = function (a, b)
        {
            var c, d = [], e = [], f = A[a + " "];
            if (!f)
            {
                b || (b = g(a)), c = b.length;
                while (c--)
                {
                    f = wb(b[c]), f[u] ? d.push(f) : e.push(f)
                }
                f = A(a, xb(e, d)), f.selector = a
            }
            return f
        }, i = fb.select = function (a, b, e, f)
        {
            var i, j, k, l, m, n = "function" == typeof a && a, o = !f && g(a = n.selector || a);
            if (e = e || [], 1 === o.length)
            {
                if (j = o[0] = o[0].slice(0), j.length > 2 && "ID" === (k = j[0]).type && c.getById && 9 === b.nodeType && p && d.relative[j[1].type])
                {
                    if (b = (d.find.ID(k.matches[0].replace(cb, db), b) || [])[0], !b)
                    {
                        return e
                    }
                    n && (b = b.parentNode), a = a.slice(j.shift().value.length)
                }
                i = X.needsContext.test(a) ? 0 : j.length;
                while (i--)
                {
                    if (k = j[i], d.relative[l = k.type])
                    {
                        break
                    }
                    if ((m = d.find[l]) && (f = m(k.matches[0].replace(cb, db), ab.test(j[0].type) && ob(b.parentNode) || b)))
                    {
                        if (j.splice(i, 1), a = f.length && qb(j), !a)
                        {
                            return I.apply(e, f), e
                        }
                        break
                    }
                }
            }
            return (n || h(a, o))(f, b, !p, e, ab.test(a) && ob(b.parentNode) || b), e
        }, c.sortStable = u.split("").sort(B).join("") === u, c.detectDuplicates = !!l, m(), c.sortDetached = ib(function (a)
        {
            return 1 & a.compareDocumentPosition(n.createElement("div"))
        }), ib(function (a)
        {
            return a.innerHTML = "<a href='#'></a>", "#" === a.firstChild.getAttribute("href")
        }) || jb("type|href|height|width", function (a, b, c)
        {
            return c ? void 0 : a.getAttribute(b, "type" === b.toLowerCase() ? 1 : 2)
        }), c.attributes && ib(function (a)
        {
            return a.innerHTML = "<input/>", a.firstChild.setAttribute("value", ""), "" === a.firstChild.getAttribute("value")
        }) || jb("value", function (a, b, c)
        {
            return c || "input" !== a.nodeName.toLowerCase() ? void 0 : a.defaultValue
        }), ib(function (a)
        {
            return null == a.getAttribute("disabled")
        }) || jb(L, function (a, b, c)
        {
            var d;
            return c ? void 0 : a[b] === !0 ? b.toLowerCase() : (d = a.getAttributeNode(b)) && d.specified ? d.value : null
        }), fb
    }(a);
    m.find = s, m.expr = s.selectors, m.expr[":"] = m.expr.pseudos, m.unique = s.uniqueSort, m.text = s.getText, m.isXMLDoc = s.isXML, m.contains = s.contains;
    var t = m.expr.match.needsContext, u = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, v = /^.[^:#\[\.,]*$/;

    function w(a, b, c)
    {
        if (m.isFunction(b))
        {
            return m.grep(a, function (a, d)
            {
                return !!b.call(a, d, a) !== c
            })
        }
        if (b.nodeType)
        {
            return m.grep(a, function (a)
            {
                return a === b !== c
            })
        }
        if ("string" == typeof b)
        {
            if (v.test(b))
            {
                return m.filter(b, a, c)
            }
            b = m.filter(b, a)
        }
        return m.grep(a, function (a)
        {
            return m.inArray(a, b) >= 0 !== c
        })
    }

    m.filter = function (a, b, c)
    {
        var d = b[0];
        return c && (a = ":not(" + a + ")"), 1 === b.length && 1 === d.nodeType ? m.find.matchesSelector(d, a) ? [d] : [] : m.find.matches(a, m.grep(b, function (a)
        {
            return 1 === a.nodeType
        }))
    }, m.fn.extend({
        find: function (a)
        {
            var b, c = [], d = this, e = d.length;
            if ("string" != typeof a)
            {
                return this.pushStack(m(a).filter(function ()
                {
                    for (b = 0; e > b; b++)
                    {
                        if (m.contains(d[b], this))
                        {
                            return !0
                        }
                    }
                }))
            }
            for (b = 0; e > b; b++)
            {
                m.find(a, d[b], c)
            }
            return c = this.pushStack(e > 1 ? m.unique(c) : c), c.selector = this.selector ? this.selector + " " + a : a, c
        }, filter: function (a)
        {
            return this.pushStack(w(this, a || [], !1))
        }, not: function (a)
        {
            return this.pushStack(w(this, a || [], !0))
        }, is: function (a)
        {
            return !!w(this, "string" == typeof a && t.test(a) ? m(a) : a || [], !1).length
        }
    });
    var x, y = a.document, z = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, A = m.fn.init = function (a, b)
    {
        var c, d;
        if (!a)
        {
            return this
        }
        if ("string" == typeof a)
        {
            if (c = "<" === a.charAt(0) && ">" === a.charAt(a.length - 1) && a.length >= 3 ? [null, a, null] : z.exec(a), !c || !c[1] && b)
            {
                return !b || b.jquery ? (b || x).find(a) : this.constructor(b).find(a)
            }
            if (c[1])
            {
                if (b = b instanceof m ? b[0] : b, m.merge(this, m.parseHTML(c[1], b && b.nodeType ? b.ownerDocument || b : y, !0)), u.test(c[1]) && m.isPlainObject(b))
                {
                    for (c in b)
                    {
                        m.isFunction(this[c]) ? this[c](b[c]) : this.attr(c, b[c])
                    }
                }
                return this
            }
            if (d = y.getElementById(c[2]), d && d.parentNode)
            {
                if (d.id !== c[2])
                {
                    return x.find(a)
                }
                this.length = 1, this[0] = d
            }
            return this.context = y, this.selector = a, this
        }
        return a.nodeType ? (this.context = this[0] = a, this.length = 1, this) : m.isFunction(a) ? "undefined" != typeof x.ready ? x.ready(a) : a(m) : (void 0 !== a.selector && (this.selector = a.selector, this.context = a.context), m.makeArray(a, this))
    };
    A.prototype = m.fn, x = m(y);
    var B = /^(?:parents|prev(?:Until|All))/, C = {children: !0, contents: !0, next: !0, prev: !0};
    m.extend({
        dir: function (a, b, c)
        {
            var d = [], e = a[b];
            while (e && 9 !== e.nodeType && (void 0 === c || 1 !== e.nodeType || !m(e).is(c)))
            {
                1 === e.nodeType && d.push(e), e = e[b]
            }
            return d
        }, sibling: function (a, b)
        {
            for (var c = []; a; a = a.nextSibling)
            {
                1 === a.nodeType && a !== b && c.push(a)
            }
            return c
        }
    }), m.fn.extend({
        has: function (a)
        {
            var b, c = m(a, this), d = c.length;
            return this.filter(function ()
            {
                for (b = 0; d > b; b++)
                {
                    if (m.contains(this, c[b]))
                    {
                        return !0
                    }
                }
            })
        }, closest: function (a, b)
        {
            for (var c, d = 0, e = this.length, f = [], g = t.test(a) || "string" != typeof a ? m(a, b || this.context) : 0; e > d; d++)
            {
                for (c = this[d]; c && c !== b; c = c.parentNode)
                {
                    if (c.nodeType < 11 && (g ? g.index(c) > -1 : 1 === c.nodeType && m.find.matchesSelector(c, a)))
                    {
                        f.push(c);
                        break
                    }
                }
            }
            return this.pushStack(f.length > 1 ? m.unique(f) : f)
        }, index: function (a)
        {
            return a ? "string" == typeof a ? m.inArray(this[0], m(a)) : m.inArray(a.jquery ? a[0] : a, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (a, b)
        {
            return this.pushStack(m.unique(m.merge(this.get(), m(a, b))))
        }, addBack: function (a)
        {
            return this.add(null == a ? this.prevObject : this.prevObject.filter(a))
        }
    });
    function D(a, b)
    {
        do {
            a = a[b]
        } while (a && 1 !== a.nodeType);
        return a
    }

    m.each({
        parent: function (a)
        {
            var b = a.parentNode;
            return b && 11 !== b.nodeType ? b : null
        }, parents: function (a)
        {
            return m.dir(a, "parentNode")
        }, parentsUntil: function (a, b, c)
        {
            return m.dir(a, "parentNode", c)
        }, next: function (a)
        {
            return D(a, "nextSibling")
        }, prev: function (a)
        {
            return D(a, "previousSibling")
        }, nextAll: function (a)
        {
            return m.dir(a, "nextSibling")
        }, prevAll: function (a)
        {
            return m.dir(a, "previousSibling")
        }, nextUntil: function (a, b, c)
        {
            return m.dir(a, "nextSibling", c)
        }, prevUntil: function (a, b, c)
        {
            return m.dir(a, "previousSibling", c)
        }, siblings: function (a)
        {
            return m.sibling((a.parentNode || {}).firstChild, a)
        }, children: function (a)
        {
            return m.sibling(a.firstChild)
        }, contents: function (a)
        {
            return m.nodeName(a, "iframe") ? a.contentDocument || a.contentWindow.document : m.merge([], a.childNodes)
        }
    }, function (a, b)
    {
        m.fn[a] = function (c, d)
        {
            var e = m.map(this, b, c);
            return "Until" !== a.slice(-5) && (d = c), d && "string" == typeof d && (e = m.filter(d, e)), this.length > 1 && (C[a] || (e = m.unique(e)), B.test(a) && (e = e.reverse())), this.pushStack(e)
        }
    });
    var E = /\S+/g, F = {};

    function G(a)
    {
        var b = F[a] = {};
        return m.each(a.match(E) || [], function (a, c)
        {
            b[c] = !0
        }), b
    }

    m.Callbacks = function (a)
    {
        a = "string" == typeof a ? F[a] || G(a) : m.extend({}, a);
        var b, c, d, e, f, g, h = [], i = !a.once && [], j = function (l)
        {
            for (c = a.memory && l, d = !0, f = g || 0, g = 0, e = h.length, b = !0; h && e > f; f++)
            {
                if (h[f].apply(l[0], l[1]) === !1 && a.stopOnFalse)
                {
                    c = !1;
                    break
                }
            }
            b = !1, h && (i ? i.length && j(i.shift()) : c ? h = [] : k.disable())
        }, k = {
            add: function ()
            {
                if (h)
                {
                    var d = h.length;
                    !function f(b)
                    {
                        m.each(b, function (b, c)
                        {
                            var d = m.type(c);
                            "function" === d ? a.unique && k.has(c) || h.push(c) : c && c.length && "string" !== d && f(c)
                        })
                    }(arguments), b ? e = h.length : c && (g = d, j(c))
                }
                return this
            }, remove: function ()
            {
                return h && m.each(arguments, function (a, c)
                {
                    var d;
                    while ((d = m.inArray(c, h, d)) > -1)
                    {
                        h.splice(d, 1), b && (e >= d && e--, f >= d && f--)
                    }
                }), this
            }, has: function (a)
            {
                return a ? m.inArray(a, h) > -1 : !(!h || !h.length)
            }, empty: function ()
            {
                return h = [], e = 0, this
            }, disable: function ()
            {
                return h = i = c = void 0, this
            }, disabled: function ()
            {
                return !h
            }, lock: function ()
            {
                return i = void 0, c || k.disable(), this
            }, locked: function ()
            {
                return !i
            }, fireWith: function (a, c)
            {
                return !h || d && !i || (c = c || [], c = [a, c.slice ? c.slice() : c], b ? i.push(c) : j(c)), this
            }, fire: function ()
            {
                return k.fireWith(this, arguments), this
            }, fired: function ()
            {
                return !!d
            }
        };
        return k
    }, m.extend({
        Deferred: function (a)
        {
            var b = [["resolve", "done", m.Callbacks("once memory"), "resolved"], ["reject", "fail", m.Callbacks("once memory"), "rejected"], ["notify", "progress", m.Callbacks("memory")]], c = "pending", d = {
                state: function ()
                {
                    return c
                }, always: function ()
                {
                    return e.done(arguments).fail(arguments), this
                }, then: function ()
                {
                    var a = arguments;
                    return m.Deferred(function (c)
                    {
                        m.each(b, function (b, f)
                        {
                            var g = m.isFunction(a[b]) && a[b];
                            e[f[1]](function ()
                            {
                                var a = g && g.apply(this, arguments);
                                a && m.isFunction(a.promise) ? a.promise().done(c.resolve).fail(c.reject).progress(c.notify) : c[f[0] + "With"](this === d ? c.promise() : this, g ? [a] : arguments)
                            })
                        }), a = null
                    }).promise()
                }, promise: function (a)
                {
                    return null != a ? m.extend(a, d) : d
                }
            }, e = {};
            return d.pipe = d.then, m.each(b, function (a, f)
            {
                var g = f[2], h = f[3];
                d[f[1]] = g.add, h && g.add(function ()
                {
                    c = h
                }, b[1 ^ a][2].disable, b[2][2].lock), e[f[0]] = function ()
                {
                    return e[f[0] + "With"](this === e ? d : this, arguments), this
                }, e[f[0] + "With"] = g.fireWith
            }), d.promise(e), a && a.call(e, e), e
        }, when: function (a)
        {
            var b = 0, c = d.call(arguments), e = c.length, f = 1 !== e || a && m.isFunction(a.promise) ? e : 0, g = 1 === f ? a : m.Deferred(), h = function (a, b, c)
            {
                return function (e)
                {
                    b[a] = this, c[a] = arguments.length > 1 ? d.call(arguments) : e, c === i ? g.notifyWith(b, c) : --f || g.resolveWith(b, c)
                }
            }, i, j, k;
            if (e > 1)
            {
                for (i = new Array(e), j = new Array(e), k = new Array(e); e > b; b++)
                {
                    c[b] && m.isFunction(c[b].promise) ? c[b].promise().done(h(b, k, c)).fail(g.reject).progress(h(b, j, i)) : --f
                }
            }
            return f || g.resolveWith(k, c), g.promise()
        }
    });
    var H;
    m.fn.ready = function (a)
    {
        return m.ready.promise().done(a), this
    }, m.extend({
        isReady: !1, readyWait: 1, holdReady: function (a)
        {
            a ? m.readyWait++ : m.ready(!0)
        }, ready: function (a)
        {
            if (a === !0 ? !--m.readyWait : !m.isReady)
            {
                if (!y.body)
                {
                    return setTimeout(m.ready)
                }
                m.isReady = !0, a !== !0 && --m.readyWait > 0 || (H.resolveWith(y, [m]), m.fn.triggerHandler && (m(y).triggerHandler("ready"), m(y).off("ready")))
            }
        }
    });
    function I()
    {
        y.addEventListener ? (y.removeEventListener("DOMContentLoaded", J, !1), a.removeEventListener("load", J, !1)) : (y.detachEvent("onreadystatechange", J), a.detachEvent("onload", J))
    }

    function J()
    {
        (y.addEventListener || "load" === event.type || "complete" === y.readyState) && (I(), m.ready())
    }

    m.ready.promise = function (b)
    {
        if (!H)
        {
            if (H = m.Deferred(), "complete" === y.readyState)
            {
                setTimeout(m.ready)
            }
            else
            {
                if (y.addEventListener)
                {
                    y.addEventListener("DOMContentLoaded", J, !1), a.addEventListener("load", J, !1)
                }
                else
                {
                    y.attachEvent("onreadystatechange", J), a.attachEvent("onload", J);
                    var c = !1;
                    try
                    {
                        c = null == a.frameElement && y.documentElement
                    } catch (d)
                    {
                    }
                    c && c.doScroll && !function e()
                    {
                        if (!m.isReady)
                        {
                            try
                            {
                                c.doScroll("left")
                            } catch (a)
                            {
                                return setTimeout(e, 50)
                            }
                            I(), m.ready()
                        }
                    }()
                }
            }
        }
        return H.promise(b)
    };
    var K = "undefined", L;
    for (L in m(k))
    {
        break
    }
    k.ownLast = "0" !== L, k.inlineBlockNeedsLayout = !1, m(function ()
    {
        var a, b, c, d;
        c = y.getElementsByTagName("body")[0], c && c.style && (b = y.createElement("div"), d = y.createElement("div"), d.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", c.appendChild(d).appendChild(b), typeof b.style.zoom !== K && (b.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", k.inlineBlockNeedsLayout = a = 3 === b.offsetWidth, a && (c.style.zoom = 1)), c.removeChild(d))
    }), function ()
    {
        var a = y.createElement("div");
        if (null == k.deleteExpando)
        {
            k.deleteExpando = !0;
            try
            {
                delete a.test
            } catch (b)
            {
                k.deleteExpando = !1
            }
        }
        a = null
    }(), m.acceptData = function (a)
    {
        var b = m.noData[(a.nodeName + " ").toLowerCase()], c = +a.nodeType || 1;
        return 1 !== c && 9 !== c ? !1 : !b || b !== !0 && a.getAttribute("classid") === b
    };
    var M = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, N = /([A-Z])/g;

    function O(a, b, c)
    {
        if (void 0 === c && 1 === a.nodeType)
        {
            var d = "data-" + b.replace(N, "-$1").toLowerCase();
            if (c = a.getAttribute(d), "string" == typeof c)
            {
                try
                {
                    c = "true" === c ? !0 : "false" === c ? !1 : "null" === c ? null : +c + "" === c ? +c : M.test(c) ? m.parseJSON(c) : c
                } catch (e)
                {
                }
                m.data(a, b, c)
            }
            else
            {
                c = void 0
            }
        }
        return c
    }

    function P(a)
    {
        var b;
        for (b in a)
        {
            if (("data" !== b || !m.isEmptyObject(a[b])) && "toJSON" !== b)
            {
                return !1
            }
        }
        return !0
    }

    function Q(a, b, d, e)
    {
        if (m.acceptData(a))
        {
            var f, g, h = m.expando, i = a.nodeType, j = i ? m.cache : a, k = i ? a[h] : a[h] && h;
            if (k && j[k] && (e || j[k].data) || void 0 !== d || "string" != typeof b)
            {
                return k || (k = i ? a[h] = c.pop() || m.guid++ : h), j[k] || (j[k] = i ? {} : {toJSON: m.noop}), ("object" == typeof b || "function" == typeof b) && (e ? j[k] = m.extend(j[k], b) : j[k].data = m.extend(j[k].data, b)), g = j[k], e || (g.data || (g.data = {}), g = g.data), void 0 !== d && (g[m.camelCase(b)] = d), "string" == typeof b ? (f = g[b], null == f && (f = g[m.camelCase(b)])) : f = g, f
            }
        }
    }

    function R(a, b, c)
    {
        if (m.acceptData(a))
        {
            var d, e, f = a.nodeType, g = f ? m.cache : a, h = f ? a[m.expando] : m.expando;
            if (g[h])
            {
                if (b && (d = c ? g[h] : g[h].data))
                {
                    m.isArray(b) ? b = b.concat(m.map(b, m.camelCase)) : b in d ? b = [b] : (b = m.camelCase(b), b = b in d ? [b] : b.split(" ")), e = b.length;
                    while (e--)
                    {
                        delete d[b[e]]
                    }
                    if (c ? !P(d) : !m.isEmptyObject(d))
                    {
                        return
                    }
                }
                (c || (delete g[h].data, P(g[h]))) && (f ? m.cleanData([a], !0) : k.deleteExpando || g != g.window ? delete g[h] : g[h] = null)
            }
        }
    }

    m.extend({
        cache: {}, noData: {"applet ": !0, "embed ": !0, "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"}, hasData: function (a)
        {
            return a = a.nodeType ? m.cache[a[m.expando]] : a[m.expando], !!a && !P(a)
        }, data: function (a, b, c)
        {
            return Q(a, b, c)
        }, removeData: function (a, b)
        {
            return R(a, b)
        }, _data: function (a, b, c)
        {
            return Q(a, b, c, !0)
        }, _removeData: function (a, b)
        {
            return R(a, b, !0)
        }
    }), m.fn.extend({
        data: function (a, b)
        {
            var c, d, e, f = this[0], g = f && f.attributes;
            if (void 0 === a)
            {
                if (this.length && (e = m.data(f), 1 === f.nodeType && !m._data(f, "parsedAttrs")))
                {
                    c = g.length;
                    while (c--)
                    {
                        g[c] && (d = g[c].name, 0 === d.indexOf("data-") && (d = m.camelCase(d.slice(5)), O(f, d, e[d])))
                    }
                    m._data(f, "parsedAttrs", !0)
                }
                return e
            }
            return "object" == typeof a ? this.each(function ()
            {
                m.data(this, a)
            }) : arguments.length > 1 ? this.each(function ()
            {
                m.data(this, a, b)
            }) : f ? O(f, a, m.data(f, a)) : void 0
        }, removeData: function (a)
        {
            return this.each(function ()
            {
                m.removeData(this, a)
            })
        }
    }), m.extend({
        queue: function (a, b, c)
        {
            var d;
            return a ? (b = (b || "fx") + "queue", d = m._data(a, b), c && (!d || m.isArray(c) ? d = m._data(a, b, m.makeArray(c)) : d.push(c)), d || []) : void 0
        }, dequeue: function (a, b)
        {
            b = b || "fx";
            var c = m.queue(a, b), d = c.length, e = c.shift(), f = m._queueHooks(a, b), g = function ()
            {
                m.dequeue(a, b)
            };
            "inprogress" === e && (e = c.shift(), d--), e && ("fx" === b && c.unshift("inprogress"), delete f.stop, e.call(a, g, f)), !d && f && f.empty.fire()
        }, _queueHooks: function (a, b)
        {
            var c = b + "queueHooks";
            return m._data(a, c) || m._data(a, c, {
                    empty: m.Callbacks("once memory").add(function ()
                    {
                        m._removeData(a, b + "queue"), m._removeData(a, c)
                    })
                })
        }
    }), m.fn.extend({
        queue: function (a, b)
        {
            var c = 2;
            return "string" != typeof a && (b = a, a = "fx", c--), arguments.length < c ? m.queue(this[0], a) : void 0 === b ? this : this.each(function ()
            {
                var c = m.queue(this, a, b);
                m._queueHooks(this, a), "fx" === a && "inprogress" !== c[0] && m.dequeue(this, a)
            })
        }, dequeue: function (a)
        {
            return this.each(function ()
            {
                m.dequeue(this, a)
            })
        }, clearQueue: function (a)
        {
            return this.queue(a || "fx", [])
        }, promise: function (a, b)
        {
            var c, d = 1, e = m.Deferred(), f = this, g = this.length, h = function ()
            {
                --d || e.resolveWith(f, [f])
            };
            "string" != typeof a && (b = a, a = void 0), a = a || "fx";
            while (g--)
            {
                c = m._data(f[g], a + "queueHooks"), c && c.empty && (d++, c.empty.add(h))
            }
            return h(), e.promise(b)
        }
    });
    var S = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, T = ["Top", "Right", "Bottom", "Left"], U = function (a, b)
    {
        return a = b || a, "none" === m.css(a, "display") || !m.contains(a.ownerDocument, a)
    }, V = m.access = function (a, b, c, d, e, f, g)
    {
        var h = 0, i = a.length, j = null == c;
        if ("object" === m.type(c))
        {
            e = !0;
            for (h in c)
            {
                m.access(a, b, h, c[h], !0, f, g)
            }
        }
        else
        {
            if (void 0 !== d && (e = !0, m.isFunction(d) || (g = !0), j && (g ? (b.call(a, d), b = null) : (j = b, b = function (a, b, c)
                {
                    return j.call(m(a), c)
                })), b))
            {
                for (; i > h; h++)
                {
                    b(a[h], c, g ? d : d.call(a[h], h, b(a[h], c)))
                }
            }
        }
        return e ? a : j ? b.call(a) : i ? b(a[0], c) : f
    }, W = /^(?:checkbox|radio)$/i;
    !function ()
    {
        var a = y.createElement("input"), b = y.createElement("div"), c = y.createDocumentFragment();
        if (b.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", k.leadingWhitespace = 3 === b.firstChild.nodeType, k.tbody = !b.getElementsByTagName("tbody").length, k.htmlSerialize = !!b.getElementsByTagName("link").length, k.html5Clone = "<:nav></:nav>" !== y.createElement("nav").cloneNode(!0).outerHTML, a.type = "checkbox", a.checked = !0, c.appendChild(a), k.appendChecked = a.checked, b.innerHTML = "<textarea>x</textarea>", k.noCloneChecked = !!b.cloneNode(!0).lastChild.defaultValue, c.appendChild(b), b.innerHTML = "<input type='radio' checked='checked' name='t'/>", k.checkClone = b.cloneNode(!0).cloneNode(!0).lastChild.checked, k.noCloneEvent = !0, b.attachEvent && (b.attachEvent("onclick", function ()
            {
                k.noCloneEvent = !1
            }), b.cloneNode(!0).click()), null == k.deleteExpando)
        {
            k.deleteExpando = !0;
            try
            {
                delete b.test
            } catch (d)
            {
                k.deleteExpando = !1
            }
        }
    }(), function ()
    {
        var b, c, d = y.createElement("div");
        for (b in {submit: !0, change: !0, focusin: !0})
        {
            c = "on" + b, (k[b + "Bubbles"] = c in a) || (d.setAttribute(c, "t"), k[b + "Bubbles"] = d.attributes[c].expando === !1)
        }
        d = null
    }();
    var X = /^(?:input|select|textarea)$/i, Y = /^key/, Z = /^(?:mouse|pointer|contextmenu)|click/, $ = /^(?:focusinfocus|focusoutblur)$/, _ = /^([^.]*)(?:\.(.+)|)$/;

    function ab()
    {
        return !0
    }

    function bb()
    {
        return !1
    }

    function cb()
    {
        try
        {
            return y.activeElement
        } catch (a)
        {
        }
    }

    m.event = {
        global: {},
        add: function (a, b, c, d, e)
        {
            var f, g, h, i, j, k, l, n, o, p, q, r = m._data(a);
            if (r)
            {
                c.handler && (i = c, c = i.handler, e = i.selector), c.guid || (c.guid = m.guid++), (g = r.events) || (g = r.events = {}), (k = r.handle) || (k = r.handle = function (a)
                {
                    return typeof m === K || a && m.event.triggered === a.type ? void 0 : m.event.dispatch.apply(k.elem, arguments)
                }, k.elem = a), b = (b || "").match(E) || [""], h = b.length;
                while (h--)
                {
                    f = _.exec(b[h]) || [], o = q = f[1], p = (f[2] || "").split(".").sort(), o && (j = m.event.special[o] || {}, o = (e ? j.delegateType : j.bindType) || o, j = m.event.special[o] || {}, l = m.extend({
                        type: o,
                        origType: q,
                        data: d,
                        handler: c,
                        guid: c.guid,
                        selector: e,
                        needsContext: e && m.expr.match.needsContext.test(e),
                        namespace: p.join(".")
                    }, i), (n = g[o]) || (n = g[o] = [], n.delegateCount = 0, j.setup && j.setup.call(a, d, p, k) !== !1 || (a.addEventListener ? a.addEventListener(o, k, !1) : a.attachEvent && a.attachEvent("on" + o, k))), j.add && (j.add.call(a, l), l.handler.guid || (l.handler.guid = c.guid)), e ? n.splice(n.delegateCount++, 0, l) : n.push(l), m.event.global[o] = !0)
                }
                a = null
            }
        },
        remove: function (a, b, c, d, e)
        {
            var f, g, h, i, j, k, l, n, o, p, q, r = m.hasData(a) && m._data(a);
            if (r && (k = r.events))
            {
                b = (b || "").match(E) || [""], j = b.length;
                while (j--)
                {
                    if (h = _.exec(b[j]) || [], o = q = h[1], p = (h[2] || "").split(".").sort(), o)
                    {
                        l = m.event.special[o] || {}, o = (d ? l.delegateType : l.bindType) || o, n = k[o] || [], h = h[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), i = f = n.length;
                        while (f--)
                        {
                            g = n[f], !e && q !== g.origType || c && c.guid !== g.guid || h && !h.test(g.namespace) || d && d !== g.selector && ("**" !== d || !g.selector) || (n.splice(f, 1), g.selector && n.delegateCount--, l.remove && l.remove.call(a, g))
                        }
                        i && !n.length && (l.teardown && l.teardown.call(a, p, r.handle) !== !1 || m.removeEvent(a, o, r.handle), delete k[o])
                    }
                    else
                    {
                        for (o in k)
                        {
                            m.event.remove(a, o + b[j], c, d, !0)
                        }
                    }
                }
                m.isEmptyObject(k) && (delete r.handle, m._removeData(a, "events"))
            }
        },
        trigger: function (b, c, d, e)
        {
            var f, g, h, i, k, l, n, o = [d || y], p = j.call(b, "type") ? b.type : b, q = j.call(b, "namespace") ? b.namespace.split(".") : [];
            if (h = l = d = d || y, 3 !== d.nodeType && 8 !== d.nodeType && !$.test(p + m.event.triggered) && (p.indexOf(".") >= 0 && (q = p.split("."), p = q.shift(), q.sort()), g = p.indexOf(":") < 0 && "on" + p, b = b[m.expando] ? b : new m.Event(p, "object" == typeof b && b), b.isTrigger = e ? 2 : 3, b.namespace = q.join("."), b.namespace_re = b.namespace ? new RegExp("(^|\\.)" + q.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, b.result = void 0, b.target || (b.target = d), c = null == c ? [b] : m.makeArray(c, [b]), k = m.event.special[p] || {}, e || !k.trigger || k.trigger.apply(d, c) !== !1))
            {
                if (!e && !k.noBubble && !m.isWindow(d))
                {
                    for (i = k.delegateType || p, $.test(i + p) || (h = h.parentNode); h; h = h.parentNode)
                    {
                        o.push(h), l = h
                    }
                    l === (d.ownerDocument || y) && o.push(l.defaultView || l.parentWindow || a)
                }
                n = 0;
                while ((h = o[n++]) && !b.isPropagationStopped())
                {
                    b.type = n > 1 ? i : k.bindType || p, f = (m._data(h, "events") || {})[b.type] && m._data(h, "handle"), f && f.apply(h, c), f = g && h[g], f && f.apply && m.acceptData(h) && (b.result = f.apply(h, c), b.result === !1 && b.preventDefault())
                }
                if (b.type = p, !e && !b.isDefaultPrevented() && (!k._default || k._default.apply(o.pop(), c) === !1) && m.acceptData(d) && g && d[p] && !m.isWindow(d))
                {
                    l = d[g], l && (d[g] = null), m.event.triggered = p;
                    try
                    {
                        d[p]()
                    } catch (r)
                    {
                    }
                    m.event.triggered = void 0, l && (d[g] = l)
                }
                return b.result
            }
        },
        dispatch: function (a)
        {
            a = m.event.fix(a);
            var b, c, e, f, g, h = [], i = d.call(arguments), j = (m._data(this, "events") || {})[a.type] || [], k = m.event.special[a.type] || {};
            if (i[0] = a, a.delegateTarget = this, !k.preDispatch || k.preDispatch.call(this, a) !== !1)
            {
                h = m.event.handlers.call(this, a, j), b = 0;
                while ((f = h[b++]) && !a.isPropagationStopped())
                {
                    a.currentTarget = f.elem, g = 0;
                    while ((e = f.handlers[g++]) && !a.isImmediatePropagationStopped())
                    {
                        (!a.namespace_re || a.namespace_re.test(e.namespace)) && (a.handleObj = e, a.data = e.data, c = ((m.event.special[e.origType] || {}).handle || e.handler).apply(f.elem, i), void 0 !== c && (a.result = c) === !1 && (a.preventDefault(), a.stopPropagation()))
                    }
                }
                return k.postDispatch && k.postDispatch.call(this, a), a.result
            }
        },
        handlers: function (a, b)
        {
            var c, d, e, f, g = [], h = b.delegateCount, i = a.target;
            if (h && i.nodeType && (!a.button || "click" !== a.type))
            {
                for (; i != this; i = i.parentNode || this)
                {
                    if (1 === i.nodeType && (i.disabled !== !0 || "click" !== a.type))
                    {
                        for (e = [], f = 0; h > f; f++)
                        {
                            d = b[f], c = d.selector + " ", void 0 === e[c] && (e[c] = d.needsContext ? m(c, this).index(i) >= 0 : m.find(c, this, null, [i]).length), e[c] && e.push(d)
                        }
                        e.length && g.push({elem: i, handlers: e})
                    }
                }
            }
            return h < b.length && g.push({elem: this, handlers: b.slice(h)}), g
        },
        fix: function (a)
        {
            if (a[m.expando])
            {
                return a
            }
            var b, c, d, e = a.type, f = a, g = this.fixHooks[e];
            g || (this.fixHooks[e] = g = Z.test(e) ? this.mouseHooks : Y.test(e) ? this.keyHooks : {}), d = g.props ? this.props.concat(g.props) : this.props, a = new m.Event(f), b = d.length;
            while (b--)
            {
                c = d[b], a[c] = f[c]
            }
            return a.target || (a.target = f.srcElement || y), 3 === a.target.nodeType && (a.target = a.target.parentNode), a.metaKey = !!a.metaKey, g.filter ? g.filter(a, f) : a
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (a, b)
            {
                return null == a.which && (a.which = null != b.charCode ? b.charCode : b.keyCode), a
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "), filter: function (a, b)
            {
                var c, d, e, f = b.button, g = b.fromElement;
                return null == a.pageX && null != b.clientX && (d = a.target.ownerDocument || y, e = d.documentElement, c = d.body, a.pageX = b.clientX + (e && e.scrollLeft || c && c.scrollLeft || 0) - (e && e.clientLeft || c && c.clientLeft || 0), a.pageY = b.clientY + (e && e.scrollTop || c && c.scrollTop || 0) - (e && e.clientTop || c && c.clientTop || 0)), !a.relatedTarget && g && (a.relatedTarget = g === a.target ? b.toElement : g), a.which || void 0 === f || (a.which = 1 & f ? 1 : 2 & f ? 3 : 4 & f ? 2 : 0), a
            }
        },
        special: {
            load: {noBubble: !0}, focus: {
                trigger: function ()
                {
                    if (this !== cb() && this.focus)
                    {
                        try
                        {
                            return this.focus(), !1
                        } catch (a)
                        {
                        }
                    }
                }, delegateType: "focusin"
            }, blur: {
                trigger: function ()
                {
                    return this === cb() && this.blur ? (this.blur(), !1) : void 0
                }, delegateType: "focusout"
            }, click: {
                trigger: function ()
                {
                    return m.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                }, _default: function (a)
                {
                    return m.nodeName(a.target, "a")
                }
            }, beforeunload: {
                postDispatch: function (a)
                {
                    void 0 !== a.result && a.originalEvent && (a.originalEvent.returnValue = a.result)
                }
            }
        },
        simulate: function (a, b, c, d)
        {
            var e = m.extend(new m.Event, c, {type: a, isSimulated: !0, originalEvent: {}});
            d ? m.event.trigger(e, null, b) : m.event.dispatch.call(b, e), e.isDefaultPrevented() && c.preventDefault()
        }
    }, m.removeEvent = y.removeEventListener ? function (a, b, c)
    {
        a.removeEventListener && a.removeEventListener(b, c, !1)
    } : function (a, b, c)
    {
        var d = "on" + b;
        a.detachEvent && (typeof a[d] === K && (a[d] = null), a.detachEvent(d, c))
    }, m.Event = function (a, b)
    {
        return this instanceof m.Event ? (a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || void 0 === a.defaultPrevented && a.returnValue === !1 ? ab : bb) : this.type = a, b && m.extend(this, b), this.timeStamp = a && a.timeStamp || m.now(), void (this[m.expando] = !0)) : new m.Event(a, b)
    }, m.Event.prototype = {
        isDefaultPrevented: bb, isPropagationStopped: bb, isImmediatePropagationStopped: bb, preventDefault: function ()
        {
            var a = this.originalEvent;
            this.isDefaultPrevented = ab, a && (a.preventDefault ? a.preventDefault() : a.returnValue = !1)
        }, stopPropagation: function ()
        {
            var a = this.originalEvent;
            this.isPropagationStopped = ab, a && (a.stopPropagation && a.stopPropagation(), a.cancelBubble = !0)
        }, stopImmediatePropagation: function ()
        {
            var a = this.originalEvent;
            this.isImmediatePropagationStopped = ab, a && a.stopImmediatePropagation && a.stopImmediatePropagation(), this.stopPropagation()
        }
    }, m.each({mouseenter: "mouseover", mouseleave: "mouseout", pointerenter: "pointerover", pointerleave: "pointerout"}, function (a, b)
    {
        m.event.special[a] = {
            delegateType: b, bindType: b, handle: function (a)
            {
                var c, d = this, e = a.relatedTarget, f = a.handleObj;
                return (!e || e !== d && !m.contains(d, e)) && (a.type = f.origType, c = f.handler.apply(this, arguments), a.type = b), c
            }
        }
    }), k.submitBubbles || (m.event.special.submit = {
        setup: function ()
        {
            return m.nodeName(this, "form") ? !1 : void m.event.add(this, "click._submit keypress._submit", function (a)
            {
                var b = a.target, c = m.nodeName(b, "input") || m.nodeName(b, "button") ? b.form : void 0;
                c && !m._data(c, "submitBubbles") && (m.event.add(c, "submit._submit", function (a)
                {
                    a._submit_bubble = !0
                }), m._data(c, "submitBubbles", !0))
            })
        }, postDispatch: function (a)
        {
            a._submit_bubble && (delete a._submit_bubble, this.parentNode && !a.isTrigger && m.event.simulate("submit", this.parentNode, a, !0))
        }, teardown: function ()
        {
            return m.nodeName(this, "form") ? !1 : void m.event.remove(this, "._submit")
        }
    }), k.changeBubbles || (m.event.special.change = {
        setup: function ()
        {
            return X.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (m.event.add(this, "propertychange._change", function (a)
            {
                "checked" === a.originalEvent.propertyName && (this._just_changed = !0)
            }), m.event.add(this, "click._change", function (a)
            {
                this._just_changed && !a.isTrigger && (this._just_changed = !1), m.event.simulate("change", this, a, !0)
            })), !1) : void m.event.add(this, "beforeactivate._change", function (a)
            {
                var b = a.target;
                X.test(b.nodeName) && !m._data(b, "changeBubbles") && (m.event.add(b, "change._change", function (a)
                {
                    !this.parentNode || a.isSimulated || a.isTrigger || m.event.simulate("change", this.parentNode, a, !0)
                }), m._data(b, "changeBubbles", !0))
            })
        }, handle: function (a)
        {
            var b = a.target;
            return this !== b || a.isSimulated || a.isTrigger || "radio" !== b.type && "checkbox" !== b.type ? a.handleObj.handler.apply(this, arguments) : void 0
        }, teardown: function ()
        {
            return m.event.remove(this, "._change"), !X.test(this.nodeName)
        }
    }), k.focusinBubbles || m.each({focus: "focusin", blur: "focusout"}, function (a, b)
    {
        var c = function (a)
        {
            m.event.simulate(b, a.target, m.event.fix(a), !0)
        };
        m.event.special[b] = {
            setup: function ()
            {
                var d = this.ownerDocument || this, e = m._data(d, b);
                e || d.addEventListener(a, c, !0), m._data(d, b, (e || 0) + 1)
            }, teardown: function ()
            {
                var d = this.ownerDocument || this, e = m._data(d, b) - 1;
                e ? m._data(d, b, e) : (d.removeEventListener(a, c, !0), m._removeData(d, b))
            }
        }
    }), m.fn.extend({
        on: function (a, b, c, d, e)
        {
            var f, g;
            if ("object" == typeof a)
            {
                "string" != typeof b && (c = c || b, b = void 0);
                for (f in a)
                {
                    this.on(f, b, c, a[f], e)
                }
                return this
            }
            if (null == c && null == d ? (d = b, c = b = void 0) : null == d && ("string" == typeof b ? (d = c, c = void 0) : (d = c, c = b, b = void 0)), d === !1)
            {
                d = bb
            }
            else
            {
                if (!d)
                {
                    return this
                }
            }
            return 1 === e && (g = d, d = function (a)
            {
                return m().off(a), g.apply(this, arguments)
            }, d.guid = g.guid || (g.guid = m.guid++)), this.each(function ()
            {
                m.event.add(this, a, d, c, b)
            })
        }, one: function (a, b, c, d)
        {
            return this.on(a, b, c, d, 1)
        }, off: function (a, b, c)
        {
            var d, e;
            if (a && a.preventDefault && a.handleObj)
            {
                return d = a.handleObj, m(a.delegateTarget).off(d.namespace ? d.origType + "." + d.namespace : d.origType, d.selector, d.handler), this
            }
            if ("object" == typeof a)
            {
                for (e in a)
                {
                    this.off(e, b, a[e])
                }
                return this
            }
            return (b === !1 || "function" == typeof b) && (c = b, b = void 0), c === !1 && (c = bb), this.each(function ()
            {
                m.event.remove(this, a, c, b)
            })
        }, trigger: function (a, b)
        {
            return this.each(function ()
            {
                m.event.trigger(a, b, this)
            })
        }, triggerHandler: function (a, b)
        {
            var c = this[0];
            return c ? m.event.trigger(a, b, c, !0) : void 0
        }
    });
    function db(a)
    {
        var b = eb.split("|"), c = a.createDocumentFragment();
        if (c.createElement)
        {
            while (b.length)
            {
                c.createElement(b.pop())
            }
        }
        return c
    }

    var eb = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video", fb = / jQuery\d+="(?:null|\d+)"/g, gb = new RegExp("<(?:" + eb + ")[\\s/>]", "i"), hb = /^\s+/, ib = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, jb = /<([\w:]+)/, kb = /<tbody/i, lb = /<|&#?\w+;/, mb = /<(?:script|style|link)/i, nb = /checked\s*(?:[^=]|=\s*.checked.)/i, ob = /^$|\/(?:java|ecma)script/i, pb = /^true\/(.*)/, qb = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, rb = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        legend: [1, "<fieldset>", "</fieldset>"],
        area: [1, "<map>", "</map>"],
        param: [1, "<object>", "</object>"],
        thead: [1, "<table>", "</table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: k.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
    }, sb = db(y), tb = sb.appendChild(y.createElement("div"));
    rb.optgroup = rb.option, rb.tbody = rb.tfoot = rb.colgroup = rb.caption = rb.thead, rb.th = rb.td;
    function ub(a, b)
    {
        var c, d, e = 0, f = typeof a.getElementsByTagName !== K ? a.getElementsByTagName(b || "*") : typeof a.querySelectorAll !== K ? a.querySelectorAll(b || "*") : void 0;
        if (!f)
        {
            for (f = [], c = a.childNodes || a; null != (d = c[e]); e++)
            {
                !b || m.nodeName(d, b) ? f.push(d) : m.merge(f, ub(d, b))
            }
        }
        return void 0 === b || b && m.nodeName(a, b) ? m.merge([a], f) : f
    }

    function vb(a)
    {
        W.test(a.type) && (a.defaultChecked = a.checked)
    }

    function wb(a, b)
    {
        return m.nodeName(a, "table") && m.nodeName(11 !== b.nodeType ? b : b.firstChild, "tr") ? a.getElementsByTagName("tbody")[0] || a.appendChild(a.ownerDocument.createElement("tbody")) : a
    }

    function xb(a)
    {
        return a.type = (null !== m.find.attr(a, "type")) + "/" + a.type, a
    }

    function yb(a)
    {
        var b = pb.exec(a.type);
        return b ? a.type = b[1] : a.removeAttribute("type"), a
    }

    function zb(a, b)
    {
        for (var c, d = 0; null != (c = a[d]); d++)
        {
            m._data(c, "globalEval", !b || m._data(b[d], "globalEval"))
        }
    }

    function Ab(a, b)
    {
        if (1 === b.nodeType && m.hasData(a))
        {
            var c, d, e, f = m._data(a), g = m._data(b, f), h = f.events;
            if (h)
            {
                delete g.handle, g.events = {};
                for (c in h)
                {
                    for (d = 0, e = h[c].length; e > d; d++)
                    {
                        m.event.add(b, c, h[c][d])
                    }
                }
            }
            g.data && (g.data = m.extend({}, g.data))
        }
    }

    function Bb(a, b)
    {
        var c, d, e;
        if (1 === b.nodeType)
        {
            if (c = b.nodeName.toLowerCase(), !k.noCloneEvent && b[m.expando])
            {
                e = m._data(b);
                for (d in e.events)
                {
                    m.removeEvent(b, d, e.handle)
                }
                b.removeAttribute(m.expando)
            }
            "script" === c && b.text !== a.text ? (xb(b).text = a.text, yb(b)) : "object" === c ? (b.parentNode && (b.outerHTML = a.outerHTML), k.html5Clone && a.innerHTML && !m.trim(b.innerHTML) && (b.innerHTML = a.innerHTML)) : "input" === c && W.test(a.type) ? (b.defaultChecked = b.checked = a.checked, b.value !== a.value && (b.value = a.value)) : "option" === c ? b.defaultSelected = b.selected = a.defaultSelected : ("input" === c || "textarea" === c) && (b.defaultValue = a.defaultValue)
        }
    }

    m.extend({
        clone: function (a, b, c)
        {
            var d, e, f, g, h, i = m.contains(a.ownerDocument, a);
            if (k.html5Clone || m.isXMLDoc(a) || !gb.test("<" + a.nodeName + ">") ? f = a.cloneNode(!0) : (tb.innerHTML = a.outerHTML, tb.removeChild(f = tb.firstChild)), !(k.noCloneEvent && k.noCloneChecked || 1 !== a.nodeType && 11 !== a.nodeType || m.isXMLDoc(a)))
            {
                for (d = ub(f), h = ub(a), g = 0; null != (e = h[g]); ++g)
                {
                    d[g] && Bb(e, d[g])
                }
            }
            if (b)
            {
                if (c)
                {
                    for (h = h || ub(a), d = d || ub(f), g = 0; null != (e = h[g]); g++)
                    {
                        Ab(e, d[g])
                    }
                }
                else
                {
                    Ab(a, f)
                }
            }
            return d = ub(f, "script"), d.length > 0 && zb(d, !i && ub(a, "script")), d = h = e = null, f
        }, buildFragment: function (a, b, c, d)
        {
            for (var e, f, g, h, i, j, l, n = a.length, o = db(b), p = [], q = 0; n > q; q++)
            {
                if (f = a[q], f || 0 === f)
                {
                    if ("object" === m.type(f))
                    {
                        m.merge(p, f.nodeType ? [f] : f)
                    }
                    else
                    {
                        if (lb.test(f))
                        {
                            h = h || o.appendChild(b.createElement("div")), i = (jb.exec(f) || ["", ""])[1].toLowerCase(), l = rb[i] || rb._default, h.innerHTML = l[1] + f.replace(ib, "<$1></$2>") + l[2], e = l[0];
                            while (e--)
                            {
                                h = h.lastChild
                            }
                            if (!k.leadingWhitespace && hb.test(f) && p.push(b.createTextNode(hb.exec(f)[0])), !k.tbody)
                            {
                                f = "table" !== i || kb.test(f) ? "<table>" !== l[1] || kb.test(f) ? 0 : h : h.firstChild, e = f && f.childNodes.length;
                                while (e--)
                                {
                                    m.nodeName(j = f.childNodes[e], "tbody") && !j.childNodes.length && f.removeChild(j)
                                }
                            }
                            m.merge(p, h.childNodes), h.textContent = "";
                            while (h.firstChild)
                            {
                                h.removeChild(h.firstChild)
                            }
                            h = o.lastChild
                        }
                        else
                        {
                            p.push(b.createTextNode(f))
                        }
                    }
                }
            }
            h && o.removeChild(h), k.appendChecked || m.grep(ub(p, "input"), vb), q = 0;
            while (f = p[q++])
            {
                if ((!d || -1 === m.inArray(f, d)) && (g = m.contains(f.ownerDocument, f), h = ub(o.appendChild(f), "script"), g && zb(h), c))
                {
                    e = 0;
                    while (f = h[e++])
                    {
                        ob.test(f.type || "") && c.push(f)
                    }
                }
            }
            return h = null, o
        }, cleanData: function (a, b)
        {
            for (var d, e, f, g, h = 0, i = m.expando, j = m.cache, l = k.deleteExpando, n = m.event.special; null != (d = a[h]); h++)
            {
                if ((b || m.acceptData(d)) && (f = d[i], g = f && j[f]))
                {
                    if (g.events)
                    {
                        for (e in g.events)
                        {
                            n[e] ? m.event.remove(d, e) : m.removeEvent(d, e, g.handle)
                        }
                    }
                    j[f] && (delete j[f], l ? delete d[i] : typeof d.removeAttribute !== K ? d.removeAttribute(i) : d[i] = null, c.push(f))
                }
            }
        }
    }), m.fn.extend({
        text: function (a)
        {
            return V(this, function (a)
            {
                return void 0 === a ? m.text(this) : this.empty().append((this[0] && this[0].ownerDocument || y).createTextNode(a))
            }, null, a, arguments.length)
        }, append: function ()
        {
            return this.domManip(arguments, function (a)
            {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType)
                {
                    var b = wb(this, a);
                    b.appendChild(a)
                }
            })
        }, prepend: function ()
        {
            return this.domManip(arguments, function (a)
            {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType)
                {
                    var b = wb(this, a);
                    b.insertBefore(a, b.firstChild)
                }
            })
        }, before: function ()
        {
            return this.domManip(arguments, function (a)
            {
                this.parentNode && this.parentNode.insertBefore(a, this)
            })
        }, after: function ()
        {
            return this.domManip(arguments, function (a)
            {
                this.parentNode && this.parentNode.insertBefore(a, this.nextSibling)
            })
        }, remove: function (a, b)
        {
            for (var c, d = a ? m.filter(a, this) : this, e = 0; null != (c = d[e]); e++)
            {
                b || 1 !== c.nodeType || m.cleanData(ub(c)), c.parentNode && (b && m.contains(c.ownerDocument, c) && zb(ub(c, "script")), c.parentNode.removeChild(c))
            }
            return this
        }, empty: function ()
        {
            for (var a, b = 0; null != (a = this[b]); b++)
            {
                1 === a.nodeType && m.cleanData(ub(a, !1));
                while (a.firstChild)
                {
                    a.removeChild(a.firstChild)
                }
                a.options && m.nodeName(a, "select") && (a.options.length = 0)
            }
            return this
        }, clone: function (a, b)
        {
            return a = null == a ? !1 : a, b = null == b ? a : b, this.map(function ()
            {
                return m.clone(this, a, b)
            })
        }, html: function (a)
        {
            return V(this, function (a)
            {
                var b = this[0] || {}, c = 0, d = this.length;
                if (void 0 === a)
                {
                    return 1 === b.nodeType ? b.innerHTML.replace(fb, "") : void 0
                }
                if (!("string" != typeof a || mb.test(a) || !k.htmlSerialize && gb.test(a) || !k.leadingWhitespace && hb.test(a) || rb[(jb.exec(a) || ["", ""])[1].toLowerCase()]))
                {
                    a = a.replace(ib, "<$1></$2>");
                    try
                    {
                        for (; d > c; c++)
                        {
                            b = this[c] || {}, 1 === b.nodeType && (m.cleanData(ub(b, !1)), b.innerHTML = a)
                        }
                        b = 0
                    } catch (e)
                    {
                    }
                }
                b && this.empty().append(a)
            }, null, a, arguments.length)
        }, replaceWith: function ()
        {
            var a = arguments[0];
            return this.domManip(arguments, function (b)
            {
                a = this.parentNode, m.cleanData(ub(this)), a && a.replaceChild(b, this)
            }), a && (a.length || a.nodeType) ? this : this.remove()
        }, detach: function (a)
        {
            return this.remove(a, !0)
        }, domManip: function (a, b)
        {
            a = e.apply([], a);
            var c, d, f, g, h, i, j = 0, l = this.length, n = this, o = l - 1, p = a[0], q = m.isFunction(p);
            if (q || l > 1 && "string" == typeof p && !k.checkClone && nb.test(p))
            {
                return this.each(function (c)
                {
                    var d = n.eq(c);
                    q && (a[0] = p.call(this, c, d.html())), d.domManip(a, b)
                })
            }
            if (l && (i = m.buildFragment(a, this[0].ownerDocument, !1, this), c = i.firstChild, 1 === i.childNodes.length && (i = c), c))
            {
                for (g = m.map(ub(i, "script"), xb), f = g.length; l > j; j++)
                {
                    d = i, j !== o && (d = m.clone(d, !0, !0), f && m.merge(g, ub(d, "script"))), b.call(this[j], d, j)
                }
                if (f)
                {
                    for (h = g[g.length - 1].ownerDocument, m.map(g, yb), j = 0; f > j; j++)
                    {
                        d = g[j], ob.test(d.type || "") && !m._data(d, "globalEval") && m.contains(h, d) && (d.src ? m._evalUrl && m._evalUrl(d.src) : m.globalEval((d.text || d.textContent || d.innerHTML || "").replace(qb, "")))
                    }
                }
                i = c = null
            }
            return this
        }
    }), m.each({appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith"}, function (a, b)
    {
        m.fn[a] = function (a)
        {
            for (var c, d = 0, e = [], g = m(a), h = g.length - 1; h >= d; d++)
            {
                c = d === h ? this : this.clone(!0), m(g[d])[b](c), f.apply(e, c.get())
            }
            return this.pushStack(e)
        }
    });
    var Cb, Db = {};

    function Eb(b, c)
    {
        var d, e = m(c.createElement(b)).appendTo(c.body), f = a.getDefaultComputedStyle && (d = a.getDefaultComputedStyle(e[0])) ? d.display : m.css(e[0], "display");
        return e.detach(), f
    }

    function Fb(a)
    {
        var b = y, c = Db[a];
        return c || (c = Eb(a, b), "none" !== c && c || (Cb = (Cb || m("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement), b = (Cb[0].contentWindow || Cb[0].contentDocument).document, b.write(), b.close(), c = Eb(a, b), Cb.detach()), Db[a] = c), c
    }

    !function ()
    {
        var a;
        k.shrinkWrapBlocks = function ()
        {
            if (null != a)
            {
                return a
            }
            a = !1;
            var b, c, d;
            return c = y.getElementsByTagName("body")[0], c && c.style ? (b = y.createElement("div"), d = y.createElement("div"), d.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", c.appendChild(d).appendChild(b), typeof b.style.zoom !== K && (b.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", b.appendChild(y.createElement("div")).style.width = "5px", a = 3 !== b.offsetWidth), c.removeChild(d), a) : void 0
        }
    }();
    var Gb = /^margin/, Hb = new RegExp("^(" + S + ")(?!px)[a-z%]+$", "i"), Ib, Jb, Kb = /^(top|right|bottom|left)$/;
    a.getComputedStyle ? (Ib = function (a)
    {
        return a.ownerDocument.defaultView.getComputedStyle(a, null)
    }, Jb = function (a, b, c)
    {
        var d, e, f, g, h = a.style;
        return c = c || Ib(a), g = c ? c.getPropertyValue(b) || c[b] : void 0, c && ("" !== g || m.contains(a.ownerDocument, a) || (g = m.style(a, b)), Hb.test(g) && Gb.test(b) && (d = h.width, e = h.minWidth, f = h.maxWidth, h.minWidth = h.maxWidth = h.width = g, g = c.width, h.width = d, h.minWidth = e, h.maxWidth = f)), void 0 === g ? g : g + ""
    }) : y.documentElement.currentStyle && (Ib = function (a)
    {
        return a.currentStyle
    }, Jb = function (a, b, c)
    {
        var d, e, f, g, h = a.style;
        return c = c || Ib(a), g = c ? c[b] : void 0, null == g && h && h[b] && (g = h[b]), Hb.test(g) && !Kb.test(b) && (d = h.left, e = a.runtimeStyle, f = e && e.left, f && (e.left = a.currentStyle.left), h.left = "fontSize" === b ? "1em" : g, g = h.pixelLeft + "px", h.left = d, f && (e.left = f)), void 0 === g ? g : g + "" || "auto"
    });
    function Lb(a, b)
    {
        return {
            get: function ()
            {
                var c = a();
                if (null != c)
                {
                    return c ? void delete this.get : (this.get = b).apply(this, arguments)
                }
            }
        }
    }

    !function ()
    {
        var b, c, d, e, f, g, h;
        if (b = y.createElement("div"), b.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", d = b.getElementsByTagName("a")[0], c = d && d.style)
        {
            c.cssText = "float:left;opacity:.5", k.opacity = "0.5" === c.opacity, k.cssFloat = !!c.cssFloat, b.style.backgroundClip = "content-box", b.cloneNode(!0).style.backgroundClip = "", k.clearCloneStyle = "content-box" === b.style.backgroundClip, k.boxSizing = "" === c.boxSizing || "" === c.MozBoxSizing || "" === c.WebkitBoxSizing, m.extend(k, {
                reliableHiddenOffsets: function ()
                {
                    return null == g && i(), g
                }, boxSizingReliable: function ()
                {
                    return null == f && i(), f
                }, pixelPosition: function ()
                {
                    return null == e && i(), e
                }, reliableMarginRight: function ()
                {
                    return null == h && i(), h
                }
            });
            function i()
            {
                var b, c, d, i;
                c = y.getElementsByTagName("body")[0], c && c.style && (b = y.createElement("div"), d = y.createElement("div"), d.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", c.appendChild(d).appendChild(b), b.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", e = f = !1, h = !0, a.getComputedStyle && (e = "1%" !== (a.getComputedStyle(b, null) || {}).top, f = "4px" === (a.getComputedStyle(b, null) || {width: "4px"}).width, i = b.appendChild(y.createElement("div")), i.style.cssText = b.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", i.style.marginRight = i.style.width = "0", b.style.width = "1px", h = !parseFloat((a.getComputedStyle(i, null) || {}).marginRight)), b.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", i = b.getElementsByTagName("td"), i[0].style.cssText = "margin:0;border:0;padding:0;display:none", g = 0 === i[0].offsetHeight, g && (i[0].style.display = "", i[1].style.display = "none", g = 0 === i[0].offsetHeight), c.removeChild(d))
            }
        }
    }(), m.swap = function (a, b, c, d)
    {
        var e, f, g = {};
        for (f in b)
        {
            g[f] = a.style[f], a.style[f] = b[f]
        }
        e = c.apply(a, d || []);
        for (f in b)
        {
            a.style[f] = g[f]
        }
        return e
    };
    var Mb = /alpha\([^)]*\)/i, Nb = /opacity\s*=\s*([^)]*)/, Ob = /^(none|table(?!-c[ea]).+)/, Pb = new RegExp("^(" + S + ")(.*)$", "i"), Qb = new RegExp("^([+-])=(" + S + ")", "i"), Rb = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, Sb = {letterSpacing: "0", fontWeight: "400"}, Tb = ["Webkit", "O", "Moz", "ms"];

    function Ub(a, b)
    {
        if (b in a)
        {
            return b
        }
        var c = b.charAt(0).toUpperCase() + b.slice(1), d = b, e = Tb.length;
        while (e--)
        {
            if (b = Tb[e] + c, b in a)
            {
                return b
            }
        }
        return d
    }

    function Vb(a, b)
    {
        for (var c, d, e, f = [], g = 0, h = a.length; h > g; g++)
        {
            d = a[g], d.style && (f[g] = m._data(d, "olddisplay"), c = d.style.display, b ? (f[g] || "none" !== c || (d.style.display = ""), "" === d.style.display && U(d) && (f[g] = m._data(d, "olddisplay", Fb(d.nodeName)))) : (e = U(d), (c && "none" !== c || !e) && m._data(d, "olddisplay", e ? c : m.css(d, "display"))))
        }
        for (g = 0; h > g; g++)
        {
            d = a[g], d.style && (b && "none" !== d.style.display && "" !== d.style.display || (d.style.display = b ? f[g] || "" : "none"))
        }
        return a
    }

    function Wb(a, b, c)
    {
        var d = Pb.exec(b);
        return d ? Math.max(0, d[1] - (c || 0)) + (d[2] || "px") : b
    }

    function Xb(a, b, c, d, e)
    {
        for (var f = c === (d ? "border" : "content") ? 4 : "width" === b ? 1 : 0, g = 0; 4 > f; f += 2)
        {
            "margin" === c && (g += m.css(a, c + T[f], !0, e)), d ? ("content" === c && (g -= m.css(a, "padding" + T[f], !0, e)), "margin" !== c && (g -= m.css(a, "border" + T[f] + "Width", !0, e))) : (g += m.css(a, "padding" + T[f], !0, e), "padding" !== c && (g += m.css(a, "border" + T[f] + "Width", !0, e)))
        }
        return g
    }

    function Yb(a, b, c)
    {
        var d = !0, e = "width" === b ? a.offsetWidth : a.offsetHeight, f = Ib(a), g = k.boxSizing && "border-box" === m.css(a, "boxSizing", !1, f);
        if (0 >= e || null == e)
        {
            if (e = Jb(a, b, f), (0 > e || null == e) && (e = a.style[b]), Hb.test(e))
            {
                return e
            }
            d = g && (k.boxSizingReliable() || e === a.style[b]), e = parseFloat(e) || 0
        }
        return e + Xb(a, b, c || (g ? "border" : "content"), d, f) + "px"
    }

    m.extend({
        cssHooks: {
            opacity: {
                get: function (a, b)
                {
                    if (b)
                    {
                        var c = Jb(a, "opacity");
                        return "" === c ? "1" : c
                    }
                }
            }
        },
        cssNumber: {columnCount: !0, fillOpacity: !0, flexGrow: !0, flexShrink: !0, fontWeight: !0, lineHeight: !0, opacity: !0, order: !0, orphans: !0, widows: !0, zIndex: !0, zoom: !0},
        cssProps: {"float": k.cssFloat ? "cssFloat" : "styleFloat"},
        style: function (a, b, c, d)
        {
            if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style)
            {
                var e, f, g, h = m.camelCase(b), i = a.style;
                if (b = m.cssProps[h] || (m.cssProps[h] = Ub(i, h)), g = m.cssHooks[b] || m.cssHooks[h], void 0 === c)
                {
                    return g && "get" in g && void 0 !== (e = g.get(a, !1, d)) ? e : i[b]
                }
                if (f = typeof c, "string" === f && (e = Qb.exec(c)) && (c = (e[1] + 1) * e[2] + parseFloat(m.css(a, b)), f = "number"), null != c && c === c && ("number" !== f || m.cssNumber[h] || (c += "px"), k.clearCloneStyle || "" !== c || 0 !== b.indexOf("background") || (i[b] = "inherit"), !(g && "set" in g && void 0 === (c = g.set(a, c, d)))))
                {
                    try
                    {
                        i[b] = c
                    } catch (j)
                    {
                    }
                }
            }
        },
        css: function (a, b, c, d)
        {
            var e, f, g, h = m.camelCase(b);
            return b = m.cssProps[h] || (m.cssProps[h] = Ub(a.style, h)), g = m.cssHooks[b] || m.cssHooks[h], g && "get" in g && (f = g.get(a, !0, c)), void 0 === f && (f = Jb(a, b, d)), "normal" === f && b in Sb && (f = Sb[b]), "" === c || c ? (e = parseFloat(f), c === !0 || m.isNumeric(e) ? e || 0 : f) : f
        }
    }), m.each(["height", "width"], function (a, b)
    {
        m.cssHooks[b] = {
            get: function (a, c, d)
            {
                return c ? Ob.test(m.css(a, "display")) && 0 === a.offsetWidth ? m.swap(a, Rb, function ()
                {
                    return Yb(a, b, d)
                }) : Yb(a, b, d) : void 0
            }, set: function (a, c, d)
            {
                var e = d && Ib(a);
                return Wb(a, c, d ? Xb(a, b, d, k.boxSizing && "border-box" === m.css(a, "boxSizing", !1, e), e) : 0)
            }
        }
    }), k.opacity || (m.cssHooks.opacity = {
        get: function (a, b)
        {
            return Nb.test((b && a.currentStyle ? a.currentStyle.filter : a.style.filter) || "") ? 0.01 * parseFloat(RegExp.$1) + "" : b ? "1" : ""
        }, set: function (a, b)
        {
            var c = a.style, d = a.currentStyle, e = m.isNumeric(b) ? "alpha(opacity=" + 100 * b + ")" : "", f = d && d.filter || c.filter || "";
            c.zoom = 1, (b >= 1 || "" === b) && "" === m.trim(f.replace(Mb, "")) && c.removeAttribute && (c.removeAttribute("filter"), "" === b || d && !d.filter) || (c.filter = Mb.test(f) ? f.replace(Mb, e) : f + " " + e)
        }
    }), m.cssHooks.marginRight = Lb(k.reliableMarginRight, function (a, b)
    {
        return b ? m.swap(a, {display: "inline-block"}, Jb, [a, "marginRight"]) : void 0
    }), m.each({margin: "", padding: "", border: "Width"}, function (a, b)
    {
        m.cssHooks[a + b] = {
            expand: function (c)
            {
                for (var d = 0, e = {}, f = "string" == typeof c ? c.split(" ") : [c]; 4 > d; d++)
                {
                    e[a + T[d] + b] = f[d] || f[d - 2] || f[0]
                }
                return e
            }
        }, Gb.test(a) || (m.cssHooks[a + b].set = Wb)
    }), m.fn.extend({
        css: function (a, b)
        {
            return V(this, function (a, b, c)
            {
                var d, e, f = {}, g = 0;
                if (m.isArray(b))
                {
                    for (d = Ib(a), e = b.length; e > g; g++)
                    {
                        f[b[g]] = m.css(a, b[g], !1, d)
                    }
                    return f
                }
                return void 0 !== c ? m.style(a, b, c) : m.css(a, b)
            }, a, b, arguments.length > 1)
        }, show: function ()
        {
            return Vb(this, !0)
        }, hide: function ()
        {
            return Vb(this)
        }, toggle: function (a)
        {
            return "boolean" == typeof a ? a ? this.show() : this.hide() : this.each(function ()
            {
                U(this) ? m(this).show() : m(this).hide()
            })
        }
    });
    function Zb(a, b, c, d, e)
    {
        return new Zb.prototype.init(a, b, c, d, e)
    }

    m.Tween = Zb, Zb.prototype = {
        constructor: Zb, init: function (a, b, c, d, e, f)
        {
            this.elem = a, this.prop = c, this.easing = e || "swing", this.options = b, this.start = this.now = this.cur(), this.end = d, this.unit = f || (m.cssNumber[c] ? "" : "px")
        }, cur: function ()
        {
            var a = Zb.propHooks[this.prop];
            return a && a.get ? a.get(this) : Zb.propHooks._default.get(this)
        }, run: function (a)
        {
            var b, c = Zb.propHooks[this.prop];
            return this.pos = b = this.options.duration ? m.easing[this.easing](a, this.options.duration * a, 0, 1, this.options.duration) : a, this.now = (this.end - this.start) * b + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), c && c.set ? c.set(this) : Zb.propHooks._default.set(this), this
        }
    }, Zb.prototype.init.prototype = Zb.prototype, Zb.propHooks = {
        _default: {
            get: function (a)
            {
                var b;
                return null == a.elem[a.prop] || a.elem.style && null != a.elem.style[a.prop] ? (b = m.css(a.elem, a.prop, ""), b && "auto" !== b ? b : 0) : a.elem[a.prop]
            }, set: function (a)
            {
                m.fx.step[a.prop] ? m.fx.step[a.prop](a) : a.elem.style && (null != a.elem.style[m.cssProps[a.prop]] || m.cssHooks[a.prop]) ? m.style(a.elem, a.prop, a.now + a.unit) : a.elem[a.prop] = a.now
            }
        }
    }, Zb.propHooks.scrollTop = Zb.propHooks.scrollLeft = {
        set: function (a)
        {
            a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now)
        }
    }, m.easing = {
        linear: function (a)
        {
            return a
        }, swing: function (a)
        {
            return 0.5 - Math.cos(a * Math.PI) / 2
        }
    }, m.fx = Zb.prototype.init, m.fx.step = {};
    var $b, _b, ac = /^(?:toggle|show|hide)$/, bc = new RegExp("^(?:([+-])=|)(" + S + ")([a-z%]*)$", "i"), cc = /queueHooks$/, dc = [ic], ec = {
        "*": [function (a, b)
        {
            var c = this.createTween(a, b), d = c.cur(), e = bc.exec(b), f = e && e[3] || (m.cssNumber[a] ? "" : "px"), g = (m.cssNumber[a] || "px" !== f && +d) && bc.exec(m.css(c.elem, a)), h = 1, i = 20;
            if (g && g[3] !== f)
            {
                f = f || g[3], e = e || [], g = +d || 1;
                do {
                    h = h || ".5", g /= h, m.style(c.elem, a, g + f)
                } while (h !== (h = c.cur() / d) && 1 !== h && --i)
            }
            return e && (g = c.start = +g || +d || 0, c.unit = f, c.end = e[1] ? g + (e[1] + 1) * e[2] : +e[2]), c
        }]
    };

    function fc()
    {
        return setTimeout(function ()
        {
            $b = void 0
        }), $b = m.now()
    }

    function gc(a, b)
    {
        var c, d = {height: a}, e = 0;
        for (b = b ? 1 : 0; 4 > e; e += 2 - b)
        {
            c = T[e], d["margin" + c] = d["padding" + c] = a
        }
        return b && (d.opacity = d.width = a), d
    }

    function hc(a, b, c)
    {
        for (var d, e = (ec[b] || []).concat(ec["*"]), f = 0, g = e.length; g > f; f++)
        {
            if (d = e[f].call(c, b, a))
            {
                return d
            }
        }
    }

    function ic(a, b, c)
    {
        var d, e, f, g, h, i, j, l, n = this, o = {}, p = a.style, q = a.nodeType && U(a), r = m._data(a, "fxshow");
        c.queue || (h = m._queueHooks(a, "fx"), null == h.unqueued && (h.unqueued = 0, i = h.empty.fire, h.empty.fire = function ()
        {
            h.unqueued || i()
        }), h.unqueued++, n.always(function ()
        {
            n.always(function ()
            {
                h.unqueued--, m.queue(a, "fx").length || h.empty.fire()
            })
        })), 1 === a.nodeType && ("height" in b || "width" in b) && (c.overflow = [p.overflow, p.overflowX, p.overflowY], j = m.css(a, "display"), l = "none" === j ? m._data(a, "olddisplay") || Fb(a.nodeName) : j, "inline" === l && "none" === m.css(a, "float") && (k.inlineBlockNeedsLayout && "inline" !== Fb(a.nodeName) ? p.zoom = 1 : p.display = "inline-block")), c.overflow && (p.overflow = "hidden", k.shrinkWrapBlocks() || n.always(function ()
        {
            p.overflow = c.overflow[0], p.overflowX = c.overflow[1], p.overflowY = c.overflow[2]
        }));
        for (d in b)
        {
            if (e = b[d], ac.exec(e))
            {
                if (delete b[d], f = f || "toggle" === e, e === (q ? "hide" : "show"))
                {
                    if ("show" !== e || !r || void 0 === r[d])
                    {
                        continue
                    }
                    q = !0
                }
                o[d] = r && r[d] || m.style(a, d)
            }
            else
            {
                j = void 0
            }
        }
        if (m.isEmptyObject(o))
        {
            "inline" === ("none" === j ? Fb(a.nodeName) : j) && (p.display = j)
        }
        else
        {
            r ? "hidden" in r && (q = r.hidden) : r = m._data(a, "fxshow", {}), f && (r.hidden = !q), q ? m(a).show() : n.done(function ()
            {
                m(a).hide()
            }), n.done(function ()
            {
                var b;
                m._removeData(a, "fxshow");
                for (b in o)
                {
                    m.style(a, b, o[b])
                }
            });
            for (d in o)
            {
                g = hc(q ? r[d] : 0, d, n), d in r || (r[d] = g.start, q && (g.end = g.start, g.start = "width" === d || "height" === d ? 1 : 0))
            }
        }
    }

    function jc(a, b)
    {
        var c, d, e, f, g;
        for (c in a)
        {
            if (d = m.camelCase(c), e = b[d], f = a[c], m.isArray(f) && (e = f[1], f = a[c] = f[0]), c !== d && (a[d] = f, delete a[c]), g = m.cssHooks[d], g && "expand" in g)
            {
                f = g.expand(f), delete a[d];
                for (c in f)
                {
                    c in a || (a[c] = f[c], b[c] = e)
                }
            }
            else
            {
                b[d] = e
            }
        }
    }

    function kc(a, b, c)
    {
        var d, e, f = 0, g = dc.length, h = m.Deferred().always(function ()
        {
            delete i.elem
        }), i = function ()
        {
            if (e)
            {
                return !1
            }
            for (var b = $b || fc(), c = Math.max(0, j.startTime + j.duration - b), d = c / j.duration || 0, f = 1 - d, g = 0, i = j.tweens.length; i > g; g++)
            {
                j.tweens[g].run(f)
            }
            return h.notifyWith(a, [j, f, c]), 1 > f && i ? c : (h.resolveWith(a, [j]), !1)
        }, j = h.promise({
            elem: a,
            props: m.extend({}, b),
            opts: m.extend(!0, {specialEasing: {}}, c),
            originalProperties: b,
            originalOptions: c,
            startTime: $b || fc(),
            duration: c.duration,
            tweens: [],
            createTween: function (b, c)
            {
                var d = m.Tween(a, j.opts, b, c, j.opts.specialEasing[b] || j.opts.easing);
                return j.tweens.push(d), d
            },
            stop: function (b)
            {
                var c = 0, d = b ? j.tweens.length : 0;
                if (e)
                {
                    return this
                }
                for (e = !0; d > c; c++)
                {
                    j.tweens[c].run(1)
                }
                return b ? h.resolveWith(a, [j, b]) : h.rejectWith(a, [j, b]), this
            }
        }), k = j.props;
        for (jc(k, j.opts.specialEasing); g > f; f++)
        {
            if (d = dc[f].call(j, a, k, j.opts))
            {
                return d
            }
        }
        return m.map(k, hc, j), m.isFunction(j.opts.start) && j.opts.start.call(a, j), m.fx.timer(m.extend(i, {
            elem: a,
            anim: j,
            queue: j.opts.queue
        })), j.progress(j.opts.progress).done(j.opts.done, j.opts.complete).fail(j.opts.fail).always(j.opts.always)
    }

    m.Animation = m.extend(kc, {
        tweener: function (a, b)
        {
            m.isFunction(a) ? (b = a, a = ["*"]) : a = a.split(" ");
            for (var c, d = 0, e = a.length; e > d; d++)
            {
                c = a[d], ec[c] = ec[c] || [], ec[c].unshift(b)
            }
        }, prefilter: function (a, b)
        {
            b ? dc.unshift(a) : dc.push(a)
        }
    }), m.speed = function (a, b, c)
    {
        var d = a && "object" == typeof a ? m.extend({}, a) : {complete: c || !c && b || m.isFunction(a) && a, duration: a, easing: c && b || b && !m.isFunction(b) && b};
        return d.duration = m.fx.off ? 0 : "number" == typeof d.duration ? d.duration : d.duration in m.fx.speeds ? m.fx.speeds[d.duration] : m.fx.speeds._default, (null == d.queue || d.queue === !0) && (d.queue = "fx"), d.old = d.complete, d.complete = function ()
        {
            m.isFunction(d.old) && d.old.call(this), d.queue && m.dequeue(this, d.queue)
        }, d
    }, m.fn.extend({
        fadeTo: function (a, b, c, d)
        {
            return this.filter(U).css("opacity", 0).show().end().animate({opacity: b}, a, c, d)
        }, animate: function (a, b, c, d)
        {
            var e = m.isEmptyObject(a), f = m.speed(b, c, d), g = function ()
            {
                var b = kc(this, m.extend({}, a), f);
                (e || m._data(this, "finish")) && b.stop(!0)
            };
            return g.finish = g, e || f.queue === !1 ? this.each(g) : this.queue(f.queue, g)
        }, stop: function (a, b, c)
        {
            var d = function (a)
            {
                var b = a.stop;
                delete a.stop, b(c)
            };
            return "string" != typeof a && (c = b, b = a, a = void 0), b && a !== !1 && this.queue(a || "fx", []), this.each(function ()
            {
                var b = !0, e = null != a && a + "queueHooks", f = m.timers, g = m._data(this);
                if (e)
                {
                    g[e] && g[e].stop && d(g[e])
                }
                else
                {
                    for (e in g)
                    {
                        g[e] && g[e].stop && cc.test(e) && d(g[e])
                    }
                }
                for (e = f.length; e--;)
                {
                    f[e].elem !== this || null != a && f[e].queue !== a || (f[e].anim.stop(c), b = !1, f.splice(e, 1))
                }
                (b || !c) && m.dequeue(this, a)
            })
        }, finish: function (a)
        {
            return a !== !1 && (a = a || "fx"), this.each(function ()
            {
                var b, c = m._data(this), d = c[a + "queue"], e = c[a + "queueHooks"], f = m.timers, g = d ? d.length : 0;
                for (c.finish = !0, m.queue(this, a, []), e && e.stop && e.stop.call(this, !0), b = f.length; b--;)
                {
                    f[b].elem === this && f[b].queue === a && (f[b].anim.stop(!0), f.splice(b, 1))
                }
                for (b = 0; g > b; b++)
                {
                    d[b] && d[b].finish && d[b].finish.call(this)
                }
                delete c.finish
            })
        }
    }), m.each(["toggle", "show", "hide"], function (a, b)
    {
        var c = m.fn[b];
        m.fn[b] = function (a, d, e)
        {
            return null == a || "boolean" == typeof a ? c.apply(this, arguments) : this.animate(gc(b, !0), a, d, e)
        }
    }), m.each({slideDown: gc("show"), slideUp: gc("hide"), slideToggle: gc("toggle"), fadeIn: {opacity: "show"}, fadeOut: {opacity: "hide"}, fadeToggle: {opacity: "toggle"}}, function (a, b)
    {
        m.fn[a] = function (a, c, d)
        {
            return this.animate(b, a, c, d)
        }
    }), m.timers = [], m.fx.tick = function ()
    {
        var a, b = m.timers, c = 0;
        for ($b = m.now(); c < b.length; c++)
        {
            a = b[c], a() || b[c] !== a || b.splice(c--, 1)
        }
        b.length || m.fx.stop(), $b = void 0
    }, m.fx.timer = function (a)
    {
        m.timers.push(a), a() ? m.fx.start() : m.timers.pop()
    }, m.fx.interval = 13, m.fx.start = function ()
    {
        _b || (_b = setInterval(m.fx.tick, m.fx.interval))
    }, m.fx.stop = function ()
    {
        clearInterval(_b), _b = null
    }, m.fx.speeds = {slow: 600, fast: 200, _default: 400}, m.fn.delay = function (a, b)
    {
        return a = m.fx ? m.fx.speeds[a] || a : a, b = b || "fx", this.queue(b, function (b, c)
        {
            var d = setTimeout(b, a);
            c.stop = function ()
            {
                clearTimeout(d)
            }
        })
    }, function ()
    {
        var a, b, c, d, e;
        b = y.createElement("div"), b.setAttribute("className", "t"), b.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", d = b.getElementsByTagName("a")[0], c = y.createElement("select"), e = c.appendChild(y.createElement("option")), a = b.getElementsByTagName("input")[0], d.style.cssText = "top:1px", k.getSetAttribute = "t" !== b.className, k.style = /top/.test(d.getAttribute("style")), k.hrefNormalized = "/a" === d.getAttribute("href"), k.checkOn = !!a.value, k.optSelected = e.selected, k.enctype = !!y.createElement("form").enctype, c.disabled = !0, k.optDisabled = !e.disabled, a = y.createElement("input"), a.setAttribute("value", ""), k.input = "" === a.getAttribute("value"), a.value = "t", a.setAttribute("type", "radio"), k.radioValue = "t" === a.value
    }();
    var lc = /\r/g;
    m.fn.extend({
        val: function (a)
        {
            var b, c, d, e = this[0];
            if (arguments.length)
            {
                return d = m.isFunction(a), this.each(function (c)
                {
                    var e;
                    1 === this.nodeType && (e = d ? a.call(this, c, m(this).val()) : a, null == e ? e = "" : "number" == typeof e ? e += "" : m.isArray(e) && (e = m.map(e, function (a)
                    {
                        return null == a ? "" : a + ""
                    })), b = m.valHooks[this.type] || m.valHooks[this.nodeName.toLowerCase()], b && "set" in b && void 0 !== b.set(this, e, "value") || (this.value = e))
                })
            }
            if (e)
            {
                return b = m.valHooks[e.type] || m.valHooks[e.nodeName.toLowerCase()], b && "get" in b && void 0 !== (c = b.get(e, "value")) ? c : (c = e.value, "string" == typeof c ? c.replace(lc, "") : null == c ? "" : c)
            }
        }
    }), m.extend({
        valHooks: {
            option: {
                get: function (a)
                {
                    var b = m.find.attr(a, "value");
                    return null != b ? b : m.trim(m.text(a))
                }
            }, select: {
                get: function (a)
                {
                    for (var b, c, d = a.options, e = a.selectedIndex, f = "select-one" === a.type || 0 > e, g = f ? null : [], h = f ? e + 1 : d.length, i = 0 > e ? h : f ? e : 0; h > i; i++)
                    {
                        if (c = d[i], !(!c.selected && i !== e || (k.optDisabled ? c.disabled : null !== c.getAttribute("disabled")) || c.parentNode.disabled && m.nodeName(c.parentNode, "optgroup")))
                        {
                            if (b = m(c).val(), f)
                            {
                                return b
                            }
                            g.push(b)
                        }
                    }
                    return g
                }, set: function (a, b)
                {
                    var c, d, e = a.options, f = m.makeArray(b), g = e.length;
                    while (g--)
                    {
                        if (d = e[g], m.inArray(m.valHooks.option.get(d), f) >= 0)
                        {
                            try
                            {
                                d.selected = c = !0
                            } catch (h)
                            {
                                d.scrollHeight
                            }
                        }
                        else
                        {
                            d.selected = !1
                        }
                    }
                    return c || (a.selectedIndex = -1), e
                }
            }
        }
    }), m.each(["radio", "checkbox"], function ()
    {
        m.valHooks[this] = {
            set: function (a, b)
            {
                return m.isArray(b) ? a.checked = m.inArray(m(a).val(), b) >= 0 : void 0
            }
        }, k.checkOn || (m.valHooks[this].get = function (a)
        {
            return null === a.getAttribute("value") ? "on" : a.value
        })
    });
    var mc, nc, oc = m.expr.attrHandle, pc = /^(?:checked|selected)$/i, qc = k.getSetAttribute, rc = k.input;
    m.fn.extend({
        attr: function (a, b)
        {
            return V(this, m.attr, a, b, arguments.length > 1)
        }, removeAttr: function (a)
        {
            return this.each(function ()
            {
                m.removeAttr(this, a)
            })
        }
    }), m.extend({
        attr: function (a, b, c)
        {
            var d, e, f = a.nodeType;
            if (a && 3 !== f && 8 !== f && 2 !== f)
            {
                return typeof a.getAttribute === K ? m.prop(a, b, c) : (1 === f && m.isXMLDoc(a) || (b = b.toLowerCase(), d = m.attrHooks[b] || (m.expr.match.bool.test(b) ? nc : mc)), void 0 === c ? d && "get" in d && null !== (e = d.get(a, b)) ? e : (e = m.find.attr(a, b), null == e ? void 0 : e) : null !== c ? d && "set" in d && void 0 !== (e = d.set(a, c, b)) ? e : (a.setAttribute(b, c + ""), c) : void m.removeAttr(a, b))
            }
        }, removeAttr: function (a, b)
        {
            var c, d, e = 0, f = b && b.match(E);
            if (f && 1 === a.nodeType)
            {
                while (c = f[e++])
                {
                    d = m.propFix[c] || c, m.expr.match.bool.test(c) ? rc && qc || !pc.test(c) ? a[d] = !1 : a[m.camelCase("default-" + c)] = a[d] = !1 : m.attr(a, c, ""), a.removeAttribute(qc ? c : d)
                }
            }
        }, attrHooks: {
            type: {
                set: function (a, b)
                {
                    if (!k.radioValue && "radio" === b && m.nodeName(a, "input"))
                    {
                        var c = a.value;
                        return a.setAttribute("type", b), c && (a.value = c), b
                    }
                }
            }
        }
    }), nc = {
        set: function (a, b, c)
        {
            return b === !1 ? m.removeAttr(a, c) : rc && qc || !pc.test(c) ? a.setAttribute(!qc && m.propFix[c] || c, c) : a[m.camelCase("default-" + c)] = a[c] = !0, c
        }
    }, m.each(m.expr.match.bool.source.match(/\w+/g), function (a, b)
    {
        var c = oc[b] || m.find.attr;
        oc[b] = rc && qc || !pc.test(b) ? function (a, b, d)
        {
            var e, f;
            return d || (f = oc[b], oc[b] = e, e = null != c(a, b, d) ? b.toLowerCase() : null, oc[b] = f), e
        } : function (a, b, c)
        {
            return c ? void 0 : a[m.camelCase("default-" + b)] ? b.toLowerCase() : null
        }
    }), rc && qc || (m.attrHooks.value = {
        set: function (a, b, c)
        {
            return m.nodeName(a, "input") ? void (a.defaultValue = b) : mc && mc.set(a, b, c)
        }
    }), qc || (mc = {
        set: function (a, b, c)
        {
            var d = a.getAttributeNode(c);
            return d || a.setAttributeNode(d = a.ownerDocument.createAttribute(c)), d.value = b += "", "value" === c || b === a.getAttribute(c) ? b : void 0
        }
    }, oc.id = oc.name = oc.coords = function (a, b, c)
    {
        var d;
        return c ? void 0 : (d = a.getAttributeNode(b)) && "" !== d.value ? d.value : null
    }, m.valHooks.button = {
        get: function (a, b)
        {
            var c = a.getAttributeNode(b);
            return c && c.specified ? c.value : void 0
        }, set: mc.set
    }, m.attrHooks.contenteditable = {
        set: function (a, b, c)
        {
            mc.set(a, "" === b ? !1 : b, c)
        }
    }, m.each(["width", "height"], function (a, b)
    {
        m.attrHooks[b] = {
            set: function (a, c)
            {
                return "" === c ? (a.setAttribute(b, "auto"), c) : void 0
            }
        }
    })), k.style || (m.attrHooks.style = {
        get: function (a)
        {
            return a.style.cssText || void 0
        }, set: function (a, b)
        {
            return a.style.cssText = b + ""
        }
    });
    var sc = /^(?:input|select|textarea|button|object)$/i, tc = /^(?:a|area)$/i;
    m.fn.extend({
        prop: function (a, b)
        {
            return V(this, m.prop, a, b, arguments.length > 1)
        }, removeProp: function (a)
        {
            return a = m.propFix[a] || a, this.each(function ()
            {
                try
                {
                    this[a] = void 0, delete this[a]
                } catch (b)
                {
                }
            })
        }
    }), m.extend({
        propFix: {"for": "htmlFor", "class": "className"}, prop: function (a, b, c)
        {
            var d, e, f, g = a.nodeType;
            if (a && 3 !== g && 8 !== g && 2 !== g)
            {
                return f = 1 !== g || !m.isXMLDoc(a), f && (b = m.propFix[b] || b, e = m.propHooks[b]), void 0 !== c ? e && "set" in e && void 0 !== (d = e.set(a, c, b)) ? d : a[b] = c : e && "get" in e && null !== (d = e.get(a, b)) ? d : a[b]
            }
        }, propHooks: {
            tabIndex: {
                get: function (a)
                {
                    var b = m.find.attr(a, "tabindex");
                    return b ? parseInt(b, 10) : sc.test(a.nodeName) || tc.test(a.nodeName) && a.href ? 0 : -1
                }
            }
        }
    }), k.hrefNormalized || m.each(["href", "src"], function (a, b)
    {
        m.propHooks[b] = {
            get: function (a)
            {
                return a.getAttribute(b, 4)
            }
        }
    }), k.optSelected || (m.propHooks.selected = {
        get: function (a)
        {
            var b = a.parentNode;
            return b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex), null
        }
    }), m.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function ()
    {
        m.propFix[this.toLowerCase()] = this
    }), k.enctype || (m.propFix.enctype = "encoding");
    var uc = /[\t\r\n\f]/g;
    m.fn.extend({
        addClass: function (a)
        {
            var b, c, d, e, f, g, h = 0, i = this.length, j = "string" == typeof a && a;
            if (m.isFunction(a))
            {
                return this.each(function (b)
                {
                    m(this).addClass(a.call(this, b, this.className))
                })
            }
            if (j)
            {
                for (b = (a || "").match(E) || []; i > h; h++)
                {
                    if (c = this[h], d = 1 === c.nodeType && (c.className ? (" " + c.className + " ").replace(uc, " ") : " "))
                    {
                        f = 0;
                        while (e = b[f++])
                        {
                            d.indexOf(" " + e + " ") < 0 && (d += e + " ")
                        }
                        g = m.trim(d), c.className !== g && (c.className = g)
                    }
                }
            }
            return this
        }, removeClass: function (a)
        {
            var b, c, d, e, f, g, h = 0, i = this.length, j = 0 === arguments.length || "string" == typeof a && a;
            if (m.isFunction(a))
            {
                return this.each(function (b)
                {
                    m(this).removeClass(a.call(this, b, this.className))
                })
            }
            if (j)
            {
                for (b = (a || "").match(E) || []; i > h; h++)
                {
                    if (c = this[h], d = 1 === c.nodeType && (c.className ? (" " + c.className + " ").replace(uc, " ") : ""))
                    {
                        f = 0;
                        while (e = b[f++])
                        {
                            while (d.indexOf(" " + e + " ") >= 0)
                            {
                                d = d.replace(" " + e + " ", " ")
                            }
                        }
                        g = a ? m.trim(d) : "", c.className !== g && (c.className = g)
                    }
                }
            }
            return this
        }, toggleClass: function (a, b)
        {
            var c = typeof a;
            return "boolean" == typeof b && "string" === c ? b ? this.addClass(a) : this.removeClass(a) : this.each(m.isFunction(a) ? function (c)
            {
                m(this).toggleClass(a.call(this, c, this.className, b), b)
            } : function ()
            {
                if ("string" === c)
                {
                    var b, d = 0, e = m(this), f = a.match(E) || [];
                    while (b = f[d++])
                    {
                        e.hasClass(b) ? e.removeClass(b) : e.addClass(b)
                    }
                }
                else
                {
                    (c === K || "boolean" === c) && (this.className && m._data(this, "__className__", this.className), this.className = this.className || a === !1 ? "" : m._data(this, "__className__") || "")
                }
            })
        }, hasClass: function (a)
        {
            for (var b = " " + a + " ", c = 0, d = this.length; d > c; c++)
            {
                if (1 === this[c].nodeType && (" " + this[c].className + " ").replace(uc, " ").indexOf(b) >= 0)
                {
                    return !0
                }
            }
            return !1
        }
    }), m.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (a, b)
    {
        m.fn[b] = function (a, c)
        {
            return arguments.length > 0 ? this.on(b, null, a, c) : this.trigger(b)
        }
    }), m.fn.extend({
        hover: function (a, b)
        {
            return this.mouseenter(a).mouseleave(b || a)
        }, bind: function (a, b, c)
        {
            return this.on(a, null, b, c)
        }, unbind: function (a, b)
        {
            return this.off(a, null, b)
        }, delegate: function (a, b, c, d)
        {
            return this.on(b, a, c, d)
        }, undelegate: function (a, b, c)
        {
            return 1 === arguments.length ? this.off(a, "**") : this.off(b, a || "**", c)
        }
    });
    var vc = m.now(), wc = /\?/, xc = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    m.parseJSON = function (b)
    {
        if (a.JSON && a.JSON.parse)
        {
            return a.JSON.parse(b + "")
        }
        var c, d = null, e = m.trim(b + "");
        return e && !m.trim(e.replace(xc, function (a, b, e, f)
        {
            return c && b && (d = 0), 0 === d ? a : (c = e || b, d += !f - !e, "")
        })) ? Function("return " + e)() : m.error("Invalid JSON: " + b)
    }, m.parseXML = function (b)
    {
        var c, d;
        if (!b || "string" != typeof b)
        {
            return null
        }
        try
        {
            a.DOMParser ? (d = new DOMParser, c = d.parseFromString(b, "text/xml")) : (c = new ActiveXObject("Microsoft.XMLDOM"), c.async = "false", c.loadXML(b))
        } catch (e)
        {
            c = void 0
        }
        return c && c.documentElement && !c.getElementsByTagName("parsererror").length || m.error("Invalid XML: " + b), c
    };
    var yc, zc, Ac = /#.*$/, Bc = /([?&])_=[^&]*/, Cc = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, Dc = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, Ec = /^(?:GET|HEAD)$/, Fc = /^\/\//, Gc = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, Hc = {}, Ic = {}, Jc = "*/".concat("*");
    try
    {
        zc = location.href
    } catch (Kc)
    {
        zc = y.createElement("a"), zc.href = "", zc = zc.href
    }
    yc = Gc.exec(zc.toLowerCase()) || [];
    function Lc(a)
    {
        return function (b, c)
        {
            "string" != typeof b && (c = b, b = "*");
            var d, e = 0, f = b.toLowerCase().match(E) || [];
            if (m.isFunction(c))
            {
                while (d = f[e++])
                {
                    "+" === d.charAt(0) ? (d = d.slice(1) || "*", (a[d] = a[d] || []).unshift(c)) : (a[d] = a[d] || []).push(c)
                }
            }
        }
    }

    function Mc(a, b, c, d)
    {
        var e = {}, f = a === Ic;

        function g(h)
        {
            var i;
            return e[h] = !0, m.each(a[h] || [], function (a, h)
            {
                var j = h(b, c, d);
                return "string" != typeof j || f || e[j] ? f ? !(i = j) : void 0 : (b.dataTypes.unshift(j), g(j), !1)
            }), i
        }

        return g(b.dataTypes[0]) || !e["*"] && g("*")
    }

    function Nc(a, b)
    {
        var c, d, e = m.ajaxSettings.flatOptions || {};
        for (d in b)
        {
            void 0 !== b[d] && ((e[d] ? a : c || (c = {}))[d] = b[d])
        }
        return c && m.extend(!0, a, c), a
    }

    function Oc(a, b, c)
    {
        var d, e, f, g, h = a.contents, i = a.dataTypes;
        while ("*" === i[0])
        {
            i.shift(), void 0 === e && (e = a.mimeType || b.getResponseHeader("Content-Type"))
        }
        if (e)
        {
            for (g in h)
            {
                if (h[g] && h[g].test(e))
                {
                    i.unshift(g);
                    break
                }
            }
        }
        if (i[0] in c)
        {
            f = i[0]
        }
        else
        {
            for (g in c)
            {
                if (!i[0] || a.converters[g + " " + i[0]])
                {
                    f = g;
                    break
                }
                d || (d = g)
            }
            f = f || d
        }
        return f ? (f !== i[0] && i.unshift(f), c[f]) : void 0
    }

    function Pc(a, b, c, d)
    {
        var e, f, g, h, i, j = {}, k = a.dataTypes.slice();
        if (k[1])
        {
            for (g in a.converters)
            {
                j[g.toLowerCase()] = a.converters[g]
            }
        }
        f = k.shift();
        while (f)
        {
            if (a.responseFields[f] && (c[a.responseFields[f]] = b), !i && d && a.dataFilter && (b = a.dataFilter(b, a.dataType)), i = f, f = k.shift())
            {
                if ("*" === f)
                {
                    f = i
                }
                else
                {
                    if ("*" !== i && i !== f)
                    {
                        if (g = j[i + " " + f] || j["* " + f], !g)
                        {
                            for (e in j)
                            {
                                if (h = e.split(" "), h[1] === f && (g = j[i + " " + h[0]] || j["* " + h[0]]))
                                {
                                    g === !0 ? g = j[e] : j[e] !== !0 && (f = h[0], k.unshift(h[1]));
                                    break
                                }
                            }
                        }
                        if (g !== !0)
                        {
                            if (g && a["throws"])
                            {
                                b = g(b)
                            }
                            else
                            {
                                try
                                {
                                    b = g(b)
                                } catch (l)
                                {
                                    return {state: "parsererror", error: g ? l : "No conversion from " + i + " to " + f}
                                }
                            }
                        }
                    }
                }
            }
        }
        return {state: "success", data: b}
    }

    m.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: zc,
            type: "GET",
            isLocal: Dc.test(yc[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {"*": Jc, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript"},
            contents: {xml: /xml/, html: /html/, json: /json/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": m.parseJSON, "text xml": m.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (a, b)
        {
            return b ? Nc(Nc(a, m.ajaxSettings), b) : Nc(m.ajaxSettings, a)
        },
        ajaxPrefilter: Lc(Hc),
        ajaxTransport: Lc(Ic),
        ajax: function (a, b)
        {
            "object" == typeof a && (b = a, a = void 0), b = b || {};
            var c, d, e, f, g, h, i, j, k = m.ajaxSetup({}, b), l = k.context || k, n = k.context && (l.nodeType || l.jquery) ? m(l) : m.event, o = m.Deferred(), p = m.Callbacks("once memory"), q = k.statusCode || {}, r = {}, s = {}, t = 0, u = "canceled", v = {
                readyState: 0,
                getResponseHeader: function (a)
                {
                    var b;
                    if (2 === t)
                    {
                        if (!j)
                        {
                            j = {};
                            while (b = Cc.exec(f))
                            {
                                j[b[1].toLowerCase()] = b[2]
                            }
                        }
                        b = j[a.toLowerCase()]
                    }
                    return null == b ? null : b
                },
                getAllResponseHeaders: function ()
                {
                    return 2 === t ? f : null
                },
                setRequestHeader: function (a, b)
                {
                    var c = a.toLowerCase();
                    return t || (a = s[c] = s[c] || a, r[a] = b), this
                },
                overrideMimeType: function (a)
                {
                    return t || (k.mimeType = a), this
                },
                statusCode: function (a)
                {
                    var b;
                    if (a)
                    {
                        if (2 > t)
                        {
                            for (b in a)
                            {
                                q[b] = [q[b], a[b]]
                            }
                        }
                        else
                        {
                            v.always(a[v.status])
                        }
                    }
                    return this
                },
                abort: function (a)
                {
                    var b = a || u;
                    return i && i.abort(b), x(0, b), this
                }
            };
            if (o.promise(v).complete = p.add, v.success = v.done, v.error = v.fail, k.url = ((a || k.url || zc) + "").replace(Ac, "").replace(Fc, yc[1] + "//"), k.type = b.method || b.type || k.method || k.type, k.dataTypes = m.trim(k.dataType || "*").toLowerCase().match(E) || [""], null == k.crossDomain && (c = Gc.exec(k.url.toLowerCase()), k.crossDomain = !(!c || c[1] === yc[1] && c[2] === yc[2] && (c[3] || ("http:" === c[1] ? "80" : "443")) === (yc[3] || ("http:" === yc[1] ? "80" : "443")))), k.data && k.processData && "string" != typeof k.data && (k.data = m.param(k.data, k.traditional)), Mc(Hc, k, b, v), 2 === t)
            {
                return v
            }
            h = k.global, h && 0 === m.active++ && m.event.trigger("ajaxStart"), k.type = k.type.toUpperCase(), k.hasContent = !Ec.test(k.type), e = k.url, k.hasContent || (k.data && (e = k.url += (wc.test(e) ? "&" : "?") + k.data, delete k.data), k.cache === !1 && (k.url = Bc.test(e) ? e.replace(Bc, "$1_=" + vc++) : e + (wc.test(e) ? "&" : "?") + "_=" + vc++)), k.ifModified && (m.lastModified[e] && v.setRequestHeader("If-Modified-Since", m.lastModified[e]), m.etag[e] && v.setRequestHeader("If-None-Match", m.etag[e])), (k.data && k.hasContent && k.contentType !== !1 || b.contentType) && v.setRequestHeader("Content-Type", k.contentType), v.setRequestHeader("Accept", k.dataTypes[0] && k.accepts[k.dataTypes[0]] ? k.accepts[k.dataTypes[0]] + ("*" !== k.dataTypes[0] ? ", " + Jc + "; q=0.01" : "") : k.accepts["*"]);
            for (d in k.headers)
            {
                v.setRequestHeader(d, k.headers[d])
            }
            if (k.beforeSend && (k.beforeSend.call(l, v, k) === !1 || 2 === t))
            {
                return v.abort()
            }
            u = "abort";
            for (d in {success: 1, error: 1, complete: 1})
            {
                v[d](k[d])
            }
            if (i = Mc(Ic, k, b, v))
            {
                v.readyState = 1, h && n.trigger("ajaxSend", [v, k]), k.async && k.timeout > 0 && (g = setTimeout(function ()
                {
                    v.abort("timeout")
                }, k.timeout));
                try
                {
                    t = 1, i.send(r, x)
                } catch (w)
                {
                    if (!(2 > t))
                    {
                        throw w
                    }
                    x(-1, w)
                }
            }
            else
            {
                x(-1, "No Transport")
            }
            function x(a, b, c, d)
            {
                var j, r, s, u, w, x = b;
                2 !== t && (t = 2, g && clearTimeout(g), i = void 0, f = d || "", v.readyState = a > 0 ? 4 : 0, j = a >= 200 && 300 > a || 304 === a, c && (u = Oc(k, v, c)), u = Pc(k, u, v, j), j ? (k.ifModified && (w = v.getResponseHeader("Last-Modified"), w && (m.lastModified[e] = w), w = v.getResponseHeader("etag"), w && (m.etag[e] = w)), 204 === a || "HEAD" === k.type ? x = "nocontent" : 304 === a ? x = "notmodified" : (x = u.state, r = u.data, s = u.error, j = !s)) : (s = x, (a || !x) && (x = "error", 0 > a && (a = 0))), v.status = a, v.statusText = (b || x) + "", j ? o.resolveWith(l, [r, x, v]) : o.rejectWith(l, [v, x, s]), v.statusCode(q), q = void 0, h && n.trigger(j ? "ajaxSuccess" : "ajaxError", [v, k, j ? r : s]), p.fireWith(l, [v, x]), h && (n.trigger("ajaxComplete", [v, k]), --m.active || m.event.trigger("ajaxStop")))
            }

            return v
        },
        getJSON: function (a, b, c)
        {
            return m.get(a, b, c, "json")
        },
        getScript: function (a, b)
        {
            return m.get(a, void 0, b, "script")
        }
    }), m.each(["get", "post"], function (a, b)
    {
        m[b] = function (a, c, d, e)
        {
            return m.isFunction(c) && (e = e || d, d = c, c = void 0), m.ajax({url: a, type: b, dataType: e, data: c, success: d})
        }
    }), m.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (a, b)
    {
        m.fn[b] = function (a)
        {
            return this.on(b, a)
        }
    }), m._evalUrl = function (a)
    {
        return m.ajax({url: a, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
    }, m.fn.extend({
        wrapAll: function (a)
        {
            if (m.isFunction(a))
            {
                return this.each(function (b)
                {
                    m(this).wrapAll(a.call(this, b))
                })
            }
            if (this[0])
            {
                var b = m(a, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && b.insertBefore(this[0]), b.map(function ()
                {
                    var a = this;
                    while (a.firstChild && 1 === a.firstChild.nodeType)
                    {
                        a = a.firstChild
                    }
                    return a
                }).append(this)
            }
            return this
        }, wrapInner: function (a)
        {
            return this.each(m.isFunction(a) ? function (b)
            {
                m(this).wrapInner(a.call(this, b))
            } : function ()
            {
                var b = m(this), c = b.contents();
                c.length ? c.wrapAll(a) : b.append(a)
            })
        }, wrap: function (a)
        {
            var b = m.isFunction(a);
            return this.each(function (c)
            {
                m(this).wrapAll(b ? a.call(this, c) : a)
            })
        }, unwrap: function ()
        {
            return this.parent().each(function ()
            {
                m.nodeName(this, "body") || m(this).replaceWith(this.childNodes)
            }).end()
        }
    }), m.expr.filters.hidden = function (a)
    {
        return a.offsetWidth <= 0 && a.offsetHeight <= 0 || !k.reliableHiddenOffsets() && "none" === (a.style && a.style.display || m.css(a, "display"))
    }, m.expr.filters.visible = function (a)
    {
        return !m.expr.filters.hidden(a)
    };
    var Qc = /%20/g, Rc = /\[\]$/, Sc = /\r?\n/g, Tc = /^(?:submit|button|image|reset|file)$/i, Uc = /^(?:input|select|textarea|keygen)/i;

    function Vc(a, b, c, d)
    {
        var e;
        if (m.isArray(b))
        {
            m.each(b, function (b, e)
            {
                c || Rc.test(a) ? d(a, e) : Vc(a + "[" + ("object" == typeof e ? b : "") + "]", e, c, d)
            })
        }
        else
        {
            if (c || "object" !== m.type(b))
            {
                d(a, b)
            }
            else
            {
                for (e in b)
                {
                    Vc(a + "[" + e + "]", b[e], c, d)
                }
            }
        }
    }

    m.param = function (a, b)
    {
        var c, d = [], e = function (a, b)
        {
            b = m.isFunction(b) ? b() : null == b ? "" : b, d[d.length] = encodeURIComponent(a) + "=" + encodeURIComponent(b)
        };
        if (void 0 === b && (b = m.ajaxSettings && m.ajaxSettings.traditional), m.isArray(a) || a.jquery && !m.isPlainObject(a))
        {
            m.each(a, function ()
            {
                e(this.name, this.value)
            })
        }
        else
        {
            for (c in a)
            {
                Vc(c, a[c], b, e)
            }
        }
        return d.join("&").replace(Qc, "+")
    }, m.fn.extend({
        serialize: function ()
        {
            return m.param(this.serializeArray())
        }, serializeArray: function ()
        {
            return this.map(function ()
            {
                var a = m.prop(this, "elements");
                return a ? m.makeArray(a) : this
            }).filter(function ()
            {
                var a = this.type;
                return this.name && !m(this).is(":disabled") && Uc.test(this.nodeName) && !Tc.test(a) && (this.checked || !W.test(a))
            }).map(function (a, b)
            {
                var c = m(this).val();
                return null == c ? null : m.isArray(c) ? m.map(c, function (a)
                {
                    return {name: b.name, value: a.replace(Sc, "\r\n")}
                }) : {name: b.name, value: c.replace(Sc, "\r\n")}
            }).get()
        }
    }), m.ajaxSettings.xhr = void 0 !== a.ActiveXObject ? function ()
    {
        return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && Zc() || $c()
    } : Zc;
    var Wc = 0, Xc = {}, Yc = m.ajaxSettings.xhr();
    a.ActiveXObject && m(a).on("unload", function ()
    {
        for (var a in Xc)
        {
            Xc[a](void 0, !0)
        }
    }), k.cors = !!Yc && "withCredentials" in Yc, Yc = k.ajax = !!Yc, Yc && m.ajaxTransport(function (a)
    {
        if (!a.crossDomain || k.cors)
        {
            var b;
            return {
                send: function (c, d)
                {
                    var e, f = a.xhr(), g = ++Wc;
                    if (f.open(a.type, a.url, a.async, a.username, a.password), a.xhrFields)
                    {
                        for (e in a.xhrFields)
                        {
                            f[e] = a.xhrFields[e]
                        }
                    }
                    a.mimeType && f.overrideMimeType && f.overrideMimeType(a.mimeType), a.crossDomain || c["X-Requested-With"] || (c["X-Requested-With"] = "XMLHttpRequest");
                    for (e in c)
                    {
                        void 0 !== c[e] && f.setRequestHeader(e, c[e] + "")
                    }
                    f.send(a.hasContent && a.data || null), b = function (c, e)
                    {
                        var h, i, j;
                        if (b && (e || 4 === f.readyState))
                        {
                            if (delete Xc[g], b = void 0, f.onreadystatechange = m.noop, e)
                            {
                                4 !== f.readyState && f.abort()
                            }
                            else
                            {
                                j = {}, h = f.status, "string" == typeof f.responseText && (j.text = f.responseText);
                                try
                                {
                                    i = f.statusText
                                } catch (k)
                                {
                                    i = ""
                                }
                                h || !a.isLocal || a.crossDomain ? 1223 === h && (h = 204) : h = j.text ? 200 : 404
                            }
                        }
                        j && d(h, i, j, f.getAllResponseHeaders())
                    }, a.async ? 4 === f.readyState ? setTimeout(b) : f.onreadystatechange = Xc[g] = b : b()
                }, abort: function ()
                {
                    b && b(void 0, !0)
                }
            }
        }
    });
    function Zc()
    {
        try
        {
            return new a.XMLHttpRequest
        } catch (b)
        {
        }
    }

    function $c()
    {
        try
        {
            return new a.ActiveXObject("Microsoft.XMLHTTP")
        } catch (b)
        {
        }
    }

    m.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /(?:java|ecma)script/},
        converters: {
            "text script": function (a)
            {
                return m.globalEval(a), a
            }
        }
    }), m.ajaxPrefilter("script", function (a)
    {
        void 0 === a.cache && (a.cache = !1), a.crossDomain && (a.type = "GET", a.global = !1)
    }), m.ajaxTransport("script", function (a)
    {
        if (a.crossDomain)
        {
            var b, c = y.head || m("head")[0] || y.documentElement;
            return {
                send: function (d, e)
                {
                    b = y.createElement("script"), b.async = !0, a.scriptCharset && (b.charset = a.scriptCharset), b.src = a.url, b.onload = b.onreadystatechange = function (a, c)
                    {
                        (c || !b.readyState || /loaded|complete/.test(b.readyState)) && (b.onload = b.onreadystatechange = null, b.parentNode && b.parentNode.removeChild(b), b = null, c || e(200, "success"))
                    }, c.insertBefore(b, c.firstChild)
                }, abort: function ()
                {
                    b && b.onload(void 0, !0)
                }
            }
        }
    });
    var _c = [], ad = /(=)\?(?=&|$)|\?\?/;
    m.ajaxSetup({
        jsonp: "callback", jsonpCallback: function ()
        {
            var a = _c.pop() || m.expando + "_" + vc++;
            return this[a] = !0, a
        }
    }), m.ajaxPrefilter("json jsonp", function (b, c, d)
    {
        var e, f, g, h = b.jsonp !== !1 && (ad.test(b.url) ? "url" : "string" == typeof b.data && !(b.contentType || "").indexOf("application/x-www-form-urlencoded") && ad.test(b.data) && "data");
        return h || "jsonp" === b.dataTypes[0] ? (e = b.jsonpCallback = m.isFunction(b.jsonpCallback) ? b.jsonpCallback() : b.jsonpCallback, h ? b[h] = b[h].replace(ad, "$1" + e) : b.jsonp !== !1 && (b.url += (wc.test(b.url) ? "&" : "?") + b.jsonp + "=" + e), b.converters["script json"] = function ()
        {
            return g || m.error(e + " was not called"), g[0]
        }, b.dataTypes[0] = "json", f = a[e], a[e] = function ()
        {
            g = arguments
        }, d.always(function ()
        {
            a[e] = f, b[e] && (b.jsonpCallback = c.jsonpCallback, _c.push(e)), g && m.isFunction(f) && f(g[0]), g = f = void 0
        }), "script") : void 0
    }), m.parseHTML = function (a, b, c)
    {
        if (!a || "string" != typeof a)
        {
            return null
        }
        "boolean" == typeof b && (c = b, b = !1), b = b || y;
        var d = u.exec(a), e = !c && [];
        return d ? [b.createElement(d[1])] : (d = m.buildFragment([a], b, e), e && e.length && m(e).remove(), m.merge([], d.childNodes))
    };
    var bd = m.fn.load;
    m.fn.load = function (a, b, c)
    {
        if ("string" != typeof a && bd)
        {
            return bd.apply(this, arguments)
        }
        var d, e, f, g = this, h = a.indexOf(" ");
        return h >= 0 && (d = m.trim(a.slice(h, a.length)), a = a.slice(0, h)), m.isFunction(b) ? (c = b, b = void 0) : b && "object" == typeof b && (f = "POST"), g.length > 0 && m.ajax({
            url: a,
            type: f,
            dataType: "html",
            data: b
        }).done(function (a)
        {
            e = arguments, g.html(d ? m("<div>").append(m.parseHTML(a)).find(d) : a)
        }).complete(c && function (a, b)
        {
            g.each(c, e || [a.responseText, b, a])
        }), this
    }, m.expr.filters.animated = function (a)
    {
        return m.grep(m.timers, function (b)
        {
            return a === b.elem
        }).length
    };
    var cd = a.document.documentElement;

    function dd(a)
    {
        return m.isWindow(a) ? a : 9 === a.nodeType ? a.defaultView || a.parentWindow : !1
    }

    m.offset = {
        setOffset: function (a, b, c)
        {
            var d, e, f, g, h, i, j, k = m.css(a, "position"), l = m(a), n = {};
            "static" === k && (a.style.position = "relative"), h = l.offset(), f = m.css(a, "top"), i = m.css(a, "left"), j = ("absolute" === k || "fixed" === k) && m.inArray("auto", [f, i]) > -1, j ? (d = l.position(), g = d.top, e = d.left) : (g = parseFloat(f) || 0, e = parseFloat(i) || 0), m.isFunction(b) && (b = b.call(a, c, h)), null != b.top && (n.top = b.top - h.top + g), null != b.left && (n.left = b.left - h.left + e), "using" in b ? b.using.call(a, n) : l.css(n)
        }
    }, m.fn.extend({
        offset: function (a)
        {
            if (arguments.length)
            {
                return void 0 === a ? this : this.each(function (b)
                {
                    m.offset.setOffset(this, a, b)
                })
            }
            var b, c, d = {top: 0, left: 0}, e = this[0], f = e && e.ownerDocument;
            if (f)
            {
                return b = f.documentElement, m.contains(b, e) ? (typeof e.getBoundingClientRect !== K && (d = e.getBoundingClientRect()), c = dd(f), {
                    top: d.top + (c.pageYOffset || b.scrollTop) - (b.clientTop || 0),
                    left: d.left + (c.pageXOffset || b.scrollLeft) - (b.clientLeft || 0)
                }) : d
            }
        }, position: function ()
        {
            if (this[0])
            {
                var a, b, c = {top: 0, left: 0}, d = this[0];
                return "fixed" === m.css(d, "position") ? b = d.getBoundingClientRect() : (a = this.offsetParent(), b = this.offset(), m.nodeName(a[0], "html") || (c = a.offset()), c.top += m.css(a[0], "borderTopWidth", !0), c.left += m.css(a[0], "borderLeftWidth", !0)), {
                    top: b.top - c.top - m.css(d, "marginTop", !0),
                    left: b.left - c.left - m.css(d, "marginLeft", !0)
                }
            }
        }, offsetParent: function ()
        {
            return this.map(function ()
            {
                var a = this.offsetParent || cd;
                while (a && !m.nodeName(a, "html") && "static" === m.css(a, "position"))
                {
                    a = a.offsetParent
                }
                return a || cd
            })
        }
    }), m.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (a, b)
    {
        var c = /Y/.test(b);
        m.fn[a] = function (d)
        {
            return V(this, function (a, d, e)
            {
                var f = dd(a);
                return void 0 === e ? f ? b in f ? f[b] : f.document.documentElement[d] : a[d] : void (f ? f.scrollTo(c ? m(f).scrollLeft() : e, c ? e : m(f).scrollTop()) : a[d] = e)
            }, a, d, arguments.length, null)
        }
    }), m.each(["top", "left"], function (a, b)
    {
        m.cssHooks[b] = Lb(k.pixelPosition, function (a, c)
        {
            return c ? (c = Jb(a, b), Hb.test(c) ? m(a).position()[b] + "px" : c) : void 0
        })
    }), m.each({Height: "height", Width: "width"}, function (a, b)
    {
        m.each({padding: "inner" + a, content: b, "": "outer" + a}, function (c, d)
        {
            m.fn[d] = function (d, e)
            {
                var f = arguments.length && (c || "boolean" != typeof d), g = c || (d === !0 || e === !0 ? "margin" : "border");
                return V(this, function (b, c, d)
                {
                    var e;
                    return m.isWindow(b) ? b.document.documentElement["client" + a] : 9 === b.nodeType ? (e = b.documentElement, Math.max(b.body["scroll" + a], e["scroll" + a], b.body["offset" + a], e["offset" + a], e["client" + a])) : void 0 === d ? m.css(b, c, g) : m.style(b, c, d, g)
                }, b, f ? d : void 0, f, null)
            }
        })
    }), m.fn.size = function ()
    {
        return this.length
    }, m.fn.andSelf = m.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function ()
    {
        return m
    });
    var ed = a.jQuery, fd = a.$;
    return m.noConflict = function (b)
    {
        return a.$ === m && (a.$ = fd), b && a.jQuery === m && (a.jQuery = ed), m
    }, typeof b === K && (a.jQuery = a.$ = m), m
});
$.ajaxSetup({cache: false});

//--------------module:im.base-------------

var Tool = {
    compress: function (b, g, e, a)
    {
        var d = "image/jpeg";
        if (a != undefined && a == "png")
        {
            d = "image/png"
        }
        var c = document.createElement("canvas");
        var f = 1;
        if (b.naturalWidth > b.naturalHeight)
        {
            f = g / b.naturalWidth
        }
        else
        {
            f = g / b.naturalHeight
        }
        c.width = b.naturalWidth * f;
        c.height = b.naturalHeight * f;
        var h = c.getContext("2d");
        h.drawImage(b, 0, 0, c.width, c.height);
        var i = c.toDataURL(d, e || 0.5);
        return i
    }, each: function (f, e, d)
    {
        if (f.length === void +0)
        {
            for (var c in f)
            {
                if (f.hasOwnProperty(c))
                {
                    e.call(d, f[c], c, f)
                }
            }
            return f
        }
        for (var b = 0, a = f.length; b < a; b++)
        {
            e.call(d, f[b], b, f)
        }
        return f
    }, parseJSON: function (a)
    {
        if (typeof a != "string")
        {
            return a = a.replace(/^\s+|\s+$/g, "")
        }
        var b = /^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""));
        if (!b)
        {
            throw"Invalid JSON"
        }
        var c = window.JSON;
        return c && c.parse ? c.parse(a) : (new Function("return " + a))()
    }, isArray: function (a)
    {
        return this.type(a) == "array"
    }, isFun: function (a)
    {
        return this.type(a) == "function"
    }, type: (function ()
    {
        var b = {}, e = "Boolean Number String Function Array Date RegExp Null Undefined".split(" ");
        for (var c = 0, a = e.length; c < a; c++)
        {
            b["[object " + e[c] + "]"] = e[c].toLowerCase()
        }
        return function d(f)
        {
            return b[Object.prototype.toString.call(f)] || "object"
        }
    })(), isObj: function (a)
    {
        return this.type(a) == "object"
    }, jsonToParam: function (b)
    {
        var c = "";
        for (var a in b)
        {
            c += "&" + a + "=" + b[a]
        }
        if (c.length)
        {
            c = c.substr(1)
        }
        return c
    }, log: function (a)
    {
        if (window.console && window.console.info)
        {
            window.console.info(a)
        }
    }, toHttp: function (a)
    {
        if (!!a)
        {
            return a.replace("https", "http")
        }
    }
};
var Ajax = function ()
{
    function a()
    {
    }

    function c(d)
    {
        Tool.log("requset failure: status" + d.status)
    }

    function b(e, f)
    {
        var h = f.async !== false, d = f.method || "POST", i = f.data || null, l = f.success || a, g = f.failure || c, j = f.header || {}, d = d.toUpperCase();
        if (d == "GET" && i)
        {
            e += (e.indexOf("?") == -1 ? "?" : "&") + i;
            i = null
        }
        else
        {
            if (d == "POST" && i)
            {
                i = Tool.jsonToParam(i)
            }
        }
        var m = new XMLHttpRequest();
        if ("withCredentials" in m)
        {
            m.open(d, e, h);
            if (d == "POST")
            {
                m.setRequestHeader("Accept", "application/json;");
                m.setRequestHeader("Content-type", "application/json;")
            }
            m.onreadystatechange = function ()
            {
                if (m.readyState == 4)
                {
                    var n = m.status;
                    if (n == 200 || n == 304)
                    {
                        l(Tool.parseJSON(m.responseText))
                    }
                    else
                    {
                        g(m)
                    }
                }
            }
        }
        else
        {
            if (typeof XDomainRequest != "undefined")
            {
                m = new XDomainRequest();
                m.open(d, e);
                if (d == "POST")
                {
                    m.setRequestHeader("Accept", "application/json;");
                    m.setRequestHeader("Content-type", "application/json;")
                }
                m.onload = function (n)
                {
                    l(Tool.parseJSON(m.responseText))
                };
                m.onerror = function ()
                {
                    g(m)
                }
            }
            else
            {
                Tool.log("The browser does not support");
                return
            }
        }
        for (var k in j)
        {
            m.setRequestHeader(k, j[k])
        }
        m.timeout = 6000;
        m.ontimeout = function ()
        {
            Tool.log("request timeout");
            g()
        };
        m.send(i);
        return m
    }

    return {request: b}
}();

//--------------module:im.status-------------

var IM_STATUS = {
    LOGIN: {
        APPKEY_ERROR: 311,
        PASSWORD_ERROR: 312,
        LOGIN_KEY_ERROR: 313,
        INSIDE_ERROR: 314,
        ACCOUNT_ERROR: 315,
        CREATE_ID_ERROR: 316,
        CREATE_ACCOUNT_ERROR: 317,
        LOGIN_TYPE_ERROR: 318,
        RE_LOGIN_ERROR: 319,
        REPEAT_OPER: 320,
        TIMEOUT: 321
    },
    LOGOUT: {FAIL: 505, INVALID_USER: 507},
    MSG: {RECEIVER_ERROR: 410, FAIL: 414, BROWER_NOT_SUPPORT: 415, IMG_TYPE_ERROR: 416},
    GROUP: {NO_OPER_PERMISSION: 1001, USER_HAS_IN_GROUP: 1003, USER_NOT_FOUND_IN_GROUP: 1007, INVALID_USER_ACCOUNT: 1021, INVALID_GROUP_ID: 1025, DO_NOT_DEL_OWNER: 1029},
    PARAM_IS_NULL: 300,
    NO_LOGIN: 301,
    SUCCESS: 200,
    FAIL: 300,
    SYS_EXCEPTION: 500
};
var IM_CONSTANT = {
    MSG_TYPE: {TEXT: 0, IMG: 3, NOTIFY: 5, VOICE: 6, USER_DEFINED: 8},
    CHAT_TYPE: {USER: 0, ROOM: 1, GROUP: 2},
    NOTIFY: {GROUP: {LEAVE: "group.leave", ENTER: "group.enter", KICKOUT: "group.kickOut", DISMISS: "group.dismiss"}, ROOM: {LEAVE: "room.leave", ENTER: "room.enter", GET_USER_LIST: "room.user.list"}}
};

//--------------module:base64-------------

function Base64()
{
    _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    this.encode = function (c)
    {
        var a = "";
        var k, h, f, j, g, e, d;
        var b = 0;
        c = _utf8_encode(c);
        while (b < c.length)
        {
            k = c.charCodeAt(b++);
            h = c.charCodeAt(b++);
            f = c.charCodeAt(b++);
            j = k >> 2;
            g = ((k & 3) << 4) | (h >> 4);
            e = ((h & 15) << 2) | (f >> 6);
            d = f & 63;
            if (isNaN(h))
            {
                e = d = 64
            }
            else
            {
                if (isNaN(f))
                {
                    d = 64
                }
            }
            a = a + _keyStr.charAt(j) + _keyStr.charAt(g) + _keyStr.charAt(e) + _keyStr.charAt(d)
        }
        return a
    };
    this.decode = function (c)
    {
        var a = "";
        var k, h, f;
        var j, g, e, d;
        var b = 0;
        c = c.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (b < c.length)
        {
            j = _keyStr.indexOf(c.charAt(b++));
            g = _keyStr.indexOf(c.charAt(b++));
            e = _keyStr.indexOf(c.charAt(b++));
            d = _keyStr.indexOf(c.charAt(b++));
            k = (j << 2) | (g >> 4);
            h = ((g & 15) << 4) | (e >> 2);
            f = ((e & 3) << 6) | d;
            a = a + String.fromCharCode(k);
            if (e != 64)
            {
                a = a + String.fromCharCode(h)
            }
            if (d != 64)
            {
                a = a + String.fromCharCode(f)
            }
        }
        a = _utf8_decode(a);
        return a
    };
    _utf8_encode = function (b)
    {
        b = b.replace(/\r\n/g, "\n");
        var a = "";
        for (var e = 0; e < b.length; e++)
        {
            var d = b.charCodeAt(e);
            if (d < 128)
            {
                a += String.fromCharCode(d)
            }
            else
            {
                if ((d > 127) && (d < 2048))
                {
                    a += String.fromCharCode((d >> 6) | 192);
                    a += String.fromCharCode((d & 63) | 128)
                }
                else
                {
                    a += String.fromCharCode((d >> 12) | 224);
                    a += String.fromCharCode(((d >> 6) & 63) | 128);
                    a += String.fromCharCode((d & 63) | 128)
                }
            }
        }
        return a
    };
    _utf8_decode = function (a)
    {
        var b = "";
        var d = 0;
        var e = c1 = c2 = 0;
        while (d < a.length)
        {
            e = a.charCodeAt(d);
            if (e < 128)
            {
                b += String.fromCharCode(e);
                d++
            }
            else
            {
                if ((e > 191) && (e < 224))
                {
                    c2 = a.charCodeAt(d + 1);
                    b += String.fromCharCode(((e & 31) << 6) | (c2 & 63));
                    d += 2
                }
                else
                {
                    c2 = a.charCodeAt(d + 1);
                    c3 = a.charCodeAt(d + 2);
                    b += String.fromCharCode(((e & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    d += 3
                }
            }
        }
        return b
    }
};

//--------------module:socketio-------------

!function (a)
{
    "object" == typeof exports ? module.exports = a() : "function" == typeof define && define.amd ? define(a) : "undefined" != typeof window ? window.io = a() : "undefined" != typeof global ? global.io = a() : "undefined" != typeof self && (self.io = a())
}(function ()
{
    var d, b, a;
    return (function c(f, k, h)
    {
        function g(n, l)
        {
            if (!k[n])
            {
                if (!f[n])
                {
                    var i = typeof require == "function" && require;
                    if (!l && i)
                    {
                        return i(n, !0)
                    }
                    if (e)
                    {
                        return e(n, !0)
                    }
                    throw new Error("Cannot find module '" + n + "'")
                }
                var m = k[n] = {exports: {}};
                f[n][0].call(m.exports, function (o)
                {
                    var p = f[n][1][o];
                    return g(p ? p : o)
                }, m, m.exports, c, f, k, h)
            }
            return k[n].exports
        }

        var e = typeof require == "function" && require;
        for (var j = 0; j < h.length; j++)
        {
            g(h[j])
        }
        return g
    })({
        1: [function (f, g, e)
        {
            g.exports = f("./lib/")
        }, {"./lib/": 2}], 2: [function (l, j, m)
        {
            var h = l("./url");
            var g = l("socket.io-parser");
            var e = l("./manager");
            var i = l("debug")("socket.io-client");
            j.exports = m = k;
            var f = m.managers = {};

            function k(p, o)
            {
                if (typeof p == "object")
                {
                    o = p;
                    p = undefined
                }
                o = o || {};
                var n = h(p);
                var q = n.source;
                var s = n.id;
                var r;
                if (o.forceNew || false === o.multiplex)
                {
                    i("ignoring socket cache for %s", q);
                    r = e(q, o)
                }
                else
                {
                    if (!f[s])
                    {
                        i("new io instance for %s", q);
                        f[s] = e(q, o)
                    }
                    r = f[s]
                }
                return r.socket(n.path)
            }

            m.protocol = g.protocol;
            m.connect = k;
            m.Manager = l("./manager");
            m.Socket = l("./socket")
        }, {"./manager": 3, "./socket": 5, "./url": 6, debug: 8, "socket.io-parser": 39}], 3: [function (k, j, m)
        {
            var h = k("./url");
            var o = k("engine.io-client");
            var i = k("./socket");
            var q = k("emitter");
            var g = k("socket.io-parser");
            var n = k("./on");
            var p = k("bind");
            var l = k("object-component");
            var f = k("debug")("socket.io-client:manager");
            j.exports = e;
            function e(s, r)
            {
                if (!(this instanceof e))
                {
                    return new e(s, r)
                }
                if ("object" == typeof s)
                {
                    r = s;
                    s = undefined
                }
                r = r || {};
                r.path = r.path || "/socket.io";
                this.nsps = {};
                this.subs = [];
                this.opts = r;
                this.reconnection(r.reconnection !== false);
                this.reconnectionAttempts(r.reconnectionAttempts || Infinity);
                this.reconnectionDelay(r.reconnectionDelay || 1000);
                this.reconnectionDelayMax(r.reconnectionDelayMax || 5000);
                this.timeout(null == r.timeout ? 20000 : r.timeout);
                this.readyState = "closed";
                this.uri = s;
                this.connected = 0;
                this.attempts = 0;
                this.encoding = false;
                this.packetBuffer = [];
                this.encoder = new g.Encoder();
                this.decoder = new g.Decoder();
                this.open()
            }

            q(e.prototype);
            e.prototype.reconnection = function (r)
            {
                if (!arguments.length)
                {
                    return this._reconnection
                }
                this._reconnection = !!r;
                return this
            };
            e.prototype.reconnectionAttempts = function (r)
            {
                if (!arguments.length)
                {
                    return this._reconnectionAttempts
                }
                this._reconnectionAttempts = r;
                return this
            };
            e.prototype.reconnectionDelay = function (r)
            {
                if (!arguments.length)
                {
                    return this._reconnectionDelay
                }
                this._reconnectionDelay = r;
                return this
            };
            e.prototype.reconnectionDelayMax = function (r)
            {
                if (!arguments.length)
                {
                    return this._reconnectionDelayMax
                }
                this._reconnectionDelayMax = r;
                return this
            };
            e.prototype.timeout = function (r)
            {
                if (!arguments.length)
                {
                    return this._timeout
                }
                this._timeout = r;
                return this
            };
            e.prototype.maybeReconnectOnOpen = function ()
            {
                if (!this.openReconnect && !this.reconnecting && this._reconnection)
                {
                    this.openReconnect = true;
                    this.reconnect()
                }
            };
            e.prototype.open = e.prototype.connect = function (u)
            {
                f("readyState %s", this.readyState);
                if (~this.readyState.indexOf("open"))
                {
                    return this
                }
                f("opening %s", this.uri);
                this.engine = o(this.uri, this.opts);
                var r = this.engine;
                var t = this;
                this.readyState = "opening";
                var w = n(r, "open", function ()
                {
                    t.onopen();
                    u && u()
                });
                var s = n(r, "error", function (z)
                {
                    f("connect_error");
                    t.cleanup();
                    t.readyState = "closed";
                    t.emit("connect_error", z);
                    if (u)
                    {
                        var y = new Error("Connection error");
                        y.data = z;
                        u(y)
                    }
                    t.maybeReconnectOnOpen()
                });
                if (false !== this._timeout)
                {
                    var v = this._timeout;
                    f("connect attempt will timeout after %d", v);
                    var x = setTimeout(function ()
                    {
                        f("connect attempt timed out after %d", v);
                        w.destroy();
                        r.close();
                        r.emit("error", "timeout");
                        t.emit("connect_timeout", v)
                    }, v);
                    this.subs.push({
                        destroy: function ()
                        {
                            clearTimeout(x)
                        }
                    })
                }
                this.subs.push(w);
                this.subs.push(s);
                return this
            };
            e.prototype.onopen = function ()
            {
                f("open");
                this.cleanup();
                this.readyState = "open";
                this.emit("open");
                var r = this.engine;
                this.subs.push(n(r, "data", p(this, "ondata")));
                this.subs.push(n(this.decoder, "decoded", p(this, "ondecoded")));
                this.subs.push(n(r, "error", p(this, "onerror")));
                this.subs.push(n(r, "close", p(this, "onclose")))
            };
            e.prototype.ondata = function (r)
            {
                this.decoder.add(r)
            };
            e.prototype.ondecoded = function (r)
            {
                this.emit("packet", r)
            };
            e.prototype.onerror = function (r)
            {
                f("error", r);
                this.emit("error", r)
            };
            e.prototype.socket = function (t)
            {
                var r = this.nsps[t];
                if (!r)
                {
                    r = new i(this, t);
                    this.nsps[t] = r;
                    var s = this;
                    r.on("connect", function ()
                    {
                        s.connected++
                    })
                }
                return r
            };
            e.prototype.destroy = function (r)
            {
                --this.connected || this.close()
            };
            e.prototype.packet = function (s)
            {
                f("writing packet %j", s);
                var r = this;
                if (!r.encoding)
                {
                    r.encoding = true;
                    this.encoder.encode(s, function (u)
                    {
                        for (var t = 0; t < u.length; t++)
                        {
                            r.engine.write(u[t])
                        }
                        r.encoding = false;
                        r.processPacketQueue()
                    })
                }
                else
                {
                    r.packetBuffer.push(s)
                }
            };
            e.prototype.processPacketQueue = function ()
            {
                if (this.packetBuffer.length > 0 && !this.encoding)
                {
                    var r = this.packetBuffer.shift();
                    this.packet(r)
                }
            };
            e.prototype.cleanup = function ()
            {
                var r;
                while (r = this.subs.shift())
                {
                    r.destroy()
                }
                this.packetBuffer = [];
                this.encoding = false;
                this.decoder.destroy()
            };
            e.prototype.close = e.prototype.disconnect = function ()
            {
                this.skipReconnect = true;
                this.engine.close()
            };
            e.prototype.onclose = function (r)
            {
                f("close");
                this.cleanup();
                this.readyState = "closed";
                this.emit("close", r);
                if (this._reconnection && !this.skipReconnect)
                {
                    this.reconnect()
                }
            };
            e.prototype.reconnect = function ()
            {
                if (this.reconnecting)
                {
                    return this
                }
                var r = this;
                this.attempts++;
                if (this.attempts > this._reconnectionAttempts)
                {
                    f("reconnect failed");
                    this.emit("reconnect_failed");
                    this.reconnecting = false
                }
                else
                {
                    var s = this.attempts * this.reconnectionDelay();
                    s = Math.min(s, this.reconnectionDelayMax());
                    f("will wait %dms before reconnect attempt", s);
                    this.reconnecting = true;
                    var t = setTimeout(function ()
                    {
                        f("attempting reconnect");
                        r.emit("reconnect_attempt");
                        r.open(function (u)
                        {
                            if (u)
                            {
                                f("reconnect attempt error");
                                r.reconnecting = false;
                                r.reconnect();
                                r.emit("reconnect_error", u.data)
                            }
                            else
                            {
                                f("reconnect success");
                                r.onreconnect()
                            }
                        })
                    }, s);
                    this.subs.push({
                        destroy: function ()
                        {
                            clearTimeout(t)
                        }
                    })
                }
            };
            e.prototype.onreconnect = function ()
            {
                var r = this.attempts;
                this.attempts = 0;
                this.reconnecting = false;
                this.emit("reconnect", r)
            }
        }, {"./on": 4, "./socket": 5, "./url": 6, bind: 7, debug: 8, emitter: 9, "engine.io-client": 10, "object-component": 36, "socket.io-parser": 39}], 4: [function (g, h, f)
        {
            h.exports = e;
            function e(k, j, i)
            {
                k.on(j, i);
                return {
                    destroy: function ()
                    {
                        k.removeListener(j, i)
                    }
                }
            }
        }, {}], 5: [function (i, h, k)
        {
            var f = i("socket.io-parser");
            var p = i("emitter");
            var j = i("to-array");
            var m = i("./on");
            var n = i("bind");
            var e = i("debug")("socket.io-client:socket");
            var l = i("has-binary-data");
            var o = i("indexof");
            h.exports = k = g;
            var r = {connect: 1, disconnect: 1, error: 1};
            var q = p.prototype.emit;

            function g(t, s)
            {
                this.io = t;
                this.nsp = s;
                this.json = this;
                this.ids = 0;
                this.acks = {};
                this.open();
                this.buffer = [];
                this.connected = false;
                this.disconnected = true
            }

            p(g.prototype);
            g.prototype.open = g.prototype.connect = function ()
            {
                if (this.connected)
                {
                    return this
                }
                var s = this.io;
                s.open();
                this.subs = [m(s, "open", n(this, "onopen")), m(s, "error", n(this, "onerror")), m(s, "packet", n(this, "onpacket")), m(s, "close", n(this, "onclose"))];
                if ("open" == this.io.readyState)
                {
                    this.onopen()
                }
                return this
            };
            g.prototype.send = function ()
            {
                var s = j(arguments);
                s.unshift("message");
                this.emit.apply(this, s);
                return this
            };
            g.prototype.emit = function (u)
            {
                if (r.hasOwnProperty(u))
                {
                    q.apply(this, arguments);
                    return this
                }
                var t = j(arguments);
                var s = f.EVENT;
                if (l(t))
                {
                    s = f.BINARY_EVENT
                }
                var v = {type: s, data: t};
                if ("function" == typeof t[t.length - 1])
                {
                    e("emitting packet with ack id %d", this.ids);
                    this.acks[this.ids] = t.pop();
                    v.id = this.ids++
                }
                this.packet(v);
                return this
            };
            g.prototype.packet = function (s)
            {
                s.nsp = this.nsp;
                this.io.packet(s)
            };
            g.prototype.onerror = function (s)
            {
                this.emit("error", s)
            };
            g.prototype.onopen = function ()
            {
                e("transport is open - connecting");
                if ("/" != this.nsp)
                {
                    this.packet({type: f.CONNECT})
                }
            };
            g.prototype.onclose = function (s)
            {
                e("close (%s)", s);
                this.connected = false;
                this.disconnected = true;
                this.emit("disconnect", s)
            };
            g.prototype.onpacket = function (s)
            {
                if (s.nsp != this.nsp)
                {
                    return
                }
                switch (s.type)
                {
                    case f.CONNECT:
                        this.onconnect();
                        break;
                    case f.EVENT:
                        this.onevent(s);
                        break;
                    case f.BINARY_EVENT:
                        this.onevent(s);
                        break;
                    case f.ACK:
                        this.onack(s);
                        break;
                    case f.BINARY_ACK:
                        this.onack(s);
                        break;
                    case f.DISCONNECT:
                        this.ondisconnect();
                        break;
                    case f.ERROR:
                        this.emit("error", s.data);
                        break
                }
            };
            g.prototype.onevent = function (t)
            {
                var s = t.data || [];
                e("emitting event %j", s);
                if (null != t.id)
                {
                    e("attaching ack callback to event");
                    s.push(this.ack(t.id))
                }
                if (this.connected)
                {
                    q.apply(this, s)
                }
                else
                {
                    this.buffer.push(s)
                }
            };
            g.prototype.ack = function (u)
            {
                var s = this;
                var t = false;
                return function ()
                {
                    if (t)
                    {
                        return
                    }
                    t = true;
                    var v = j(arguments);
                    e("sending ack %j", v);
                    var w = l(v) ? f.BINARY_ACK : f.ACK;
                    s.packet({type: w, id: u, data: v})
                }
            };
            g.prototype.onack = function (t)
            {
                e("calling ack %s with %j", t.id, t.data);
                var s = this.acks[t.id];
                s.apply(this, t.data);
                delete this.acks[t.id]
            };
            g.prototype.onconnect = function ()
            {
                this.connected = true;
                this.disconnected = false;
                this.emit("connect");
                this.emitBuffered()
            };
            g.prototype.emitBuffered = function ()
            {
                for (var s = 0; s < this.buffer.length; s++)
                {
                    q.apply(this, this.buffer[s])
                }
                this.buffer = []
            };
            g.prototype.ondisconnect = function ()
            {
                e("server disconnect (%s)", this.nsp);
                this.destroy();
                this.onclose("io server disconnect")
            };
            g.prototype.destroy = function ()
            {
                for (var s = 0; s < this.subs.length; s++)
                {
                    this.subs[s].destroy()
                }
                this.io.destroy(this)
            };
            g.prototype.close = g.prototype.disconnect = function ()
            {
                if (!this.connected)
                {
                    return this
                }
                e("performing disconnect (%s)", this.nsp);
                this.packet({type: f.DISCONNECT});
                this.destroy();
                this.onclose("io client disconnect");
                return this
            }
        }, {"./on": 4, bind: 7, debug: 8, emitter: 9, "has-binary-data": 31, indexof: 35, "socket.io-parser": 39, "to-array": 42}], 6: [function (h, i, f)
        {
            var j = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var k = h("parseuri");
            var e = h("debug")("socket.io-client:url");
            i.exports = g;
            function g(l, n)
            {
                var m = l;
                var n = n || j.location;
                if (null == l)
                {
                    l = n.protocol + "//" + n.hostname
                }
                if ("string" == typeof l)
                {
                    if ("/" == l.charAt(0))
                    {
                        if ("undefined" != typeof n)
                        {
                            l = n.hostname + l
                        }
                    }
                    if (!/^(https?|wss?):\/\//.test(l))
                    {
                        e("protocol-less url %s", l);
                        if ("undefined" != typeof n)
                        {
                            l = n.protocol + "//" + l
                        }
                        else
                        {
                            l = "https://" + l
                        }
                    }
                    e("parse %s", l);
                    m = k(l)
                }
                if ((/(http|ws)/.test(m.protocol) && 80 == m.port) || (/(http|ws)s/.test(m.protocol) && 443 == m.port))
                {
                    delete m.port
                }
                m.path = m.path || "/";
                m.id = m.protocol + m.host + (m.port ? (":" + m.port) : "");
                m.href = m.protocol + "://" + m.host + (m.port ? (":" + m.port) : "");
                return m
            }
        }, {debug: 8, parseuri: 37}], 7: [function (f, g, e)
        {
            var h = [].slice;
            g.exports = function (k, j)
            {
                if ("string" == typeof j)
                {
                    j = k[j]
                }
                if ("function" != typeof j)
                {
                    throw new Error("bind() requires a function")
                }
                var i = [].slice.call(arguments, 2);
                return function ()
                {
                    return j.apply(k, i.concat(h.call(arguments)))
                }
            }
        }, {}], 8: [function (h, i, g)
        {
            i.exports = f;
            function f(e)
            {
                if (!f.enabled(e))
                {
                    return function ()
                    {
                    }
                }
                return function (l)
                {
                    l = j(l);
                    var n = new Date;
                    var m = n - (f[e] || n);
                    f[e] = n;
                    l = e + " " + l + " +" + f.humanize(m);
                    window.console && console.log && Function.prototype.apply.call(console.log, console, arguments)
                }
            }

            f.names = [];
            f.skips = [];
            f.enable = function (m)
            {
                try
                {
                    localStorage.debug = m
                } catch (p)
                {
                }
                var o = (m || "").split(/[\s,]+/), l = o.length;
                for (var n = 0; n < l; n++)
                {
                    m = o[n].replace("*", ".*?");
                    if (m[0] === "-")
                    {
                        f.skips.push(new RegExp("^" + m.substr(1) + "$"))
                    }
                    else
                    {
                        f.names.push(new RegExp("^" + m + "$"))
                    }
                }
            };
            f.disable = function ()
            {
                f.enable("")
            };
            f.humanize = function (l)
            {
                var n = 1000, m = 60 * 1000, e = 60 * m;
                if (l >= e)
                {
                    return (l / e).toFixed(1) + "h"
                }
                if (l >= m)
                {
                    return (l / m).toFixed(1) + "m"
                }
                if (l >= n)
                {
                    return (l / n | 0) + "s"
                }
                return l + "ms"
            };
            f.enabled = function (l)
            {
                for (var m = 0, e = f.skips.length; m < e; m++)
                {
                    if (f.skips[m].test(l))
                    {
                        return false
                    }
                }
                for (var m = 0, e = f.names.length; m < e; m++)
                {
                    if (f.names[m].test(l))
                    {
                        return true
                    }
                }
                return false
            };
            function j(e)
            {
                if (e instanceof Error)
                {
                    return e.stack || e.message
                }
                return e
            }

            try
            {
                if (window.localStorage)
                {
                    f.enable(localStorage.debug)
                }
            } catch (k)
            {
            }
        }, {}], 9: [function (h, i, f)
        {
            var g = h("indexof");
            i.exports = j;
            function j(k)
            {
                if (k)
                {
                    return e(k)
                }
            }

            function e(l)
            {
                for (var k in j.prototype)
                {
                    l[k] = j.prototype[k]
                }
                return l
            }

            j.prototype.on = function (l, k)
            {
                this._callbacks = this._callbacks || {};
                (this._callbacks[l] = this._callbacks[l] || []).push(k);
                return this
            };
            j.prototype.once = function (n, m)
            {
                var l = this;
                this._callbacks = this._callbacks || {};
                function k()
                {
                    l.off(n, k);
                    m.apply(this, arguments)
                }

                m._off = k;
                this.on(n, k);
                return this
            };
            j.prototype.off = j.prototype.removeListener = j.prototype.removeAllListeners = function (n, l)
            {
                this._callbacks = this._callbacks || {};
                if (0 == arguments.length)
                {
                    this._callbacks = {};
                    return this
                }
                var m = this._callbacks[n];
                if (!m)
                {
                    return this
                }
                if (1 == arguments.length)
                {
                    delete this._callbacks[n];
                    return this
                }
                var k = g(m, l._off || l);
                if (~k)
                {
                    m.splice(k, 1)
                }
                return this
            };
            j.prototype.emit = function (o)
            {
                this._callbacks = this._callbacks || {};
                var l = [].slice.call(arguments, 1), n = this._callbacks[o];
                if (n)
                {
                    n = n.slice(0);
                    for (var m = 0, k = n.length; m < k; ++m)
                    {
                        n[m].apply(this, l)
                    }
                }
                return this
            };
            j.prototype.listeners = function (k)
            {
                this._callbacks = this._callbacks || {};
                return this._callbacks[k] || []
            };
            j.prototype.hasListeners = function (k)
            {
                return !!this.listeners(k).length
            }
        }, {indexof: 35}], 10: [function (f, g, e)
        {
            g.exports = f("./lib/")
        }, {"./lib/": 11}], 11: [function (f, g, e)
        {
            g.exports = f("./socket");
            g.exports.parser = f("engine.io-parser")
        }, {"./socket": 12, "engine.io-parser": 20}], 12: [function (k, i, n)
        {
            var h = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var m = k("./transports");
            var r = k("emitter");
            var f = k("debug")("engine.io-client:socket");
            var p = k("indexof");
            var e = k("engine.io-parser");
            var l = k("parseuri");
            var o = k("parsejson");
            var j = k("parseqs");
            i.exports = g;
            function s()
            {
            }

            function g(v, u)
            {
                if (!(this instanceof g))
                {
                    return new g(v, u)
                }
                u = u || {};
                if (v && "object" == typeof v)
                {
                    u = v;
                    v = null
                }
                if (v)
                {
                    v = l(v);
                    u.host = v.host;
                    u.secure = v.protocol == "https" || v.protocol == "wss";
                    u.port = v.port;
                    if (v.query)
                    {
                        u.query = v.query
                    }
                }
                this.secure = null != u.secure ? u.secure : (h.location && "https:" == location.protocol);
                if (u.host)
                {
                    var t = u.host.split(":");
                    u.hostname = t.shift();
                    if (t.length)
                    {
                        u.port = t.pop()
                    }
                }
                this.agent = u.agent || false;
                this.hostname = u.hostname || (h.location ? location.hostname : "localhost");
                this.port = u.port || (h.location && location.port ? location.port : (this.secure ? 443 : 80));
                this.query = u.query || {};
                if ("string" == typeof this.query)
                {
                    this.query = j.decode(this.query)
                }
                this.upgrade = false !== u.upgrade;
                this.path = (u.path || "/engine.io").replace(/\/$/, "") + "/";
                this.forceJSONP = !!u.forceJSONP;
                this.forceBase64 = !!u.forceBase64;
                this.timestampParam = u.timestampParam || "t";
                this.timestampRequests = u.timestampRequests;
                this.transports = u.transports || ["polling", "websocket"];
                this.readyState = "";
                this.writeBuffer = [];
                this.callbackBuffer = [];
                this.policyPort = u.policyPort || 843;
                this.rememberUpgrade = u.rememberUpgrade || false;
                this.open();
                this.binaryType = null;
                this.onlyBinaryUpgrades = u.onlyBinaryUpgrades
            }

            g.priorWebsocketSuccess = false;
            r(g.prototype);
            g.protocol = e.protocol;
            g.Socket = g;
            g.Transport = k("./transport");
            g.transports = k("./transports");
            g.parser = k("engine.io-parser");
            g.prototype.createTransport = function (t)
            {
                f('creating transport "%s"', t);
                var u = q(this.query);
                u.EIO = e.protocol;
                u.transport = t;
                if (this.id)
                {
                    u.sid = this.id
                }
                var v = new m[t]({
                    agent: this.agent,
                    hostname: this.hostname,
                    port: this.port,
                    secure: this.secure,
                    path: this.path,
                    query: u,
                    forceJSONP: this.forceJSONP,
                    forceBase64: this.forceBase64,
                    timestampRequests: this.timestampRequests,
                    timestampParam: this.timestampParam,
                    policyPort: this.policyPort,
                    socket: this
                });
                return v
            };
            function q(u)
            {
                var v = {};
                for (var t in u)
                {
                    if (u.hasOwnProperty(t))
                    {
                        v[t] = u[t]
                    }
                }
                return v
            }

            g.prototype.open = function ()
            {
                var t;
                if (this.rememberUpgrade && g.priorWebsocketSuccess && this.transports.indexOf("websocket") != -1)
                {
                    t = "websocket"
                }
                else
                {
                    t = this.transports[0]
                }
                this.readyState = "opening";
                var t = this.createTransport(t);
                t.open();
                this.setTransport(t)
            };
            g.prototype.setTransport = function (u)
            {
                f("setting transport %s", u.name);
                var t = this;
                if (this.transport)
                {
                    f("clearing existing transport %s", this.transport.name);
                    this.transport.removeAllListeners()
                }
                this.transport = u;
                u.on("drain", function ()
                {
                    t.onDrain()
                }).on("packet", function (v)
                {
                    t.onPacket(v)
                }).on("error", function (v)
                {
                    t.onError(v)
                }).on("close", function ()
                {
                    t.onClose("transport close")
                })
            };
            g.prototype.probe = function (t)
            {
                f('probing transport "%s"', t);
                var w = this.createTransport(t, {probe: 1}), y = false, C = this;
                g.priorWebsocketSuccess = false;
                function x()
                {
                    if (C.onlyBinaryUpgrades)
                    {
                        var E = !this.supportsBinary && C.transport.supportsBinary;
                        y = y || E
                    }
                    if (y)
                    {
                        return
                    }
                    f('probe transport "%s" opened', t);
                    w.send([{type: "ping", data: "probe"}]);
                    w.once("packet", function (G)
                    {
                        if (y)
                        {
                            return
                        }
                        if ("pong" == G.type && "probe" == G.data)
                        {
                            f('probe transport "%s" pong', t);
                            C.upgrading = true;
                            C.emit("upgrading", w);
                            g.priorWebsocketSuccess = "websocket" == w.name;
                            f('pausing current transport "%s"', C.transport.name);
                            C.transport.pause(function ()
                            {
                                if (y)
                                {
                                    return
                                }
                                if ("closed" == C.readyState || "closing" == C.readyState)
                                {
                                    return
                                }
                                f("changing transport and sending upgrade packet");
                                u();
                                C.setTransport(w);
                                w.send([{type: "upgrade"}]);
                                C.emit("upgrade", w);
                                w = null;
                                C.upgrading = false;
                                C.flush()
                            })
                        }
                        else
                        {
                            f('probe transport "%s" failed', t);
                            var F = new Error("probe error");
                            F.transport = w.name;
                            C.emit("upgradeError", F)
                        }
                    })
                }

                function A()
                {
                    if (y)
                    {
                        return
                    }
                    y = true;
                    u();
                    w.close();
                    w = null
                }

                function z(F)
                {
                    var E = new Error("probe error: " + F);
                    E.transport = w.name;
                    A();
                    f('probe transport "%s" failed because of error: %s', t, F);
                    C.emit("upgradeError", E)
                }

                function D()
                {
                    z("transport closed")
                }

                function B()
                {
                    z("socket closed")
                }

                function v(E)
                {
                    if (w && E.name != w.name)
                    {
                        f('"%s" works - aborting "%s"', E.name, w.name);
                        A()
                    }
                }

                function u()
                {
                    w.removeListener("open", x);
                    w.removeListener("error", z);
                    w.removeListener("close", D);
                    C.removeListener("close", B);
                    C.removeListener("upgrading", v)
                }

                w.once("open", x);
                w.once("error", z);
                w.once("close", D);
                this.once("close", B);
                this.once("upgrading", v);
                w.open()
            };
            g.prototype.onOpen = function ()
            {
                f("socket open");
                this.readyState = "open";
                g.priorWebsocketSuccess = "websocket" == this.transport.name;
                this.emit("open");
                this.flush();
                if ("open" == this.readyState && this.upgrade && this.transport.pause)
                {
                    f("starting upgrade probes");
                    for (var u = 0, t = this.upgrades.length; u < t; u++)
                    {
                        this.probe(this.upgrades[u])
                    }
                }
            };
            g.prototype.onPacket = function (u)
            {
                if ("opening" == this.readyState || "open" == this.readyState)
                {
                    f('socket receive: type "%s", data "%s"', u.type, u.data);
                    this.emit("packet", u);
                    this.emit("heartbeat");
                    switch (u.type)
                    {
                        case"open":
                            this.onHandshake(o(u.data));
                            break;
                        case"pong":
                            this.setPing();
                            break;
                        case"error":
                            var t = new Error("server error");
                            t.code = u.data;
                            this.emit("error", t);
                            break;
                        case"message":
                            this.emit("data", u.data);
                            this.emit("message", u.data);
                            break
                    }
                }
                else
                {
                    f('packet received with socket readyState "%s"', this.readyState)
                }
            };
            g.prototype.onHandshake = function (t)
            {
                this.emit("handshake", t);
                this.id = t.sid;
                this.transport.query.sid = t.sid;
                this.upgrades = this.filterUpgrades(t.upgrades);
                this.pingInterval = t.pingInterval;
                this.pingTimeout = t.pingTimeout;
                this.onOpen();
                if ("closed" == this.readyState)
                {
                    return
                }
                this.setPing();
                this.removeListener("heartbeat", this.onHeartbeat);
                this.on("heartbeat", this.onHeartbeat)
            };
            g.prototype.onHeartbeat = function (u)
            {
                clearTimeout(this.pingTimeoutTimer);
                var t = this;
                t.pingTimeoutTimer = setTimeout(function ()
                {
                    if ("closed" == t.readyState)
                    {
                        return
                    }
                    t.onClose("ping timeout")
                }, u || (t.pingInterval + t.pingTimeout))
            };
            g.prototype.setPing = function ()
            {
                var t = this;
                clearTimeout(t.pingIntervalTimer);
                t.pingIntervalTimer = setTimeout(function ()
                {
                    f("writing ping packet - expecting pong within %sms", t.pingTimeout);
                    t.ping();
                    t.onHeartbeat(t.pingTimeout)
                }, t.pingInterval)
            };
            g.prototype.ping = function ()
            {
                this.sendPacket("ping")
            };
            g.prototype.onDrain = function ()
            {
                for (var t = 0; t < this.prevBufferLen; t++)
                {
                    if (this.callbackBuffer[t])
                    {
                        this.callbackBuffer[t]()
                    }
                }
                this.writeBuffer.splice(0, this.prevBufferLen);
                this.callbackBuffer.splice(0, this.prevBufferLen);
                this.prevBufferLen = 0;
                if (this.writeBuffer.length == 0)
                {
                    this.emit("drain")
                }
                else
                {
                    this.flush()
                }
            };
            g.prototype.flush = function ()
            {
                if ("closed" != this.readyState && this.transport.writable && !this.upgrading && this.writeBuffer.length)
                {
                    f("flushing %d packets in socket", this.writeBuffer.length);
                    this.transport.send(this.writeBuffer);
                    this.prevBufferLen = this.writeBuffer.length;
                    this.emit("flush")
                }
            };
            g.prototype.write = g.prototype.send = function (u, t)
            {
                this.sendPacket("message", u, t);
                return this
            };
            g.prototype.sendPacket = function (u, v, t)
            {
                var w = {type: u, data: v};
                this.emit("packetCreate", w);
                this.writeBuffer.push(w);
                this.callbackBuffer.push(t);
                this.flush()
            };
            g.prototype.close = function ()
            {
                if ("opening" == this.readyState || "open" == this.readyState)
                {
                    this.onClose("forced close");
                    f("socket closing - telling transport to close");
                    this.transport.close()
                }
                return this
            };
            g.prototype.onError = function (t)
            {
                f("socket error %j", t);
                g.priorWebsocketSuccess = false;
                this.emit("error", t);
                this.onClose("transport error", t)
            };
            g.prototype.onClose = function (u, v)
            {
                if ("opening" == this.readyState || "open" == this.readyState)
                {
                    f('socket close with reason: "%s"', u);
                    var t = this;
                    clearTimeout(this.pingIntervalTimer);
                    clearTimeout(this.pingTimeoutTimer);
                    setTimeout(function ()
                    {
                        t.writeBuffer = [];
                        t.callbackBuffer = [];
                        t.prevBufferLen = 0
                    }, 0);
                    this.transport.removeAllListeners("close");
                    this.transport.close();
                    this.transport.removeAllListeners();
                    this.readyState = "closed";
                    this.id = null;
                    this.emit("close", u, v)
                }
            };
            g.prototype.filterUpgrades = function (v)
            {
                var w = [];
                for (var u = 0, t = v.length; u < t; u++)
                {
                    if (~p(this.transports, v[u]))
                    {
                        w.push(v[u])
                    }
                }
                return w
            }
        }, {"./transport": 13, "./transports": 14, debug: 8, emitter: 9, "engine.io-parser": 20, indexof: 35, parsejson: 28, parseqs: 29, parseuri: 37}], 13: [function (f, g, e)
        {
            var j = f("engine.io-parser");
            var h = f("emitter");
            g.exports = i;
            function i(k)
            {
                this.path = k.path;
                this.hostname = k.hostname;
                this.port = k.port;
                this.secure = k.secure;
                this.query = k.query;
                this.timestampParam = k.timestampParam;
                this.timestampRequests = k.timestampRequests;
                this.readyState = "";
                this.agent = k.agent || false;
                this.socket = k.socket
            }

            h(i.prototype);
            i.timestamps = 0;
            i.prototype.onError = function (m, l)
            {
                var k = new Error(m);
                k.type = "TransportError";
                k.description = l;
                this.emit("error", k);
                return this
            };
            i.prototype.open = function ()
            {
                if ("closed" == this.readyState || "" == this.readyState)
                {
                    this.readyState = "opening";
                    this.doOpen()
                }
                return this
            };
            i.prototype.close = function ()
            {
                if ("opening" == this.readyState || "open" == this.readyState)
                {
                    this.doClose();
                    this.onClose()
                }
                return this
            };
            i.prototype.send = function (k)
            {
                if ("open" == this.readyState)
                {
                    this.write(k)
                }
                else
                {
                    throw new Error("Transport not open")
                }
            };
            i.prototype.onOpen = function ()
            {
                this.readyState = "open";
                this.writable = true;
                this.emit("open")
            };
            i.prototype.onData = function (k)
            {
                this.onPacket(j.decodePacket(k, this.socket.binaryType))
            };
            i.prototype.onPacket = function (k)
            {
                this.emit("packet", k)
            };
            i.prototype.onClose = function ()
            {
                this.readyState = "closed";
                this.emit("close")
            }
        }, {emitter: 9, "engine.io-parser": 20}], 14: [function (h, f, j)
        {
            var e = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var m = h("xmlhttprequest");
            var l = h("./polling-xhr");
            var k = h("./polling-jsonp");
            var i = h("./websocket");
            j.polling = g;
            j.websocket = i;
            function g(o)
            {
                var r;
                var p = false;
                if (e.location)
                {
                    var q = "https:" == location.protocol;
                    var n = location.port;
                    if (!n)
                    {
                        n = q ? 443 : 80
                    }
                    p = o.hostname != location.hostname || n != o.port
                }
                o.xdomain = p;
                r = new m(o);
                if ("open" in r && !o.forceJSONP)
                {
                    return new l(o)
                }
                else
                {
                    return new k(o)
                }
            }
        }, {"./polling-jsonp": 15, "./polling-xhr": 16, "./websocket": 18, xmlhttprequest: 19}], 15: [function (g, f, i)
        {
            var e = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var p = g("./polling");
            var j = g("inherits");
            f.exports = l;
            var n = /\n/g;
            var h = /\\n/g;
            var o;
            var m = 0;

            function k()
            {
            }

            function l(r)
            {
                p.call(this, r);
                this.query = this.query || {};
                if (!o)
                {
                    if (!e.___eio)
                    {
                        e.___eio = []
                    }
                    o = e.___eio
                }
                this.index = o.length;
                var q = this;
                o.push(function (s)
                {
                    q.onData(s)
                });
                this.query.j = this.index;
                if (e.document && e.addEventListener)
                {
                    e.addEventListener("beforeunload", function ()
                    {
                        if (q.script)
                        {
                            q.script.onerror = k
                        }
                    })
                }
            }

            j(l, p);
            l.prototype.supportsBinary = false;
            l.prototype.doClose = function ()
            {
                if (this.script)
                {
                    this.script.parentNode.removeChild(this.script);
                    this.script = null
                }
                if (this.form)
                {
                    this.form.parentNode.removeChild(this.form);
                    this.form = null
                }
                p.prototype.doClose.call(this)
            };
            l.prototype.doPoll = function ()
            {
                var r = this;
                var q = document.createElement("script");
                if (this.script)
                {
                    this.script.parentNode.removeChild(this.script);
                    this.script = null
                }
                q.async = true;
                q.src = this.uri();
                q.onerror = function (u)
                {
                    r.onError("jsonp poll error", u)
                };
                var t = document.getElementsByTagName("script")[0];
                t.parentNode.insertBefore(q, t);
                this.script = q;
                var s = "undefined" != typeof navigator && /gecko/i.test(navigator.userAgent);
                if (s)
                {
                    setTimeout(function ()
                    {
                        var u = document.createElement("iframe");
                        document.body.appendChild(u);
                        document.body.removeChild(u)
                    }, 100)
                }
            };
            l.prototype.doWrite = function (w, y)
            {
                var z = this;
                if (!this.form)
                {
                    var r = document.createElement("form");
                    var s = document.createElement("textarea");
                    var q = this.iframeId = "eio_iframe_" + this.index;
                    var v;
                    r.className = "socketio";
                    r.style.position = "absolute";
                    r.style.top = "-1000px";
                    r.style.left = "-1000px";
                    r.target = q;
                    r.method = "POST";
                    r.setAttribute("accept-charset", "utf-8");
                    s.name = "d";
                    r.appendChild(s);
                    document.body.appendChild(r);
                    this.form = r;
                    this.area = s
                }
                this.form.action = this.uri();
                function t()
                {
                    u();
                    y()
                }

                function u()
                {
                    if (z.iframe)
                    {
                        try
                        {
                            z.form.removeChild(z.iframe)
                        } catch (B)
                        {
                            z.onError("jsonp polling iframe removal error", B)
                        }
                    }
                    try
                    {
                        var A = '<iframe src="javascript:0" name="' + z.iframeId + '">';
                        v = document.createElement(A)
                    } catch (B)
                    {
                        v = document.createElement("iframe");
                        v.name = z.iframeId;
                        v.src = "javascript:0"
                    }
                    v.id = z.iframeId;
                    z.form.appendChild(v);
                    z.iframe = v
                }

                u();
                w = w.replace(h, "\\\n");
                this.area.value = w.replace(n, "\\n");
                try
                {
                    this.form.submit()
                } catch (x)
                {
                }
                if (this.iframe.attachEvent)
                {
                    this.iframe.onreadystatechange = function ()
                    {
                        if (z.iframe.readyState == "complete")
                        {
                            t()
                        }
                    }
                }
                else
                {
                    this.iframe.onload = t
                }
            }
        }, {"./polling": 17, inherits: 27}], 16: [function (i, h, j)
        {
            var g = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var o = i("xmlhttprequest");
            var p = i("./polling");
            var n = i("emitter");
            var f = i("debug")("engine.io-client:polling-xhr");
            var k = i("inherits");
            h.exports = l;
            h.exports.Request = e;
            function m()
            {
            }

            function l(s)
            {
                p.call(this, s);
                if (g.location)
                {
                    var t = "https:" == location.protocol;
                    var r = location.port;
                    if (!r)
                    {
                        r = t ? 443 : 80
                    }
                    this.xd = s.hostname != g.location.hostname || r != s.port
                }
            }

            k(l, p);
            l.prototype.supportsBinary = true;
            l.prototype.request = function (r)
            {
                r = r || {};
                r.uri = this.uri();
                r.xd = this.xd;
                r.agent = this.agent || false;
                r.supportsBinary = this.supportsBinary;
                return new e(r)
            };
            l.prototype.doWrite = function (v, s)
            {
                var u = typeof v !== "string" && v !== undefined;
                var t = this.request({method: "POST", data: v, isBinary: u});
                var r = this;
                t.on("success", s);
                t.on("error", function (w)
                {
                    r.onError("xhr post error", w)
                });
                this.sendXhr = t
            };
            l.prototype.doPoll = function ()
            {
                f("xhr poll");
                var s = this.request();
                var r = this;
                s.on("data", function (t)
                {
                    r.onData(t)
                });
                s.on("error", function (t)
                {
                    r.onError("xhr poll error", t)
                });
                this.pollXhr = s
            };
            function e(r)
            {
                this.method = r.method || "GET";
                this.uri = r.uri;
                this.xd = !!r.xd;
                this.async = false !== r.async;
                this.data = undefined != r.data ? r.data : null;
                this.agent = r.agent;
                this.create(r.isBinary, r.supportsBinary)
            }

            n(e.prototype);
            e.prototype.create = function (s, v)
            {
                var u = this.xhr = new o({agent: this.agent, xdomain: this.xd});
                var r = this;
                try
                {
                    f("xhr open %s: %s", this.method, this.uri);
                    u.open(this.method, this.uri, this.async);
                    if (v)
                    {
                        u.responseType = "arraybuffer"
                    }
                    if ("POST" == this.method)
                    {
                        try
                        {
                            if (s)
                            {
                                u.setRequestHeader("Content-type", "application/octet-stream")
                            }
                            else
                            {
                                u.setRequestHeader("Content-type", "text/plain;charset=UTF-8")
                            }
                        } catch (t)
                        {
                        }
                    }
                    if ("withCredentials" in u)
                    {
                        u.withCredentials = true
                    }
                    u.onreadystatechange = function ()
                    {
                        var w;
                        try
                        {
                            if (4 != u.readyState)
                            {
                                return
                            }
                            if (200 == u.status || 1223 == u.status)
                            {
                                var y = u.getResponseHeader("Content-Type");
                                if (y === "application/octet-stream")
                                {
                                    w = u.response
                                }
                                else
                                {
                                    if (!v)
                                    {
                                        w = u.responseText
                                    }
                                    else
                                    {
                                        w = "ok"
                                    }
                                }
                            }
                            else
                            {
                                setTimeout(function ()
                                {
                                    r.onError(u.status)
                                }, 0)
                            }
                        } catch (x)
                        {
                            r.onError(x)
                        }
                        if (null != w)
                        {
                            r.onData(w)
                        }
                    };
                    f("xhr data %s", this.data);
                    u.send(this.data)
                } catch (t)
                {
                    setTimeout(function ()
                    {
                        r.onError(t)
                    }, 0);
                    return
                }
                if (g.document)
                {
                    this.index = e.requestsCount++;
                    e.requests[this.index] = this
                }
            };
            e.prototype.onSuccess = function ()
            {
                this.emit("success");
                this.cleanup()
            };
            e.prototype.onData = function (r)
            {
                this.emit("data", r);
                this.onSuccess()
            };
            e.prototype.onError = function (r)
            {
                this.emit("error", r);
                this.cleanup()
            };
            e.prototype.cleanup = function ()
            {
                if ("undefined" == typeof this.xhr || null === this.xhr)
                {
                    return
                }
                this.xhr.onreadystatechange = m;
                try
                {
                    this.xhr.abort()
                } catch (r)
                {
                }
                if (g.document)
                {
                    delete e.requests[this.index]
                }
                this.xhr = null
            };
            e.prototype.abort = function ()
            {
                this.cleanup()
            };
            if (g.document)
            {
                e.requestsCount = 0;
                e.requests = {};
                if (g.attachEvent)
                {
                    g.attachEvent("onunload", q)
                }
                else
                {
                    if (g.addEventListener)
                    {
                        g.addEventListener("beforeunload", q)
                    }
                }
            }
            function q()
            {
                for (var r in e.requests)
                {
                    if (e.requests.hasOwnProperty(r))
                    {
                        e.requests[r].abort()
                    }
                }
            }
        }, {"./polling": 17, debug: 8, emitter: 9, inherits: 27, xmlhttprequest: 19}], 17: [function (j, g, k)
        {
            var m = j("../transport");
            var i = j("parseqs");
            var e = j("engine.io-parser");
            var f = j("debug")("engine.io-client:polling");
            var l = j("inherits");
            g.exports = n;
            var h = (function ()
            {
                var o = j("xmlhttprequest");
                var p = new o({agent: this.agent, xdomain: false});
                return null != p.responseType
            })();

            function n(p)
            {
                var o = (p && p.forceBase64);
                if (!h || o)
                {
                    this.supportsBinary = false
                }
                m.call(this, p)
            }

            l(n, m);
            n.prototype.name = "polling";
            n.prototype.doOpen = function ()
            {
                this.poll()
            };
            n.prototype.pause = function (o)
            {
                var s = 0;
                var p = this;
                this.readyState = "pausing";
                function r()
                {
                    f("paused");
                    p.readyState = "paused";
                    o()
                }

                if (this.polling || !this.writable)
                {
                    var q = 0;
                    if (this.polling)
                    {
                        f("we are currently polling - waiting to pause");
                        q++;
                        this.once("pollComplete", function ()
                        {
                            f("pre-pause polling complete");
                            --q || r()
                        })
                    }
                    if (!this.writable)
                    {
                        f("we are currently writing - waiting to pause");
                        q++;
                        this.once("drain", function ()
                        {
                            f("pre-pause writing complete");
                            --q || r()
                        })
                    }
                }
                else
                {
                    r()
                }
            };
            n.prototype.poll = function ()
            {
                f("polling");
                this.polling = true;
                this.doPoll();
                this.emit("poll")
            };
            n.prototype.onData = function (p)
            {
                var o = this;
                f("polling got data %s", p);
                var q = function (t, r, s)
                {
                    if ("opening" == o.readyState)
                    {
                        o.onOpen()
                    }
                    if ("close" == t.type)
                    {
                        o.onClose();
                        return false
                    }
                    o.onPacket(t)
                };
                e.decodePayload(p, this.socket.binaryType, q);
                if ("closed" != this.readyState)
                {
                    this.polling = false;
                    this.emit("pollComplete");
                    if ("open" == this.readyState)
                    {
                        this.poll()
                    }
                    else
                    {
                        f('ignoring poll - transport state "%s"', this.readyState)
                    }
                }
            };
            n.prototype.doClose = function ()
            {
                var o = this;

                function p()
                {
                    f("writing close packet");
                    o.write([{type: "close"}])
                }

                if ("open" == this.readyState)
                {
                    f("transport open - closing");
                    p()
                }
                else
                {
                    f("transport not open - deferring close");
                    this.once("open", p)
                }
            };
            n.prototype.write = function (q)
            {
                var p = this;
                this.writable = false;
                var o = function ()
                {
                    p.writable = true;
                    p.emit("drain")
                };
                var p = this;
                e.encodePayload(q, this.supportsBinary, function (r)
                {
                    p.doWrite(r, o)
                })
            };
            n.prototype.uri = function ()
            {
                var q = this.query || {};
                var p = this.secure ? "https" : "http";
                var o = "";
                if (false !== this.timestampRequests)
                {
                    q[this.timestampParam] = +new Date + "-" + m.timestamps++
                }
                if (!this.supportsBinary && !q.sid)
                {
                    q.b64 = 1
                }
                q = i.encode(q);
                if (this.port && (("https" == p && this.port != 443) || ("http" == p && this.port != 80)))
                {
                    o = ":" + this.port
                }
                if (q.length)
                {
                    q = "?" + q
                }
                return p + "://" + this.hostname + o + this.path + q
            }
        }, {"../transport": 13, debug: 8, "engine.io-parser": 20, inherits: 27, parseqs: 29, xmlhttprequest: 19}], 18: [function (j, h, k)
        {
            var m = j("../transport");
            var e = j("engine.io-parser");
            var i = j("parseqs");
            var f = j("debug")("engine.io-client:websocket");
            var l = j("inherits");
            var n = j("ws");
            h.exports = g;
            function g(p)
            {
                var o = (p && p.forceBase64);
                if (o)
                {
                    this.supportsBinary = false
                }
                m.call(this, p)
            }

            l(g, m);
            g.prototype.name = "websocket";
            g.prototype.supportsBinary = true;
            g.prototype.doOpen = function ()
            {
                if (!this.check())
                {
                    return
                }
                var o = this;
                var r = this.uri();
                var q = void (0);
                var p = {agent: this.agent};
                this.ws = new n(r, q, p);
                if (this.ws.binaryType === undefined)
                {
                    this.supportsBinary = false
                }
                this.ws.binaryType = "arraybuffer";
                this.addEventListeners()
            };
            g.prototype.addEventListeners = function ()
            {
                var o = this;
                this.ws.onopen = function ()
                {
                    o.onOpen()
                };
                this.ws.onclose = function ()
                {
                    o.onClose()
                };
                this.ws.onmessage = function (p)
                {
                    o.onData(p.data)
                };
                this.ws.onerror = function (p)
                {
                    o.onError("websocket error", p)
                }
            };
            if ("undefined" != typeof navigator && /iPad|iPhone|iPod/i.test(navigator.userAgent))
            {
                g.prototype.onData = function (p)
                {
                    var o = this;
                    setTimeout(function ()
                    {
                        m.prototype.onData.call(o, p)
                    }, 0)
                }
            }
            g.prototype.write = function (r)
            {
                var p = this;
                this.writable = false;
                for (var q = 0, o = r.length; q < o; q++)
                {
                    e.encodePacket(r[q], this.supportsBinary, function (t)
                    {
                        try
                        {
                            p.ws.send(t)
                        } catch (u)
                        {
                            f("websocket closed before onclose event")
                        }
                    })
                }
                function s()
                {
                    p.writable = true;
                    p.emit("drain")
                }

                setTimeout(s, 0)
            };
            g.prototype.onClose = function ()
            {
                m.prototype.onClose.call(this)
            };
            g.prototype.doClose = function ()
            {
                if (typeof this.ws !== "undefined")
                {
                    this.ws.close()
                }
            };
            g.prototype.uri = function ()
            {
                var q = this.query || {};
                var p = this.secure ? "wss" : "ws";
                var o = "";
                if (this.port && (("wss" == p && this.port != 443) || ("ws" == p && this.port != 80)))
                {
                    o = ":" + this.port
                }
                if (this.timestampRequests)
                {
                    q[this.timestampParam] = +new Date
                }
                if (!this.supportsBinary)
                {
                    q.b64 = 1
                }
                q = i.encode(q);
                if (q.length)
                {
                    q = "?" + q
                }
                return p + "://" + this.hostname + o + this.path + q
            };
            g.prototype.check = function ()
            {
                return !!n && !("__initialize" in n && this.name === g.prototype.name)
            }
        }, {"../transport": 13, debug: 8, "engine.io-parser": 20, inherits: 27, parseqs: 29, ws: 30}], 19: [function (g, h, f)
        {
            var e = g("has-cors");
            h.exports = function (j)
            {
                var i = j.xdomain;
                try
                {
                    if ("undefined" != typeof XMLHttpRequest && (!i || e))
                    {
                        return new XMLHttpRequest()
                    }
                } catch (k)
                {
                }
                if (!i)
                {
                    try
                    {
                        return new ActiveXObject("Microsoft.XMLHTTP")
                    } catch (k)
                    {
                    }
                }
            }
        }, {"has-cors": 33}], 20: [function (m, e, v)
        {
            var p = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var n = m("./keys");
            var l = m("arraybuffer.slice");
            var q = m("base64-arraybuffer");
            var h = m("after");
            var f = m("utf8");
            var t = navigator.userAgent.match(/Android/i);
            v.protocol = 2;
            var o = v.packets = {open: 0, close: 1, ping: 2, pong: 3, message: 4, upgrade: 5, noop: 6};
            var i = n(o);
            var j = {type: "error", data: "parser error"};
            var k = m("blob");
            v.encodePacket = function (y, A, z)
            {
                if (typeof A == "function")
                {
                    z = A;
                    A = false
                }
                var w = (y.data === undefined) ? undefined : y.data.buffer || y.data;
                if (p.ArrayBuffer && w instanceof ArrayBuffer)
                {
                    return g(y, A, z)
                }
                else
                {
                    if (k && w instanceof p.Blob)
                    {
                        return r(y, A, z)
                    }
                }
                var x = o[y.type];
                if (undefined !== y.data)
                {
                    x += f.encode(String(y.data))
                }
                return z("" + x)
            };
            function g(A, C, B)
            {
                if (!C)
                {
                    return v.encodeBase64Packet(A, B)
                }
                var z = A.data;
                var y = new Uint8Array(z);
                var x = new Uint8Array(1 + z.byteLength);
                x[0] = o[A.type];
                for (var w = 0; w < y.length; w++)
                {
                    x[w + 1] = y[w]
                }
                return B(x.buffer)
            }

            function s(x, z, y)
            {
                if (!z)
                {
                    return v.encodeBase64Packet(x, y)
                }
                var w = new FileReader();
                w.onload = function ()
                {
                    x.data = w.result;
                    v.encodePacket(x, z, y)
                };
                return w.readAsArrayBuffer(x.data)
            }

            function r(y, A, z)
            {
                if (!A)
                {
                    return v.encodeBase64Packet(y, z)
                }
                if (t)
                {
                    return s(y, A, z)
                }
                var x = new Uint8Array(1);
                x[0] = o[y.type];
                var w = new k([x.buffer, y.data]);
                return z(w)
            }

            v.encodeBase64Packet = function (w, D)
            {
                var E = "b" + v.packets[w.type];
                if (k && w.data instanceof k)
                {
                    var A = new FileReader();
                    A.onload = function ()
                    {
                        var F = A.result.split(",")[1];
                        D(E + F)
                    };
                    return A.readAsDataURL(w.data)
                }
                var C;
                try
                {
                    C = String.fromCharCode.apply(null, new Uint8Array(w.data))
                } catch (B)
                {
                    var x = new Uint8Array(w.data);
                    var y = new Array(x.length);
                    for (var z = 0; z < x.length; z++)
                    {
                        y[z] = x[z]
                    }
                    C = String.fromCharCode.apply(null, y)
                }
                E += p.btoa(C);
                return D(E)
            };
            v.decodePacket = function (z, A)
            {
                if (typeof z == "string" || z === undefined)
                {
                    if (z.charAt(0) == "b")
                    {
                        return v.decodeBase64Packet(z.substr(1), A)
                    }
                    z = f.decode(z);
                    var y = z.charAt(0);
                    if (Number(y) != y || !i[y])
                    {
                        return j
                    }
                    if (z.length > 1)
                    {
                        return {type: i[y], data: z.substring(1)}
                    }
                    else
                    {
                        return {type: i[y]}
                    }
                }
                var w = new Uint8Array(z);
                var y = w[0];
                var x = l(z, 1);
                if (k && A === "blob")
                {
                    x = new k([x])
                }
                return {type: i[y], data: x}
            };
            v.decodeBase64Packet = function (y, z)
            {
                var w = i[y.charAt(0)];
                if (!p.ArrayBuffer)
                {
                    return {type: w, data: {base64: true, data: y.substr(1)}}
                }
                var x = q.decode(y.substr(1));
                if (z === "blob" && k)
                {
                    x = new k([x])
                }
                return {type: w, data: x}
            };
            v.encodePayload = function (y, A, z)
            {
                if (typeof A == "function")
                {
                    z = A;
                    A = null
                }
                if (A)
                {
                    if (k && !t)
                    {
                        return v.encodePayloadAsBlob(y, z)
                    }
                    return v.encodePayloadAsArrayBuffer(y, z)
                }
                if (!y.length)
                {
                    return z("0:")
                }
                function w(B)
                {
                    return B.length + ":" + B
                }

                function x(C, B)
                {
                    v.encodePacket(C, A, function (D)
                    {
                        B(null, w(D))
                    })
                }

                u(y, x, function (C, B)
                {
                    return z(B.join(""))
                })
            };
            function u(A, C, x)
            {
                var w = new Array(A.length);
                var B = h(A.length, x);
                var y = function (E, F, D)
                {
                    C(F, function (G, H)
                    {
                        w[E] = H;
                        D(G, w)
                    })
                };
                for (var z = 0; z < A.length; z++)
                {
                    y(z, A[z], B)
                }
            }

            v.decodePayload = function (D, w, G)
            {
                if (typeof D != "string")
                {
                    return v.decodePayloadAsBinary(D, w, G)
                }
                if (typeof w === "function")
                {
                    G = w;
                    w = null
                }
                var x;
                if (D == "")
                {
                    return G(j, 0, 1)
                }
                var y = "", z, A;
                for (var E = 0, B = D.length; E < B; E++)
                {
                    var C = D.charAt(E);
                    if (":" != C)
                    {
                        y += C
                    }
                    else
                    {
                        if ("" == y || (y != (z = Number(y))))
                        {
                            return G(j, 0, 1)
                        }
                        A = D.substr(E + 1, z);
                        if (y != A.length)
                        {
                            return G(j, 0, 1)
                        }
                        if (A.length)
                        {
                            x = v.decodePacket(A, w);
                            if (j.type == x.type && j.data == x.data)
                            {
                                return G(j, 0, 1)
                            }
                            var F = G(x, E + z, B);
                            if (false === F)
                            {
                                return
                            }
                        }
                        E += z;
                        y = ""
                    }
                }
                if (y != "")
                {
                    return G(j, 0, 1)
                }
            };
            v.encodePayloadAsArrayBuffer = function (x, y)
            {
                if (!x.length)
                {
                    return y(new ArrayBuffer(0))
                }
                function w(A, z)
                {
                    v.encodePacket(A, true, function (B)
                    {
                        return z(null, B)
                    })
                }

                u(x, w, function (A, C)
                {
                    var D = C.reduce(function (F, G)
                    {
                        var E;
                        if (typeof G === "string")
                        {
                            E = G.length
                        }
                        else
                        {
                            E = G.byteLength
                        }
                        return F + E.toString().length + E + 2
                    }, 0);
                    var B = new Uint8Array(D);
                    var z = 0;
                    C.forEach(function (I)
                    {
                        var E = typeof I === "string";
                        var H = I;
                        if (E)
                        {
                            var F = new Uint8Array(I.length);
                            for (var G = 0; G < I.length; G++)
                            {
                                F[G] = I.charCodeAt(G)
                            }
                            H = F.buffer
                        }
                        if (E)
                        {
                            B[z++] = 0
                        }
                        else
                        {
                            B[z++] = 1
                        }
                        var J = H.byteLength.toString();
                        for (var G = 0; G < J.length; G++)
                        {
                            B[z++] = parseInt(J[G])
                        }
                        B[z++] = 255;
                        var F = new Uint8Array(H);
                        for (var G = 0; G < F.length; G++)
                        {
                            B[z++] = F[G]
                        }
                    });
                    return y(B.buffer)
                })
            };
            v.encodePayloadAsBlob = function (x, y)
            {
                function w(A, z)
                {
                    v.encodePacket(A, true, function (H)
                    {
                        var F = new Uint8Array(1);
                        F[0] = 1;
                        if (typeof H === "string")
                        {
                            var C = new Uint8Array(H.length);
                            for (var E = 0; E < H.length; E++)
                            {
                                C[E] = H.charCodeAt(E)
                            }
                            H = C.buffer;
                            F[0] = 0
                        }
                        var B = (H instanceof ArrayBuffer) ? H.byteLength : H.size;
                        var I = B.toString();
                        var G = new Uint8Array(I.length + 1);
                        for (var E = 0; E < I.length; E++)
                        {
                            G[E] = parseInt(I[E])
                        }
                        G[I.length] = 255;
                        if (k)
                        {
                            var D = new k([F.buffer, G.buffer, H]);
                            z(null, D)
                        }
                    })
                }

                u(x, w, function (A, z)
                {
                    return y(new k(z))
                })
            };
            v.decodePayloadAsBinary = function (C, w, H)
            {
                if (typeof w === "function")
                {
                    H = w;
                    w = null
                }
                var G = C;
                var I = [];
                while (G.byteLength > 0)
                {
                    var x = new Uint8Array(G);
                    var y = x[0] === 0;
                    var F = "";
                    for (var B = 1; ; B++)
                    {
                        if (x[B] == 255)
                        {
                            break
                        }
                        F += x[B]
                    }
                    G = l(G, 2 + F.length);
                    F = parseInt(F);
                    var A = l(G, 0, F);
                    if (y)
                    {
                        try
                        {
                            A = String.fromCharCode.apply(null, new Uint8Array(A))
                        } catch (D)
                        {
                            var z = new Uint8Array(A);
                            A = "";
                            for (var B = 0; B < z.length; B++)
                            {
                                A += String.fromCharCode(z[B])
                            }
                        }
                    }
                    I.push(A);
                    G = l(G, F)
                }
                var E = I.length;
                I.forEach(function (J, K)
                {
                    H(v.decodePacket(J, w), K, E)
                })
            }
        }, {"./keys": 21, after: 22, "arraybuffer.slice": 23, "base64-arraybuffer": 24, blob: 25, utf8: 26}], 21: [function (f, g, e)
        {
            g.exports = Object.keys || function h(m)
            {
                var j = [];
                var k = Object.prototype.hasOwnProperty;
                for (var l in m)
                {
                    if (k.call(m, l))
                    {
                        j.push(l)
                    }
                }
                return j
            }
        }, {}], 22: [function (f, g, e)
        {
            g.exports = i;
            function i(m, n, l)
            {
                var j = false;
                l = l || h;
                k.count = m;
                return (m === 0) ? n() : k;
                function k(p, o)
                {
                    if (k.count <= 0)
                    {
                        throw new Error("after called too many times")
                    }
                    --k.count;
                    if (p)
                    {
                        j = true;
                        n(p);
                        n = l
                    }
                    else
                    {
                        if (k.count === 0 && !j)
                        {
                            n(null, o)
                        }
                    }
                }
            }

            function h()
            {
            }
        }, {}], 23: [function (f, g, e)
        {
            g.exports = function (n, p, k)
            {
                var j = n.byteLength;
                p = p || 0;
                k = k || j;
                if (n.slice)
                {
                    return n.slice(p, k)
                }
                if (p < 0)
                {
                    p += j
                }
                if (k < 0)
                {
                    k += j
                }
                if (k > j)
                {
                    k = j
                }
                if (p >= j || p >= k || j === 0)
                {
                    return new ArrayBuffer(0)
                }
                var o = new Uint8Array(n);
                var h = new Uint8Array(k - p);
                for (var l = p, m = 0; l < k; l++, m++)
                {
                    h[m] = o[l]
                }
                return h.buffer
            }
        }, {}], 24: [function (f, g, e)
        {
            (function (h)
            {
                e.encode = function (n)
                {
                    var l = new Uint8Array(n), m, j = l.length, k = "";
                    for (m = 0; m < j; m += 3)
                    {
                        k += h[l[m] >> 2];
                        k += h[((l[m] & 3) << 4) | (l[m + 1] >> 4)];
                        k += h[((l[m + 1] & 15) << 2) | (l[m + 2] >> 6)];
                        k += h[l[m + 2] & 63]
                    }
                    if ((j % 3) === 2)
                    {
                        k = k.substring(0, k.length - 1) + "="
                    }
                    else
                    {
                        if (j % 3 === 1)
                        {
                            k = k.substring(0, k.length - 2) + "=="
                        }
                    }
                    return k
                };
                e.decode = function (r)
                {
                    var k = r.length * 0.75, s = r.length, q, m = 0, n, l, j, u;
                    if (r[r.length - 1] === "=")
                    {
                        k--;
                        if (r[r.length - 2] === "=")
                        {
                            k--
                        }
                    }
                    var o = new ArrayBuffer(k), t = new Uint8Array(o);
                    for (q = 0; q < s; q += 4)
                    {
                        n = h.indexOf(r[q]);
                        l = h.indexOf(r[q + 1]);
                        j = h.indexOf(r[q + 2]);
                        u = h.indexOf(r[q + 3]);
                        t[m++] = (n << 2) | (l >> 4);
                        t[m++] = ((l & 15) << 4) | (j >> 2);
                        t[m++] = ((j & 3) << 6) | (u & 63)
                    }
                    return o
                }
            })("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/")
        }, {}], 25: [function (f, i, e)
        {
            var l = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var k = l.BlobBuilder || l.WebKitBlobBuilder || l.MSBlobBuilder || l.MozBlobBuilder;
            var h = (function ()
            {
                try
                {
                    var m = new Blob(["hi"]);
                    return m.size == 2
                } catch (n)
                {
                    return false
                }
            })();
            var j = k && k.prototype.append && k.prototype.getBlob;

            function g(o, m)
            {
                m = m || {};
                var p = new k();
                for (var n = 0; n < o.length; n++)
                {
                    p.append(o[n])
                }
                return (m.type) ? p.getBlob(m.type) : p.getBlob()
            }

            i.exports = (function ()
            {
                if (h)
                {
                    return l.Blob
                }
                else
                {
                    if (j)
                    {
                        return g
                    }
                    else
                    {
                        return undefined
                    }
                }
            })()
        }, {}], 26: [function (f, g, e)
        {
            var h = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            /*! http://mths.be/utf8js v2.0.0 by @mathias */
            (function (v)
            {
                var o = typeof e == "object" && e;
                var B = typeof g == "object" && g && g.exports == o && g;
                var u = typeof h == "object" && h;
                if (u.global === u || u.window === u)
                {
                    v = u
                }
                var t = String.fromCharCode;

                function n(F)
                {
                    var E = [];
                    var D = 0;
                    var G = F.length;
                    var H;
                    var C;
                    while (D < G)
                    {
                        H = F.charCodeAt(D++);
                        if (H >= 55296 && H <= 56319 && D < G)
                        {
                            C = F.charCodeAt(D++);
                            if ((C & 64512) == 56320)
                            {
                                E.push(((H & 1023) << 10) + (C & 1023) + 65536)
                            }
                            else
                            {
                                E.push(H);
                                D--
                            }
                        }
                        else
                        {
                            E.push(H)
                        }
                    }
                    return E
                }

                function y(G)
                {
                    var E = G.length;
                    var D = -1;
                    var F;
                    var C = "";
                    while (++D < E)
                    {
                        F = G[D];
                        if (F > 65535)
                        {
                            F -= 65536;
                            C += t(F >>> 10 & 1023 | 55296);
                            F = 56320 | F & 1023
                        }
                        C += t(F)
                    }
                    return C
                }

                function m(D, C)
                {
                    return t(((D >> C) & 63) | 128)
                }

                function x(C)
                {
                    if ((C & 4294967168) == 0)
                    {
                        return t(C)
                    }
                    var D = "";
                    if ((C & 4294965248) == 0)
                    {
                        D = t(((C >> 6) & 31) | 192)
                    }
                    else
                    {
                        if ((C & 4294901760) == 0)
                        {
                            D = t(((C >> 12) & 15) | 224);
                            D += m(C, 6)
                        }
                        else
                        {
                            if ((C & 4292870144) == 0)
                            {
                                D = t(((C >> 18) & 7) | 240);
                                D += m(C, 12);
                                D += m(C, 6)
                            }
                        }
                    }
                    D += t((C & 63) | 128);
                    return D
                }

                function w(F)
                {
                    var E = n(F);
                    var G = E.length;
                    var D = -1;
                    var C;
                    var H = "";
                    while (++D < G)
                    {
                        C = E[D];
                        H += x(C)
                    }
                    return H
                }

                function i()
                {
                    if (r >= q)
                    {
                        throw Error("Invalid byte index")
                    }
                    var C = s[r] & 255;
                    r++;
                    if ((C & 192) == 128)
                    {
                        return C & 63
                    }
                    throw Error("Invalid continuation byte")
                }

                function k()
                {
                    var D;
                    var C;
                    var G;
                    var F;
                    var E;
                    if (r > q)
                    {
                        throw Error("Invalid byte index")
                    }
                    if (r == q)
                    {
                        return false
                    }
                    D = s[r] & 255;
                    r++;
                    if ((D & 128) == 0)
                    {
                        return D
                    }
                    if ((D & 224) == 192)
                    {
                        var C = i();
                        E = ((D & 31) << 6) | C;
                        if (E >= 128)
                        {
                            return E
                        }
                        else
                        {
                            throw Error("Invalid continuation byte")
                        }
                    }
                    if ((D & 240) == 224)
                    {
                        C = i();
                        G = i();
                        E = ((D & 15) << 12) | (C << 6) | G;
                        if (E >= 2048)
                        {
                            return E
                        }
                        else
                        {
                            throw Error("Invalid continuation byte")
                        }
                    }
                    if ((D & 248) == 240)
                    {
                        C = i();
                        G = i();
                        F = i();
                        E = ((D & 15) << 18) | (C << 12) | (G << 6) | F;
                        if (E >= 65536 && E <= 1114111)
                        {
                            return E
                        }
                    }
                    throw Error("Invalid UTF-8 detected")
                }

                var s;
                var q;
                var r;

                function j(E)
                {
                    s = n(E);
                    q = s.length;
                    r = 0;
                    var C = [];
                    var D;
                    while ((D = k()) !== false)
                    {
                        C.push(D)
                    }
                    return y(C)
                }

                var l = {version: "2.0.0", encode: w, decode: j};
                if (typeof d == "function" && typeof d.amd == "object" && d.amd)
                {
                    d(function ()
                    {
                        return l
                    })
                }
                else
                {
                    if (o && !o.nodeType)
                    {
                        if (B)
                        {
                            B.exports = l
                        }
                        else
                        {
                            var z = {};
                            var p = z.hasOwnProperty;
                            for (var A in l)
                            {
                                p.call(l, A) && (o[A] = l[A])
                            }
                        }
                    }
                    else
                    {
                        v.utf8 = l
                    }
                }
            }(this))
        }, {}], 27: [function (f, g, e)
        {
            if (typeof Object.create === "function")
            {
                g.exports = function h(j, i)
                {
                    j.super_ = i;
                    j.prototype = Object.create(i.prototype, {constructor: {value: j, enumerable: false, writable: true, configurable: true}})
                }
            }
            else
            {
                g.exports = function h(j, i)
                {
                    j.super_ = i;
                    var k = function ()
                    {
                    };
                    k.prototype = i.prototype;
                    j.prototype = new k();
                    j.prototype.constructor = j
                }
            }
        }, {}], 28: [function (g, f, i)
        {
            var e = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var k = /^[\],:{}\s]*$/;
            var o = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g;
            var n = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g;
            var h = /(?:^|:|,)(?:\s*\[)+/g;
            var l = /^\s+/;
            var m = /\s+$/;
            f.exports = function j(p)
            {
                if ("string" != typeof p || !p)
                {
                    return null
                }
                p = p.replace(l, "").replace(m, "");
                if (e.JSON && JSON.parse)
                {
                    return JSON.parse(p)
                }
                if (k.test(p.replace(o, "@").replace(n, "]").replace(h, "")))
                {
                    return (new Function("return " + p))()
                }
            }
        }, {}], 29: [function (f, g, e)
        {
            e.encode = function (j)
            {
                var k = "";
                for (var h in j)
                {
                    if (j.hasOwnProperty(h))
                    {
                        if (k.length)
                        {
                            k += "&"
                        }
                        k += encodeURIComponent(h) + "=" + encodeURIComponent(j[h])
                    }
                }
                return k
            };
            e.decode = function (h)
            {
                var m = {};
                var n = h.split("&");
                for (var k = 0, j = n.length; k < j; k++)
                {
                    var o = n[k].split("=");
                    m[decodeURIComponent(o[0])] = decodeURIComponent(o[1])
                }
                return m
            }
        }, {}], 30: [function (h, i, f)
        {
            var j = (function ()
            {
                return this
            })();
            var g = j.WebSocket || j.MozWebSocket;
            i.exports = g ? e : null;
            function e(n, m, l)
            {
                var k;
                if (m)
                {
                    k = new g(n, m)
                }
                else
                {
                    k = new g(n)
                }
                return k
            }

            if (g)
            {
                e.prototype = g.prototype
            }
        }, {}], 31: [function (g, h, f)
        {
            var j = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var e = g("isarray");
            h.exports = i;
            function i(l)
            {
                function k(o)
                {
                    if (!o)
                    {
                        return false
                    }
                    if ((j.Buffer && Buffer.isBuffer(o)) || (j.ArrayBuffer && o instanceof ArrayBuffer) || (j.Blob && o instanceof Blob) || (j.File && o instanceof File))
                    {
                        return true
                    }
                    if (e(o))
                    {
                        for (var n = 0; n < o.length; n++)
                        {
                            if (k(o[n]))
                            {
                                return true
                            }
                        }
                    }
                    else
                    {
                        if (o && "object" == typeof o)
                        {
                            if (o.toJSON)
                            {
                                o = o.toJSON()
                            }
                            for (var m in o)
                            {
                                if (k(o[m]))
                                {
                                    return true
                                }
                            }
                        }
                    }
                    return false
                }

                return k(l)
            }
        }, {isarray: 32}], 32: [function (f, g, e)
        {
            g.exports = Array.isArray || function (h)
            {
                return Object.prototype.toString.call(h) == "[object Array]"
            }
        }, {}], 33: [function (f, g, e)
        {
            var i = f("global");
            try
            {
                g.exports = "XMLHttpRequest" in i && "withCredentials" in new i.XMLHttpRequest()
            } catch (h)
            {
                g.exports = false
            }
        }, {global: 34}], 34: [function (f, g, e)
        {
            g.exports = (function ()
            {
                return this
            })()
        }, {}], 35: [function (f, g, e)
        {
            var h = [].indexOf;
            g.exports = function (j, l)
            {
                if (h)
                {
                    return j.indexOf(l)
                }
                for (var k = 0; k < j.length; ++k)
                {
                    if (j[k] === l)
                    {
                        return k
                    }
                }
                return -1
            }
        }, {}], 36: [function (g, h, e)
        {
            var f = Object.prototype.hasOwnProperty;
            e.keys = Object.keys || function (k)
            {
                var j = [];
                for (var i in k)
                {
                    if (f.call(k, i))
                    {
                        j.push(i)
                    }
                }
                return j
            };
            e.values = function (k)
            {
                var j = [];
                for (var i in k)
                {
                    if (f.call(k, i))
                    {
                        j.push(k[i])
                    }
                }
                return j
            };
            e.merge = function (j, i)
            {
                for (var k in i)
                {
                    if (f.call(i, k))
                    {
                        j[k] = i[k]
                    }
                }
                return j
            };
            e.length = function (i)
            {
                return e.keys(i).length
            };
            e.isEmpty = function (i)
            {
                return 0 == e.length(i)
            }
        }, {}], 37: [function (f, g, e)
        {
            var h = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/;
            var j = ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"];
            g.exports = function i(o)
            {
                var k = h.exec(o || ""), n = {}, l = 14;
                while (l--)
                {
                    n[j[l]] = k[l] || ""
                }
                return n
            }
        }, {}], 38: [function (g, h, f)
        {
            var i = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var e = g("isarray");
            f.deconstructPacket = function (o)
            {
                var m = [];
                var l = o.data;

                function k(s)
                {
                    if (!s)
                    {
                        return s
                    }
                    if ((i.Buffer && Buffer.isBuffer(s)) || (i.ArrayBuffer && s instanceof ArrayBuffer))
                    {
                        var t = {_placeholder: true, num: m.length};
                        m.push(s);
                        return t
                    }
                    else
                    {
                        if (e(s))
                        {
                            var r = new Array(s.length);
                            for (var q = 0; q < s.length; q++)
                            {
                                r[q] = k(s[q])
                            }
                            return r
                        }
                        else
                        {
                            if ("object" == typeof s && !(s instanceof Date))
                            {
                                var r = {};
                                for (var p in s)
                                {
                                    r[p] = k(s[p])
                                }
                                return r
                            }
                        }
                    }
                    return s
                }

                var n = o;
                n.data = k(l);
                n.attachments = m.length;
                return {packet: n, buffers: m}
            };
            f.reconstructPacket = function (m, l)
            {
                var k = 0;

                function n(r)
                {
                    if (r && r._placeholder)
                    {
                        var o = l[r.num];
                        return o
                    }
                    else
                    {
                        if (e(r))
                        {
                            for (var q = 0; q < r.length; q++)
                            {
                                r[q] = n(r[q])
                            }
                            return r
                        }
                        else
                        {
                            if (r && "object" == typeof r)
                            {
                                for (var p in r)
                                {
                                    r[p] = n(r[p])
                                }
                                return r
                            }
                        }
                    }
                    return r
                }

                m.data = n(m.data);
                m.attachments = undefined;
                return m
            };
            f.removeBlobs = function (n, o)
            {
                function k(t, u, q)
                {
                    if (!t)
                    {
                        return t
                    }
                    if ((i.Blob && t instanceof Blob) || (i.File && t instanceof File))
                    {
                        l++;
                        var p = new FileReader();
                        p.onload = function ()
                        {
                            if (q)
                            {
                                q[u] = this.result
                            }
                            else
                            {
                                m = this.result
                            }
                            if (!--l)
                            {
                                o(m)
                            }
                        };
                        p.readAsArrayBuffer(t)
                    }
                    if (e(t))
                    {
                        for (var s = 0; s < t.length; s++)
                        {
                            k(t[s], s, t)
                        }
                    }
                    else
                    {
                        if (t && "object" == typeof t && !j(t))
                        {
                            for (var r in t)
                            {
                                k(t[r], r, t)
                            }
                        }
                    }
                }

                var l = 0;
                var m = n;
                k(m);
                if (!l)
                {
                    o(m)
                }
            };
            function j(k)
            {
                return (i.Buffer && Buffer.isBuffer(k)) || (i.ArrayBuffer && k instanceof ArrayBuffer)
            }
        }, {isarray: 40}], 39: [function (i, g, l)
        {
            var f = typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {};
            var e = i("debug")("socket.io-parser");
            var t = i("json3");
            var o = i("isarray");
            var q = i("emitter");
            var n = i("./binary");
            l.protocol = 3;
            l.types = ["CONNECT", "DISCONNECT", "EVENT", "BINARY_EVENT", "ACK", "BINARY_ACK", "ERROR"];
            l.CONNECT = 0;
            l.DISCONNECT = 1;
            l.EVENT = 2;
            l.ACK = 3;
            l.ERROR = 4;
            l.BINARY_EVENT = 5;
            l.BINARY_ACK = 6;
            l.Encoder = k;
            function k()
            {
            }

            k.prototype.encode = function (v, w)
            {
                e("encoding packet %j", v);
                if (l.BINARY_EVENT == v.type || l.BINARY_ACK == v.type)
                {
                    h(v, w)
                }
                else
                {
                    var u = j(v);
                    w([u])
                }
            };
            function j(v)
            {
                var w = "";
                var u = false;
                w += v.type;
                if (l.BINARY_EVENT == v.type || l.BINARY_ACK == v.type)
                {
                    w += v.attachments;
                    w += "-"
                }
                if (v.nsp && "/" != v.nsp)
                {
                    u = true;
                    w += v.nsp
                }
                if (null != v.id)
                {
                    if (u)
                    {
                        w += ",";
                        u = false
                    }
                    w += v.id
                }
                if (null != v.data)
                {
                    if (u)
                    {
                        w += ","
                    }
                    w += t.stringify(v.data)
                }
                e("encoded %j as %s", v, w);
                return w
            }

            function h(v, w)
            {
                function u(A)
                {
                    var y = n.deconstructPacket(A);
                    var z = j(y.packet);
                    var x = y.buffers;
                    x.unshift(z);
                    w(x)
                }

                n.removeBlobs(v, u)
            }

            l.Decoder = s;
            function s()
            {
                this.reconstructor = null
            }

            q(s.prototype);
            s.prototype.add = function (v)
            {
                var u;
                if ("string" == typeof v)
                {
                    u = m(v);
                    if (l.BINARY_EVENT == u.type || l.BINARY_ACK == u.type)
                    {
                        this.reconstructor = new r(u);
                        if (this.reconstructor.reconPack.attachments == 0)
                        {
                            this.emit("decoded", u)
                        }
                    }
                    else
                    {
                        this.emit("decoded", u)
                    }
                }
                else
                {
                    if ((f.Buffer && Buffer.isBuffer(v)) || (f.ArrayBuffer && v instanceof ArrayBuffer) || v.base64)
                    {
                        if (!this.reconstructor)
                        {
                            throw new Error("got binary data when not reconstructing a packet")
                        }
                        else
                        {
                            u = this.reconstructor.takeBinaryData(v);
                            if (u)
                            {
                                this.reconstructor = null;
                                this.emit("decoded", u)
                            }
                        }
                    }
                    else
                    {
                        throw new Error("Unknown type: " + v)
                    }
                }
            };
            function m(y)
            {
                var x = {};
                var u = 0;
                x.type = Number(y.charAt(0));
                if (null == l.types[x.type])
                {
                    return p()
                }
                if (l.BINARY_EVENT == x.type || l.BINARY_ACK == x.type)
                {
                    x.attachments = "";
                    while (y.charAt(++u) != "-")
                    {
                        x.attachments += y.charAt(u)
                    }
                    x.attachments = Number(x.attachments)
                }
                if ("/" == y.charAt(u + 1))
                {
                    x.nsp = "";
                    while (++u)
                    {
                        var z = y.charAt(u);
                        if ("," == z)
                        {
                            break
                        }
                        x.nsp += z;
                        if (u + 1 == y.length)
                        {
                            break
                        }
                    }
                }
                else
                {
                    x.nsp = "/"
                }
                var v = y.charAt(u + 1);
                if ("" != v && Number(v) == v)
                {
                    x.id = "";
                    while (++u)
                    {
                        var z = y.charAt(u);
                        if (null == z || Number(z) != z)
                        {
                            --u;
                            break
                        }
                        x.id += y.charAt(u);
                        if (u + 1 == y.length)
                        {
                            break
                        }
                    }
                    x.id = Number(x.id)
                }
                if (y.charAt(++u))
                {
                    try
                    {
                        x.data = t.parse(y.substr(u))
                    } catch (w)
                    {
                        return p()
                    }
                }
                e("decoded %s as %j", y, x);
                return x
            }

            s.prototype.destroy = function ()
            {
                if (this.reconstructor)
                {
                    this.reconstructor.finishedReconstruction()
                }
            };
            function r(u)
            {
                this.reconPack = u;
                this.buffers = []
            }

            r.prototype.takeBinaryData = function (u)
            {
                this.buffers.push(u);
                if (this.buffers.length == this.reconPack.attachments)
                {
                    var v = n.reconstructPacket(this.reconPack, this.buffers);
                    this.finishedReconstruction();
                    return v
                }
                return null
            };
            r.prototype.finishedReconstruction = function ()
            {
                this.reconPack = null;
                this.buffers = []
            };
            function p(u)
            {
                return {type: l.ERROR, data: "parser error"}
            }
        }, {"./binary": 38, debug: 8, emitter: 9, isarray: 40, json3: 41}], 40: [function (f, g, e)
        {
            g.exports = f(32)
        }, {}], 41: [function (f, g, e)
        {
            /*! JSON v3.2.6 | http://bestiejs.github.io/json3 | Copyright 2012-2013, Kit Cambridge | http://kit.mit-license.org */
            (function (M)
            {
                var q = {}.toString, m, h, F;
                var D = typeof d === "function" && d.amd;
                var R = typeof JSON == "object" && JSON;
                var L = typeof e == "object" && e && !e.nodeType && e;
                if (L && R)
                {
                    L.stringify = R.stringify;
                    L.parse = R.parse
                }
                else
                {
                    L = M.JSON = R || {}
                }
                var w = new Date(-3509827334573292);
                try
                {
                    w = w.getUTCFullYear() == -109252 && w.getUTCMonth() === 0 && w.getUTCDate() === 1 && w.getUTCHours() == 10 && w.getUTCMinutes() == 37 && w.getUTCSeconds() == 6 && w.getUTCMilliseconds() == 708
                } catch (r)
                {
                }
                function j(T)
                {
                    if (j[T] !== F)
                    {
                        return j[T]
                    }
                    var U;
                    if (T == "bug-string-char-index")
                    {
                        U = "a"[0] != "a"
                    }
                    else
                    {
                        if (T == "json")
                        {
                            U = j("json-stringify") && j("json-parse")
                        }
                        else
                        {
                            var ab, Y = '{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}';
                            if (T == "json-stringify")
                            {
                                var Z = L.stringify, aa = typeof Z == "function" && w;
                                if (aa)
                                {
                                    (ab = function ()
                                    {
                                        return 1
                                    }).toJSON = ab;
                                    try
                                    {
                                        aa = Z(0) === "0" && Z(new Number()) === "0" && Z(new String()) == '""' && Z(q) === F && Z(F) === F && Z() === F && Z(ab) === "1" && Z([ab]) == "[1]" && Z([F]) == "[null]" && Z(null) == "null" && Z([F, q, null]) == "[null,null,null]" && Z({a: [ab, true, false, null, "\x00\b\n\f\r\t"]}) == Y && Z(null, ab) === "1" && Z([1, 2], null, 1) == "[\n 1,\n 2\n]" && Z(new Date(-8640000000000000)) == '"-271821-04-20T00:00:00.000Z"' && Z(new Date(8640000000000000)) == '"+275760-09-13T00:00:00.000Z"' && Z(new Date(-62198755200000)) == '"-000001-01-01T00:00:00.000Z"' && Z(new Date(-1)) == '"1969-12-31T23:59:59.999Z"'
                                    } catch (V)
                                    {
                                        aa = false
                                    }
                                }
                                U = aa
                            }
                            if (T == "json-parse")
                            {
                                var X = L.parse;
                                if (typeof X == "function")
                                {
                                    try
                                    {
                                        if (X("0") === 0 && !X(false))
                                        {
                                            ab = X(Y);
                                            var W = ab.a.length == 5 && ab.a[0] === 1;
                                            if (W)
                                            {
                                                try
                                                {
                                                    W = !X('"\t"')
                                                } catch (V)
                                                {
                                                }
                                                if (W)
                                                {
                                                    try
                                                    {
                                                        W = X("01") !== 1
                                                    } catch (V)
                                                    {
                                                    }
                                                }
                                                if (W)
                                                {
                                                    try
                                                    {
                                                        W = X("1.") !== 1
                                                    } catch (V)
                                                    {
                                                    }
                                                }
                                            }
                                        }
                                    } catch (V)
                                    {
                                        W = false
                                    }
                                }
                                U = W
                            }
                        }
                    }
                    return j[T] = !!U
                }

                if (!j("json"))
                {
                    var N = "[object Function]";
                    var K = "[object Date]";
                    var H = "[object Number]";
                    var I = "[object String]";
                    var z = "[object Array]";
                    var v = "[object Boolean]";
                    var A = j("bug-string-char-index");
                    if (!w)
                    {
                        var n = Math.floor;
                        var S = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
                        var y = function (T, U)
                        {
                            return S[U] + 365 * (T - 1970) + n((T - 1969 + (U = +(U > 1))) / 4) - n((T - 1901 + U) / 100) + n((T - 1601 + U) / 400)
                        }
                    }
                    if (!(m = {}.hasOwnProperty))
                    {
                        m = function (V)
                        {
                            var T = {}, U;
                            if ((T.__proto__ = null, T.__proto__ = {toString: 1}, T).toString != q)
                            {
                                m = function (Y)
                                {
                                    var X = this.__proto__, W = Y in (this.__proto__ = null, this);
                                    this.__proto__ = X;
                                    return W
                                }
                            }
                            else
                            {
                                U = T.constructor;
                                m = function (X)
                                {
                                    var W = (this.constructor || U).prototype;
                                    return X in this && !(X in W && this[X] === W[X])
                                }
                            }
                            T = null;
                            return m.call(this, V)
                        }
                    }
                    var O = {"boolean": 1, number: 1, string: 1, "undefined": 1};
                    var p = function (T, V)
                    {
                        var U = typeof T[V];
                        return U == "object" ? !!T[V] : !O[U]
                    };
                    h = function (V, Y)
                    {
                        var W = 0, T, U, X;
                        (T = function ()
                        {
                            this.valueOf = 0
                        }).prototype.valueOf = 0;
                        U = new T();
                        for (X in U)
                        {
                            if (m.call(U, X))
                            {
                                W++
                            }
                        }
                        T = U = null;
                        if (!W)
                        {
                            U = ["valueOf", "toString", "toLocaleString", "propertyIsEnumerable", "isPrototypeOf", "hasOwnProperty", "constructor"];
                            h = function (aa, ae)
                            {
                                var ad = q.call(aa) == N, ac, ab;
                                var Z = !ad && typeof aa.constructor != "function" && p(aa, "hasOwnProperty") ? aa.hasOwnProperty : m;
                                for (ac in aa)
                                {
                                    if (!(ad && ac == "prototype") && Z.call(aa, ac))
                                    {
                                        ae(ac)
                                    }
                                }
                                for (ab = U.length; ac = U[--ab]; Z.call(aa, ac) && ae(ac))
                                {
                                }
                            }
                        }
                        else
                        {
                            if (W == 2)
                            {
                                h = function (aa, ad)
                                {
                                    var Z = {}, ac = q.call(aa) == N, ab;
                                    for (ab in aa)
                                    {
                                        if (!(ac && ab == "prototype") && !m.call(Z, ab) && (Z[ab] = 1) && m.call(aa, ab))
                                        {
                                            ad(ab)
                                        }
                                    }
                                }
                            }
                            else
                            {
                                h = function (aa, ad)
                                {
                                    var ac = q.call(aa) == N, ab, Z;
                                    for (ab in aa)
                                    {
                                        if (!(ac && ab == "prototype") && m.call(aa, ab) && !(Z = ab === "constructor"))
                                        {
                                            ad(ab)
                                        }
                                    }
                                    if (Z || m.call(aa, (ab = "constructor")))
                                    {
                                        ad(ab)
                                    }
                                }
                            }
                        }
                        return h(V, Y)
                    };
                    if (!j("json-stringify"))
                    {
                        var l = {92: "\\\\", 34: '\\"', 8: "\\b", 12: "\\f", 10: "\\n", 13: "\\r", 9: "\\t"};
                        var E = "000000";
                        var o = function (T, U)
                        {
                            return (E + (U || 0)).slice(-T)
                        };
                        var t = "\\u00";
                        var x = function (Z)
                        {
                            var T = '"', W = 0, X = Z.length, Y = X > 10 && A, V;
                            if (Y)
                            {
                                V = Z.split("")
                            }
                            for (; W < X; W++)
                            {
                                var U = Z.charCodeAt(W);
                                switch (U)
                                {
                                    case 8:
                                    case 9:
                                    case 10:
                                    case 12:
                                    case 13:
                                    case 34:
                                    case 92:
                                        T += l[U];
                                        break;
                                    default:
                                        if (U < 32)
                                        {
                                            T += t + o(2, U.toString(16));
                                            break
                                        }
                                        T += Y ? V[W] : A ? Z.charAt(W) : Z[W]
                                }
                            }
                            return T + '"'
                        };
                        var k = function (Z, aq, X, ac, an, T, aa)
                        {
                            var aj, V, ag, ap, ao, ab, am, ak, ah, ae, ai, U, Y, W, al, af;
                            try
                            {
                                aj = aq[Z]
                            } catch (ad)
                            {
                            }
                            if (typeof aj == "object" && aj)
                            {
                                V = q.call(aj);
                                if (V == K && !m.call(aj, "toJSON"))
                                {
                                    if (aj > -1 / 0 && aj < 1 / 0)
                                    {
                                        if (y)
                                        {
                                            ao = n(aj / 86400000);
                                            for (ag = n(ao / 365.2425) + 1970 - 1; y(ag + 1, 0) <= ao; ag++)
                                            {
                                            }
                                            for (ap = n((ao - y(ag, 0)) / 30.42); y(ag, ap + 1) <= ao; ap++)
                                            {
                                            }
                                            ao = 1 + ao - y(ag, ap);
                                            ab = (aj % 86400000 + 86400000) % 86400000;
                                            am = n(ab / 3600000) % 24;
                                            ak = n(ab / 60000) % 60;
                                            ah = n(ab / 1000) % 60;
                                            ae = ab % 1000
                                        }
                                        else
                                        {
                                            ag = aj.getUTCFullYear();
                                            ap = aj.getUTCMonth();
                                            ao = aj.getUTCDate();
                                            am = aj.getUTCHours();
                                            ak = aj.getUTCMinutes();
                                            ah = aj.getUTCSeconds();
                                            ae = aj.getUTCMilliseconds()
                                        }
                                        aj = (ag <= 0 || ag >= 10000 ? (ag < 0 ? "-" : "+") + o(6, ag < 0 ? -ag : ag) : o(4, ag)) + "-" + o(2, ap + 1) + "-" + o(2, ao) + "T" + o(2, am) + ":" + o(2, ak) + ":" + o(2, ah) + "." + o(3, ae) + "Z"
                                    }
                                    else
                                    {
                                        aj = null
                                    }
                                }
                                else
                                {
                                    if (typeof aj.toJSON == "function" && ((V != H && V != I && V != z) || m.call(aj, "toJSON")))
                                    {
                                        aj = aj.toJSON(Z)
                                    }
                                }
                            }
                            if (X)
                            {
                                aj = X.call(aq, Z, aj)
                            }
                            if (aj === null)
                            {
                                return "null"
                            }
                            V = q.call(aj);
                            if (V == v)
                            {
                                return "" + aj
                            }
                            else
                            {
                                if (V == H)
                                {
                                    return aj > -1 / 0 && aj < 1 / 0 ? "" + aj : "null"
                                }
                                else
                                {
                                    if (V == I)
                                    {
                                        return x("" + aj)
                                    }
                                }
                            }
                            if (typeof aj == "object")
                            {
                                for (W = aa.length; W--;)
                                {
                                    if (aa[W] === aj)
                                    {
                                        throw TypeError()
                                    }
                                }
                                aa.push(aj);
                                ai = [];
                                al = T;
                                T += an;
                                if (V == z)
                                {
                                    for (Y = 0, W = aj.length; Y < W; Y++)
                                    {
                                        U = k(Y, aj, X, ac, an, T, aa);
                                        ai.push(U === F ? "null" : U)
                                    }
                                    af = ai.length ? (an ? "[\n" + T + ai.join(",\n" + T) + "\n" + al + "]" : ("[" + ai.join(",") + "]")) : "[]"
                                }
                                else
                                {
                                    h(ac || aj, function (at)
                                    {
                                        var ar = k(at, aj, X, ac, an, T, aa);
                                        if (ar !== F)
                                        {
                                            ai.push(x(at) + ":" + (an ? " " : "") + ar)
                                        }
                                    });
                                    af = ai.length ? (an ? "{\n" + T + ai.join(",\n" + T) + "\n" + al + "}" : ("{" + ai.join(",") + "}")) : "{}"
                                }
                                aa.pop();
                                return af
                            }
                        };
                        L.stringify = function (T, V, W)
                        {
                            var U, ac, aa, Z;
                            if (typeof V == "function" || typeof V == "object" && V)
                            {
                                if ((Z = q.call(V)) == N)
                                {
                                    ac = V
                                }
                                else
                                {
                                    if (Z == z)
                                    {
                                        aa = {};
                                        for (var Y = 0, X = V.length, ab; Y < X; ab = V[Y++], ((Z = q.call(ab)), Z == I || Z == H) && (aa[ab] = 1))
                                        {
                                        }
                                    }
                                }
                            }
                            if (W)
                            {
                                if ((Z = q.call(W)) == H)
                                {
                                    if ((W -= W % 1) > 0)
                                    {
                                        for (U = "", W > 10 && (W = 10); U.length < W; U += " ")
                                        {
                                        }
                                    }
                                }
                                else
                                {
                                    if (Z == I)
                                    {
                                        U = W.length <= 10 ? W : W.slice(0, 10)
                                    }
                                }
                            }
                            return k("", (ab = {}, ab[""] = T, ab), ac, aa, U, "", [])
                        }
                    }
                    if (!j("json-parse"))
                    {
                        var G = String.fromCharCode;
                        var i = {92: "\\", 34: '"', 47: "/", 98: "\b", 116: "\t", 110: "\n", 102: "\f", 114: "\r"};
                        var B, Q;
                        var C = function ()
                        {
                            B = Q = null;
                            throw SyntaxError()
                        };
                        var u = function ()
                        {
                            var Y = Q, W = Y.length, X, V, T, Z, U;
                            while (B < W)
                            {
                                U = Y.charCodeAt(B);
                                switch (U)
                                {
                                    case 9:
                                    case 10:
                                    case 13:
                                    case 32:
                                        B++;
                                        break;
                                    case 123:
                                    case 125:
                                    case 91:
                                    case 93:
                                    case 58:
                                    case 44:
                                        X = A ? Y.charAt(B) : Y[B];
                                        B++;
                                        return X;
                                    case 34:
                                        for (X = "@", B++; B < W;)
                                        {
                                            U = Y.charCodeAt(B);
                                            if (U < 32)
                                            {
                                                C()
                                            }
                                            else
                                            {
                                                if (U == 92)
                                                {
                                                    U = Y.charCodeAt(++B);
                                                    switch (U)
                                                    {
                                                        case 92:
                                                        case 34:
                                                        case 47:
                                                        case 98:
                                                        case 116:
                                                        case 110:
                                                        case 102:
                                                        case 114:
                                                            X += i[U];
                                                            B++;
                                                            break;
                                                        case 117:
                                                            V = ++B;
                                                            for (T = B + 4; B < T; B++)
                                                            {
                                                                U = Y.charCodeAt(B);
                                                                if (!(U >= 48 && U <= 57 || U >= 97 && U <= 102 || U >= 65 && U <= 70))
                                                                {
                                                                    C()
                                                                }
                                                            }
                                                            X += G("0x" + Y.slice(V, B));
                                                            break;
                                                        default:
                                                            C()
                                                    }
                                                }
                                                else
                                                {
                                                    if (U == 34)
                                                    {
                                                        break
                                                    }
                                                    U = Y.charCodeAt(B);
                                                    V = B;
                                                    while (U >= 32 && U != 92 && U != 34)
                                                    {
                                                        U = Y.charCodeAt(++B)
                                                    }
                                                    X += Y.slice(V, B)
                                                }
                                            }
                                        }
                                        if (Y.charCodeAt(B) == 34)
                                        {
                                            B++;
                                            return X
                                        }
                                        C();
                                    default:
                                        V = B;
                                        if (U == 45)
                                        {
                                            Z = true;
                                            U = Y.charCodeAt(++B)
                                        }
                                        if (U >= 48 && U <= 57)
                                        {
                                            if (U == 48 && ((U = Y.charCodeAt(B + 1)), U >= 48 && U <= 57))
                                            {
                                                C()
                                            }
                                            Z = false;
                                            for (; B < W && ((U = Y.charCodeAt(B)), U >= 48 && U <= 57); B++)
                                            {
                                            }
                                            if (Y.charCodeAt(B) == 46)
                                            {
                                                T = ++B;
                                                for (; T < W && ((U = Y.charCodeAt(T)), U >= 48 && U <= 57); T++)
                                                {
                                                }
                                                if (T == B)
                                                {
                                                    C()
                                                }
                                                B = T
                                            }
                                            U = Y.charCodeAt(B);
                                            if (U == 101 || U == 69)
                                            {
                                                U = Y.charCodeAt(++B);
                                                if (U == 43 || U == 45)
                                                {
                                                    B++
                                                }
                                                for (T = B; T < W && ((U = Y.charCodeAt(T)), U >= 48 && U <= 57); T++)
                                                {
                                                }
                                                if (T == B)
                                                {
                                                    C()
                                                }
                                                B = T
                                            }
                                            return +Y.slice(V, B)
                                        }
                                        if (Z)
                                        {
                                            C()
                                        }
                                        if (Y.slice(B, B + 4) == "true")
                                        {
                                            B += 4;
                                            return true
                                        }
                                        else
                                        {
                                            if (Y.slice(B, B + 5) == "false")
                                            {
                                                B += 5;
                                                return false
                                            }
                                            else
                                            {
                                                if (Y.slice(B, B + 4) == "null")
                                                {
                                                    B += 4;
                                                    return null
                                                }
                                            }
                                        }
                                        C()
                                }
                            }
                            return "$"
                        };
                        var P = function (U)
                        {
                            var T, V;
                            if (U == "$")
                            {
                                C()
                            }
                            if (typeof U == "string")
                            {
                                if ((A ? U.charAt(0) : U[0]) == "@")
                                {
                                    return U.slice(1)
                                }
                                if (U == "[")
                                {
                                    T = [];
                                    for (; ; V || (V = true))
                                    {
                                        U = u();
                                        if (U == "]")
                                        {
                                            break
                                        }
                                        if (V)
                                        {
                                            if (U == ",")
                                            {
                                                U = u();
                                                if (U == "]")
                                                {
                                                    C()
                                                }
                                            }
                                            else
                                            {
                                                C()
                                            }
                                        }
                                        if (U == ",")
                                        {
                                            C()
                                        }
                                        T.push(P(U))
                                    }
                                    return T
                                }
                                else
                                {
                                    if (U == "{")
                                    {
                                        T = {};
                                        for (; ; V || (V = true))
                                        {
                                            U = u();
                                            if (U == "}")
                                            {
                                                break
                                            }
                                            if (V)
                                            {
                                                if (U == ",")
                                                {
                                                    U = u();
                                                    if (U == "}")
                                                    {
                                                        C()
                                                    }
                                                }
                                                else
                                                {
                                                    C()
                                                }
                                            }
                                            if (U == "," || typeof U != "string" || (A ? U.charAt(0) : U[0]) != "@" || u() != ":")
                                            {
                                                C()
                                            }
                                            T[U.slice(1)] = P(u())
                                        }
                                        return T
                                    }
                                }
                                C()
                            }
                            return U
                        };
                        var J = function (V, U, W)
                        {
                            var T = s(V, U, W);
                            if (T === F)
                            {
                                delete V[U]
                            }
                            else
                            {
                                V[U] = T
                            }
                        };
                        var s = function (W, V, X)
                        {
                            var U = W[V], T;
                            if (typeof U == "object" && U)
                            {
                                if (q.call(U) == z)
                                {
                                    for (T = U.length; T--;)
                                    {
                                        J(U, T, X)
                                    }
                                }
                                else
                                {
                                    h(U, function (Y)
                                    {
                                        J(U, Y, X)
                                    })
                                }
                            }
                            return X.call(W, V, U)
                        };
                        L.parse = function (V, W)
                        {
                            var T, U;
                            B = 0;
                            Q = "" + V;
                            T = P(u());
                            if (u() != "$")
                            {
                                C()
                            }
                            B = Q = null;
                            return W && q.call(W) == N ? s((U = {}, U[""] = T, U), "", W) : T
                        }
                    }
                }
                if (D)
                {
                    d(function ()
                    {
                        return L
                    })
                }
            }(this))
        }, {}], 42: [function (f, h, e)
        {
            h.exports = g;
            function g(l, j)
            {
                var m = [];
                j = j || 0;
                for (var k = j || 0; k < l.length; k++)
                {
                    m[k - j] = l[k]
                }
                return m
            }
        }, {}]
    }, {}, [1])(1)
});

//--------------module:im.core-------------

var IM = (function (h)
{
    var j = "http://42.62.79.105:8080/api";
    var b = "http://webim.gotye.com.cn:9092";
    var t;
    var f;
    var c = new Base64();
    var a = 0;
    var g = false;
    var r = null;
    var m = /^(.*)\.(jpg|jpeg|png|bmp)$/;
    var k = 1024 * 300;
    var o = 1000;
    var s = 1024 * 5;
    var e = 200;
    var p = null;
    var l = {};
    var q;
    var i = function ()
    {
    };

    function n()
    {
    }

    function d()
    {
        if (!!a)
        {
            return
        }
        var u = {appkey: t, account: c.encode(r.account), pwd: r.pwd};
        a = 1;
        Tool.log("login user :" + r.account);
        q.emit("login", u, function (w, x)
        {
            Tool.log("login resp status:" + w);
            if ((IM_STATUS.SUCCESS == w || IM_STATUS.LOGIN.RE_LOGIN_ERROR == w))
            {
                a = 2;
                f = x.key;
                var v = (x.apiURL || "").replace(/ +/g, "");
                if (!!v && v.indexOf("AppPortlet") == -1)
                {
                    j = v
                }
                r.succCall.call(h, w)
            }
            else
            {
                r.failCall.call(h, w);
                q.disconnect()
            }
        })
    }

    i.prototype = {
        bind: function (u)
        {
            if (!u)
            {
                Tool.log("appkey is null")
            }
            else
            {
                t = u
            }
            l.connectEvents = new Array();
            l.disconnectEvents = new Array();
            l.beKillEvents = new Array();
            l.connectTimeoutEvents = new Array();
            l.connectErrorEvents = new Array();
            var w = l.messageEvents = {};
            w[IM_CONSTANT.CHAT_TYPE.USER] = new Array();
            w[IM_CONSTANT.CHAT_TYPE.GROUP] = new Array();
            w[IM_CONSTANT.CHAT_TYPE.ROOM] = new Array();
            var v = l.notify = {};
            v[IM_CONSTANT.NOTIFY.GROUP.LEAVE] = new Array();
            v[IM_CONSTANT.NOTIFY.GROUP.ENTER] = new Array();
            v[IM_CONSTANT.NOTIFY.GROUP.KICKOUT] = new Array();
            v[IM_CONSTANT.NOTIFY.GROUP.DISMISS] = new Array()
        }, setAppkey: function (u)
        {
            if (!u)
            {
                Tool.log("appkey is null");
                return
            }
            t = u
        }, _connectToServer: function (v)
        {
            if (g)
            {
                return
            }
            if (!q)
            {
                var u = this;
                Ajax.request(j + "/GetWebImUrl", {
                    data: "appkey=" + t, method: "GET", success: function (w)
                    {
                        if (w.status == IM_STATUS.SUCCESS)
                        {
                            q = io.connect(w.serverPath);
                            u._bindEvents();
                            u.onConnect.call(this, v)
                        }
                    }, failure: function ()
                    {
                        q = io.connect(b);
                        u._bindEvents();
                        u.onConnect.call(this, v)
                    }
                })
            }
            else
            {
                this.connect()
            }
        }, _bindEvents: function ()
        {
            var w = l.messageEvents;
            var v = l.notify;
            var u = this;
            q.on("connect", function ()
            {
                Tool.log("Fired upon a successful connection!");
                g = true;
                for (var x = 0, y; y = l.connectEvents[x++];)
                {
                    y.call(h)
                }
            });
            q.on("disconnect", function ()
            {
                Tool.log("Fired upon a disconnection");
                g = false;
                a = 0;
                for (var x = 0, y; y = l.disconnectEvents[x++];)
                {
                    y.call(h)
                }
            });
            q.on("beKill", function ()
            {
                a = 0;
                r = null;
                Tool.log("onForceLogout");
                for (var x = 0, y; y = l.beKillEvents[x++];)
                {
                    y.call(h)
                }
                u.disconnect()
            });
            q.on("leaveGroup", function (y)
            {
                Tool.log("leaveGroup notify");
                y.eventType = IM_CONSTANT.NOTIFY.GROUP.LEAVE;
                for (var x = 0, z; z = v[IM_CONSTANT.NOTIFY.GROUP.LEAVE][x++];)
                {
                    z.call(h, y)
                }
            });
            q.on("enterGroup", function (y)
            {
                Tool.log("enterGroup notify");
                y.eventType = IM_CONSTANT.NOTIFY.GROUP.ENTER;
                for (var x = 0, z; z = v[IM_CONSTANT.NOTIFY.GROUP.ENTER][x++];)
                {
                    z.call(h, y)
                }
            });
            q.on("kickOutGroup", function (y)
            {
                Tool.log("kickOutGroup notify");
                y.eventType = IM_CONSTANT.NOTIFY.GROUP.KICKOUT;
                for (var x = 0, z; z = v[IM_CONSTANT.NOTIFY.GROUP.KICKOUT][x++];)
                {
                    z.call(h, y)
                }
            });
            q.on("dismissGroup", function (y)
            {
                Tool.log("dismissGroup notify");
                y.eventType = IM_CONSTANT.NOTIFY.GROUP.DISMISS;
                for (var x = 0, z; z = v[IM_CONSTANT.NOTIFY.GROUP.DISMISS][x++];)
                {
                    z.call(h, y)
                }
            });
            q.on("message", function (y)
            {
                Tool.log("receiver msg");
                if (IM_CONSTANT.MSG_TYPE.TEXT === y.msgType || IM_CONSTANT.MSG_TYPE.USER_DEFINED === y.msgType)
                {
                    y.content = c.decode(y.content || "");
                    y.extraData = c.decode(y.extraData || "")
                }
                else
                {
                    if (IM_CONSTANT.MSG_TYPE.SMALL_IMG === y.msgType)
                    {
                        y.thumb = "data:image/jpeg;base64," + y.text || ""
                    }
                    else
                    {
                        if (IM_CONSTANT.MSG_TYPE.IMG === y.msgType)
                        {
                            if (y.url.indexOf("/") === 0)
                            {
                                y.url = j + y.url
                            }
                            else
                            {
                                if (y.url.indexOf("GetFile") === 0)
                                {
                                    y.url = j + "/" + y.url
                                }
                            }
                            y.thumb = "data:image/jpeg;base64," + y.thumb
                        }
                        else
                        {
                            if (IM_CONSTANT.MSG_TYPE.VOICE === y.msgType)
                            {
                                y.url = j + "GetFile?t=mp3&FileID=" + c.decode(y.text)
                            }
                        }
                    }
                }
                if (y.text)
                {
                    delete y.text
                }
                for (var x = 0, z; z = w[y.chatType][x++];)
                {
                    z.call(h, y)
                }
            });
            q.on("connect_timeout", function ()
            {
                Tool.log("Fired upon a connection timeout!");
                for (var x = 0, y; y = l.connectTimeoutEvents[x++];)
                {
                    y.call(h)
                }
            });
            q.on("connect_error", function ()
            {
                Tool.log("Fired upon a connection error!");
                for (var x = 0, y; y = l.connectErrorEvents[x++];)
                {
                    y.call(h)
                }
            })
        }, sendAny: function (w, v, u)
        {
            if (!Tool.isObj(w))
            {
                w = {receiverId: arguments[0], content: arguments[1]};
                v = arguments[2];
                u = arguments[3]
            }
            w.msgType = IM_CONSTANT.MSG_TYPE.USER_DEFINED;
            this.send(w, v, u)
        }, sendNotify: function (w, v, u)
        {
            w.msgType = IM_CONSTANT.MSG_TYPE.NOTIFY;
            this.send(w, v, u)
        }, sendText: function (w, v, u)
        {
            if (!Tool.isObj(w))
            {
                w = {receiverId: arguments[0], content: arguments[1]};
                v = arguments[2];
                u = arguments[3]
            }
            w.msgType = IM_CONSTANT.MSG_TYPE.TEXT;
            this.send(w, v, u)
        }, sendImg: function (w, v, A)
        {
            var z = w.chatType;
            var x = w.fileId;
            if (z != 0 && z != 1 && z != 2)
            {
                Tool.log("invaild chatType");
                return
            }
            if (!w.receiverId)
            {
                Tool.log("receiverId is null");
                return
            }
            v = v || n;
            A = A || n;
            if (!window.FileReader)
            {
                A.call(h, IM_STATUS.MSG.BROWER_NOT_SUPPORT);
                return
            }
            var u = document.getElementById(x);
            var y = u.files[0];
            var B = new FileReader();
            var C = this;
            B.onloadend = function (G)
            {
                var E = new Image();
                E.src = G.target.result;
                var F = {type: 1};
                var D = "";
                if (G.total > k)
                {
                    E.src = Tool.compress(E, o, 0.8)
                }
                D = E.src;
                if (G.total < s)
                {
                    D = E.src
                }
                else
                {
                    D = Tool.compress(E, e, 0.8)
                }
                F.file = E.src.substr(E.src.indexOf("base64,") + 7);
                Ajax.request(j + "/UploadFile", {
                    data: F, header: {UKEY: f}, success: function (H)
                    {
                        if (H.status == IM_STATUS.SUCCESS)
                        {
                            w.url = H.url;
                            w.thumb = D.substr(D.indexOf("base64,") + 7);
                            w.msgType = IM_CONSTANT.MSG_TYPE.IMG;
                            w.content = "";
                            C.send(w, function (I)
                            {
                                v.call(h, I.status, j + w.url, D)
                            }, A)
                        }
                        else
                        {
                            A.call(h, H.status)
                        }
                    }
                })
            };
            B.readAsDataURL(y)
        }, send: function (x, v, u)
        {
            if (!a)
            {
                Tool.log("No login");
                return
            }
            x.chatType = x.chatType || IM_CONSTANT.CHAT_TYPE.USER;
            if (!x.receiverId)
            {
                Tool.log("receiverId is null");
                return
            }
            var w = this;
            if (p == null || p == "")
            {
                this._getKeyword(function ()
                {
                    w._sendMsgDealKeyWords(x, v, u)
                })
            }
            else
            {
                w._sendMsgDealKeyWords(x, v, u)
            }
        }, _sendMsgDealKeyWords: function (A, z, w)
        {
            var v = A.content;
            if (p != null && p.length > 0)
            {
                var u = p.length;
                for (var x = 0; x < u; x++)
                {
                    var y = new RegExp(p[x], "gi");
                    v = v.replace(y, "**")
                }
            }
            A.content = v;
            A.receiverId = c.encode(A.receiverId);
            A.msgType = A.msgType || IM_CONSTANT.MSG_TYPE.TEXT;
            if (!A.content && A.msgType != IM_CONSTANT.MSG_TYPE.IMG)
            {
                Tool.log("content is null");
                return
            }
            A.content = c.encode(A.content);
            A.extraData = c.encode(A.extraData || "");
            z = z || n;
            w = w || n;
            q.emit("message", A, function (B)
            {
                if (IM_STATUS.SUCCESS == B)
                {
                    z.call(h, B)
                }
                else
                {
                    w.call(h, B)
                }
            })
        }, _getKeyword: function (u)
        {
            Ajax.request(j + "/GetKeyword", {
                method: "POST", header: {UKEY: f}, success: function (w)
                {
                    if (w.status == IM_STATUS.SUCCESS)
                    {
                        var v = w.keyword;
                        if (v != null && v.length > 0)
                        {
                            p = v.split(",")
                        }
                    }
                    u.call()
                }
            })
        }, onMsg: function (w, x)
        {
            if (arguments.length == 1 && Tool.isFun(arguments[0]))
            {
                if (!arguments[0])
                {
                    return
                }
                l.messageEvents[IM_CONSTANT.CHAT_TYPE.USER].push(arguments[0])
            }
            else
            {
                if (arguments.length == 2)
                {
                    if (!x)
                    {
                        return
                    }
                    if (Tool.isArray(w))
                    {
                        for (var v in w)
                        {
                            var u = w[v];
                            if (!!l.messageEvents[u])
                            {
                                l.messageEvents[u].push(x)
                            }
                        }
                    }
                    else
                    {
                        if (!!l.messageEvents[u])
                        {
                            l.messageEvents[u].push(x)
                        }
                    }
                }
            }
        }, onNotify: function (u, v)
        {
            if (u == null || !l.notify[u])
            {
                Tool.log("invail notifyType");
                return
            }
            l.notify[u].push(v)
        }, login: function (w, x, v, u)
        {
            if (a)
            {
                Tool.log("has logined");
                return
            }
            if (!t)
            {
                Tool.log("no bind appkey ");
                return
            }
            if (!w)
            {
                Tool.log("account is null");
                return
            }
            r = {account: w, pwd: x, succCall: v || n, failCall: u || n};
            this._connectToServer.call(this, d)
        }, logout: function (v, u)
        {
            if (!a)
            {
                return
            }
            v = v || n;
            u = u || n;
            var w = this;
            q.emit("logout", function (x)
            {
                a = 0;
                r = null;
                if (IM_STATUS.SUCCESS == x)
                {
                    v.call(h, x)
                }
                else
                {
                    u.call(h, x)
                }
                w.disconnect()
            })
        }, isLogined: function ()
        {
            return !a
        }, connect: function ()
        {
            q.connect()
        }, disconnect: function ()
        {
            q.disconnect()
        }, onForceLogout: function (u)
        {
            if (!u)
            {
                return
            }
            l.beKillEvents.push(u)
        }, onConnect: function (u)
        {
            if (!u)
            {
                return
            }
            l.connectEvents.push(u)
        }, onConnectError: function (u)
        {
            if (!u)
            {
                return
            }
            l.connectErrorEvents.push(u)
        }, onConnectTimeout: function (u)
        {
            if (!u)
            {
                return
            }
            l.connectTimeoutEvents.push(u)
        }, onDisconnect: function (u)
        {
            if (!u)
            {
                return
            }
            l.disconnectEvents.push(u)
        }
    };
    i.prototype.res = {
        getOfflineMsgs: function (w, v, u)
        {
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            if (!Tool.isObj(w))
            {
                w = {targetId: arguments[0], count: arguments[1]};
                v = arguments[2];
                u = arguments[3]
            }
            v = v || n;
            u = u || n;
            if (!w.targetId || !w.count)
            {
                Tool.log("param is null");
                u.call(h, IM_STATUS.PARAM_IS_NULL);
                return
            }
            w.version = "1.0";
            w.isAppReq = 0;
            w.chatType = w.chatType || IM_CONSTANT.CHAT_TYPE.USER;
            Ajax.request(j + "/GetOfflineMsgs", {
                data: w, header: {UKEY: f}, success: function (A)
                {
                    if (A.status == IM_STATUS.SUCCESS)
                    {
                        var y = [];
                        for (var z = A.entities.length - 1; z >= 0; z--)
                        {
                            var B = A.entities[z];
                            var x = {
                                senderAccount: B.senderAccount,
                                sendAccount: B.senderAccount,
                                msgType: B.msgType,
                                sendTimeMs: B.sendTime,
                                sendTime: B.sendTime / 1000,
                                content: c.decode(B.msgContent),
                                extraData: c.decode(B.extraData),
                                receiverType: B.receiverType,
                                receiverId: B.receiverId
                            };
                            if (B.msgType == IM_CONSTANT.MSG_TYPE.IMG || B.msgType == IM_CONSTANT.MSG_TYPE.VOICE)
                            {
                                x.content = j + x.content
                            }
                            y.push(x)
                        }
                        v.call(h, y)
                    }
                    else
                    {
                        u.call(h, A.status)
                    }
                }
            })
        }, getMsgs: function (w, v, u)
        {
            if (!Tool.isObj(w))
            {
                w = {senderAccount: r.account, endTime: arguments[1], receiverType: IM_CONSTANT.CHAT_TYPE.USER, receiverId: arguments[0], index: arguments[2], count: arguments[3]};
                v = arguments[4];
                u = arguments[5]
            }
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            if (w.receiverType !== 0 && w.receiverType !== 1 && w.receiverType !== 2)
            {
                Tool.log("invaild receiverType");
                u.call(h, IM_STATUS.PARAM_IS_NULL);
                return
            }
            if (!w.receiverId)
            {
                Tool.log("receiverId is null");
                u.call(h, IM_STATUS.PARAM_IS_NULL);
                return
            }
            w.version = "1.0";
            w.isAppReq = 0;
            Ajax.request(j + "/GetMsgs", {
                data: w, header: {UKEY: f}, success: function (A)
                {
                    if (A.status == IM_STATUS.SUCCESS)
                    {
                        var y = [];
                        for (var z = A.entities.length - 1; z >= 0; z--)
                        {
                            var B = A.entities[z];
                            var x = {
                                senderAccount: B.senderAccount,
                                sendAccount: B.senderAccount,
                                msgType: B.msgType,
                                sendTimeMs: B.sendTime,
                                sendTime: B.sendTime / 1000,
                                content: c.decode(B.msgContent),
                                extraData: c.decode(B.extraData),
                                receiverType: B.receiverType,
                                receiverId: B.receiverId
                            };
                            if (B.msgType == IM_CONSTANT.MSG_TYPE.IMG || B.msgType == IM_CONSTANT.MSG_TYPE.VOICE)
                            {
                                x.content = j + x.content
                            }
                            y.push(x)
                        }
                        if (v)
                        {
                            v.call(h, y)
                        }
                    }
                    else
                    {
                        if (u)
                        {
                            u.call(h, A.status)
                        }
                    }
                }
            })
        }, getContacts: function (v, u)
        {
            return this.getChatSessions(function (z)
            {
                var x = new Array();
                for (var w = 0, y; y = z[w++];)
                {
                    if (y.chatType == IM_CONSTANT.CHAT_TYPE.USER)
                    {
                        x.push(y)
                    }
                }
                v.call(h, x)
            }, u)
        }, getChatSessions: function (w, v, u)
        {
            if (arguments.length <= 2 && !Tool.isObj(w))
            {
                v = arguments[0];
                u = arguments[1]
            }
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            w.isAppReq = 0;
            Ajax.request(j + "/GetChatSessions", {
                data: w, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        if (v)
                        {
                            for (var x = 0, z; z = y.entities[x++];)
                            {
                                if (z.chatType == IM_CONSTANT.CHAT_TYPE.USER)
                                {
                                    z.account = z.targetId
                                }
                                else
                                {
                                    if (z.chatType == IM_CONSTANT.CHAT_TYPE.GROUP)
                                    {
                                        z.groupId = z.targetId
                                    }
                                    else
                                    {
                                        if (z.chatType == IM_CONSTANT.CHAT_TYPE.ROOM)
                                        {
                                            z.roomId = z.targetId
                                        }
                                    }
                                }
                            }
                            v.call(h, y.entities)
                        }
                    }
                    else
                    {
                        if (u)
                        {
                            u.call(h, y.status)
                        }
                    }
                }
            })
        }, delContacts: function (w, x, u)
        {
            if (!w)
            {
                Tool.log("param is null");
                u.call(h, IM_STATUS.PARAM_IS_NULL);
                return
            }
            var y = new Array();
            if (Tool.isArray(w))
            {
                for (var v in w)
                {
                    y.push({targetId: w[v], chatType: IM_CONSTANT.CHAT_TYPE.USER})
                }
            }
            else
            {
                y.push({targetId: w, chatType: IM_CONSTANT.CHAT_TYPE.USER})
            }
            return this.delChatSessions(w, x, u)
        }, delChatSessions: function (x, v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            if (!x)
            {
                Tool.log("param is null");
                u.call(h, IM_STATUS.PARAM_IS_NULL);
                return
            }
            var w = {chatSessions: x, isAppReq: 0};
            Ajax.request(j + "/DelChatSessions", {
                data: w, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        if (v)
                        {
                            v.call(h)
                        }
                    }
                    else
                    {
                        if (u)
                        {
                            u.call(h, y.status)
                        }
                    }
                }
            })
        }, getGroups: function (v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var w = {isAppReq: 0};
            Ajax.request(j + "/GetGroups", {
                data: w, header: {UKEY: f}, success: function (z)
                {
                    if (z.status == IM_STATUS.SUCCESS)
                    {
                        var x = Tool.toHttp(j);
                        for (var y = 0, A; A = z.entities[y++];)
                        {
                            if (!!A.groupHead)
                            {
                                A.groupHead = x + "/" + A.groupHead
                            }
                        }
                        v.call(h, z.entities)
                    }
                    else
                    {
                        u.call(h, z.status)
                    }
                }
            })
        }, searchGroups: function (w, v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            w.isAppReq = 0;
            Ajax.request(j + "/SearchGroup", {
                data: w, header: {UKEY: f}, success: function (z)
                {
                    if (z.status == IM_STATUS.SUCCESS)
                    {
                        var x = Tool.toHttp(j);
                        for (var y = 0, A; A = z.entities[y++];)
                        {
                            A.groupHead = x + "/" + A.groupHead
                        }
                        v.call(h, z.entities)
                    }
                    else
                    {
                        u.call(h, z.status)
                    }
                }
            })
        }, getGroup: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {groupId: v};
            x.isAppReq = 0;
            Ajax.request(j + "/GetGroup", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.entity)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }, getGroupMembers: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {groupId: v};
            x.isAppReq = 0;
            Ajax.request(j + "/GetGroup", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.entities)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }, enterGroup: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {groupId: v};
            x.isAppReq = 0;
            Ajax.request(j + "/EnterGroup", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.affectedRows)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }, leaveGroup: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {groupId: v};
            x.isAppReq = 0;
            Ajax.request(j + "/LeaveGroup", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.affectedRows)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }, dismissGroup: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {groupId: v};
            x.isAppReq = 0;
            Ajax.request(j + "/DismissGroup", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.affectedRows)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }, addGroupMember: function (w, v, x, u)
        {
            x = x || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var y = {groupId: w, userAccount: v};
            y.isAppReq = 0;
            Ajax.request(j + "/AddGroupMember", {
                data: y, header: {UKEY: f}, success: function (z)
                {
                    if (z.status == IM_STATUS.SUCCESS)
                    {
                        x.call(h, z.affectedRows)
                    }
                    else
                    {
                        u.call(h, z.status)
                    }
                }
            })
        }, delGroupMember: function (w, v, x, u)
        {
            x = x || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var y = {groupId: w, userAccount: v};
            y.isAppReq = 0;
            Ajax.request(j + "/DelGroupMember", {
                data: y, header: {UKEY: f}, success: function (z)
                {
                    if (z.status == IM_STATUS.SUCCESS)
                    {
                        x.call(h, z.affectedRows)
                    }
                    else
                    {
                        u.call(h, z.status)
                    }
                }
            })
        }, createGroup: function (w, v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            w.isAppReq = 0;
            Ajax.request(j + "/CreateGroup", {
                data: w, header: {UKEY: f}, success: function (x)
                {
                    if (x.status == IM_STATUS.SUCCESS)
                    {
                        v.call(h, x.entity)
                    }
                    else
                    {
                        u.call(h, x.status)
                    }
                }
            })
        }, getFriends: function (v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var w = {};
            w.isAppReq = 0;
            Ajax.request(j + "/GetFriends", {
                data: w, header: {UKEY: f}, success: function (x)
                {
                    if (x.status == IM_STATUS.SUCCESS)
                    {
                        v.call(h, x.entities)
                    }
                    else
                    {
                        u.call(h, x.status)
                    }
                }
            })
        }, getBlacklists: function (v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var w = {};
            w.isAppReq = 0;
            Ajax.request(j + "/GetBlacklists", {
                data: w, header: {UKEY: f}, success: function (x)
                {
                    if (x.status == IM_STATUS.SUCCESS)
                    {
                        v.call(h, x.entities)
                    }
                    else
                    {
                        u.call(h, x.status)
                    }
                }
            })
        }, searchUsers: function (w, v, u)
        {
            v = v || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            w.isAppReq = 0;
            Ajax.request(j + "/SearchUser", {
                data: w, header: {UKEY: f}, success: function (x)
                {
                    if (x.status == IM_STATUS.SUCCESS)
                    {
                        v.call(h, x.userList)
                    }
                    else
                    {
                        u.call(h, x.status)
                    }
                }
            })
        }, addFriend: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {friendAccount: v};
            x.isAppReq = 0;
            Ajax.request(j + "/AddFriend", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.affectedRows)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }, addBlacklist: function (v, w, u)
        {
            w = w || n;
            u = u || n;
            if (!a)
            {
                Tool.log("No login");
                u.call(h, IM_STATUS.NO_LOGIN);
                return
            }
            var x = {friendAccount: v};
            x.isAppReq = 0;
            Ajax.request(j + "/AddBlacklist", {
                data: x, header: {UKEY: f}, success: function (y)
                {
                    if (y.status == IM_STATUS.SUCCESS)
                    {
                        w.call(h, y.affectedRows)
                    }
                    else
                    {
                        u.call(h, y.status)
                    }
                }
            })
        }
    };
    return new i()
})(window);

//--------------module:web.cache-------------

var UI_EVENT_MGR = (function (e)
{
    var a = {};
    var b = {};
    var d = {};
    a.MSGS = {};
    a.CHATSESSIONS = {};
    a.GROUPS = {};
    a.FRIENDS = {};
    a.BLACKLIST = {};
    a.MSGS[IM_CONSTANT.CHAT_TYPE.USER] = {};
    a.MSGS[IM_CONSTANT.CHAT_TYPE.GROUP] = {};
    a.EVENT = {
        MSG: c,
        CHAT_SESSION: c,
        CHAT_BOX_TITLE: c,
        SEND_BOX: c,
        CHAT_GROUP: c,
        CHAT_FRIENDS: c,
        CHAT_BLACKLIST: c,
        SEARCH_BOX: c,
        CHANGE_TITLE_BOX: c,
        ENTERSESSION: c,
        LEAVESESSION: c,
        SCROLLSTATUS: c
    };
    var c = function ()
    {
    };
    return {
        env: (function (f)
        {
            f.env = {appkey: "", logingUser: "", curChatSession: ""};
            return {
                set: function (h)
                {
                    for (var g in h)
                    {
                        f.env[g] = h[g]
                    }
                }, get: function (g)
                {
                    if (!g)
                    {
                        return f.env
                    }
                    return f.env[g]
                }
            }
        })(a), on: function (f, g)
        {
            if (Tool.isFun(g))
            {
                a.EVENT[f] = g
            }
            else
            {
                a.EVENT[f].call(e, g)
            }
        }, addMsg: function (l)
        {
            if (!Tool.isArray(l))
            {
                l = [l]
            }
            var k = $("#account").val();
            for (var h in l)
            {
                var m = l[h];
                var j = m.receiverType;
                if (j == undefined)
                {
                    j = m.chatType
                }
                var f;
                var g = [];
                m.sendTime = UI_Util.getFormatTime(m.sendTime * 1000);
                if (j == IM_CONSTANT.CHAT_TYPE.USER)
                {
                    if (m.senderAccount == k)
                    {
                        f = m.receiverId
                    }
                    else
                    {
                        f = m.senderAccount
                    }
                }
                else
                {
                    if (j == IM_CONSTANT.CHAT_TYPE.GROUP)
                    {
                        f = m.receiverId
                    }
                }
                g = a.MSGS[j][f];
                if (!g)
                {
                    g = new Array()
                }
                g.push(m);
                a.MSGS[j][f] = g
            }
        }, getMsgs: function (h, f)
        {
            var g = a.MSGS[h][f];
            if (g)
            {
                g.sort(function (j, i)
                {
                    if (j.sendTimeMs < i.sendTimeMs)
                    {
                        return -1
                    }
                    if (j.sendTimeMs > i.sendTimeMs)
                    {
                        return 1
                    }
                });
                return g
            }
            return new Array()
        }, getLastMsg: function (h, f)
        {
            var i = {};
            var g = a.MSGS[h][f];
            if (g)
            {
                i.content = g[g.length - 1].content;
                i.msgTime = UI_Util.getFormatTime(g[g.length - 1].sendTimeMs);
                return i
            }
            return i
        }, addChatSessions: function (j)
        {
            if (!Tool.isArray(j))
            {
                j = [j]
            }
            for (var h in j)
            {
                var f = j[h];
                var g = f.chatType + "_" + f.targetId;
                f.status = 0;
                f.level = f.msgNumber;
                f.lastChatTime = UI_Util.getFormatTime(f.lastChatTime);
                a.CHATSESSIONS[g] = f
            }
        }, setChatSession: function (f)
        {
            var g = f.chatType + "_" + f.targetId;
            if (!a.CHATSESSIONS[g])
            {
                f.msgNumber = 1;
                a.CHATSESSIONS[g] = f
            }
            else
            {
                var h = a.CHATSESSIONS[g];
                if (f.msgNumber != null)
                {
                    h.msgNumber = f.msgNumber
                }
                if (f.lastMsg)
                {
                    h.lastMsg = f.lastMsg
                }
                if (f.msgTime)
                {
                    h.msgTime = f.msgTime
                }
                if (f.status != null)
                {
                    h.status = f.status
                }
                if (f.level != null)
                {
                    h.level = f.level
                }
            }
            this.on("CHAT_SESSION")
        }, findChatSessionByStatus: function (f)
        {
            var i = new Array();
            for (var h in a.CHATSESSIONS)
            {
                var g = a.CHATSESSIONS[h];
                if (g.status == f)
                {
                    i.push(g)
                }
            }
            return i
        }, getChatSessions: function ()
        {
            var g = new Array();
            for (var f in a.CHATSESSIONS)
            {
                g.push(a.CHATSESSIONS[f])
            }
            g.sort(function (i, h)
            {
                if (i.level < h.level)
                {
                    return 1
                }
                if (i.level > h.level)
                {
                    return -1
                }
                if (i.level == h.level)
                {
                    return 0
                }
            });
            return g
        }, getChatSession: function (f)
        {
            return a.CHATSESSIONS[f]
        }, addGroups: function (f)
        {
            if (!Tool.isArray(f))
            {
                f = [f]
            }
            for (var h in f)
            {
                var j = f[h];
                var g = j.groupId;
                a.GROUPS[g] = j
            }
        }, getGroups: function ()
        {
            var f = new Array();
            for (var g in a.GROUPS)
            {
                f.push(a.GROUPS[g])
            }
            return f
        }, addFriends: function (h)
        {
            if (!Tool.isArray(h))
            {
                h = [h]
            }
            for (var g in h)
            {
                var j = h[g];
                var f = j.account;
                a.FRIENDS[f] = j
            }
        }, getFriends: function ()
        {
            var g = new Array();
            for (var f in a.FRIENDS)
            {
                g.push(a.FRIENDS[f])
            }
            return g
        }, addBlackList: function (f)
        {
            if (!Tool.isArray(f))
            {
                f = [f]
            }
            for (var h in f)
            {
                var j = f[h];
                var g = j.account;
                a.BLACKLIST[g] = j
            }
        }, getBlackList: function ()
        {
            var f = new Array();
            for (var g in a.BLACKLIST)
            {
                f.push(a.BLACKLIST[g])
            }
            return f
        }, cleanCache: function ()
        {
            for (var f in a)
            {
                if (f != "EVENT")
                {
                    a[f] = {}
                }
            }
            a.MSGS[IM_CONSTANT.CHAT_TYPE.USER] = {};
            a.MSGS[IM_CONSTANT.CHAT_TYPE.GROUP] = {}
        }
    }
})(window);
var UI_Util = {
    getFormatTime: function (b)
    {
        if (!b || b == undefined)
        {
            return ""
        }
        var a = new Date(b);
        return a.getHours() + ":" + a.getMinutes() + ":" + a.getSeconds()
    }
};

//--------------module:web.db-------------

var WebDB = (function (e)
{
    var a = new Base64();

    function d(f, h)
    {
        var g = 1;
        var i = new Date();
        i.setTime(i.getTime() + g * 24 * 60 * 60 * 1000);
        document.cookie = f + "=" + escape(h) + ";expires=" + i.toGMTString()
    }

    function b(g)
    {
        var f, h = new RegExp("(^| )" + g + "=([^;]*)(;|$)");
        if (f = document.cookie.match(h))
        {
            return unescape(f[2])
        }
        else
        {
            return null
        }
    }

    function c(f)
    {
        var h = new Date();
        h.setTime(h.getTime() - 1);
        var g = b(f);
        if (g != null)
        {
            document.cookie = f + "= ;expires=" + h.toGMTString()
        }
    }

    return {
        on: function (f, g)
        {
            if (Tool.isFun(g))
            {
                dbMap[f] = g
            }
            else
            {
                dbMap[f].call(e)
            }
        }, put: function (f, g)
        {
            g = JSON.stringify(g);
            g = a.encode(g);
            d(f, g)
        }, get: function (f)
        {
            if (!f)
            {
                return ""
            }
            var g = b(f);
            if (!g)
            {
                return null
            }
            g = a.decode(g);
            var h = Tool.parseJSON(g);
            return h
        }, clean: function (f)
        {
            c(f)
        }
    }
})(window);

//--------------module:ejs-------------

(function (d)
{
    this.dom = {
        quote: window.JSON && JSON.stringify || String.quote || function (i)
        {
            i = i.replace(/[\x00-\x1f\\]/g, function (k)
            {
                var j = h[k];
                return j ? j : "\\u" + ("0000" + k.charCodeAt(0).toString(16)).slice(-4)
            });
            return '"' + i.replace(/"/g, '\\"') + '"'
        }
    };
    if (!String.prototype.trim)
    {
        String.prototype.trim = function ()
        {
            return this.replace(/^[\s\xa0]+|[\s\xa0]+$/g, "")
        }
    }
    var h = {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", "\\": "\\\\"}, c = "\t__views.push(", a = ");\n", f = "&>", e = /\s*<&\s*/, g = /\s*&>\s*/;
    var b = dom.ejs = function (u, D)
    {
        if (!b[u])
        {
            var t = e, C = g, j = f, z = c, q = a, w, y, m = d.getElementById(u);
            if (!m)
            {
                throw"can not find the target element"
            }
            w = m.text;
            var k = w.trim().split(t), B = ["var __views = [];\n"], x = 0, v = k.length, p, l;
            while (x < v)
            {
                l = k[x++];
                p = l.split(C);
                if (~l.indexOf(j))
                {
                    switch (p[0].charAt(0))
                    {
                        case"=":
                            y = p[0].substring(1);
                            B.push(z, y, q);
                            break;
                        case"#":
                            break;
                        default:
                            y = p[0];
                            B.push(y, "\n")
                    }
                    p[1] && B.push(z, dom.quote.call(null, p[1]), q)
                }
                else
                {
                    B.push(z, dom.quote.call(null, p[0]), q)
                }
            }
            var s = [], o = [];
            for (x in D)
            {
                s.push(x);
                o.push(D[x])
            }
            s.push(B.concat(" return __views.join('');").join(""));
            try
            {
                return (b[u] = Function.apply(0, s)).apply(0, o)
            } catch (A)
            {
                if (console)
                {
                    console.warn(A.message)
                }
                return ""
            }
        }
        var r = [];
        for (x in D)
        {
            r.push(D[x])
        }
        try
        {
            return b[u].apply(0, r)
        } catch (A)
        {
            if (console)
            {
                console.warn(A.message)
            }
            return ""
        }
    }
})(document);

//--------------module:mousewheel-------------

/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 *
 * Requires: 1.2.2+
 */
(function (f)
{
    function g(a)
    {
        var n = a || window.event, m = [].slice.call(arguments, 1), l = 0, k = !0, j = 0, i = 0;
        return a = f.event.fix(n), a.type = "mousewheel", n.wheelDelta && (l = n.wheelDelta / 120), n.detail && (l = -n.detail / 3), i = l, n.axis !== undefined && n.axis === n.HORIZONTAL_AXIS && (i = 0, j = -1 * l), n.wheelDeltaY !== undefined && (i = n.wheelDeltaY / 120), n.wheelDeltaX !== undefined && (j = -1 * n.wheelDeltaX / 120), m.unshift(a, l, j, i), (f.event.dispatch || f.event.handle).apply(this, m)
    }

    var e = ["DOMMouseScroll", "mousewheel"];
    if (f.event.fixHooks)
    {
        for (var h = e.length; h;)
        {
            f.event.fixHooks[e[--h]] = f.event.mouseHooks
        }
    }
    f.event.special.mousewheel = {
        setup: function ()
        {
            if (this.addEventListener)
            {
                for (var b = e.length; b;)
                {
                    this.addEventListener(e[--b], g, !1)
                }
            }
            else
            {
                this.onmousewheel = g
            }
        }, teardown: function ()
        {
            if (this.removeEventListener)
            {
                for (var b = e.length; b;)
                {
                    this.removeEventListener(e[--b], g, !1)
                }
            }
            else
            {
                this.onmousewheel = null
            }
        }
    }, f.fn.extend({
        mousewheel: function (b)
        {
            return b ? this.bind("mousewheel", b) : this.trigger("mousewheel")
        }, unmousewheel: function (b)
        {
            return this.unbind("mousewheel", b)
        }
    })
})(jQuery);

//--------------module:qqFace-------------

jQuery.browser = {};
(function ()
{
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./))
    {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1
    }
})();
(function (a)
{
    a.fn.qqFace = function (c)
    {
        var g = {id: "facebox", path: "face/", assign: "content", tip: "em_"};
        var d = a.extend(g, c);
        var b = a("#" + d.assign);
        var h = d.id;
        var f = d.path;
        var e = d.tip;
        if (b.length <= 0)
        {
            alert("缺少表情赋值对象。");
            return false
        }
        a(this).click(function (l)
        {
            var o, n;
            if (a("#" + h).length <= 0)
            {
                o = '<div id="' + h + '" style="position:absolute;display:none;z-index:1000;" class="qqFace"><table border="0" cellspacing="0" cellpadding="0"><tr>';
                for (var j = 1; j <= 75; j++)
                {
                    n = "[" + e + j + "]";
                    o += '<td><img src="' + f + j + '.gif" onclick="$(\'#' + d.assign + "').setCaret();$('#" + d.assign + "').insertAtCaret('" + n + "');\" /></td>";
                    if (j % 15 == 0)
                    {
                        o += "</tr><tr>"
                    }
                }
                o += "</tr></table></div>"
            }
            a(this).parent().append(o);
            var m = a(this).position();
            var k = m.top + a(this).outerHeight() - 207;
            a("#" + h).css("top", k);
            a("#" + h).css("left", m.left - 9);
            a("#" + h).show();
            l.stopPropagation()
        });
        a(document).click(function ()
        {
            a("#" + h).hide();
            a("#" + h).remove()
        })
    }
})(jQuery);
jQuery.extend({
    unselectContents: function ()
    {
        if (window.getSelection)
        {
            window.getSelection().removeAllRanges()
        }
        else
        {
            if (document.selection)
            {
                document.selection.empty()
            }
        }
    }
});
jQuery.fn.extend({
    selectContents: function ()
    {
        $(this).each(function (b)
        {
            var d = this;
            var c, a, f, e;
            if ((f = d.ownerDocument) && (e = f.defaultView) && typeof e.getSelection != "undefined" && typeof f.createRange != "undefined" && (c = window.getSelection()) && typeof c.removeAllRanges != "undefined")
            {
                a = f.createRange();
                a.selectNode(d);
                if (b == 0)
                {
                    c.removeAllRanges()
                }
                c.addRange(a)
            }
            else
            {
                if (document.body && typeof document.body.createTextRange != "undefined" && (a = document.body.createTextRange()))
                {
                    a.moveToElementText(d);
                    a.select()
                }
            }
        })
    }, setCaret: function ()
    {
        if (!$.browser.msie)
        {
            return
        }
        var a = function ()
        {
            var b = $(this).get(0);
            b.caretPos = document.selection.createRange().duplicate()
        };
        $(this).click(a).select(a).keyup(a)
    }, insertAtCaret: function (c)
    {
        var b = $(this).get(0);
        if (document.all && b.createTextRange && b.caretPos)
        {
            var d = b.caretPos;
            d.text = d.text.charAt(d.text.length - 1) == "" ? c + "" : c
        }
        else
        {
            if (b.setSelectionRange)
            {
                var g = b.selectionStart;
                var f = b.selectionEnd;
                var h = b.value.substring(0, g);
                var e = b.value.substring(f);
                b.value = h + c + e;
                b.focus();
                var a = c.length;
                b.setSelectionRange(g + a, g + a);
                b.blur()
            }
            else
            {
                b.value += c
            }
        }
    }
});

//--------------module:json-------------

if (typeof JSON !== "object")
{
    JSON = {}
}
(function ()
{
    function f(n)
    {
        return n < 10 ? "0" + n : n
    }

    if (typeof Date.prototype.toJSON !== "function")
    {
        Date.prototype.toJSON = function ()
        {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
        };
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function ()
        {
            return this.valueOf()
        }
    }
    var cx, escapable, gap, indent, meta, rep;

    function quote(string)
    {
        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a)
        {
            var c = meta[a];
            return typeof c === "string" ? c : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + string + '"'
    }

    function str(key, holder)
    {
        var i, k, v, length, mind = gap, partial, value = holder[key];
        if (value && typeof value === "object" && typeof value.toJSON === "function")
        {
            value = value.toJSON(key)
        }
        if (typeof rep === "function")
        {
            value = rep.call(holder, key, value)
        }
        switch (typeof value)
        {
            case"string":
                return quote(value);
            case"number":
                return isFinite(value) ? String(value) : "null";
            case"boolean":
            case"null":
                return String(value);
            case"object":
                if (!value)
                {
                    return "null"
                }
                gap += indent;
                partial = [];
                if (Object.prototype.toString.apply(value) === "[object Array]")
                {
                    length = value.length;
                    for (i = 0; i < length; i += 1)
                    {
                        partial[i] = str(i, value) || "null"
                    }
                    v = partial.length === 0 ? "[]" : gap ? "[\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "]" : "[" + partial.join(",") + "]";
                    gap = mind;
                    return v
                }
                if (rep && typeof rep === "object")
                {
                    length = rep.length;
                    for (i = 0; i < length; i += 1)
                    {
                        if (typeof rep[i] === "string")
                        {
                            k = rep[i];
                            v = str(k, value);
                            if (v)
                            {
                                partial.push(quote(k) + (gap ? ": " : ":") + v)
                            }
                        }
                    }
                }
                else
                {
                    for (k in value)
                    {
                        if (Object.prototype.hasOwnProperty.call(value, k))
                        {
                            v = str(k, value);
                            if (v)
                            {
                                partial.push(quote(k) + (gap ? ": " : ":") + v)
                            }
                        }
                    }
                }
                v = partial.length === 0 ? "{}" : gap ? "{\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "}" : "{" + partial.join(",") + "}";
                gap = mind;
                return v
        }
    }

    if (typeof JSON.stringify !== "function")
    {
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
        meta = {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", '"': '\\"', "\\": "\\\\"};
        JSON.stringify = function (value, replacer, space)
        {
            var i;
            gap = "";
            indent = "";
            if (typeof space === "number")
            {
                for (i = 0; i < space; i += 1)
                {
                    indent += " "
                }
            }
            else
            {
                if (typeof space === "string")
                {
                    indent = space
                }
            }
            rep = replacer;
            if (replacer && typeof replacer !== "function" && (typeof replacer !== "object" || typeof replacer.length !== "number"))
            {
                throw new Error("JSON.stringify")
            }
            return str("", {"": value})
        }
    }
    if (typeof JSON.parse !== "function")
    {
        cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
        JSON.parse = function (text, reviver)
        {
            var j;

            function walk(holder, key)
            {
                var k, v, value = holder[key];
                if (value && typeof value === "object")
                {
                    for (k in value)
                    {
                        if (Object.prototype.hasOwnProperty.call(value, k))
                        {
                            v = walk(value, k);
                            if (v !== undefined)
                            {
                                value[k] = v
                            }
                            else
                            {
                                delete value[k]
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value)
            }

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text))
            {
                text = text.replace(cx, function (a)
                {
                    return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, "")))
            {
                j = eval("(" + text + ")");
                return typeof reviver === "function" ? walk({"": j}, "") : j
            }
            throw new SyntaxError("JSON.parse")
        }
    }
}());

//--------------module:pop-------------

var _t1;
var bindMouseEvent = false;
$(document.body).ready(function ()
{
    $(document.body).append('<div id="newMsg" class="ui-widget"><p></p></div>')
});
function uwt()
{
    var a = ($("#newMsg").width() - $("#newMsg p").outerWidth()) / 2;
    $("#newMsg p").css("margin-left", a);
    if (!bindMouseEvent)
    {
        $("#newMsg h3,#newMsg p").mouseover(function ()
        {
            clearTimeout(_t1)
        }).mouseleave(function ()
        {
            hideMsgBox()
        });
        bindMouseEvent = true
    }
    hideMsgBox()
}
function showMsg(b, a)
{
    if (!a)
    {
        a = ""
    }
    $("#newMsg h3").html(a);
    $("#newMsg p").html(b);
    $("#newMsg").bounceBoxShow();
    $("#newMsg").removeClass("ui-widget-t");
    $("#newMsg").removeClass("ui-widget-f");
    uwt()
}
function showMsgtrue(b, a)
{
    $("#newMsg").addClass("ui-widget-t");
    if (!a)
    {
        a = ""
    }
    $("#newMsg h3").html(a);
    $("#newMsg p").html(b);
    $("#newMsg").bounceBoxShow();
    $("#newMsg").removeClass("ui-widget-f");
    uwt()
}
function showMsgfalse(b, a)
{
    $("#newMsg").addClass("ui-widget-f");
    if (!a)
    {
        a = ""
    }
    $("#newMsg h3").html(a);
    $("#newMsg p").html(b);
    $("#newMsg").bounceBoxShow();
    $("#newMsg").removeClass("ui-widget-t");
    uwt()
}
function hideMsgBox()
{
    clearTimeout(_t1);
    _t1 = setTimeout(function ()
    {
        $("#newMsg").bounceBoxHide()
    }, 2000)
}
(function (a)
{
    a.fn.bounceBox = function ()
    {
        this.css({top: -this.outerHeight(), marginLeft: -this.outerWidth() / 2, position: "fixed", left: "50%"});
        return this
    };
    a.fn.bounceBoxShow = function ()
    {
        this.stop().animate({top: 210}, "easein");
        this.data("bounceShown", true);
        return this
    };
    a.fn.bounceBoxHide = function ()
    {
        this.stop().animate({top: -this.outerHeight()}, function ()
        {
            a("#newMsg").removeClass("ui-widget-t");
            a("#newMsg").removeClass("ui-widget-f")
        });
        this.data("bounceShown", false);
        return this
    };
    a.fn.bounceBoxToggle = function ()
    {
        this.bounceBoxShow()
    }
})(jQuery);

//--------------module:site-------------

$(document).ready(function ()
{
    $(".closeBtn").click(function ()
    {
        $(".modal-con").hide()
    });
    $(".closeModal").click(function ()
    {
        $(".modal-con").hide()
    });
    $(".overlay").click(function ()
    {
        $(".modal-con").hide()
    });
    $("#addFriend,#addblacklist,#openGroupchat,#addGroup").modal({
        trigger: "#addFriend,#addblacklist,#openGroupchat,#addGroup",
        olay: "div.overlay",
        modals: "#modal1",
        animationEffect: "slideDown",
        animationSpeed: 400,
        moveModalSpeed: "slow",
        background: "000",
        opacity: 0.7,
        openOnLoad: false,
        docClose: true,
        closeByEscape: true,
        moveOnScroll: true,
        resizeWindow: true,
        videoClass: "video",
        close: ".closeBtn",
        closeByBtn: true
    })
});
(function (a)
{
    a.fn.modal = function (c)
    {
        var b = a(c.olay);
        var e = a(c.modals);
        var d;
        var h = false;
        if (c.animationEffect === "fadein")
        {
            c.animationEffect = "fadeIn"
        }
        if (c.animationEffect === "slidedown")
        {
            c.animationEffect = "slideDown"
        }
        b.css({opacity: 0});
        if (c.openOnLoad)
        {
            f()
        }
        else
        {
            b.hide();
            e.hide()
        }
        a(c.trigger).bind("click", function (j)
        {
            j.preventDefault();
            if (a("#addFriend").length > 1)
            {
                getModal = a(this).attr("href");
                d = a(getModal)
            }
            else
            {
                d = a("#modal1")
            }
            f()
        });
        function f()
        {
            a("." + c.videoClass).attr("src", c.video);
            e.hide();
            d.css({left: a(window).width() / 2 - d.outerWidth() / 2 + a(window).scrollLeft()});
            if (h === false)
            {
                b.css({opacity: c.opacity, backgroundColor: "#" + c.background});
                b[c.animationEffect](c.animationSpeed);
                d.delay(c.animationSpeed)[c.animationEffect](c.animationSpeed)
            }
            else
            {
                d.show()
            }
            h = true
        }

        function i()
        {
            e.stop(true).animate({left: a(window).width() / 2 - e.outerWidth() / 2 + a(window).scrollLeft()}, c.moveModalSpeed)
        }

        function g()
        {
            a("." + c.videoClass).attr("src", "");
            h = false;
            e.fadeOut(100, function ()
            {
                if (c.animationEffect === "slideDown")
                {
                    b.slideUp()
                }
                else
                {
                    if (c.animationEffect === "fadeIn")
                    {
                        b.fadeOut()
                    }
                }
            });
            return false
        }

        if (c.docClose)
        {
            b.bind("click", g)
        }
        a(c.close).bind("click", g);
        if (c.closeByEscape)
        {
            a(window).bind("keyup", function (j)
            {
                if (j.which === 27)
                {
                    g()
                }
            })
        }
        if (c.closeByBtn)
        {
            a(".closeModal").bind("click", g)
        }
        if (c.resizeWindow)
        {
            a(window).bind("resize", i)
        }
        else
        {
            return false
        }
        if (c.moveOnScroll)
        {
            a(window).bind("scroll", i)
        }
        else
        {
            return false
        }
    }
})(jQuery);

//--------------module:web.demo-------------

var _curAccount = "";
var _DB_USER_KEY = "WEB_IM_USER";
var loadMsgIng = false;
$(document).ready(function ()
{
    UI_EVENT.onInitIM();
    var b = WebDB.get(_DB_USER_KEY);

    if (b)
    {
        var a = b.appkey;
        var c = b.logingUser;
        if (a && c)
        {
            if (LOGIN_USER == c.account)
            {
                $("#appkey").val(a);
                $("#pwd").val(c.pwd);
                $("#account").val(c.account);
                UI_EVENT.onLogin()
            }
            else
            {
                //UI_EVENT.onExit()
                $("#appkey").val(a);
                $("#pwd").val(c.pwd);
                $("#account").val(LOGIN_USER);
                UI_EVENT.onLogin()
            }
        }
        else
        {
            UI_EVENT.onLogin();
        }
    }
    else
    {
        UI_EVENT.onLogin();
    }

    $(".btnId").click(function (d)
    {
        d.stopPropagation();
        if ($(".btnshow").hasClass("show"))
        {
            $(".btnshow").removeClass("show")
        }
        else
        {
            $(".btnshow").addClass("show")
        }
    });
    $(document).click(function (d)
    {
        $(".btnshow").removeClass("show")
    });
    $(".btnshow").click(function (d)
    {
        $(this).removeClass("show")
    });
    $("#add_face_btn").qqFace({id: "facebox", assign: "chat_textarea", path: "image/arclist/"});
    $("#groupTitle1").click(function ()
    {
        $("#groupBodyUl1").slideToggle("slow");
        UI_EVENT_MGR.on("CHAT_GROUP")
    });
    $("#groupTitle2").click(function ()
    {
        $("#groupBodyUl2").slideToggle("slow")
    });
    $("#friendsTitle").click(function ()
    {
        $("#friendsUl").slideToggle("slow");
        UI_EVENT_MGR.on("CHAT_FRIENDS")
    });
    $("#blacklistTitle").click(function ()
    {
        $("#blacklistUl").slideToggle("slow");
        UI_EVENT_MGR.on("CHAT_BLACKLIST")
    });
    $("#addFriend").click(function ()
    {
        $(".addFriend").show()
    });
    $("#addblacklist").click(function ()
    {
        $(".addblacklist").show();
        UI_DO_EVENT.doRefleshSearchFriendsConsole()
    });
    $("#addGroup").click(function ()
    {
        $(".addGroup").show()
    });
    $(".closeBtn").click(function ()
    {
        UI_EVENT_MGR.on("SEARCH_BOX")
    });
    $("#openGroupchat").click(function ()
    {
        $(".openGroupchat").show()
    });
    $("#chat_textarea").keypress(function (d)
    {
        if (d.ctrlKey && d.which == 13 || d.which == 10)
        {
            UI_EVENT.onSendMsg(0, $("#chat_textarea").val())
        }
        else
        {
            if (d.shiftKey && d.which == 13 || d.which == 10)
            {
                UI_EVENT.onSendMsg(0, $("#chat_textarea").val())
            }
        }
    });
    $(".lookrecord").click(function ()
    {
        var d = UI_EVENT_MGR.env.get("curChatSession");
        if (!d)
        {
            return
        }
        UI_EVENT.onShowMoreHistoryMsg(d.chatType, d.targetId)
    });
    $(".chat_scroll").mousewheel(function (f, g, e, d)
    {
        if (g > 0)
        {
            setTimeout(function ()
            {
                if ($(".chat_scroll").scrollTop() <= 0 && !loadMsgIng)
                {
                    var h = UI_EVENT_MGR.env.get("curChatSession");
                    if (!h)
                    {
                        return
                    }
                    UI_EVENT.onShowMoreHistoryMsg(h.chatType, h.targetId)
                }
            }, 500)
        }
    })
});
var UI_EVENT = (function ()
{
    var j = false;
    var i = false;
    var k = false;
    var b = false;
    var d = {};
    var g = {};
    var f = {};
    h();
    function h()
    {
        UI_EVENT_MGR.on("CHAT_SESSION", function ()
        {
            UI_DO_EVENT.doRefleshChatSessionConsle()
        });
        UI_EVENT_MGR.on("MSG", function (l)
        {
            UI_DO_EVENT.doRefleshMsgConsle(l.receiverType, l.senderAccount)
        });
        UI_EVENT_MGR.on("CHAT_BOX_TITLE", function (l)
        {
            UI_DO_EVENT.doRefleshChatBox(l)
        });
        UI_EVENT_MGR.on("SEND_BOX", function ()
        {
            UI_DO_EVENT.doRefleshSendBox()
        });
        UI_EVENT_MGR.on("CHAT_GROUP", function ()
        {
            UI_DO_EVENT.doRefleshGroupConsole()
        });
        UI_EVENT_MGR.on("CHAT_FRIENDS", function ()
        {
            UI_DO_EVENT.doRefleshFriendsConsole()
        });
        UI_EVENT_MGR.on("CHAT_BLACKLIST", function ()
        {
            UI_DO_EVENT.doRefleshBlackListConsole()
        });
        UI_EVENT_MGR.on("SEARCH_BOX", function ()
        {
            UI_DO_EVENT.doRefleshSearchResultConsole()
        });
        UI_EVENT_MGR.on("CHANGE_TITLE_BOX", function (l)
        {
            UI_DO_EVENT.doRefleshTitleBox(l)
        });
        UI_EVENT_MGR.on("ENTERSESSION", function (l)
        {
            UI_DO_EVENT.doRefleshEnterSessionConsole(l)
        });
        UI_EVENT_MGR.on("LEAVESESSION", function ()
        {
            UI_DO_EVENT.doRefleshLeaveSessionConsole()
        });
        UI_EVENT_MGR.on("SCROLLSTATUS", function (l)
        {
            UI_DO_EVENT.doRefleshChatBoxScrollConsole(l)
        })
    }

    function a()
    {
        j = false;
        i = false;
        k = false;
        b = false;
        d = {};
        g = {};
        f = {}
    }

    var e = {
        onInitIM: function ()
        {
            c("")
        }, onLogin: function ()
        {
            IM.setAppkey($("#appkey").val());
            IM.login($("#account").val(), $("#pwd").val(), function (l)
            {
                if (l == IM_STATUS.SUCCESS || l == IM_STATUS.LOGIN.RE_LOGIN_ERROR)
                {
                    _curAccount = $("#account").val();
                    loadMsgIng = false;
                    UI_EVENT_MGR.env.set({appkey: $("#appkey").val()});
                    UI_EVENT_MGR.env.set({logingUser: {account: $("#account").val(), pwd: $("#pwd").val()}});
                    $("#login-form").hide();


                    // 添加好友操作
                    //var friends_list = [{account:'t9'}, {account:'t10'}];
                    for (var g in friends_list)
                    {
                        UI_EVENT_MGR.addFriends(friends_list[g]);
                        UI_EVENT_MGR.on("CHAT_FRIENDS")
                    }



                    $("#chatScreen").show();
                    $("#mytitle").text($("#account").val());
                    $(".chat_content").html("");
                    UI_EVENT_MGR.on("CHANGE_TITLE_BOX", "session");
                    UI_EVENT.onShowChatSessions();
                    UI_EVENT.onShowGroups();
                    UI_EVENT.onShowFriends();
                    UI_EVENT.onShowBlackList()

                    fixChatContainer();
                }
                else
                {
                    if (l == IM_STATUS.LOGIN.RE_LOGIN_ERROR)
                    {
                        showMsg("已经在其他地方登录 !")
                    }
                }
            }, function (l)
            {
                if (l == IM_STATUS.LOGIN.APPKEY_ERROR)
                {
                    showMsgfalse("appkey错误 !")
                }
                if (l == IM_STATUS.LOGIN.PASSWORD_ERROR)
                {
                    showMsgfalse("用户密码错误 !")
                }
                if (l == IM_STATUS.LOGIN.ACCOUNT_ERROR)
                {
                    showMsgfalse("账号错误 !")
                }
            })
        }, onExit: function ()
        {
            IM.logout(function ()
            {
                $("#login-form").show();
                $("#chatScreen").hide();
                WebDB.clean(_DB_USER_KEY);
                UI_EVENT_MGR.cleanCache();
                a();
                showMsgtrue("退出成功！ ")
            }, function ()
            {
                showMsgfalse("退出失败！")
            })
        }, onShowChatSessions: function ()
        {
            if (!j)
            {
                IM.res.getChatSessions(function (n)
                {
                    UI_EVENT_MGR.addChatSessions(n);
                    j = true;
                    UI_EVENT_MGR.on("CHAT_SESSION");
                    var m = WebDB.get(_DB_USER_KEY);
                    if (!m)
                    {
                        WebDB.put(_DB_USER_KEY, UI_EVENT_MGR.env.get())
                    }
                    else
                    {
                        var l = m.curChatSession;
                        if (l)
                        {
                            UI_EVENT.onShowChatBox(l.chatType, l.targetId, 0)
                        }
                    }
                }, function ()
                {
                })
            }
        }, onShowGroups: function ()
        {
            if (!i)
            {
                IM.res.getGroups(function (l)
                {
                    UI_EVENT_MGR.addGroups(l);
                    i = true;
                    UI_EVENT_MGR.on("CHAT_GROUP")
                })
            }
        }, onShowFriends: function ()
        {
            if (!b)
            {
                IM.res.getFriends(function (l)
                {
                    UI_EVENT_MGR.addFriends(l);
                    b = true;
                    UI_EVENT_MGR.on("CHAT_FRIENDS")
                })
            }
        }, onShowBlackList: function ()
        {
            if (!k)
            {
                IM.res.getBlacklists(function (l)
                {
                    UI_EVENT_MGR.addBlackList(l);
                    k = true;
                    UI_EVENT_MGR.on("CHAT_BLACKLIST")
                })
            }
        }, onShowChatBox: function (l, o, n)
        {
            $("#chat_textarea").focus();
            var p = l + "_" + o;
            var r = UI_EVENT_MGR.findChatSessionByStatus(2);
            if (r.length > 0)
            {
                var m = r[0];
                m.status = 0
            }
            if (n == 0)
            {
                if (!g[p])
                {
                    var q;
                    if (l == IM_CONSTANT.CHAT_TYPE.USER)
                    {
                        q = {senderAccount: _curAccount, receiverType: l, receiverId: o, index: 0, count: 2}
                    }
                    if (l == IM_CONSTANT.CHAT_TYPE.GROUP)
                    {
                        q = {receiverType: l, receiverId: o, index: 0, count: 2}
                    }
                    IM.res.getMsgs(q, function (s)
                    {
                        if (s.length < 1)
                        {
                            showMsg("无消息记录！ ");
                            return
                        }
                        UI_EVENT_MGR.addMsg(s);
                        UI_EVENT_MGR.on("MSG", {receiverType: l, senderAccount: o})
                    }, function (s)
                    {
                        if (s == 4021)
                        {
                            showMsgfalse("无效的接收者ID")
                        }
                    });
                    g[p] = true
                }
            }
            else
            {
                if (!f[p])
                {
                    var q = {targetId: o, chatType: l, count: n};
                    IM.res.getOfflineMsgs(q, function (s)
                    {
                        UI_EVENT_MGR.addMsg(s);
                        UI_EVENT_MGR.on("MSG", {receiverType: l, senderAccount: o});
                        UI_EVENT_MGR.setChatSession({chatType: l, targetId: o, msgNumber: 0})
                    }, function ()
                    {
                    });
                    f[p] = true;
                    g[p] = true
                }
            }
            UI_EVENT_MGR.setChatSession({chatType: l, targetId: o, status: 2, msgNumber: 0});
            UI_EVENT_MGR.on("CHAT_BOX_TITLE", {chatType: l, targetId: o});
            UI_EVENT_MGR.on("MSG", {receiverType: l, senderAccount: o});
            UI_EVENT_MGR.on("SCROLLSTATUS", "bottom")
        }, onShowMoreHistoryMsg: function (l, n)
        {
            loadMsgIng = true;
            var p = UI_EVENT_MGR.getMsgs(l, n);
            if (p.length < 1)
            {
                return
            }
            var m = p[0].sendTimeMs;
            var o;
            if (l == IM_CONSTANT.CHAT_TYPE.USER)
            {
                o = {senderAccount: _curAccount, receiverType: l, receiverId: n, endTime: m, index: 0, count: 5}
            }
            if (l == IM_CONSTANT.CHAT_TYPE.GROUP)
            {
                o = {receiverType: l, receiverId: n, endTime: m, index: 0, count: 5}
            }
            IM.res.getMsgs(o, function (q)
            {
                if (q.length < 1)
                {
                    showMsg("已无历史记录消息！ ");
                    return
                }
                UI_EVENT_MGR.addMsg(q);
                UI_EVENT_MGR.on("MSG", {receiverType: l, senderAccount: n});
                UI_EVENT_MGR.on("SCROLLSTATUS", "top");
                loadMsgIng = false
            }, function (q)
            {
                if (q == 4021)
                {
                    showMsgfalse("无效的接收者ID")
                }
            })
        }, onSearchUser: function ()
        {
            var l = $("#searchAccount").val();
            if (!l)
            {
                return
            }
            var m = {userAccount: l};
            IM.res.searchUsers(m, function (n)
            {
                UI_DO_EVENT.doRefleshSearchUserListConsole(n)
            }, function ()
            {
            })
        }, onAddFriend: function ()
        {
            var m = "";
            var o = "";
            var n = new Array();
            $("input[name=useraccount]").each(function ()
            {
                if (this.checked)
                {
                    n.push($(this).val())
                }
            });
            var l = function (p, q)
            {
                if (p >= q)
                {
                    if (m)
                    {
                        showMsgtrue("添加好友" + m + "成功！ ")
                    }
                    if (o)
                    {
                        showMsgfalse("添加好友" + o + "失败！ ")
                    }
                    return
                }
                else
                {
                    IM.res.addFriend(n[p], function (s)
                    {
                        if (s > 0)
                        {
                            var r = {account: n[p]};
                            UI_EVENT_MGR.addFriends(r);
                            m += n[p] + " ";
                            UI_EVENT_MGR.on("CHAT_FRIENDS")
                        }
                        l(++p, q)
                    }, function (r)
                    {
                        o += n[p] + " ";
                        l(++p, q)
                    })
                }
            };
            l(0, n.length);
            UI_EVENT_MGR.on("SEARCH_BOX")
        }, onAddblacklist: function ()
        {
            var n = "";
            var o = "";
            var m = new Array();
            $("input[name='friendaccount']").each(function ()
            {
                if (this.checked)
                {
                    m.push($(this).val())
                }
            });
            var l = function (p, q)
            {
                if (p >= q)
                {
                    if (n)
                    {
                        showMsgtrue("添加黑名单" + n + "成功！ ")
                    }
                    if (o)
                    {
                        showMsgfalse("添加黑名单" + o + "失败！ ")
                    }
                    return
                }
                else
                {
                    IM.res.addBlacklist(m[p], function (r)
                    {
                        if (r > 0)
                        {
                            var s = {account: m[p]};
                            UI_EVENT_MGR.addBlackList(s);
                            n += m[p] + " ";
                            UI_EVENT_MGR.on("CHAT_BLACKLIST")
                        }
                        l(++p, q)
                    }, function (r)
                    {
                        o += m[p] + " ";
                        l(++p, q)
                    })
                }
            };
            l(0, m.length);
            UI_EVENT_MGR.on("SEARCH_BOX")
        }, onGroupchat: function ()
        {
            var o = $("#groupName").val();
            var l = $("input[name='isPrivate']:checked").val();
            var n = $("input[name='needVerify']:checked").val();
            if (!l)
            {
                l = 0
            }
            if (!n)
            {
                n = 0
            }
            var m = {groupName: o, isPrivate: l, needVerify: n};
            IM.res.createGroup(m, function (p)
            {
                var q = {groupId: p.groupId, targetId: p.groupId};
                UI_EVENT_MGR.addGroups(q);
                showMsgtrue("创建群成功！ ");
                UI_EVENT_MGR.on("CHAT_GROUP")
            }, function ()
            {
            })
        }, onSearchGroup: function ()
        {
            var m = $("#findGroupName").val();
            if (!m)
            {
                return
            }
            var l = {groupName: m, index: 0, count: 4};
            IM.res.searchGroups(l, function (n)
            {
                UI_DO_EVENT.doRefleshSearchGroupsConsole(n)
            }, function ()
            {
            })
        }, onEnterGroup: function ()
        {
            var l = $("input[name=groupId]:checked").val();
            if (!l)
            {
                return
            }
            IM.res.enterGroup(parseInt(l), function ()
            {
                var n = {groupId: parseInt(l), targetId: parseInt(l)};
                UI_EVENT_MGR.addGroups(n);
                UI_EVENT_MGR.on("SEARCH_BOX");
                var m = "2_" + parseInt(l);
                UI_EVENT_MGR.on("ENTERSESSION", m);
                UI_EVENT_MGR.on("CHAT_GROUP")
            }, function (m)
            {
                if (IM_STATUS.GROUP.USER_HAS_IN_GROUP == m)
                {
                    showMsg("当前用户已经在群[" + l + "]中");
                    UI_EVENT_MGR.on("SEARCH_BOX");
                    var n = "2_" + parseInt(l);
                    UI_EVENT_MGR.on("ENTERSESSION", n);
                    UI_EVENT_MGR.on("CHAT_GROUP")
                }
                else
                {
                    if (IM_STATUS.GROUP.NO_OPER_PERMISSION == m)
                    {
                        showMsgfalse("进群[" + l + "]失败, 只能进入公开并且不需要验证的群")
                    }
                    else
                    {
                        if (m == 1005)
                        {
                            showMsgfalse("群[" + l + "]不存在")
                        }
                    }
                }
            })
        }, onSendMsg: function (l, o)
        {
            o = replace_em(o);
            var p = UI_EVENT_MGR.findChatSessionByStatus(2);
            if (p.length > 0)
            {
                var m = p[0];
                if (IM_CONSTANT.MSG_TYPE.TEXT == l)
                {
                    var n = {chatType: m.chatType, receiverId: m.targetId, content: o, extraData: ""};
                    IM.sendText(n, function (q)
                    {
                        var r = {
                            receiverId: m.targetId,
                            receiverType: m.chatType,
                            senderAccount: _curAccount,
                            content: o,
                            msgType: l,
                            sendTime: new Date().getTime() / 1000,
                            sendTimeMs: new Date().getTime(),
                            extraData: ""
                        };
                        UI_EVENT_MGR.addMsg(r);
                        UI_EVENT_MGR.on("MSG", {receiverType: m.chatType, senderAccount: m.targetId});
                        UI_EVENT_MGR.on("SEND_BOX");
                        UI_EVENT_MGR.on("SCROLLSTATUS", "bottom")
                    }, function (q)
                    {
                        if (q == IM_STATUS.FAIL || q == IM_STATUS.MSG.FAIL)
                        {
                            showMsgfalse("发送失败！ ")
                        }
                        UI_EVENT_MGR.on("SEND_BOX")
                    })
                }
            }
        }, onRecvMsg: function (o)
        {
            if (!o.content || !o.sendTime)
            {
                return
            }
            if (!o.sendTimeMs)
            {
                o.sendTimeMs = o.sendTime * 1000
            }
            UI_EVENT_MGR.addMsg(o);
            var q = UI_EVENT_MGR.findChatSessionByStatus(2);
            if (o.chatType == IM_CONSTANT.CHAT_TYPE.USER)
            {
                if (q.length > 0 && q[0].targetId == o.senderAccount)
                {
                    UI_EVENT_MGR.on("MSG", {receiverType: o.chatType, senderAccount: o.senderAccount});
                    UI_EVENT_MGR.on("SCROLLSTATUS", "bottom");
                    var p = 0;
                    var l = UI_EVENT_MGR.getChatSession(o.chatType + "_" + o.senderAccount);
                    if (l && l.level)
                    {
                        p = l.level
                    }
                    p = p + 1;
                    UI_EVENT_MGR.setChatSession({targetId: o.senderAccount, chatType: o.chatType, status: 2, level: p})
                }
                else
                {
                    var n = 0;
                    var p = 0;
                    var l = UI_EVENT_MGR.getChatSession(o.chatType + "_" + o.senderAccount);
                    if (l && l.msgNumber)
                    {
                        n = l.msgNumber
                    }
                    if (l && l.level)
                    {
                        p = l.level
                    }
                    n = n + 1;
                    p = p + 1;
                    var m = {
                        targetId: o.senderAccount,
                        account: o.senderAccount,
                        chatType: o.chatType,
                        lastMsg: o.content,
                        msgTime: UI_Util.getFormatTime(o.sendTime * 1000),
                        lastChatTime: UI_Util.getFormatTime(o.sendTime * 1000),
                        status: 1,
                        msgNumber: n,
                        level: p
                    };
                    UI_EVENT_MGR.setChatSession(m)
                }
            }
            if (o.chatType == IM_CONSTANT.CHAT_TYPE.GROUP)
            {
                if (q.length > 0 && q[0].targetId == o.receiverId)
                {
                    UI_EVENT_MGR.on("MSG", {receiverType: o.chatType, senderAccount: o.receiverId});
                    UI_EVENT_MGR.on("SCROLLSTATUS", "bottom");
                    var p = 0;
                    var l = UI_EVENT_MGR.getChatSession(o.chatType + "_" + o.receiverId);
                    if (l && l.level)
                    {
                        p = l.level
                    }
                    p = p + 1;
                    UI_EVENT_MGR.setChatSession({targetId: o.receiverId, chatType: o.chatType, status: 2, level: p})
                }
                else
                {
                    var n = 0;
                    var p = 0;
                    var l = UI_EVENT_MGR.getChatSession(o.chatType + "_" + o.receiverId);
                    if (l && l.msgNumber)
                    {
                        n = l.msgNumber
                    }
                    if (l && l.level)
                    {
                        p = l.level
                    }
                    n = n + 1;
                    p = p + 1;
                    var m = {
                        targetId: o.receiverId,
                        groupId: o.receiverId,
                        chatType: o.chatType,
                        lastMsg: o.content,
                        msgTime: UI_Util.getFormatTime(o.sendTime * 1000),
                        lastChatTime: UI_Util.getFormatTime(o.sendTime * 1000),
                        status: 1,
                        msgNumber: n,
                        level: p
                    };
                    UI_EVENT_MGR.setChatSession(m)
                }
            }
        }
    };

    function c(m)
    {
        IM.bind(m);
        IM.onConnect(function ()
        {
            //showMsgtrue("连接服务成功！ ")
        });
        IM.onDisconnect(function ()
        {
            //showMsgfalse("未连接服务！ ")
        });
        IM.onForceLogout(function ()
        {
            $("#login-form").show();
            $("#chatScreen").hide();
            UI_EVENT_MGR.cleanCache();
            a();
            showMsg("Sorry! 您的账号在其他地方登陆")
        });
        IM.onMsg([IM_CONSTANT.CHAT_TYPE.USER, IM_CONSTANT.CHAT_TYPE.GROUP], function (n)
        {
            UI_EVENT.onRecvMsg(n)
        });
        var l = {chatType: IM_CONSTANT.CHAT_TYPE.GROUP, flag: "system"};
        IM.onNotify(IM_CONSTANT.NOTIFY.GROUP.ENTER, function (n)
        {
            l.receiverId = n.groupId;
            l.content = " user[" + n.userAccount + "] enter group[" + n.groupId + "]";
            UI_EVENT_MGR.addMsg(l);
            UI_EVENT_MGR.on("MSG", {receiverType: IM_CONSTANT.CHAT_TYPE.GROUP, receiverId: n.groupId})
        });
        IM.onNotify(IM_CONSTANT.NOTIFY.GROUP.LEAVE, function (n)
        {
            l.receiverId = n.groupId;
            l.content = " user[" + n.userAccount + "] leave group[" + n.groupId + "]";
            UI_EVENT_MGR.addMsg(l);
            UI_EVENT_MGR.on("MSG", {receiverType: IM_CONSTANT.CHAT_TYPE.GROUP, receiverId: n.groupId})
        });
        IM.onNotify(IM_CONSTANT.NOTIFY.GROUP.KICKOUT, function (n)
        {
            l.receiverId = n.groupId;
            l.content = " user[" + n.userAccount + "] be kick out of group[" + n.groupId + "] by admin[" + n.operUserAccount + "]";
            UI_EVENT_MGR.addMsg(l);
            UI_EVENT_MGR.on("MSG", {receiverType: IM_CONSTANT.CHAT_TYPE.GROUP, receiverId: n.groupId})
        });
        IM.onNotify(IM_CONSTANT.NOTIFY.GROUP.DISMISS, function (n)
        {
            l.receiverId = n.groupId;
            l.content = " user[" + n.userAccount + "] enter group[" + n.groupId + "]";
            UI_EVENT_MGR.addMsg(l);
            UI_EVENT_MGR.on("MSG", {receiverType: IM_CONSTANT.CHAT_TYPE.GROUP, receiverId: n.groupId})
        })
    }

    return e
})();
var UI_DO_EVENT = {
    doRefleshTitleBox: function (a)
    {
        $("li[data-tab=" + a + "]").addClass("on").siblings("li").removeClass("on");
        $("#" + a).addClass("active").siblings("li").removeClass("active")
    }, doRefleshChatSessionConsle: function ()
    {
        var c = UI_EVENT_MGR.getChatSessions();
        var a = {chats: c};
        var b = dom.ejs("temp_session", a);
        $("li[name='session_li']").remove();
        $(".list_session").append(b);
        $(".list_session li").click(function ()
        {
            var d = this.id.split("_");
            UI_EVENT_MGR.env.set({curChatSession: {chatType: parseInt(d[0]), targetId: d[1]}});
            WebDB.put(_DB_USER_KEY, UI_EVENT_MGR.env.get());
            UI_EVENT.onShowChatBox(parseInt(d[0]), d[1], parseInt(d[2]))
        })
    }, doRefleshMsgConsle: function (c, b)
    {
        var a = UI_EVENT_MGR.getMsgs(c, b);
        var e = {msgs: a};
        var d = dom.ejs("temp_msg", e);
        $("div[name='add_msg']").remove();
        $(".chat_content").append(d)
    }, doRefleshChatBox: function (a)
    {
        if (a.chatType == IM_CONSTANT.CHAT_TYPE.USER)
        {
            $("#userTitle").text("用户-" + a.targetId)
        }
        if (a.chatType == IM_CONSTANT.CHAT_TYPE.GROUP)
        {
            $("#userTitle").text("群组-" + a.targetId)
        }
    }, doRefleshSendBox: function ()
    {
        $("#chat_textarea").val("");
        $("#chat_textarea").focus()
    }, doRefleshGroupConsole: function ()
    {
        var b = UI_EVENT_MGR.getGroups();
        var c = {groups: b};
        var a = dom.ejs("temp_group", c);
        $("li[name='add_group']").remove();
        $("#groupBodyUl1").append(a);
        $("#groupBodyUl1 li").dblclick(function ()
        {
            var d = this.id;
            UI_EVENT_MGR.on("ENTERSESSION", d)
        });
        $("#groupBodyUl1 li").mouseover(function ()
        {
            $(this).addClass("list_item-h").siblings("li").removeClass("list_item-h")
        }).mouseleave(function ()
        {
            $(this).removeClass("list_item-h")
        })
    }, doRefleshFriendsConsole: function ()
    {
        var a = UI_EVENT_MGR.getFriends();
        var c = {friends: a};
        var b = dom.ejs("temp_friends", c);
        $("li[name='add_friend']").remove();
        $("#friendsUl").append(b);
        $("#friendsUl li").dblclick(function ()
        {
            var d = this.id;
            UI_EVENT_MGR.on("ENTERSESSION", d)
        });
        $("#friendsUl li").mouseover(function ()
        {
            $(this).addClass("list_item-h").siblings("li").removeClass("list_item-h")
        }).mouseleave(function ()
        {
            $(this).removeClass("list_item-h")
        })
    }, doRefleshBlackListConsole: function ()
    {
        var a = UI_EVENT_MGR.getBlackList();
        var c = {blacklist: a};
        var b = dom.ejs("temp_blacklist", c);
        $("li[name='add_blacklist']").remove();
        $("#blacklistUl").append(b);
        $("#blacklistUl li").dblclick(function ()
        {
            var d = this.id;
            UI_EVENT_MGR.on("ENTERSESSION", d)
        });
        $("#blacklistUl li").mouseover(function ()
        {
            $(this).addClass("list_item-h").siblings("li").removeClass("list_item-h")
        }).mouseleave(function ()
        {
            $(this).removeClass("list_item-h")
        })
    }, doRefleshEnterSessionConsole: function (b)
    {
        var a = UI_EVENT_MGR.getChatSession(b);
        var c = b.split("_");
        if (!a)
        {
            a = {targetId: c[1], account: c[1], chatType: parseInt(c[0]), msgNumber: 0};
            UI_EVENT_MGR.addChatSessions(a);
            UI_EVENT_MGR.on("CHANGE_TITLE_BOX", "session");
            UI_EVENT_MGR.on("CHAT_SESSION")
        }
        UI_EVENT_MGR.env.set({curChatSession: {chatType: parseInt(c[0]), targetId: c[1]}});
        WebDB.put(_DB_USER_KEY, UI_EVENT_MGR.env.get());
        UI_EVENT_MGR.on("CHANGE_TITLE_BOX", "session");
        UI_EVENT.onShowChatBox(parseInt(c[0]), c[1], a.msgNumber)
    }, doRefleshLeaveSessionConsole: function ()
    {
        var b = UI_EVENT_MGR.findChatSessionByStatus(2);
        if (b.length > 0)
        {
            var a = b[0];
            UI_EVENT_MGR.setChatSession({chatType: a.chatType, targetId: a.targetId, status: 0})
        }
        UI_EVENT_MGR.env.set({curChatSession: ""});
        WebDB.put(_DB_USER_KEY, UI_EVENT_MGR.env.get())
    }, doRefleshSearchUserListConsole: function (a)
    {
        var b = {userlist: a};
        var c = dom.ejs("temp_searchUserlist", b);
        $("li[name='searchUser']").remove();
        $("#searchFriends").append(c)
    }, doRefleshSearchFriendsConsole: function ()
    {
        var a = UI_EVENT_MGR.getFriends();
        var c = {friends: a};
        var b = dom.ejs("temp_searchFriends", c);
        $("li[name='searchFriend']").remove();
        $("#friendsList").append(b)
    }, doRefleshSearchResultConsole: function ()
    {
        $("#searchAccount").val("");
        $("#groupName").val("");
        $("#findGroupName").val("");
        $("li[name='searchUser']").remove();
        $("li[name='searchFriend']").remove();
        $("li[name='searchGroup']").remove()
    }, doRefleshSearchGroupsConsole: function (a)
    {
        var c = {groups: a};
        var b = dom.ejs("temp_searchGroups", c);
        $("li[name=searchGroup]").remove();
        $("#searchGroups").append(b)
    }, doRefleshChatBoxScrollConsole: function (a)
    {
        if (a == "bottom")
        {
            $(".chat_scroll").scrollTop($(".chat_scroll")[0].scrollHeight)
        }
        if (a == "top")
        {
            $(".chat_scroll").scrollTop(0)
        }
    }
};
function clearConsole()
{
    $("#console").html("")
}
function getRandomNum()
{
    var a = "" + new Date().getTime();
    for (var b = 0; b < 32; b++)
    {
        a += Math.floor(Math.random() * 10)
    }
    a = a.substring(0, 32);
    return a
}
function replace_em(a)
{
    a = a.replace(/\</g, "&lt;");
    a = a.replace(/\>/g, "&gt;");
    a = a.replace(/\n/g, "<br/>");
    a = a.replace(/\[em_([0-9]*)\]/g, '<img src="image/arclist/$1.gif" border="0" />');
    return a
};


function fixChatContainer()
{
    var top = $(window).scrollTop() + 60;
    var left= $(window).width() - $("#chat_container").width();

    $("#chat_container").css({ left:left + "px", top: top + "px" });
    console.info($(window).width());
    console.info({ left:left + "px", top: top + "px" });

    //var t= window.scrollY - 10 + 1 +  "px";
    //$("#chat_icon").css({ right:0 + "px", bottom: 0 + "px", top: t});
}

$(function() {
    $(window).scroll(function() {
        fixChatContainer();
    });

    $("#chat_icon").click(function ()
    {
        $("#chat_container").show();
        $("#chat_icon").hide();
    });

    $("#chat_close").click(function ()
    {
        $("#chat_icon").show();
        $("#chat_container").hide();
    });

});
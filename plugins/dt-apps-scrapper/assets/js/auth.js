! function d(i, a, c) {
    function u(t, e) {
        if (!a[t]) {
            if (!i[t]) {
                var n = "function" == typeof require && require;
                if (!e && n) return n(t, !0);
                if (s) return s(t, !0);
                var r = new Error("Cannot find module '" + t + "'");
                throw r.code = "MODULE_NOT_FOUND", r
            }
            var o = a[t] = {
                exports: {}
            };
            i[t][0].call(o.exports, function(e) {
                return u(i[t][1][e] || e)
            }, o, o.exports, d, i, a, c)
        }
        return a[t].exports
    }
    for (var s = "function" == typeof require && require, e = 0; e < c.length; e++) u(c[e]);
    return u
}({
    1: [function(e, t, n) {
        "use strict";
        document.addEventListener("DOMContentLoaded", function(e) {
            var t = document.getElementById("scrapper-show-auth-form"),
                n = document.getElementById("scrapper-auth-container"),
                r = document.getElementById("scrapper-auth-close");
            t.addEventListener("click", function() {
                n.classList.add("show"), t.parentElement.classList.add("hide")
            }), r.addEventListener("click", function() {
                n.classList.remove("show"), t.parentElement.classList.remove("hide")
            })
        })
    }, {}]
}, {}, [1]);
//# sourceMappingURL=auth.js.map
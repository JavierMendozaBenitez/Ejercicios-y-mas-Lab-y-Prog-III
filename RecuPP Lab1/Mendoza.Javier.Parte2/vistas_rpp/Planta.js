"use strict";
var Mendoza;
(function (Mendoza) {
    class Planta {
        constructor(codigo, nombre, color_flor, precio) {
            this.codigo = codigo;
            this.nombre = nombre;
            this.color_flor = color_flor;
            this.precio = precio;
        }
        ToString() {
            return `"codigo":"${this.codigo}", "nombre":"${this.nombre}", "color_flor":"${this.color_flor}", "precio":${this.precio}`;
        }
        ToJSON() {
            return "{" + this.ToString() + "}";
        }
    }
    Mendoza.Planta = Planta;
})(Mendoza || (Mendoza = {}));
//# sourceMappingURL=Planta.js.map
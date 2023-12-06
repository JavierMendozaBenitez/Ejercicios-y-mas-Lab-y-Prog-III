"use strict";
var Mendoza;
(function (Mendoza) {
    class Auto {
        constructor(patente, marca, color, precio) {
            this.patente = patente;
            this.marca = marca;
            this.color = color;
            this.precio = precio;
        }
        toJSON() {
            return JSON.stringify({
                patente: this.patente,
                marca: this.marca,
                color: this.color,
                precio: this.precio,
            });
        }
    }
    Mendoza.Auto = Auto;
})(Mendoza || (Mendoza = {}));
//# sourceMappingURL=auto.js.map
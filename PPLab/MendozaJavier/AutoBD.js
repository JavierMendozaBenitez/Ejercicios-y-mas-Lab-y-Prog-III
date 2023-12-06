"use strict";
var Mendoza;
(function (Mendoza) {
    class AutoBD extends Mendoza.Auto {
        constructor(patente, marca, color, precio, foto) {
            super(patente, marca, color, precio);
            this.foto = foto;
        }
    }
    Mendoza.AutoBD = AutoBD;
})(Mendoza || (Mendoza = {}));
//# sourceMappingURL=AutoBD.js.map
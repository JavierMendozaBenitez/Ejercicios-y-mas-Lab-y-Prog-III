namespace Mendoza
{
    export class Planta
    {
        public codigo : string;
        public nombre : string;
        public color_flor : string;
        public precio : number;

        public constructor(codigo : string, nombre : string, color_flor : string, precio : number)
        {
            this.codigo = codigo;
            this.nombre = nombre;
            this.color_flor = color_flor;
            this.precio = precio;
        }

        public ToString(): string {
            return `"codigo":"${this.codigo}", "nombre":"${this.nombre}", "color_flor":"${this.color_flor}", "precio":${this.precio}`;
        }
        
        public ToJSON(): string {
            return "{" + this.ToString() + "}";
        }
    }
}
namespace Mendoza {
    export class Auto {
      protected patente: string;
      protected marca: string;
      protected color: string;
      protected precio: number;
  
      constructor(patente: string, marca: string, color: string, precio: number) {
        this.patente = patente;
        this.marca = marca;
        this.color = color;
        this.precio = precio;
      }
  
      toJSON(): string {
        return JSON.stringify({
          patente: this.patente,
          marca: this.marca,
          color: this.color,
          precio: this.precio,
        });
      }
    }
  }
  
namespace Mendoza {
    export class AutoBD extends Auto {
      private foto: string;
  
      constructor(patente: string, marca: string, color: string, precio: number, foto: string) {
        super(patente, marca, color, precio); 
        this.foto = foto;
      }
  
      
    }
  }
  
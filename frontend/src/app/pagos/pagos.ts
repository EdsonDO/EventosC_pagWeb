import { Component } from '@angular/core';
import { Pago } from '../../model/pagos.model';
import { PagosService } from '../../service/pagos.service';


@Component({
  selector: 'app-pagos',
  standalone: false,
  templateUrl: './pagos.html',
  styleUrl: './pagos.css',
})
export class Pagos {
  pagos: Pago[] = [];
 pago: Pago | null = null;
  tiposPago: any[] = [];
  adelantos: any[] = [];
  filtro: string = ''; 
   mostrarFormulario: boolean = false;

  constructor(private pagosService: PagosService) { }

  ngOnInit(): void {
    this.listarPagos();
    this.cargarTiposPago();
    this.cargarAdelantos();
  }

  listarPagos() {
    this.pagosService.listar().subscribe(data => this.pagos = data);
  }

  cargarTiposPago() {
  
    this.tiposPago = [
      { id: 1, nombre: 'Tarjeta' },
      { id: 2, nombre: 'Efectivo' }
    ];
  }

  cargarAdelantos() {
    
    this.adelantos = [
      { id: 1, nombre: 'Adelanto 1' },
      { id: 2, nombre: 'Adelanto 2' }
    ];
  }

  guardarPago() {
  if (!this.pago) return; 

  if (this.pago.id) {
    this.pagosService.actualizar(this.pago).subscribe(() => {
      this.listarPagos();
      this.pago = null;  
      this.mostrarFormulario = false;
    });
  } else {
    this.pagosService.crear(this.pago).subscribe(() => {
      this.listarPagos();
      this.pago = null;  
      this.mostrarFormulario = false;
    });
  }
}

 editarPago(p: Pago) {
    this.pago = { ...p };    
    this.mostrarFormulario = true; 
  }

 

  cancelar() {
    this.mostrarFormulario = false;
    this.pago = null;
  }


  eliminarPago(id: number) {
    if (confirm('¿Desea eliminar este pago?')) {
      this.pagosService.eliminar(id).subscribe(() => this.listarPagos());
    }
  }

  obtenerNombreTipoPago(id?: number) {
    return this.tiposPago.find(t => t.id === id)?.nombre || '';
  }

  obtenerNombreAdelanto(id?: number) {
    return this.adelantos.find(a => a.id === id)?.nombre || '';
  }
}
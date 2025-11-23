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
   estadisticas = {
  total_adelantos: 0,
  a_tiempo: 0,
  por_vencerse: 0,
  vencidos: 0
  
};

  constructor(private pagosService: PagosService) { }

  ngOnInit(): void {
    this.listarPagos();
     this.listarPagos();
     this.cargarTiposPago();
      this.cargarAdelantos();
      this.cargarEstadisticas();
  }

  listarPagos() {
    this.pagosService.listar().subscribe(data => this.pagos = data);
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

cargarEstadisticas() {
  this.pagosService.estadisticas().subscribe(resp => {
    this.estadisticas = resp;
  });
}

cargarTiposPago() {
  this.pagosService.listarTiposPago().subscribe({
    next: (data) => {
      this.tiposPago = data;
      console.log("Tipos de pago cargados:", this.tiposPago);
    },
    error: (err) => {
      console.error("Error al cargar tipos de pago", err);
    }
  });
}

cargarAdelantos() {
  this.pagosService.listarAdelantos().subscribe({
    next: (data) => {
      this.adelantos = data;
      console.log("Adelantos cargados:", this.adelantos);
    },
    error: (err) => {
      console.error("Error al cargar adelantos", err);
    }
  });
}
}
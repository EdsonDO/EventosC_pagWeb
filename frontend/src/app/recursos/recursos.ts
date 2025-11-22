import { Component } from '@angular/core';
import { RecursosService } from '../../service/recursos.service';
import { TipoRecursoService } from '../../service/tipo-recurso.service';
import { TipoRecurso } from '../../model/tipo-recurso.model';
import { Recurso } from '../../model/recursos.model';
import { MessageService } from 'primeng/api';
import { formatDate } from '@angular/common';

@Component({
  selector: 'app-recursos',
  standalone: false,
  templateUrl: './recursos.html',
  styleUrl: './recursos.css',
})
export class Recursos {

  totalRecursos: number = 0;
  totalDisponibles: number = 0;
  totalEnUso: number = 0;
  totalMantenimiento: number = 0;

  recursos: Recurso[] = [];
  recursosFiltrados: Recurso[] = [];
  busqueda: string = '';
  filtroEstado: string | null = null;
  filtroTipo: number | null = null;

  estados = [
  { label: 'Disponible', value: 'Disponible' },
  { label: 'No disponible', value: 'No disponible' },
  { label: 'En uso', value: 'En uso' },
  { label: 'Mantenimiento', value: 'Mantenimiento' }
];

  modalVisible = false;
  modalEliminarVisible = false;
  modalTitulo = '';
  recursoModal: Recurso = this.crearRecursoVacio();

  constructor(
    private recursosService: RecursosService, 
    private messageService: MessageService,
    private tipoService: TipoRecursoService
  ) {}

  ngOnInit(): void {
    this.listar();
    this.listarTipo();
  }

  listar() {
    this.recursosService.listar().subscribe(res => {
      this.recursos = res;
      this.calcularTotales();
      this.filtrar();
    });
  }

  calcularTotales() {
    this.totalRecursos = this.recursos.length;
    this.totalDisponibles = this.recursos.filter(r => r.estado === 'Disponible').length;
    this.totalEnUso = this.recursos.filter(r => r.estado === 'En uso').length;
    this.totalMantenimiento = this.recursos.filter(r => r.estado === 'Mantenimiento').length;
  }

  filtrar() {
    this.recursosFiltrados = this.recursos.filter(r => {
      
      // Filtro por texto
      const matchBusqueda = this.busqueda
        ? r.nombre_recurso.toLowerCase().includes(this.busqueda.toLowerCase())
        : true;

      // Filtro por estado
      const matchEstado = this.filtroEstado
        ? r.estado === this.filtroEstado
        : true;

      // Filtro por tipo
      const matchTipo = this.filtroTipo
        ? r.id_tipo === this.filtroTipo
        : true;

      return matchBusqueda && matchEstado && matchTipo;
    });
  }

  colorEstado(estado: string): string {
    switch (estado) {
      case 'Disponible': return 'green';
      case 'No disponible': return 'red';
      case 'En uso': return 'blue';
      case 'Mantenimiento': return 'yellow';
      default: return 'gray';
    }
  }

  abrirModalAgregar() {
    this.modalTitulo = 'Agregar Recurso';
    this.recursoModal = this.crearRecursoVacio();
    this.modalVisible = true;
  }

  abrirModalEditar(recurso: Recurso) {
    this.modalTitulo = 'Editar Recurso';
    this.recursoModal = { ...recurso };
    this.modalVisible = true;
    
    if (this.recursoModal.prox_mantenimiento) {
      // Convertir de YYYY-MM-DD (string DB) → Date para PrimeNG
      this.recursoModal.prox_mantenimiento = new Date(recurso.prox_mantenimiento + 'T00:00:00');
    }
  }

  cerrarModal() {
    this.modalVisible = false;
  }

  guardarRecurso() {
    if (this.modalTitulo === 'Agregar Recurso') {
      this.recursosService.crear(this.recursoModal).subscribe(() => {
        this.listar();
        this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Recurso agregado' });
        this.modalVisible = false;
      });
    } else {
      this.recursosService.actualizar(this.recursoModal.id!, this.recursoModal).subscribe(() => {
        this.listar();
        this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Recurso actualizado' });
        this.modalVisible = false;
      });
    }
  }

  abrirModalEliminar(recurso: Recurso) {
    this.recursoModal = { ...recurso };
    this.modalEliminarVisible = true;
  }

  cerrarModalEliminar() {
    this.modalEliminarVisible = false;
  }

  eliminarRecurso() {
    if (!this.recursoModal.id) return;
    this.recursosService.eliminar(this.recursoModal.id).subscribe(() => {
      this.listar();
      this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Recurso eliminado' });
      this.modalEliminarVisible = false;
    });
  }

  crearRecursoVacio(): Recurso {
    return { id: 0, nombre_recurso: '', cantidad: 0, ubicacion: '', estado: 'Disponible', id_tipo: 1, prox_mantenimiento: '' };
  }

  getNombreTipo(idTipo: number): string {
    const tipo = this.tipos.find(t => t.id === idTipo);
    return tipo ? tipo.nombre : 'Desconocido';
  }
  

  tipos: TipoRecurso[] = [];
  tiposFiltrados: TipoRecurso[] = [];
  busquedaTipo: string = '';

  modalTipoRecurso = false;

  modalVisibleTipo = false;
  modalTituloTipo = '';
  tipoModal: TipoRecurso = { nombre: '' };

  modalVerVisibleTipo = false;
  modalEliminarVisibleTipo = false;
  tipoSeleccionado: TipoRecurso = { nombre: '' };


  abrirModalTipoRecurso(){
    this.modalTipoRecurso = true
  }

  listarTipo() {
    this.tipoService.listar().subscribe(res => {
      this.tipos = res;
      this.filtrarTipo();
    });
  }

  filtrarTipo() {
    this.tiposFiltrados = this.tipos.filter(t => t.nombre.toLowerCase().includes(this.busquedaTipo.toLowerCase()));
  }

  abrirModalAgregarTipo() {
    this.modalTituloTipo = 'Agregar Tipo de Recurso';
    this.tipoModal = { nombre: '' };
    this.modalVisibleTipo = true;
  }

  abrirModalEditarTipo(tipo: TipoRecurso) {
    this.modalTituloTipo = 'Editar Tipo de Recurso';
    this.tipoModal = { ...tipo };
    this.modalVisibleTipo = true;
  }

  cerrarModalTipo() {
    this.modalVisibleTipo = false;
  }

  guardarTipo() {
    if (this.modalTituloTipo.includes('Agregar')) {
      this.tipoService.crear(this.tipoModal).subscribe(() => {
        this.listar();
        this.listarTipo();
        this.listarTipo();
        this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Tipo de recurso agregado' });
        this.modalVisibleTipo = false;
      });
    } else {
      if (!this.tipoModal.id) return;
      this.tipoService.actualizar(this.tipoModal.id, this.tipoModal).subscribe(() => {
        this.listar();
        this.listarTipo();
        this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Tipo de recurso actualizado' });
        this.modalVisibleTipo = false;
      });
    }
  }

  abrirModalVerTipo(tipo: TipoRecurso) {
    this.tipoSeleccionado = { ...tipo };
    this.modalVerVisibleTipo = true;
  }

  cerrarModalVerTipo() {
    this.modalVerVisibleTipo = false;
  }

  abrirModalEliminarTipo(tipo: TipoRecurso) {
    this.tipoSeleccionado = { ...tipo };
    this.modalEliminarVisibleTipo = true;
  }

  cerrarModalEliminarTipo() {
    this.modalEliminarVisibleTipo = false;
  }

  eliminarTipo() {
    if (!this.tipoSeleccionado.id) return;
    this.tipoService.eliminar(this.tipoSeleccionado.id).subscribe(() => {
      this.listar();
      this.listarTipo();
      this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Tipo de recurso eliminado' });
      this.modalEliminarVisibleTipo = false;
    });
  }

}

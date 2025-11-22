import { Component } from '@angular/core';
import { ClientesService } from '../../service/clientes.service';
import { Cliente } from '../../model/clientes.model';
import { TagModule } from 'primeng/tag';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'app-clientes',
  standalone: false,
  templateUrl: './clientes.html',
  styleUrl: './clientes.css',
})
export class Clientes {

  // By Danniels
  // Variables
  totalClientes: number = 0;
  clientesVip: number = 0;
  eventosProximos: number = 69;
  ingresosTotales: number = 69;

  clientes: Cliente[] = [];
  clientesFiltrados: Cliente[] = [];
  busqueda: string = '';
  filtroEstado: string | null = null;

  estados = [
  { label: 'Activo', value: 'Activo' },
  { label: 'Inactivo', value: 'Inactivo' },
  { label: 'VIP', value: 'VIP' }
];

  modalVisible = false;
  modalEliminarVisible = false;
  modalTitulo = '';
  clienteModal: Cliente = this.crearClienteVacio();

  constructor(
    private clienteService: ClientesService,
    private messageService: MessageService,
  ){}

  ngOnInit(): void {
    this.listar();
  }

  listar(){
    this.clienteService.listar().subscribe(res => {
      this.clientes = res;
      this.clientesFiltrados = res;
      this.calcularClientes();
    });
  }

  calcularClientes(){
    this.totalClientes = this.clientes.length;
    this.clientesVip = this.clientes.filter(r => r.estado === 'VIP').length;
  }

  filtrar(){
    this.clientesFiltrados = this.clientes.filter(r=> {

      const matchBusqueda = this.busqueda
      ? r.nombre.toLowerCase().includes(this.busqueda.toLowerCase())
      : true;

      const matchEstado = this.filtroEstado
        ? r.estado === this.filtroEstado
        : true;

      return matchBusqueda && matchEstado;
    });
  }

  nombreFormateado(nombreCliente: string): string{
    if(!nombreCliente) return '';

    const partes = nombreCliente.trim().split(' ');

    if (partes.length > 1) {
      return `${partes[0]} ${partes[1].charAt(0)}.`;
    }
    return partes[0];
  }

  abrirModalEliminar(cliente: Cliente){
    this.clienteModal = { ...cliente};
    this.modalEliminarVisible = true;
  }

  cerrarModalEliminar(){
    this.modalEliminarVisible = false;
  }

  eliminarCliente(){
    if(!this.clienteModal.id) return;
    this.clienteService.eliminar(this.clienteModal.id).subscribe(()=>{
      this.listar();
      this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Cliente eliminado'})
      this.modalEliminarVisible = false;
    })
  }

  crearClienteVacio(): Cliente {
      return { id: 0, nombre: '', apellidos: '', correo: '', telefono: '', estado: 'Activo', dni: '', fecha_inscripcion: ''};
    }

  abrirModalAgregar() {
      this.modalTitulo = 'Agregar Cliente';
      this.clienteModal = this.crearClienteVacio();
      this.modalVisible = true;
    }

    abrirModalEditar(cliente: Cliente) {
      this.modalTitulo = 'Editar Cliente';
      this.clienteModal = { ...cliente };
      this.modalVisible = true;

      if (this.clienteModal.fecha_inscripcion) {
        // Convertir de YYYY-MM-DD (string DB) → Date para PrimeNG
        this.clienteModal.fecha_inscripcion = new Date(cliente.fecha_inscripcion + 'T00:00:00');
      }
    }

    cerrarModal() {
      this.modalVisible = false;
    }

    guardarCliente() {
      if (this.modalTitulo === 'Agregar Cliente') {
        this.clienteService.crear(this.clienteModal).subscribe(() => {
          this.listar();
          this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Cliente agregado' });
          this.modalVisible = false;
        });
      } else {
        this.clienteService.actualizar(this.clienteModal.id!, this.clienteModal).subscribe(() => {
          this.listar();
          this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Cliente actualizado' });
          this.modalVisible = false;
        });
      }
    }




}

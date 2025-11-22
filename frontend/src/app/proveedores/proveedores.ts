import { Component } from '@angular/core';
import { Servicio } from '../../model/servicio.model';
import { Proveedor } from '../../model/proveedor.model';
import { ServicioService } from '../../service/servicio.service';
import { ProveedorService } from '../../service/proveedor.service';

@Component({
  selector: 'app-proveedores',
  standalone: false,
  templateUrl: './proveedores.html',
  styleUrl: './proveedores.css',
})
export class Proveedores {

  proveedores: Proveedor[] = [];
  proveedoresFiltrados: Proveedor[] = [];
  proveedorModal: Proveedor = { nombre_empresa: '', nombre_responsable: '', direccion: '', telefono: '', email: '', estado: 'Activo', id: 0, id_servicio: 0 };
  busqueda: string = '';

  filtroEstado: string | null = null;
  filtroServicio: string = '';

  estados: any[] = [
    { label: 'Disponible', value: 'Disponible' },
    { label: 'No disponible', value: 'No disponible' },
    { label: 'Ocupado', value: 'Ocupado' },
  ];
  modalVisible: boolean = false;
  modalEliminarVisible: boolean = false;
  modalTitulo: string = '';

  constructor(
    private proveedorService: ProveedorService, 
    private servicioService: ServicioService) { }

  ngOnInit(): void {
    this.obtenerProveedores();
    this.listarServicios();
  }

  obtenerNombreServicio(idServicio: number): string {
    const servicio = this.servicios.find(s => s.id === idServicio);
    return servicio ? servicio.nombre : 'Servicio no encontrado';
  }

  obtenerProveedores(): void {
    this.proveedorService.listarProveedores().subscribe(data => {
      this.proveedores = data;
      this.proveedoresFiltrados = [...this.proveedores];
    });
  }

  filtrar(): void {
    this.proveedoresFiltrados = this.proveedores.filter(proveedor => {
      return (

        // Filtrar por nombre de la empresa
        (this.busqueda ? proveedor.nombre_empresa.toLowerCase().includes(this.busqueda.toLowerCase()) : true) &&
        
        // Filtrar por estado
        (this.filtroEstado ? proveedor.estado === this.filtroEstado : true) &&
        
        // Filtrar por servicio (si se ha seleccionado un servicio)
        (this.filtroServicio ? proveedor.id_servicio === parseInt(this.filtroServicio, 10) : true)
      );
    });
  }

  abrirModalAgregar(): void {
    this.proveedorModal = { nombre_empresa: '', nombre_responsable: '', direccion: '', telefono: '', email: '', estado: 'Activo', id: 0, id_servicio: 0 };
    this.modalTitulo = 'Agregar Proveedor';
    this.modalVisible = true;
  }

  abrirModalEditar(proveedor: Proveedor): void {
    this.proveedorModal = { ...proveedor };
    this.modalTitulo = 'Editar Proveedor';
    this.modalVisible = true;
  }

  guardarProveedor(): void {
    if (this.proveedorModal.id) {
      this.proveedorService.actualizarProveedor(this.proveedorModal.id, this.proveedorModal).subscribe(() => {
        this.obtenerProveedores();
        this.cerrarModal();
      });
    } else {
      this.proveedorService.crearProveedor(this.proveedorModal).subscribe(() => {
        this.obtenerProveedores();
        this.cerrarModal();
      });
    }
  }

  cerrarModal(): void {
    this.modalVisible = false;
  }

  abrirModalEliminar(proveedor: Proveedor): void {
    this.proveedorModal = { ...proveedor };
    this.modalEliminarVisible = true;
  }

  cerrarModalEliminar(): void {
    this.modalEliminarVisible = false;
  }

  eliminarProveedor(): void {
    this.proveedorService.eliminarProveedor(this.proveedorModal.id!).subscribe(() => {
      this.obtenerProveedores();
      this.cerrarModalEliminar();
    });
  }









  // Modales
  modalServiciosVisible: boolean = false;
  modalAgregarEditarVisible: boolean = false;
  modalVerVisible: boolean = false;
  modalEliminarVisibleServicio: boolean = false;

  // Variables
  servicios: Servicio[] = [];
  serviciosFiltrados: Servicio[] = [];
  busquedaServicio: string = '';

  servicioSeleccionado: Servicio = { nombre: '' };
  servicioModal: Servicio = { nombre: '' };
  editarModo: boolean = false;
  modalTituloServicio: string = '';

 

  // Abrir modal principal
  abrirModalServicios(): void {
    this.modalServiciosVisible = true;
  }

  // Listar servicios
  listarServicios(): void {
    this.servicioService.listar().subscribe(data => {
      this.servicios = data;
      this.serviciosFiltrados = [...this.servicios];
    });
  }

  // Filtrar servicios
  filtrarServicios(): void {
    const filtro = this.busquedaServicio.toLowerCase();
    this.serviciosFiltrados = this.servicios.filter(s => s.nombre.toLowerCase().includes(filtro));
  }

  // Abrir modal Agregar
  abrirModalAgregarServicio(): void {
    this.servicioModal = { nombre: '' };
    this.editarModo = false;
    this.modalTituloServicio = 'Agregar Servicio';
    this.modalAgregarEditarVisible = true;
  }

  // Abrir modal Editar
  abrirModalEditarServicio(servicio: Servicio): void {
    this.servicioModal = { ...servicio };
    this.editarModo = true;
    this.modalTituloServicio = 'Editar Servicio';
    this.modalAgregarEditarVisible = true;
  }

  // Guardar servicio (crear o actualizar)
  guardarServicio(): void {
    if (this.editarModo && this.servicioModal.id) {
      this.servicioService.actualizar(this.servicioModal.id, this.servicioModal).subscribe(() => {
        this.listarServicios();
        this.cerrarModalAgregarEditar();
      });
    } else {
      this.servicioService.crear(this.servicioModal).subscribe(() => {
        this.listarServicios();
        this.cerrarModalAgregarEditar();
      });
    }
  }

  // Cerrar modal agregar/editar
  cerrarModalAgregarEditar(): void {
    this.modalAgregarEditarVisible = false;
    this.servicioModal = { nombre: '' };
  }

  // Abrir modal ver
  abrirModalVerServicio(servicio: Servicio): void {
    this.servicioSeleccionado = { ...servicio };
    this.modalVerVisible = true;
  }

  cerrarModalVer(): void {
    this.modalVerVisible = false;
  }

  // Abrir modal eliminar
  abrirModalEliminarServicio(servicio: Servicio): void {
    this.servicioSeleccionado = { ...servicio };
    this.modalEliminarVisibleServicio = true;
  }

  cerrarModalEliminarServicio(): void {
    this.modalEliminarVisibleServicio = false;
  }

  eliminarServicio(): void {
    if (this.servicioSeleccionado.id) {
      this.servicioService.eliminar(this.servicioSeleccionado.id).subscribe(() => {
        this.listarServicios();
        this.cerrarModalEliminar();
      });
    }
  }

}

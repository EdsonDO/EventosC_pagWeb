import { Component } from '@angular/core';
import { MantenimientoService } from '../../service/mantenimientos.service';

import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Mantenimiento } from '../../model/mantenimientos.model';

@Component({
  selector: 'app-mantenimientos',
  templateUrl: './mantenimientos.html',
  styleUrls: ['./mantenimientos.css'],
  standalone: false,
})
export class Mantenimientos  {

  lista: Mantenimiento[] = [];
  form!: FormGroup;
  mostrarDialog = false;
  modoEdicion = false;
  seleccionado!: Mantenimiento | null;

  constructor(
    private service: MantenimientoService,
    private fb: FormBuilder
  ) {}

  ngOnInit(): void {
    this.cargarDatos();
    this.form = this.fb.group({
      fecha: ['', Validators.required],
      costo: ['', Validators.required],
      descripcion: ['', Validators.required],
      prox_mantenimiento: ['', Validators.required],
      id_recursos: ['', Validators.required]
    });
  }

  cargarDatos() {
    this.service.listar().subscribe(res => {
      this.lista = res;
    });
  }

  nuevo() {
    this.modoEdicion = false;
    this.form.reset();
    this.mostrarDialog = true;
  }

  editar(item: Mantenimiento) {
    this.modoEdicion = true;
    this.seleccionado = item;
    this.form.patchValue(item);
    this.mostrarDialog = true;
  }

  guardar() {
    if (this.form.invalid) return;

    const data = this.form.value;

    if (this.modoEdicion && this.seleccionado) {
      this.service.actualizar(this.seleccionado.id!, data).subscribe(() => {
        this.cargarDatos();
        this.mostrarDialog = false;
      });
    } else {
      this.service.crear(data).subscribe(() => {
        this.cargarDatos();
        this.mostrarDialog = false;
      });
    }
  }

  eliminar(id: number) {
    if (confirm('¿Seguro de eliminar este mantenimiento?')) {
      this.service.eliminar(id).subscribe(() => this.cargarDatos());
    }
  }
}
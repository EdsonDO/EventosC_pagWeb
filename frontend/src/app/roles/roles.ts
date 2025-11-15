import { Component } from '@angular/core';
import { RolesService } from '../../service/roles.service';
import { Rol } from '../../model/roles.model';

@Component({
  selector: 'app-roles',
  standalone: false,
  templateUrl: './roles.html',
  styleUrl: './roles.css',
})
export class Roles {

  roles: Rol[] = [];
  rol: Rol = { nombre: '' };
  editMode: boolean = false;

  constructor(private rolesService: RolesService) { }

  ngOnInit(): void {
    this.listarRoles();
  }

  listarRoles() {
    this.rolesService.listar().subscribe(data => this.roles = data);
  }

  guardar() {
    if (this.editMode && this.rol.id) {
      this.rolesService.actualizar(this.rol.id, this.rol).subscribe(() => {
        this.listarRoles();
        this.limpiarFormulario();
      });
    } else {
      this.rolesService.crear(this.rol).subscribe(() => {
        this.listarRoles();
        this.limpiarFormulario();
      });
    }
  }

  editar(role: Rol) {
    this.rol = { ...role };
    this.editMode = true;
  }

  eliminar(id?: number) {
    if (id && confirm('¿Seguro que deseas eliminar este rol?')) {
      this.rolesService.eliminar(id).subscribe(() => this.listarRoles());
    }
  }

  limpiarFormulario() {
    this.rol = { nombre: '' };
    this.editMode = false;
  }
}

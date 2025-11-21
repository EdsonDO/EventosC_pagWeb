import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { Roles } from './roles/roles';
import { Recursos } from './recursos/recursos';
import { Proveedores } from './proveedores/proveedores';
import { Clientes } from './clientes/clientes';

const routes: Routes = [
  { path: 'roles', component: Roles },
  { path: 'recursos', component: Recursos },
  { path: 'proveedores', component: Proveedores },
  { path: 'clientes', component: Clientes },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { Clientes } from './clientes/clientes';
import { Proveedores } from './proveedores/proveedores';
import { Recursos } from './recursos/recursos';
import { Roles } from './roles/roles';

import { Pagos } from './pagos/pagos';
const routes: Routes = [
  { path: 'roles', component: Roles },
  { path: 'recursos', component: Recursos },
  { path: 'proveedores', component: Proveedores },
  { path: 'clientes', component: Clientes },
 // { path: 'mantenimientos',component:Mantenimientos}, opcional si quieren implementar
  { path: 'pagos',component:Pagos},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { Clientes } from './clientes/clientes';
import { Proveedores } from './proveedores/proveedores';
import { Recursos } from './recursos/recursos';
import { Roles } from './roles/roles';
import { Pagos } from './pagos/pagos';

import { ReservasComponent } from './reservas/reservas';

const routes: Routes = [
  { path: 'roles', component: Roles },
  { path: 'recursos', component: Recursos },
  { path: 'proveedores', component: Proveedores },
  { path: 'clientes', component: Clientes },
  { path: 'pagos', component: Pagos},

  { path: 'reservas', component: ReservasComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
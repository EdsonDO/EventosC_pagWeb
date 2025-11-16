import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { Roles } from './roles/roles';
import { Recursos } from './recursos/recursos';
import { Proveedores } from './proveedores/proveedores';

const routes: Routes = [
  { path: 'roles', component: Roles },
  { path: 'recursos', component: Recursos },
  { path: 'proveedores', component: Proveedores },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

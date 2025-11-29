import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule, provideHttpClient, withFetch } from '@angular/common/http';

import { App } from './app';
import { AppRoutingModule } from './app-routing-module';
import { Roles } from './roles/roles';
import { Clientes } from './clientes/clientes';
import { Mantenimientos } from './mantenimientos/mantenimientos';
import { Proveedores } from './proveedores/proveedores';
import { Recursos } from './recursos/recursos';
import { Pagos } from './pagos/pagos';
import { FilterPagosPipe } from './pipes/filter-pagos.pipe';
import { ReservasComponent } from './reservas/reservas';

// PrimeNG imports
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { MessageService } from 'primeng/api';
import { providePrimeNG } from 'primeng/config';
import { IconFieldModule } from 'primeng/iconfield';
import { InputIconModule } from 'primeng/inputicon';
import MyPreset from './mypreset';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { TableModule } from 'primeng/table';
import { CardModule } from 'primeng/card';
import { DatePickerModule } from 'primeng/datepicker';
import { DialogModule } from 'primeng/dialog';
import { DrawerModule } from 'primeng/drawer';
import { FloatLabelModule } from 'primeng/floatlabel';
import { MessageModule } from 'primeng/message';
import { SelectModule } from 'primeng/select';
import { ToastModule } from 'primeng/toast';
import { InputNumberModule } from 'primeng/inputnumber'; // <--- AGREGAR ESTO

@NgModule({
  declarations: [
    App,
    Roles,
    Recursos,
    Proveedores,
    Clientes,
    Mantenimientos,
    Pagos,
    FilterPagosPipe,
    ReservasComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    // PrimeNG Modules
    InputTextModule,
    ButtonModule,
    TableModule,
    DrawerModule,
    CardModule,
    DialogModule,
    ToastModule,
    SelectModule,
    FloatLabelModule,
    MessageModule,
    DatePickerModule,
    IconFieldModule,
    InputIconModule,
    InputNumberModule 
  ],
  providers: [
    provideHttpClient(withFetch()), 
    provideAnimationsAsync(),
    providePrimeNG({
      theme: {
        preset: MyPreset
      }
    }),
    MessageService,
  ],
  bootstrap: [App]
})
export class AppModule { }
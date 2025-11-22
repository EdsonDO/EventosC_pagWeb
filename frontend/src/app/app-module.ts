import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms'; // <<-- IMPORTANTE
import { BrowserModule } from '@angular/platform-browser';

import { App } from './app';
import { AppRoutingModule } from './app-routing-module';
import { Roles } from './roles/roles';

// PrimeNG
import { HttpClientModule } from '@angular/common/http'; // <-- aquí
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { MessageService } from 'primeng/api';
import { providePrimeNG } from 'primeng/config';
import { IconFieldModule } from 'primeng/iconfield';
import { InputIconModule } from 'primeng/inputicon';
import MyPreset from './mypreset';


// Módulo principal de la aplicación
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
import { Clientes } from './clientes/clientes';
import { Mantenimientos } from './mantenimientos/mantenimientos';
import { Proveedores } from './proveedores/proveedores';
import { Recursos } from './recursos/recursos';

//mantenimiento
import { ReactiveFormsModule } from '@angular/forms';
import { Pagos } from './pagos/pagos';

import { FilterPagosPipe } from './pipes/filter-pagos.pipe';



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
    
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    // PrimeNG Modules
    InputTextModule,
    ButtonModule,
    TableModule,
    DrawerModule,
    CardModule,
    ButtonModule,
    DialogModule,
    ToastModule,
    SelectModule,
    InputTextModule,
    FloatLabelModule,
    MessageModule,
    DatePickerModule,
    IconFieldModule,
    InputIconModule,
    // mantenimiento
     ReactiveFormsModule,
    
  ],
  providers: [
   
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

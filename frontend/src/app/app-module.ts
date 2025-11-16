import { NgModule, provideBrowserGlobalErrorListeners } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms'; // <<-- IMPORTANTE

import { AppRoutingModule } from './app-routing-module';
import { App } from './app';
import { Roles } from './roles/roles';

// PrimeNG
import { HttpClientModule } from '@angular/common/http'; // <-- aquí
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { providePrimeNG } from 'primeng/config';
import MyPreset from './mypreset';
import { MessageService } from 'primeng/api';

// Módulo principal de la aplicación
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';
import { TableModule } from 'primeng/table';

import { DrawerModule } from 'primeng/drawer';
import { Recursos } from './recursos/recursos';
import { CardModule } from 'primeng/card';
import { DialogModule } from 'primeng/dialog';
import { ToastModule } from 'primeng/toast';
import { SelectModule } from 'primeng/select'; 
import { FloatLabelModule } from 'primeng/floatlabel';
import { MessageModule } from 'primeng/message';
import { DatePickerModule } from 'primeng/datepicker';
import { Proveedores } from './proveedores/proveedores';

@NgModule({
  declarations: [
    App,
    Roles,
    Recursos,
    Proveedores
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
    DatePickerModule
  ],
  providers: [
    provideBrowserGlobalErrorListeners(),
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

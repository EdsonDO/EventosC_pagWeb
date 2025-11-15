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

@NgModule({
  declarations: [
    App,
    Roles
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    // PrimeNG Modules
    InputTextModule,
    ButtonModule,
    TableModule
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

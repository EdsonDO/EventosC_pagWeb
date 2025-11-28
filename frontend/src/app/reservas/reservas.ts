import { Component, OnInit } from '@angular/core';
import { ReservaService } from '../../service/reserva.service';
import { Reserva } from '../../model/reservas.model';

@Component({
  selector: 'app-reservas',
  templateUrl: './reservas.html',    
  styleUrls: ['./reservas.css'],    
  standalone: false
})
export class ReservasComponent implements OnInit {
  reservas: Reserva[] = [];
  
  // Datos pre-llenados para probar rápido
  nuevaReserva: Reserva = {
    fecha: new Date().toISOString().split('T')[0], 
    numero_asistentes: 1,
    total: 100.00,
    estado: 'Por Pagar',
    id_cliente: 1,
    id_pagos: 1,
    id_evento: 1,
    id_ubicacion: 1
  };

  constructor(private reservaService: ReservaService) {}

  ngOnInit(): void {
    this.cargarReservas();
  }

  cargarReservas() {
    this.reservaService.listar().subscribe({
      next: (data) => {
        this.reservas = data;
        console.log('Datos cargados:', data);
      },
      error: (e) => console.error('Error:', e)
    });
  }

  guardar() {
    this.reservaService.crear(this.nuevaReserva).subscribe({
      next: () => {
        alert('¡Guardado con éxito!');
        this.cargarReservas();
      },
      error: (e) => alert('Error al guardar (Revisa consola)')
    });
  }
}
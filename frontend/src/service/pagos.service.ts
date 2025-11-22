// src/app/services/pagos.service.ts
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Pago } from '../model/pagos.model';


@Injectable({
  providedIn: 'root'
})
export class PagosService {
  private apiUrl = 'http://localhost/EventosC_pagWeb/backend/endpoint/pagos.php';

  constructor(private http: HttpClient) { }

  listar(): Observable<Pago[]> {
    return this.http.get<Pago[]>(`${this.apiUrl}?accion=listar`);
  }

  obtener(id: number): Observable<Pago> {
    return this.http.get<Pago>(`${this.apiUrl}?accion=obtener&id=${id}`);
  }

  crear(pago: Pago): Observable<any> {
    return this.http.post(`${this.apiUrl}?accion=crear`, pago);
  }

  actualizar(pago: Pago): Observable<any> {
    return this.http.put(`${this.apiUrl}?accion=actualizar&id=${pago.id}`, pago);
  }

  eliminar(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}?accion=eliminar&id=${id}`);
  }
}

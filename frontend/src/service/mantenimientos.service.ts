import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Mantenimiento } from '../model/mantenimientos.model';
import { ApiConfig } from './api.config';

@Injectable({
  providedIn: 'root'
})
export class MantenimientoService {

  private apiUrl = `${ApiConfig.apiUrl}mantenimiento.php`;

  constructor(private http: HttpClient) {}

  listar(): Observable<Mantenimiento[]> {
    return this.http.get<Mantenimiento[]>(`${this.apiUrl}?accion=listar`);
  }

  obtener(id: number): Observable<Mantenimiento> {
    return this.http.get<Mantenimiento>(`${this.apiUrl}?accion=obtener&id=${id}`);
  }

  crear(data: Mantenimiento): Observable<any> {
    return this.http.post(`${this.apiUrl}?accion=crear`, data);
  }

  actualizar(id: number, data: Mantenimiento): Observable<any> {
    return this.http.put(`${this.apiUrl}?accion=actualizar&id=${id}`, data);
  }

  eliminar(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}?accion=eliminar&id=${id}`);
  }
}

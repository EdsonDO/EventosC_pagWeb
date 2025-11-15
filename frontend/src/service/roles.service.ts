import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ApiConfig } from './api.config';
import { Rol } from '../model/roles.model';

@Injectable({
  providedIn: 'root'
})
export class RolesService {
  private apiUrl = `${ApiConfig.apiUrl}roles.php`;

  constructor(private http: HttpClient) { }

  listar(): Observable<Rol[]> {
    return this.http.get<Rol[]>(`${this.apiUrl}?accion=listar`);
  }

  obtener(id: number): Observable<Rol> {
    return this.http.get<Rol>(`${this.apiUrl}?accion=obtener&id=${id}`);
  }

  crear(rol: Rol): Observable<any> {
    return this.http.post(`${this.apiUrl}?accion=crear`, rol);
  }

  actualizar(id: number, rol: Rol): Observable<any> {
    return this.http.post(`${this.apiUrl}?accion=actualizar&id=${id}`, rol);
  }

  eliminar(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}?accion=eliminar&id=${id}`);
  }
}

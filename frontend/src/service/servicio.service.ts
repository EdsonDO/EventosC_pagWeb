import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ApiConfig } from './api.config';
import { Servicio } from '../model/servicio.model';

@Injectable({
  providedIn: 'root'
})
export class ServicioService {

    private apiUrl = `${ApiConfig.apiUrl}servicio.php`;


    constructor(private http: HttpClient) { }

    listar(): Observable<Servicio[]> {
        return this.http.get<Servicio[]>(`${this.apiUrl}?accion=listar`);
    }

    obtener(id: number): Observable<Servicio> {
        return this.http.get<Servicio>(`${this.apiUrl}?accion=obtener&id=${id}`);
    }

    crear(servicio: Servicio): Observable<any> {
        return this.http.post(`${this.apiUrl}?accion=crear`, servicio);
    }

    actualizar(id: number, servicio: Servicio): Observable<any> {
        return this.http.put(`${this.apiUrl}?accion=actualizar&id=${id}`, servicio);
    }

    eliminar(id: number): Observable<any> {
        return this.http.delete(`${this.apiUrl}?accion=eliminar&id=${id}`);
    }
}

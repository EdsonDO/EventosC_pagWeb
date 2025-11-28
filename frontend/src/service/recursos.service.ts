import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ApiConfig } from './api.config';
import { Recurso } from '../model/recursos.model';

@Injectable({
  providedIn: 'root'
})
export class RecursosService {
    private apiUrl = `${ApiConfig.apiUrl}recursos.php`;

    constructor(private http: HttpClient) {}

    listar(): Observable<Recurso[]> {
        return this.http.get<Recurso[]>(`${this.apiUrl}?accion=listar`);
    }

    obtener(id: number): Observable<Recurso> {
        return this.http.get<Recurso>(`${this.apiUrl}?accion=obtener&id=${id}`);
    }

    crear(recurso: Recurso): Observable<any> {
        return this.http.post(`${this.apiUrl}?accion=crear`, recurso);
    }

    actualizar(id: number, recurso: Recurso): Observable<any> {
        return this.http.put(`${this.apiUrl}?accion=actualizar&id=${id}`, recurso);
    }

    eliminar(id: number): Observable<any> {
        return this.http.delete(`${this.apiUrl}?accion=eliminar&id=${id}`);
    }
}

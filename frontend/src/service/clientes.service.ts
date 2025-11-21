import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ApiConfig } from './api.config';
import { Cliente } from '../model/clientes.model';

@Injectable({
  providedIn: 'root'
})
export class ClientesService {
    private apiUrl = `${ApiConfig.apiUrl}cliente.php`;

    constructor(private http: HttpClient) {}

    listar(): Observable<Cliente[]> {
        return this.http.get<Cliente[]>(`${this.apiUrl}?accion=listar`);
    }

    obtener(id: number): Observable<Cliente> {
        return this.http.get<Cliente>(`${this.apiUrl}?accion=obtener&id=${id}`);
    }

    crear(cliente: Cliente): Observable<any> {
        return this.http.post(`${this.apiUrl}?accion=crear`, cliente);
    }

    actualizar(id: number, cliente: Cliente): Observable<any> {
        return this.http.put(`${this.apiUrl}?accion=actualizar&id=${id}`, cliente);
    }

    eliminar(id: number): Observable<any> {
        return this.http.delete(`${this.apiUrl}?accion=eliminar&id=${id}`);
    }
}

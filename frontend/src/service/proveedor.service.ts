import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ApiConfig } from './api.config';
import { Proveedor } from '../model/proveedor.model';

@Injectable({
  providedIn: 'root'
})
export class ProveedorService {
    
    private apiUrl = `${ApiConfig.apiUrl}proveedores.php`;
    
    constructor(private http: HttpClient) { }

    // Listar todos los proveedores
    listarProveedores(): Observable<Proveedor[]> {
        return this.http.get<Proveedor[]>(`${this.apiUrl}?accion=listar`);
    }

    // Obtener un proveedor por su ID
    obtenerProveedor(id: number): Observable<Proveedor> {
        return this.http.get<Proveedor>(`${this.apiUrl}?accion=obtener&id=${id}`);
    }

    // Crear un nuevo proveedor
    crearProveedor(proveedor: Proveedor): Observable<any> {
        const headers = new HttpHeaders({'Content-Type': 'application/json'});
        return this.http.post<any>(`${this.apiUrl}?accion=crear`, proveedor, { headers });
    }

    // Actualizar un proveedor existente
    actualizarProveedor(id: number, proveedor: Proveedor): Observable<any> {
        const headers = new HttpHeaders({'Content-Type': 'application/json'});
        return this.http.put<any>(`${this.apiUrl}?accion=actualizar&id=${id}`, proveedor, { headers });
    }

    // Eliminar un proveedor
    eliminarProveedor(id: number): Observable<any> {
        return this.http.delete<any>(`${this.apiUrl}?accion=eliminar&id=${id}`);
    }
    }

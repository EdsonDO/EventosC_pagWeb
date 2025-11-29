import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
// Importamos saliendo de "service" hacia "model"
import { Reserva } from '../model/reservas.model';

@Injectable({
  providedIn: 'root'
})
export class ReservaService {

private apiUrl = 'http://localhost:8000/backend/endpoint/reservas.php';

  constructor(private http: HttpClient) { }

  listar(): Observable<Reserva[]> {
    return this.http.get<Reserva[]>(`${this.apiUrl}?accion=listar`);
  }

  crear(reserva: Reserva): Observable<any> {
    return this.http.post(`${this.apiUrl}?accion=crear`, reserva);
  }
}
export interface Cliente {
  id?: number;
  nombre: string;
  apellidos: string;
  telefono: string;
  dni: string;
  estado: string;
  correo: string;
  fecha_inscripcion: string | Date | null;
}

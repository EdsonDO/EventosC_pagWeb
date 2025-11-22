export interface Mantenimiento {
  id?: number;
  fecha: string;
  costo: number;
  descripcion: string;
  prox_mantenimiento: string;
  id_recursos: number;
}

export interface Recurso {
  id?: number;
  nombre_recurso: string;
  cantidad: number;
  ubicacion: string;
  estado: string;
  prox_mantenimiento?: string | Date | null;
  id_tipo: number;
}

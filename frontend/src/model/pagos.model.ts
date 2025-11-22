export interface Pago {
  id?: number;
  numero_tarjeta?: string;
  fecha_vencimiento?: string;
  cvv?: string;
  voucer?: string;
  id_tipo_pago?: number;
  id_adelanto?: number;
}
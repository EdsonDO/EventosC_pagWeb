export interface Pago {
  id?: number;
  numero_tarjeta?: string;
  fecha_vencimiento?: string;
  cvv?: string;
  voucer?: string;
  id_tipo_pago?: number;
  id_adelanto?: number;

  tipo_pago_nombre?: string;
  adelanto_valor?: number | string;
}
